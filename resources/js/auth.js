import { state, storeSession, clearSession, apiRequest } from './api.js';
import { roleOf, setMessage, validationMessage } from './utils.js';
import { syncNavigation, renderUser } from './navigation.js';
import Swal from 'sweetalert2';

export function mergeUserProfile(profile) {
    return {
        ...(state.user || {}),
        ...profile,
        role: profile?.role || state.user?.role,
        roles: profile?.roles || state.user?.roles,
    };
}

function authPayload(mode, form) {
    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

    if (mode !== 'register') {
        return payload;
    }

    return {
        nik: payload.nik,
        name: payload.name,
        email: payload.email,
        phone: payload.phone,
        address: payload.address,
        password: payload.password,
        password_confirmation: payload.password_confirmation,
    };
}

function redirectToLogin() {
    const target = encodeURIComponent(window.location.pathname);
    window.location.assign(`/login?redirect=${target}`);
}

function requiredRole() {
    return document.body.dataset.roleRequired || '';
}

function isAuthorizedForPage(user) {
    const role = requiredRole();
    return !role || roleOf(user) === role;
}

function revealProtectedShell() {
    document.querySelectorAll('[data-protected-shell]').forEach((element) => {
        element.hidden = false;
    });
}

export async function hydrateAuthenticatedPage() {
    if (!document.body.dataset.authRequired || document.body.dataset.authRequired !== 'true') {
        syncNavigation();
        return;
    }

    if (!state.token) {
        redirectToLogin();
        return;
    }

    try {
        const response = await apiRequest('/auth/me');
        const user = mergeUserProfile(response.data);
        state.user = user;
        localStorage.setItem('grpl2_user', JSON.stringify(user));

        syncNavigation(user);
        renderUser(user);

        if (!isAuthorizedForPage(user)) {
            const roleRedirect = {
                system_admin: '/dashboard',
                applicant: '/applications',
                assessor: '/assessments',
                staff_rpl: '/submissions',
                committee: '/approvals',
            };
            window.location.replace(roleRedirect[roleOf(user)] || '/login');
            return;
        }

        revealProtectedShell();

        const flash = localStorage.getItem('grpl2_flash');
        if (flash) {
            localStorage.removeItem('grpl2_flash');
            const { icon, title, text } = JSON.parse(flash);
            await Swal.fire({
                icon,
                title,
                text,
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        clearSession();
        redirectToLogin();
    }
}

export function bindAuthForms() {
    document.querySelectorAll('[data-auth-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const mode = form.dataset.authForm;
            const button = form.querySelector('[data-submit-button]');
            const payload = authPayload(mode, form);

            button.disabled = true;
            setMessage(form, 'Memproses...', 'info');

            try {
                if (mode === 'login') {
                    const response = await apiRequest('/auth/login', {
                        method: 'POST',
                        body: JSON.stringify(payload),
                    });

                    if (!response.success || !response.token) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            text: 'Email atau password salah. Silakan periksa kembali dan coba lagi.',
                            confirmButtonText: 'Tutup',
                        });
                        return;
                    }

                    storeSession(response.token, response.user);

                    const userName = response.user?.name || 'Pengguna';
                    localStorage.setItem('grpl2_flash', JSON.stringify({
                        icon: 'success',
                        title: 'Login Berhasil',
                        text: `Selamat datang, ${userName}!`,
                    }));

                    const roleRedirect = {
                        system_admin: '/dashboard',
                        applicant: '/applications',
                        assessor: '/assessments',
                        staff_rpl: '/submissions',
                        committee: '/approvals',
                    };
                    const userRole = roleOf(response.user);
                    const defaultRedirect = roleRedirect[userRole] || '/dashboard';
                    const redirect = new URLSearchParams(window.location.search).get('redirect') || defaultRedirect;
                    window.location.assign(redirect);
                    return;
                }

                const response = await apiRequest('/auth/register', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });

                if (response.success) {
                    form.reset();
                    await Swal.fire({
                        icon: 'success',
                        title: 'Registrasi Berhasil',
                        text: 'Link verifikasi telah dikirim ke email kamu. Silakan cek inbox atau folder spam sebelum login.',
                        confirmButtonText: 'Oke',
                    });
                    return;
                }

                await Swal.fire({
                    icon: 'error',
                    title: 'Registrasi Gagal',
                    text: 'Terjadi kesalahan saat mendaftar. Periksa kembali data kamu dan coba lagi.',
                    confirmButtonText: 'Tutup',
                });
            } catch (error) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Koneksi bermasalah atau server tidak merespons. Coba beberapa saat lagi.',
                    confirmButtonText: 'Tutup',
                });
            } finally {
                button.disabled = false;
            }
        });
    });
}

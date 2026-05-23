const TOKEN_KEY = 'grpl2_token';
const USER_KEY = 'grpl2_user';

const state = {
    token: localStorage.getItem(TOKEN_KEY),
    user: readStoredUser(),
};

function readStoredUser() {
    try {
        return JSON.parse(localStorage.getItem(USER_KEY) || 'null');
    } catch {
        return null;
    }
}

function storeSession(token, user) {
    state.token = token;
    state.user = user;
    localStorage.setItem(TOKEN_KEY, token);
    localStorage.setItem(USER_KEY, JSON.stringify(user));
}

function clearSession() {
    state.token = null;
    state.user = null;
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
}

async function apiRequest(path, options = {}) {
    const headers = {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(options.headers || {}),
    };

    if (state.token) {
        headers.Authorization = `Bearer ${state.token}`;
    }

    const response = await fetch(`/api${path}`, {
        ...options,
        headers,
    });

    const contentType = response.headers.get('content-type') || '';
    const payload = contentType.includes('application/json')
        ? await response.json()
        : {};

    if (!response.ok) {
        const error = new Error(payload.message || 'Request failed');
        error.status = response.status;
        error.payload = payload;
        throw error;
    }

    return payload;
}

function roleOf(user) {
    const firstRole = user?.roles?.[0];

    if (typeof user?.role === 'string') {
        return user.role;
    }

    if (typeof firstRole === 'string') {
        return firstRole;
    }

    return firstRole?.name || '';
}

function mergeUserProfile(profile) {
    return {
        ...(state.user || {}),
        ...profile,
        role: profile?.role || state.user?.role,
        roles: profile?.roles || state.user?.roles,
    };
}

function setMessage(form, message, type = 'error') {
    const target = form.querySelector('[data-form-message]');
    if (!target) {
        return;
    }

    target.textContent = message;
    target.dataset.type = type;
}

function validationMessage(error) {
    const errors = error?.payload?.errors;
    if (!errors) {
        return error.message || 'Request failed';
    }

    return Object.values(errors)
        .flat()
        .filter(Boolean)
        .join(' ');
}

function pageMessage(message, type = 'error') {
    const target = document.querySelector('[data-page-message]');
    if (!target) {
        return;
    }

    target.textContent = message;
    target.dataset.type = type;
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function toBoolean(value) {
    return value === true || value === '1' || value === 1;
}

function collection(payload) {
    if (Array.isArray(payload?.data)) {
        return payload.data;
    }

    if (Array.isArray(payload?.data?.data)) {
        return payload.data.data;
    }

    return [];
}

function formPayload(form, options = {}) {
    const payload = {};

    form.querySelectorAll('input, select, textarea').forEach((field) => {
        if (!field.name || field.disabled) {
            return;
        }

        if (field.multiple) {
            payload[field.name] = Array.from(field.selectedOptions).map((option) => Number(option.value));
            return;
        }

        if (field.type === 'number') {
            payload[field.name] = field.value === '' ? '' : Number(field.value);
            return;
        }

        if (options.booleanFields?.includes(field.name)) {
            payload[field.name] = toBoolean(field.value);
            return;
        }

        if (options.skipEmpty?.includes(field.name) && field.value === '') {
            return;
        }

        payload[field.name] = field.value;
    });

    return payload;
}

function currentResourceId() {
    const parts = window.location.pathname.split('/').filter(Boolean);
    return parts.at(-2);
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

function syncNavigation(user = state.user) {
    const hasSession = Boolean(state.token && user);
    const currentPath = window.location.pathname;
    const userRole = roleOf(user);

    document.querySelectorAll('[data-public-nav]').forEach((element) => {
        element.hidden = hasSession;
    });

    document.querySelectorAll('[data-private-nav], [data-logout]').forEach((element) => {
        element.hidden = !hasSession;
    });

    document.querySelectorAll('[data-role-link]').forEach((element) => {
        element.hidden = !hasSession || !userRole || element.dataset.roleLink !== userRole;
    });

    document.querySelectorAll('[data-nav-link]').forEach((element) => {
        element.classList.toggle('active', element.getAttribute('href') === currentPath);
    });
}

function renderUser(user) {
    const currentRole = roleOf(user);

    document.querySelectorAll('[data-user-name]').forEach((element) => {
        element.textContent = user?.name || 'User';
    });

    document.querySelectorAll('[data-user-role]').forEach((element) => {
        element.textContent = currentRole || 'No role';
    });

    document.querySelectorAll('[data-api-status]').forEach((element) => {
        element.textContent = 'Connected';
        element.dataset.status = 'connected';
    });

    document.querySelectorAll('[data-role-card]').forEach((element) => {
        element.hidden = !currentRole || element.dataset.roleCard !== currentRole;
    });
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

async function hydrateAuthenticatedPage() {
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
        localStorage.setItem(USER_KEY, JSON.stringify(user));

        syncNavigation(user);
        renderUser(user);

        if (!isAuthorizedForPage(user)) {
            window.location.replace('/dashboard');
            return;
        }

        revealProtectedShell();
    } catch (error) {
        clearSession();
        redirectToLogin();
    }
}

function bindAuthForms() {
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
                        setMessage(form, response.message || 'Login gagal', 'error');
                        return;
                    }

                    storeSession(response.token, response.user);
                    const redirect = new URLSearchParams(window.location.search).get('redirect') || '/dashboard';
                    window.location.assign(redirect);
                    return;
                }

                const response = await apiRequest('/auth/register', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });

                if (response.success) {
                    form.reset();
                    setMessage(
                        form,
                        `${response.message}. Silakan cek email verifikasi sebelum login.`,
                        'success'
                    );
                    return;
                }

                setMessage(form, response.message || 'Register gagal', 'error');
            } catch (error) {
                setMessage(form, validationMessage(error), 'error');
            } finally {
                button.disabled = false;
            }
        });
    });
}

function bindLogout() {
    document.querySelectorAll('[data-logout]').forEach((button) => {
        button.addEventListener('click', async () => {
            button.disabled = true;

            try {
                if (state.token) {
                    await apiRequest('/auth/logout', { method: 'POST' });
                }
            } catch {
                // Local session is cleared even if the token is already invalid server-side.
            } finally {
                clearSession();
                window.location.assign('/login');
            }
        });
    });
}

function userRole(user) {
    return roleOf(user).replaceAll('_', ' ');
}

function userProfile(user) {
    if (user?.assessor) {
        return user.assessor;
    }

    if (user?.staff_rpl) {
        return user.staff_rpl;
    }

    if (user?.staffRpl) {
        return user.staffRpl;
    }

    if (user?.committee) {
        return user.committee;
    }

    return {};
}

async function loadUsers() {
    const form = document.querySelector('[data-admin-filter="users"]');
    const query = form ? new URLSearchParams(new FormData(form)) : new URLSearchParams();
    query.set('per_page', '50');

    Array.from(query.entries()).forEach(([key, value]) => {
        if (value === '') {
            query.delete(key);
        }
    });

    const target = document.querySelector('[data-users-body]');
    if (!target) {
        return;
    }

    try {
        const response = await apiRequest(`/admin/users?${query.toString()}`);
        const users = collection(response);

        target.innerHTML = users.length
            ? users.map((user) => {
                const profile = userProfile(user);
                const active = Boolean(user.is_active);

                return `
                    <tr>
                        <td>${escapeHtml(user.name)}</td>
                        <td>${escapeHtml(user.email)}</td>
                        <td>${escapeHtml(userRole(user))}</td>
                        <td>${escapeHtml(profile.nip || '-')}</td>
                        <td><span class="status-badge" data-status="${active ? 'active' : 'inactive'}">${active ? 'Active' : 'Inactive'}</span></td>
                        <td class="table-actions">
                            <a class="button button-small button-muted" href="/admin/users/${user.id}/edit">Edit</a>
                            <button class="button button-small button-muted" type="button" data-toggle-user="${user.id}" data-next-active="${active ? '0' : '1'}">
                                ${active ? 'Deactivate' : 'Activate'}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('')
            : '<tr><td colspan="6">Tidak ada user.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="6">Gagal memuat user.</td></tr>';
        pageMessage(validationMessage(error));
    }
}

async function loadUserForm(form) {
    if (form.dataset.adminUserForm !== 'edit') {
        return;
    }

    try {
        const response = await apiRequest(`/admin/users/${currentResourceId()}`);
        const user = response.data;
        const profile = userProfile(user);

        form.elements.name.value = user.name || '';
        form.elements.email.value = user.email || '';
        form.elements.nip.value = profile.nip || '';
        form.elements.phone.value = profile.phone || '';
        form.elements.address.value = profile.address || '';
    } catch (error) {
        setMessage(form, validationMessage(error));
    }
}

function bindUserForms() {
    document.querySelectorAll('[data-admin-user-form]').forEach((form) => {
        loadUserForm(form);

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const mode = form.dataset.adminUserForm;
            const button = form.querySelector('[data-submit-button]');
            const payload = formPayload(form, {
                skipEmpty: mode === 'edit' ? ['password', 'password_confirmation'] : [],
            });

            button.disabled = true;
            setMessage(form, 'Menyimpan...', 'info');

            try {
                const path = mode === 'edit' ? `/admin/users/${currentResourceId()}` : '/admin/users';
                const response = await apiRequest(path, {
                    method: mode === 'edit' ? 'PUT' : 'POST',
                    body: JSON.stringify(payload),
                });

                setMessage(form, response.message || 'User tersimpan.', 'success');

                if (mode === 'create') {
                    window.location.assign('/admin/users');
                }
            } catch (error) {
                setMessage(form, validationMessage(error));
            } finally {
                button.disabled = false;
            }
        });
    });
}

function bindUserActions() {
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-toggle-user]');
        if (!button) {
            return;
        }

        button.disabled = true;

        try {
            await apiRequest(`/admin/users/${button.dataset.toggleUser}/status`, {
                method: 'PATCH',
                body: JSON.stringify({ is_active: toBoolean(button.dataset.nextActive) }),
            });
            pageMessage('Status user diperbarui.', 'success');
            loadUsers();
        } catch (error) {
            pageMessage(validationMessage(error));
            button.disabled = false;
        }
    });
}

async function loadStudyPrograms() {
    const target = document.querySelector('[data-study-programs-body]');
    if (!target) {
        return;
    }

    try {
        const response = await apiRequest('/admin/study-programs');
        const programs = collection(response);

        target.innerHTML = programs.length
            ? programs.map((program) => `
                <tr>
                    <td>${escapeHtml(program.code)}</td>
                    <td>${escapeHtml(program.name)}</td>
                    <td>${escapeHtml(program.max_convertible_sks)} / ${escapeHtml(program.total_sks)}</td>
                    <td>${program.supports_a1 ? 'A1' : '-'} ${program.supports_a2 ? 'A2' : '-'} ${program.is_hybrid_allowed ? 'Hybrid' : ''}</td>
                    <td><span class="status-badge" data-status="${escapeHtml(program.status)}">${escapeHtml(program.status)}</span></td>
                    <td><a class="button button-small button-muted" href="/admin/study-programs/${program.id}/edit">Edit</a></td>
                </tr>
            `).join('')
            : '<tr><td colspan="6">Tidak ada program studi.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="6">Gagal memuat program studi.</td></tr>';
        pageMessage(validationMessage(error));
    }
}

async function fillStudyProgramOptions(selectedIds = []) {
    const selects = document.querySelectorAll('[data-study-program-select], [data-study-program-filter]');
    if (!selects.length) {
        return;
    }

    const response = await apiRequest('/admin/study-programs');
    const programs = collection(response);
    const selected = selectedIds.map(String);

    selects.forEach((select) => {
        const firstOption = select.dataset.studyProgramFilter !== undefined
            ? '<option value="">Semua program studi</option>'
            : '';

        select.innerHTML = `${firstOption}${programs.map((program) => `
            <option value="${program.id}" ${selected.includes(String(program.id)) ? 'selected' : ''}>
                ${escapeHtml(program.code)} - ${escapeHtml(program.name)}
            </option>
        `).join('')}`;
    });
}

async function loadStudyProgramForm(form) {
    if (form.dataset.studyProgramForm !== 'edit') {
        return;
    }

    try {
        const response = await apiRequest(`/admin/study-programs/${currentResourceId()}`);
        const program = response.data;

        Object.entries(program).forEach(([key, value]) => {
            if (form.elements[key]) {
                form.elements[key].value = typeof value === 'boolean' ? Number(value) : value;
            }
        });
    } catch (error) {
        setMessage(form, validationMessage(error));
    }
}

function bindStudyProgramForms() {
    document.querySelectorAll('[data-study-program-form]').forEach((form) => {
        loadStudyProgramForm(form);

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const mode = form.dataset.studyProgramForm;
            const button = form.querySelector('[data-submit-button]');
            const payload = formPayload(form, {
                booleanFields: ['supports_a1', 'supports_a2', 'is_hybrid_allowed'],
            });

            button.disabled = true;
            setMessage(form, 'Menyimpan...', 'info');

            try {
                const path = mode === 'edit' ? `/admin/study-programs/${currentResourceId()}` : '/admin/study-programs';
                const response = await apiRequest(path, {
                    method: mode === 'edit' ? 'PUT' : 'POST',
                    body: JSON.stringify(payload),
                });

                setMessage(form, response.message || 'Program studi tersimpan.', 'success');

                if (mode === 'create') {
                    window.location.assign('/admin/study-programs');
                }
            } catch (error) {
                setMessage(form, validationMessage(error));
            } finally {
                button.disabled = false;
            }
        });
    });
}

async function loadCourses() {
    const form = document.querySelector('[data-admin-filter="courses"]');
    const query = form ? new URLSearchParams(new FormData(form)) : new URLSearchParams();
    query.set('per_page', '50');

    Array.from(query.entries()).forEach(([key, value]) => {
        if (value === '') {
            query.delete(key);
        }
    });

    const target = document.querySelector('[data-courses-body]');
    if (!target) {
        return;
    }

    try {
        await fillStudyProgramOptions();
        const response = await apiRequest(`/admin/courses?${query.toString()}`);
        const courses = collection(response);

        target.innerHTML = courses.length
            ? courses.map((course) => {
                const active = Boolean(course.is_active);
                const programs = (course.study_programs || course.studyPrograms || [])
                    .map((program) => program.code || program.name)
                    .join(', ');

                return `
                    <tr>
                        <td>${escapeHtml(course.code)}</td>
                        <td>${escapeHtml(course.name)}</td>
                        <td>${escapeHtml(programs || '-')}</td>
                        <td>${escapeHtml(course.semester)}</td>
                        <td>${escapeHtml(course.sks)}</td>
                        <td>${escapeHtml(course.rpl_type)}</td>
                        <td><span class="status-badge" data-status="${active ? 'active' : 'inactive'}">${active ? 'Active' : 'Inactive'}</span></td>
                        <td class="table-actions">
                            <a class="button button-small button-muted" href="/admin/courses/${course.id}/edit">Edit</a>
                            <button class="button button-small button-muted" type="button" data-toggle-course="${course.id}" data-next-active="${active ? '0' : '1'}">
                                ${active ? 'Deactivate' : 'Activate'}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('')
            : '<tr><td colspan="8">Tidak ada mata kuliah.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="8">Gagal memuat mata kuliah.</td></tr>';
        pageMessage(validationMessage(error));
    }
}

async function loadCourseForm(form) {
    try {
        let selectedIds = [];

        if (form.dataset.courseForm === 'edit') {
            const response = await apiRequest(`/admin/courses/${currentResourceId()}`);
            const course = response.data;
            selectedIds = (course.study_programs || course.studyPrograms || []).map((program) => program.id);

            form.elements.code.value = course.code || '';
            form.elements.name.value = course.name || '';
            form.elements.semester.value = course.semester || '';
            form.elements.sks.value = course.sks || '';
            form.elements.rpl_type.value = course.rpl_type || '';
        }

        await fillStudyProgramOptions(selectedIds);
    } catch (error) {
        setMessage(form, validationMessage(error));
    }
}

function bindCourseForms() {
    document.querySelectorAll('[data-course-form]').forEach((form) => {
        loadCourseForm(form);

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const mode = form.dataset.courseForm;
            const button = form.querySelector('[data-submit-button]');
            const payload = formPayload(form);

            button.disabled = true;
            setMessage(form, 'Menyimpan...', 'info');

            try {
                const path = mode === 'edit' ? `/admin/courses/${currentResourceId()}` : '/admin/courses';
                const response = await apiRequest(path, {
                    method: mode === 'edit' ? 'PUT' : 'POST',
                    body: JSON.stringify(payload),
                });

                setMessage(form, response.message || 'Mata kuliah tersimpan.', 'success');

                if (mode === 'create') {
                    window.location.assign('/admin/courses');
                }
            } catch (error) {
                setMessage(form, validationMessage(error));
            } finally {
                button.disabled = false;
            }
        });
    });
}

function bindCourseActions() {
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-toggle-course]');
        if (!button) {
            return;
        }

        button.disabled = true;

        try {
            await apiRequest(`/admin/courses/${button.dataset.toggleCourse}/status`, {
                method: 'PATCH',
                body: JSON.stringify({ is_active: toBoolean(button.dataset.nextActive) }),
            });
            pageMessage('Status mata kuliah diperbarui.', 'success');
            loadCourses();
        } catch (error) {
            pageMessage(validationMessage(error));
            button.disabled = false;
        }
    });
}

function bindAdminFilters() {
    document.querySelectorAll('[data-admin-filter]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (form.dataset.adminFilter === 'users') {
                loadUsers();
            }

            if (form.dataset.adminFilter === 'courses') {
                loadCourses();
            }
        });
    });
}

function bootAdminPages() {
    const page = document.body.dataset.page;

    bindAdminFilters();
    bindUserForms();
    bindUserActions();
    bindStudyProgramForms();
    bindCourseForms();
    bindCourseActions();

    if (page === 'users') {
        loadUsers();
    }

    if (page === 'study-programs') {
        loadStudyPrograms();
    }

    if (page === 'courses') {
        loadCourses();
    }
}

function boot() {
    syncNavigation();
    bindAuthForms();
    bindLogout();
    hydrateAuthenticatedPage();
    bootAdminPages();
}

document.addEventListener('DOMContentLoaded', boot);

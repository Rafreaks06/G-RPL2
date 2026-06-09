import { apiRequest } from './api.js';
import { userProfile, userRole } from './navigation.js';
import {
    escapeHtml, collection, currentResourceId, setMessage, validationMessage,
    pageMessage, formPayload, toBoolean
} from './utils.js';
import Swal from 'sweetalert2';

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
                    localStorage.setItem('grpl2_flash', JSON.stringify({
                        icon: 'success',
                        title: 'User Berhasil Ditambahkan',
                        text: response.message || 'User baru berhasil disimpan.',
                    }));
                    window.location.assign('/admin/users');
                } else {
                    await Swal.fire({
                        icon: 'success',
                        title: 'User Berhasil Diperbarui',
                        text: response.message || 'Data user berhasil diperbarui.',
                        confirmButtonText: 'Oke',
                    });
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
                    localStorage.setItem('grpl2_flash', JSON.stringify({
                        icon: 'success',
                        title: 'Program Studi Berhasil Ditambahkan',
                        text: response.message || 'Program studi baru berhasil disimpan.',
                    }));
                    window.location.assign('/admin/study-programs');
                } else {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Program Studi Berhasil Diperbarui',
                        text: response.message || 'Data program studi berhasil diperbarui.',
                        confirmButtonText: 'Oke',
                    });
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
                    localStorage.setItem('grpl2_flash', JSON.stringify({
                        icon: 'success',
                        title: 'Mata Kuliah Berhasil Ditambahkan',
                        text: response.message || 'Mata kuliah baru berhasil disimpan.',
                    }));
                    window.location.assign('/admin/courses');
                } else {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Mata Kuliah Berhasil Diperbarui',
                        text: response.message || 'Data mata kuliah berhasil diperbarui.',
                        confirmButtonText: 'Oke',
                    });
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

export function bootAdminPages() {
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

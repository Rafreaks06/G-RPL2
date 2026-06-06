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
    const hasFormData = options.body instanceof FormData;
    const headers = {
        Accept: 'application/json',
        ...(hasFormData ? {} : { 'Content-Type': 'application/json' }),
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

async function downloadRequest(path, fallbackName = 'document') {
    const headers = {
        Accept: 'application/octet-stream',
    };

    if (state.token) {
        headers.Authorization = `Bearer ${state.token}`;
    }

    const response = await fetch(`/api${path}`, {
        headers,
    });

    if (!response.ok) {
        const error = new Error('Download failed');
        error.status = response.status;
        throw error;
    }

    const blob = await response.blob();
    const disposition = response.headers.get('content-disposition') || '';
    const match = disposition.match(/filename="?([^"]+)"?/);
    const fileName = match?.[1] || fallbackName;
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');

    link.href = url;
    link.download = fileName;
    document.body.appendChild(link);
    link.click();
    link.remove();
    URL.revokeObjectURL(url);
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

function getApplicationTypeLabel(type) {
    const labels = {
        'a1': 'A1 - Formal',
        'a2': 'A2 - Informal/Nonformal',
        'hybrid': 'Hybrid'
    };
    return labels[type] || type;
}

function getApplicationStatusLabel(status) {
    const labels = {
        'draft': 'Draft',
        'submitted': 'Submitted',
        'under_review': 'Under Review',
        'returned': 'Returned',
        'under_assessment': 'Under Assessment',
        'assessed': 'Assessed',
        'approved': 'Approved',
        'rejected': 'Rejected'
    };
    return labels[status] || status;
}

function allowedApplicationSections(type) {
    return {
        a1: type === 'a1' || type === 'hybrid',
        a2: type === 'a2' || type === 'hybrid',
    };
}

function syncApplicationSections(type) {
    const allowed = allowedApplicationSections(type);

    document.querySelectorAll('[data-rpl-section]').forEach((element) => {
        const section = element.dataset.rplSection;
        element.hidden = section === 'a1' ? !allowed.a1 : !allowed.a2;
    });

    const activeTab = document.querySelector('[data-tab-button].active:not([hidden])');
    if (activeTab) {
        return;
    }

    const firstVisibleTab = document.querySelector('[data-tab-button]:not([hidden])');
    if (firstVisibleTab) {
        activateTab(firstVisibleTab.dataset.tabButton);
    }
}

function activateTab(tab) {
    document.querySelectorAll('[data-tab-content]').forEach((content) => {
        content.classList.toggle('active', content.dataset.tabContent === tab);
    });
    document.querySelectorAll('[data-tab-button]').forEach((button) => {
        button.classList.toggle('active', button.dataset.tabButton === tab);
    });
}

async function loadStudyProgramsForApplication() {
    const select = document.querySelector('[name="study_program_id"]');
    if (!select) {
        return;
    }

    try {
        const response = await apiRequest('/study-programs');
        const programs = collection(response);
        const selectedValue = select.value;

        select.innerHTML = programs.map((program) => `
            <option value="${program.id}" ${program.id == selectedValue ? 'selected' : ''}>
                ${escapeHtml(program.code)} - ${escapeHtml(program.name)}
            </option>
        `).join('');
    } catch (error) {
        console.error('Failed to load study programs:', error);
    }
}

function bindCreateApplication() {
    loadStudyProgramsForApplication();

    const button = document.querySelector('[data-create-application]');
    if (!button) {
        return;
    }

    button.addEventListener('click', async () => {
        const form = button.closest('form') || document.querySelector('.form-grid');
        const studyProgramId = form.querySelector('[name="study_program_id"]')?.value;
        const rplType = form.querySelector('[name="rpl_type"]')?.value;

        if (!studyProgramId || !rplType) {
            setMessage(form, 'Silakan isi semua field', 'error');
            return;
        }

        button.disabled = true;
        setMessage(form, 'Membuat aplikasi...', 'info');

        try {
            const path = rplType === 'hybrid'
                ? '/applicant/applications/hybrid'
                : '/applicant/applications';

            const response = await apiRequest(path, {
                method: 'POST',
                body: JSON.stringify({ study_program_id: Number(studyProgramId), rpl_type: rplType })
            });

            setMessage(form, response.message || 'Aplikasi berhasil dibuat', 'success');

            setTimeout(() => {
                window.location.assign(`/applications/${response.data.id}`);
            }, 1000);
        } catch (error) {
            setMessage(form, validationMessage(error), 'error');
            button.disabled = false;
        }
    });
}
async function loadApplications() {
    const target = document.querySelector('[data-applications-body]');
    if (!target) {
        return;
    }

    try {
        const response = await apiRequest('/applicant/applications');
        const applications = collection(response);

        target.innerHTML = applications.length
            ? applications.map((app) => {
                const status = app.status || 'draft';
                return `
                    <tr>
                        <td>${escapeHtml(app.application_number || '-')}</td>
                        <td>${escapeHtml(app.study_program?.name || '-')}</td>
                        <td>${getApplicationTypeLabel(app.rpl_type)}</td>
                        <td><span class="status-badge" data-status="${escapeHtml(status)}">${getApplicationStatusLabel(status)}</span></td>
                        <td>${escapeHtml(new Date(app.created_at).toLocaleDateString('id-ID'))}</td>
                        <td class="table-actions">
                            <a class="button button-small button-muted" href="/applications/${app.id}">Detail</a>
                        </td>
                    </tr>
                `;
            }).join('')
            : '<tr><td colspan="6">Belum ada aplikasi.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="6">Gagal memuat aplikasi.</td></tr>';
        pageMessage(validationMessage(error));
    }
}

async function loadApplicationDetail() {
    const applicationId = currentResourceId();
    if (!applicationId) {
        return;
    }

    try {
        const response = await apiRequest(`/applicant/applications/${applicationId}`);
        const app = response.data;
        const allowed = allowedApplicationSections(app.rpl_type);

        document.querySelector('[data-application-title]').textContent = `Application ${app.application_number}`;
        document.querySelector('[data-application-number]').textContent = app.application_number;
        document.querySelector('[data-application-status-badge]').textContent = getApplicationStatusLabel(app.status);
        syncApplicationSections(app.rpl_type);

        if (allowed.a1) {
            loadA1Courses(applicationId);
        }

        if (allowed.a2) {
            loadA2LearningExperiences(applicationId);
        }

        loadDocuments(applicationId);
        bindDocumentDownload(applicationId);
        bindSubmitApplication();
    } catch (error) {
        pageMessage(validationMessage(error));
    }
}

async function loadA1Courses(applicationId) {
    const target = document.querySelector('[data-a1-courses-body]');
    if (!target) {
        return;
    }
    const editable = document.body.dataset.page === 'application-edit';

    try {
        const response = await apiRequest(`/applicant/applications/${applicationId}/a1-courses`);
        const courses = collection(response);

        target.innerHTML = courses.length
            ? courses.map((course) => `
                <tr>
                    <td>${escapeHtml(course.course_code)}</td>
                    <td>${escapeHtml(course.course_name)}</td>
                    <td>${escapeHtml(course.credits)}</td>
                    <td>${escapeHtml(course.grade)}</td>
                    <td>${escapeHtml(course.institution_name)}</td>
                    ${editable ? `
                        <td class="table-actions">
                            <button
                                class="button button-small button-muted"
                                type="button"
                                data-edit-a1-course="${course.id}"
                                data-course-code="${escapeHtml(course.course_code)}"
                                data-course-name="${escapeHtml(course.course_name)}"
                                data-credits="${escapeHtml(course.credits)}"
                                data-grade="${escapeHtml(course.grade)}"
                                data-institution-name="${escapeHtml(course.institution_name)}"
                            >
                                Edit
                            </button>
                        </td>
                    ` : ''}
                </tr>
            `).join('')
            : `<tr><td colspan="${editable ? 6 : 5}">Belum ada data A1 course.</td></tr>`;
    } catch (error) {
        target.innerHTML = `<tr><td colspan="${editable ? 6 : 5}">Gagal memuat A1 courses.</td></tr>`;
        pageMessage(validationMessage(error));
    }
}

async function loadA2LearningExperiences(applicationId) {
    const target = document.querySelector('[data-a2-experiences-body]');
    if (!target) {
        return;
    }

    try {
        const response = await apiRequest(`/applicant/applications/${applicationId}/a2-learning-experiences`);
        const experiences = collection(response);

        target.innerHTML = experiences.length
            ? experiences.map((exp) => {
                const startDate = exp.start_date ? new Date(exp.start_date).toLocaleDateString('id-ID') : '-';
                const endDate = exp.end_date ? new Date(exp.end_date).toLocaleDateString('id-ID') : (exp.is_ongoing ? 'Ongoing' : '-');
                return `
                    <tr>
                        <td>${escapeHtml(exp.title)}</td>
                        <td>${escapeHtml(exp.experience_type)}</td>
                        <td>${escapeHtml(exp.organization_name)}</td>
                        <td>${startDate} - ${endDate}</td>
                    </tr>
                `;
            }).join('')
            : '<tr><td colspan="4">Belum ada data learning experience.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="4">Gagal memuat learning experiences.</td></tr>';
        pageMessage(validationMessage(error));
    }
}

async function loadDocuments(applicationId) {
    const target = document.querySelector('[data-documents-body]');
    if (!target) {
        return;
    }

    try {
        const response = await apiRequest(`/applicant/applications/${applicationId}/documents`);
        const documents = collection(response);

        target.innerHTML = documents.length
            ? documents.map((doc) => `
                <tr>
                    <td>${escapeHtml(doc.document_name)}</td>
                    <td>${escapeHtml(doc.document_type)}</td>
                    <td>${escapeHtml(doc.file_size || '-')}</td>
                    <td>${escapeHtml(new Date(doc.created_at).toLocaleDateString('id-ID'))}</td>
                    <td class="table-actions">
                        <button class="button button-small button-muted" type="button" data-download-document="${doc.id}" data-file-name="${escapeHtml(doc.file_name || doc.document_name || 'document')}">
                            Download
                        </button>
                    </td>
                </tr>
            `).join('')
            : '<tr><td colspan="5">Belum ada dokumen.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="5">Gagal memuat dokumen.</td></tr>';
        pageMessage(validationMessage(error));
    }
}

function bindDocumentDownload(applicationId) {
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-download-document]');
        if (!button) {
            return;
        }

        event.preventDefault();
        button.disabled = true;

        try {
            await downloadRequest(
                `/applicant/applications/${applicationId}/documents/${button.dataset.downloadDocument}/download`,
                button.dataset.fileName || 'document'
            );
        } catch (error) {
            pageMessage(validationMessage(error));
        } finally {
            button.disabled = false;
        }
    });
}

function bindDocumentUpload(applicationId) {
    const form = document.querySelector('[data-upload-form]');
    if (!form) {
        return;
    }

    const button = form.querySelector('[data-upload-document]');
    if (!button) {
        return;
    }

    button.addEventListener('click', async () => {
        const formData = new FormData(form);
        formData.append('application_id', applicationId);

        button.disabled = true;
        setMessage(form, 'Mengupload...', 'info');

        try {
            const response = await apiRequest(`/applicant/applications/${applicationId}/documents`, {
                method: 'POST',
                body: formData
            });

            setMessage(form, response.message || 'Dokumen berhasil diupload', 'success');
            form.reset();
            loadDocuments(applicationId);
        } catch (error) {
            setMessage(form, validationMessage(error), 'error');
        } finally {
            button.disabled = false;
        }
    });
}

async function submitApplication(applicationId) {
    if (!confirm('Submit aplikasi ini? Tidak dapat diubah setelah submit.')) {
        return;
    }

    const button = document.querySelector('[data-submit-application]');
    if (button) {
        button.disabled = true;
    }

    try {
        const response = await apiRequest(`/applicant/applications/${applicationId}/submit`, {
            method: 'POST',
            body: JSON.stringify({})
        });

        pageMessage(response.message || 'Aplikasi berhasil disubmit', 'success');

        setTimeout(() => {
            window.location.assign('/applications');
        }, 1500);
    } catch (error) {
        pageMessage(validationMessage(error));
        if (button) {
            button.disabled = false;
        }
    }
}

async function loadApplicationEdit() {
    const applicationId = currentResourceId();
    if (!applicationId) {
        return;
    }

    try {
        const response = await apiRequest(`/applicant/applications/${applicationId}`);
        const app = response.data;
        const allowed = allowedApplicationSections(app.rpl_type);

        document.querySelector('[data-application-title]').textContent = `Edit ${app.application_number}`;
        document.querySelector('[data-application-number]').textContent = app.application_number;
        document.querySelector('[data-application-status-badge]').textContent = getApplicationStatusLabel(app.status);
        syncApplicationSections(app.rpl_type);

        if (allowed.a1) {
            loadA1Courses(applicationId);
        }

        if (allowed.a2) {
            loadA2LearningExperiences(applicationId);
        }

        loadDocuments(applicationId);
        bindDocumentUpload(applicationId);
        bindDocumentDownload(applicationId);
        bindSubmitApplication();

        const submitSection = document.querySelector('[data-submit-section]');
        if (submitSection) {
            submitSection.hidden = app.status !== 'draft';
        }
    } catch (error) {
        pageMessage(validationMessage(error));
    }
}

function bindSubmitApplication() {
    const button = document.querySelector('[data-submit-application]');
    if (!button) {
        return;
    }

    button.addEventListener('click', () => {
        const applicationId = currentResourceId();
        if (applicationId) {
            submitApplication(applicationId);
        }
    });
}

const profileLabels = {
    gender: {
        male: 'Laki-laki',
        female: 'Perempuan',
    },
    marital_status: {
        single: 'Belum Kawin',
        married: 'Kawin',
        divorced: 'Cerai',
    },
};

const requiredProfileFields = [
    'birth_place',
    'birth_date',
    'gender',
    'marital_status',
    'nationality',
    'last_education',
    'institution_name',
    'graduation_year',
];

function formatProfileValue(key, value) {
    if (value === null || value === undefined || value === '') {
        return '-';
    }

    return profileLabels[key]?.[value] || value;
}

function isProfileComplete(profile) {
    return requiredProfileFields.every((field) => Boolean(profile?.[field]));
}

async function loadApplicantProfile() {
    const profileCard = document.querySelector('[data-profile-card]');
    const form = document.querySelector('[data-profile-form]');

    if (!profileCard && !form) {
        return;
    }

    try {
        const response = await apiRequest('/applicant/profile');
        const profile = response.data || {};

        if (profileCard) {
            Object.entries({
                nik: profile.nik,
                phone: profile.phone,
                address: profile.address,
                'birth-place': profile.birth_place,
                'birth-date': profile.birth_date,
                gender: formatProfileValue('gender', profile.gender),
                'marital-status': formatProfileValue('marital_status', profile.marital_status),
                nationality: profile.nationality,
                'postal-code': profile.postal_code,
                'last-education': profile.last_education,
                'institution-name': profile.institution_name,
                'study-program': profile.study_program,
                'graduation-year': profile.graduation_year,
            }).forEach(([key, value]) => {
                profileCard.querySelectorAll(`[data-profile-${key}]`).forEach((target) => {
                    target.textContent = formatProfileValue(key, value);
                });
            });

            const complete = isProfileComplete(profile);
            const badge = profileCard.querySelector('[data-profile-completeness-badge]');
            const note = profileCard.querySelector('[data-profile-completeness-note]');

            if (badge) {
                badge.textContent = complete ? 'Lengkap' : 'Belum lengkap';
                badge.dataset.status = complete ? 'active' : 'draft';
            }

            if (note) {
                note.textContent = complete
                    ? 'Profil sudah siap untuk membuat pengajuan RPL.'
                    : 'Lengkapi field wajib sebelum membuat pengajuan RPL.';
            }
        }

        if (form) {
            Object.entries({
                phone: profile.phone,
                address: profile.address,
                birth_place: profile.birth_place,
                birth_date: profile.birth_date,
                gender: profile.gender,
                marital_status: profile.marital_status,
                nationality: profile.nationality || 'Indonesia',
                postal_code: profile.postal_code,
                last_education: profile.last_education,
                institution_name: profile.institution_name,
                study_program: profile.study_program,
                graduation_year: profile.graduation_year,
            }).forEach(([key, value]) => {
                if (form.elements[key]) {
                    form.elements[key].value = value || '';
                }
            });
        }
    } catch (error) {
        pageMessage(validationMessage(error));
    }
}

function bindApplicantProfileForm() {
    const form = document.querySelector('[data-profile-form]');
    if (!form) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const button = form.querySelector('[data-save-profile]');
        const payload = formPayload(form);

        button.disabled = true;
        setMessage(form, 'Menyimpan...', 'info');

        try {
            const response = await apiRequest('/applicant/profile', {
                method: 'PUT',
                body: JSON.stringify(payload),
            });

            setMessage(form, response.message || 'Profil berhasil disimpan.', 'success');

            setTimeout(() => {
                window.location.assign('/profile');
            }, 1000);
        } catch (error) {
            setMessage(form, validationMessage(error), 'error');
        } finally {
            button.disabled = false;
        }
    });
}

function bindModalHandlers() {
    document.addEventListener('click', (event) => {
        const closeBtn = event.target.closest('[data-close-modal]');
        if (closeBtn) {
            const modalName = closeBtn.dataset.closeModal;
            const modal = document.querySelector(`[data-modal="${modalName}"]`);
            if (modal) {
                modal.hidden = true;
            }
        }
    });
}

function bindA1CourseModal() {
    const addButton = document.querySelector('[data-add-a1-course]');
    const modal = document.querySelector('[data-modal="a1-course"]');
    const form = document.querySelector('[data-a1-course-form]');
    const saveButton = document.querySelector('[data-save-a1-course]');
    const title = document.querySelector('[data-a1-course-modal-title]');

    if (!addButton || !modal || !form || !saveButton) {
        return;
    }

    const openCreateModal = () => {
        form.reset();
        delete form.dataset.courseId;
        if (title) {
            title.textContent = 'Add A1 Course';
        }
        saveButton.textContent = 'Save';
        setMessage(form, '', 'info');
        modal.hidden = false;
    };

    const openEditModal = (button) => {
        form.reset();
        form.dataset.courseId = button.dataset.editA1Course;
        form.elements.course_code.value = button.dataset.courseCode || '';
        form.elements.course_name.value = button.dataset.courseName || '';
        form.elements.credits.value = button.dataset.credits || '';
        form.elements.grade.value = button.dataset.grade || '';
        form.elements.institution_name.value = button.dataset.institutionName || '';
        if (title) {
            title.textContent = 'Edit A1 Course';
        }
        saveButton.textContent = 'Update';
        setMessage(form, '', 'info');
        modal.hidden = false;
    };

    addButton.addEventListener('click', () => {
        openCreateModal();
    });

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-edit-a1-course]');
        if (!button) {
            return;
        }

        event.preventDefault();
        openEditModal(button);
    });

    saveButton.addEventListener('click', async () => {
        const applicationId = currentResourceId();
        if (!applicationId) {
            return;
        }

        const payload = formPayload(form);

        saveButton.disabled = true;
        setMessage(form, 'Menyimpan...', 'info');

        try {
            const courseId = form.dataset.courseId;
            const response = await apiRequest(
                courseId
                    ? `/applicant/applications/${applicationId}/a1-courses/${courseId}`
                    : `/applicant/applications/${applicationId}/a1-courses`,
                {
                    method: courseId ? 'PUT' : 'POST',
                    body: JSON.stringify(payload)
                }
            );

            setMessage(form, response.message || 'A1 course berhasil disimpan', 'success');
            form.reset();
            delete form.dataset.courseId;

            setTimeout(() => {
                modal.hidden = true;
                loadA1Courses(applicationId);
            }, 1000);
        } catch (error) {
            setMessage(form, validationMessage(error), 'error');
        } finally {
            saveButton.disabled = false;
        }
    });
}

function bindA2ExperienceModal() {
    const addButton = document.querySelector('[data-add-a2-experience]');
    const modal = document.querySelector('[data-modal="a2-experience"]');
    const form = document.querySelector('[data-a2-experience-form]');
    const saveButton = document.querySelector('[data-save-a2-experience]');
    const isOngoingCheckbox = form?.querySelector('[data-is-ongoing]');
    const endDateInput = form?.querySelector('[data-end-date]');

    if (!addButton || !modal || !form || !saveButton) {
        return;
    }

    if (isOngoingCheckbox && endDateInput) {
        isOngoingCheckbox.addEventListener('change', () => {
            endDateInput.disabled = isOngoingCheckbox.checked;
            if (isOngoingCheckbox.checked) {
                endDateInput.value = '';
            }
        });
    }

    addButton.addEventListener('click', () => {
        form.reset();
        setMessage(form, '', 'info');
        if (endDateInput) {
            endDateInput.disabled = false;
        }
        modal.hidden = false;
    });

    saveButton.addEventListener('click', async () => {
        const applicationId = currentResourceId();
        if (!applicationId) {
            return;
        }

        const payload = formPayload(form, {
            booleanFields: ['is_ongoing']
        });

        saveButton.disabled = true;
        setMessage(form, 'Menyimpan...', 'info');

        try {
            const response = await apiRequest(`/applicant/applications/${applicationId}/a2-learning-experiences`, {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            setMessage(form, response.message || 'Learning experience berhasil ditambahkan', 'success');
            form.reset();

            setTimeout(() => {
                modal.hidden = true;
                loadA2LearningExperiences(applicationId);
            }, 1000);
        } catch (error) {
            setMessage(form, validationMessage(error), 'error');
        } finally {
            saveButton.disabled = false;
        }
    });
}

document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-tab-button]');
    if (button) {
        event.preventDefault();
        activateTab(button.dataset.tabButton);
    }
});

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
    const lastPart = parts.at(-1);

    if (['create', 'edit'].includes(lastPart)) {
        return parts.at(-2);
    }

    return lastPart;
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

function navigationItemsForRole(role) {
    const shared = [
        { href: '/dashboard', label: 'Dashboard' },
    ];

    const roleItems = {
        applicant: [
            { href: '/profile', label: 'Profile' },
            { href: '/applications', label: 'Applications' },
            { href: '/applications/create', label: 'Create Application' },
        ],
        assessor: [
            { href: '/assessments', label: 'Assessments' },
        ],
        committee: [
            { href: '/approvals', label: 'Approvals' },
        ],
        staff_rpl: [
            { href: '/submissions', label: 'Submissions' },
        ],
        system_admin: [
            { href: '/admin/users', label: 'Users' },
            { href: '/admin/master-data', label: 'Master Data' },
            { href: '/admin/study-programs', label: 'Study Programs' },
            { href: '/admin/courses', label: 'Courses' },
        ],
    };

    return [
        ...shared,
        ...(roleItems[role] || []),
    ];
}

function isActiveSidebarItem(href, currentPath) {
    if (href === '/dashboard') {
        return currentPath === href;
    }

    return currentPath === href || currentPath.startsWith(`${href}/`);
}

function renderSidebarNavigation(user) {
    const role = roleOf(user);
    const currentPath = window.location.pathname;
    const items = navigationItemsForRole(role);
    const activeItem = items
        .filter((item) => isActiveSidebarItem(item.href, currentPath))
        .sort((a, b) => b.href.length - a.href.length)[0];

    document.querySelectorAll('.sidebar').forEach((sidebar) => {
        sidebar.querySelector('[data-sidebar-nav]')?.remove();

        if (!state.token || !user || !items.length) {
            return;
        }

        const nav = document.createElement('nav');
        nav.className = 'sidebar-nav';
        nav.dataset.sidebarNav = '';
        nav.setAttribute('aria-label', 'Role navigation');

        nav.innerHTML = `
            <span class="sidebar-nav-label">Menu</span>
            ${items.map((item) => `
                <a href="${item.href}" class="${activeItem?.href === item.href ? 'active' : ''}" data-nav-link>
                    ${item.label}
                </a>
            `).join('')}
            <button type="button" data-logout>Logout</button>
        `;

        sidebar.appendChild(nav);
    });

    bindLogout();
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

    renderSidebarNavigation(user);
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

function bootApplicantPages() {
    const page = document.body.dataset.page;

    bindModalHandlers();
    bindApplicantProfileForm();

    if (page === 'profile' || page === 'profile-edit') {
        loadApplicantProfile();
    }

    if (page === 'applications') {
        loadApplications();
    }

    if (page === 'applications-create') {
        bindCreateApplication();
    }

    if (page === 'application-detail') {
        loadApplicationDetail();
    }

    if (page === 'application-edit') {
        loadApplicationEdit();
        bindA1CourseModal();
        bindA2ExperienceModal();
    }
}

async function loadSubmissions() {
    const target = document.querySelector('[data-submissions-body]');
    if (!target) {
        return;
    }

    try {
        const response = await apiRequest('/staff/submissions');
        const submissions = collection(response);

        target.innerHTML = submissions.length
            ? submissions.map((sub) => {
                const applicantName = sub.applicant?.user?.name || '-';
                const studyProgram = sub.study_program?.name || '-';
                const status = sub.status || 'submitted';
                const submittedAt = sub.submitted_at
                    ? new Date(sub.submitted_at).toLocaleDateString('id-ID')
                    : '-';
                return `
                    <tr>
                        <td>${escapeHtml(sub.application_number || '-')}</td>
                        <td>${escapeHtml(applicantName)}</td>
                        <td>${escapeHtml(studyProgram)}</td>
                        <td>${getApplicationTypeLabel(sub.rpl_type)}</td>
                        <td><span class="status-badge" data-status="${escapeHtml(status)}">${getApplicationStatusLabel(status)}</span></td>
                        <td>${submittedAt}</td>
                        <td class="table-actions">
                            <a class="button button-small button-muted" href="/submissions/${sub.id}">Detail</a>
                        </td>
                    </tr>
                `;
            }).join('')
            : '<tr><td colspan="7">Tidak ada submission yang perlu direview.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="7">Gagal memuat submissions.</td></tr>';
        pageMessage(validationMessage(error));
    }
}

async function loadSubmissionDetail() {
    const applicationId = currentResourceId();
    if (!applicationId) {
        return;
    }

    try {
        const response = await apiRequest(`/staff/submissions/${applicationId}`);
        const sub = response.data;
        const allowed = allowedApplicationSections(sub.rpl_type);

        document.querySelector('[data-submission-title]').textContent = `Submission ${sub.application_number}`;
        document.querySelector('[data-submission-number]').textContent = sub.application_number;
        document.querySelector('[data-submission-status-badge]').textContent = getApplicationStatusLabel(sub.status);

        const applicantName = sub.applicant?.user?.name || '-';
        const applicantEmail = sub.applicant?.user?.email || '-';
        const studyProgram = sub.study_program?.name || '-';
        const submittedAt = sub.submitted_at
            ? new Date(sub.submitted_at).toLocaleDateString('id-ID')
            : '-';

        document.querySelector('[data-detail-applicant-name]').textContent = applicantName;
        document.querySelector('[data-detail-applicant-email]').textContent = applicantEmail;
        document.querySelector('[data-detail-study-program]').textContent = studyProgram;
        document.querySelector('[data-detail-rpl-type]').textContent = getApplicationTypeLabel(sub.rpl_type);
        document.querySelector('[data-detail-submitted-at]').textContent = submittedAt;
        document.querySelector('[data-detail-revision-count]').textContent = sub.revision_count ?? 0;
        document.querySelector('[data-detail-review-notes]').textContent = sub.review_notes || '-';
        document.querySelector('[data-detail-assessor]').textContent =
            sub.assigned_assessor?.name || '-';

        const reviewBtn = document.querySelector('[data-review-application]');
        if (reviewBtn) {
            reviewBtn.hidden = sub.status !== 'submitted';
        }

        const returnBtn = document.querySelector('[data-return-application]');
        if (returnBtn) {
            returnBtn.hidden = sub.status !== 'under_review';
        }

        const assignBtn = document.querySelector('[data-assign-assessor]');
        if (assignBtn) {
            assignBtn.hidden = sub.status !== 'under_review';
        }

        syncApplicationSections(sub.rpl_type);

        if (allowed.a1 && sub.a1_courses) {
            renderA1Courses(sub.a1_courses);
        }
        if (allowed.a2 && sub.a2_learning_experiences) {
            renderA2Experiences(sub.a2_learning_experiences);
        }
        if (sub.documents) {
            renderDocuments(sub.documents, applicationId);
        }
    } catch (error) {
        pageMessage(validationMessage(error));
    }
}

function renderA1Courses(courses) {
    const target = document.querySelector('[data-a1-courses-body]');
    if (!target) return;

    target.innerHTML = courses.length
        ? courses.map((course) => `
            <tr>
                <td>${escapeHtml(course.course_code)}</td>
                <td>${escapeHtml(course.course_name)}</td>
                <td>${escapeHtml(course.credits)}</td>
                <td>${escapeHtml(course.grade)}</td>
                <td>${escapeHtml(course.institution_name)}</td>
            </tr>
        `).join('')
        : '<tr><td colspan="5">Tidak ada data A1 course.</td></tr>';
}

function renderA2Experiences(experiences) {
    const target = document.querySelector('[data-a2-experiences-body]');
    if (!target) return;

    target.innerHTML = experiences.length
        ? experiences.map((exp) => {
            const startDate = exp.start_date ? new Date(exp.start_date).toLocaleDateString('id-ID') : '-';
            const endDate = exp.end_date ? new Date(exp.end_date).toLocaleDateString('id-ID') : (exp.is_ongoing ? 'Ongoing' : '-');
            return `
                <tr>
                    <td>${escapeHtml(exp.title)}</td>
                    <td>${escapeHtml(exp.experience_type)}</td>
                    <td>${escapeHtml(exp.organization_name)}</td>
                    <td>${startDate} - ${endDate}</td>
                </tr>
            `;
        }).join('')
        : '<tr><td colspan="4">Tidak ada data learning experience.</td></tr>';
}

function renderDocuments(documents, applicationId) {
    const target = document.querySelector('[data-documents-body]');
    if (!target) return;

    target.innerHTML = documents.length
        ? documents.map((doc) => `
            <tr>
                <td>${escapeHtml(doc.document_name)}</td>
                <td>${escapeHtml(doc.document_type)}</td>
                <td>${escapeHtml(doc.file_size || '-')}</td>
                <td>${escapeHtml(new Date(doc.created_at).toLocaleDateString('id-ID'))}</td>
                <td class="table-actions">
                    <button class="button button-small button-muted" type="button" data-download-document="${doc.id}" data-file-name="${escapeHtml(doc.file_name || doc.document_name || 'document')}">
                        Download
                    </button>
                </td>
            </tr>
        `).join('')
        : '<tr><td colspan="5">Tidak ada dokumen.</td></tr>';

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-download-document]');
        if (!button) return;

        event.preventDefault();
        button.disabled = true;

        try {
            await downloadRequest(
                `/staff/submissions/${applicationId}/documents/${button.dataset.downloadDocument}/download`,
                button.dataset.fileName || 'document'
            );
        } catch (error) {
            pageMessage(validationMessage(error));
        } finally {
            button.disabled = false;
        }
    });
}

function bindStaffActions() {
    const applicationId = currentResourceId();
    if (!applicationId) return;

    const reviewBtn = document.querySelector('[data-review-application]');
    if (reviewBtn) {
        reviewBtn.addEventListener('click', async () => {
            if (!confirm('Mulai review aplikasi ini?')) return;
            reviewBtn.disabled = true;

            try {
                const response = await apiRequest(`/staff/submissions/${applicationId}/review`, {
                    method: 'PATCH',
                    body: JSON.stringify({})
                });
                pageMessage(response.message || 'Review dimulai.', 'success');
                setTimeout(() => loadSubmissionDetail(), 800);
            } catch (error) {
                pageMessage(validationMessage(error));
            } finally {
                reviewBtn.disabled = false;
            }
        });
    }

    const returnModal = document.querySelector('[data-modal="return-submission"]');
    const returnForm = document.querySelector('[data-return-form]');
    const submitReturnBtn = document.querySelector('[data-submit-return]');

    document.addEventListener('click', (event) => {
        const openBtn = event.target.closest('[data-return-application]');
        if (openBtn && returnModal) {
            returnModal.hidden = false;
        }
    });

    if (submitReturnBtn && returnForm) {
        submitReturnBtn.addEventListener('click', async () => {
            const notes = returnForm.elements.review_notes.value.trim();
            if (!notes) {
                setMessage(returnForm, 'Catatan review wajib diisi.', 'error');
                return;
            }

            submitReturnBtn.disabled = true;
            setMessage(returnForm, 'Mengembalikan...', 'info');

            try {
                const response = await apiRequest(`/staff/submissions/${applicationId}/return`, {
                    method: 'PATCH',
                    body: JSON.stringify({ review_notes: notes })
                });
                setMessage(returnForm, response.message || 'Aplikasi dikembalikan.', 'success');
                setTimeout(() => {
                    returnModal.hidden = true;
                    returnForm.reset();
                    loadSubmissionDetail();
                }, 800);
            } catch (error) {
                setMessage(returnForm, validationMessage(error), 'error');
            } finally {
                submitReturnBtn.disabled = false;
            }
        });
    }

    const assignModal = document.querySelector('[data-modal="assign-assessor"]');
    const assignForm = document.querySelector('[data-assign-form]');
    const submitAssignBtn = document.querySelector('[data-submit-assign]');
    const assessorSelect = document.querySelector('[data-assessor-select]');

    document.addEventListener('click', async (event) => {
        const openBtn = event.target.closest('[data-assign-assessor]');
        if (openBtn && assignModal) {
            assignModal.hidden = false;
            await loadAssessorOptions();
        }
    });

    if (submitAssignBtn && assignForm) {
        submitAssignBtn.addEventListener('click', async () => {
            const assessorId = assignForm.elements.assessor_id.value;
            if (!assessorId) {
                setMessage(assignForm, 'Pilih assessor terlebih dahulu.', 'error');
                return;
            }

            submitAssignBtn.disabled = true;
            setMessage(assignForm, 'Menugaskan...', 'info');

            try {
                const response = await apiRequest(`/staff/submissions/${applicationId}/assign-assessor`, {
                    method: 'PATCH',
                    body: JSON.stringify({ assessor_id: Number(assessorId) })
                });
                setMessage(assignForm, response.message || 'Assessor ditugaskan.', 'success');
                setTimeout(() => {
                    assignModal.hidden = true;
                    assignForm.reset();
                    loadSubmissionDetail();
                }, 800);
            } catch (error) {
                setMessage(assignForm, validationMessage(error), 'error');
            } finally {
                submitAssignBtn.disabled = false;
            }
        });
    }
}

async function loadAssessorOptions() {
    const select = document.querySelector('[data-assessor-select]');
    if (!select) return;

    try {
        const response = await apiRequest('/staff/assessors');
        const assessors = collection(response);

        select.innerHTML = assessors.length
            ? '<option value="">-- Pilih Assessor --</option>' +
            assessors.map((a) => {
                const name = a.name || '-';
                const nip = a.assessor?.nip || '';
                return `<option value="${a.id}">${escapeHtml(name)}${nip ? ' (' + escapeHtml(nip) + ')' : ''}</option>`;
            }).join('')
            : '<option value="">Tidak ada assessor tersedia</option>';
    } catch (error) {
        select.innerHTML = '<option value="">Gagal memuat assessor</option>';
    }
}

function bootStaffPages() {
    const page = document.body.dataset.page;

    if (page === 'submissions') {
        loadSubmissions();
    }

    if (page === 'submission-detail') {
        loadSubmissionDetail();
        bindStaffActions();
    }
}

function boot() {
    syncNavigation();
    bindAuthForms();
    bindLogout();
    hydrateAuthenticatedPage();
    bootAdminPages();
    bootApplicantPages();
    bootStaffPages();
}

document.addEventListener('DOMContentLoaded', boot);

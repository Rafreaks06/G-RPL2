import { apiRequest, downloadRequest } from './api.js';
import {
    escapeHtml, collection, currentResourceId, setMessage, validationMessage,
    pageMessage, getApplicationTypeLabel, getApplicationStatusLabel,
    allowedApplicationSections, syncApplicationSections, formPayload, activateTab
} from './utils.js';
import Swal from 'sweetalert2';

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

function setText(selector, value = '-') {
    const element = document.querySelector(selector);

    if (!element) {
        return;
    }

    element.textContent = value ?? '-';
}

function setAllText(selector, value = '-') {
    document.querySelectorAll(selector).forEach((element) => {
        element.textContent = value ?? '-';
    });
}

function safePageMessage(message, type = 'error') {
    const target = document.querySelector('[data-page-message]');

    if (target) {
        pageMessage(message, type);
    } else {
        console.warn(message);
    }
}

function safeDate(value) {
    if (!value) {
        return '-';
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return '-';
    }

    return date.toLocaleDateString('id-ID');
}

function safeStatusClass(status) {
    const normalized = String(status || 'draft').toLowerCase();

    if (normalized.includes('approved')) {
        return 'applications-status-badge applications-status-approved status-badge status-approved';
    }

    if (normalized.includes('rejected')) {
        return 'applications-status-badge applications-status-rejected status-badge status-rejected';
    }

    if (normalized.includes('submit') || normalized.includes('review')) {
        return 'applications-status-badge applications-status-submitted status-badge status-submitted';
    }

    return 'applications-status-badge applications-status-draft status-badge status-draft';
}

function formatProfileValue(key, value) {
    if (value === null || value === undefined || value === '') {
        return '-';
    }

    return profileLabels[key]?.[value] || value;
}

function isProfileComplete(profile) {
    return requiredProfileFields.every((field) => Boolean(profile?.[field]));
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

        select.innerHTML = `
            <option value="">Pilih program studi</option>
            ${programs.map((program) => `
                <option value="${program.id}" ${program.id == selectedValue ? 'selected' : ''}>
                    ${escapeHtml(program.code)} - ${escapeHtml(program.name)}
                </option>
            `).join('')}
        `;
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

        if (!form) {
            return;
        }

        const studyProgramId = form.querySelector('[name="study_program_id"]')?.value;
        const rplType = form.querySelector('[name="rpl_type"]')?.value;

        if (!studyProgramId || !rplType) {
            await Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan isi semua field.',
                confirmButtonText: 'Oke',
            });
            return;
        }

        button.disabled = true;

        try {
            const path = rplType === 'hybrid'
                ? '/applicant/applications/hybrid'
                : '/applicant/applications';

            const response = await apiRequest(path, {
                method: 'POST',
                body: JSON.stringify({
                    study_program_id: Number(studyProgramId),
                    rpl_type: rplType
                })
            });

            await Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message || 'Aplikasi berhasil dibuat.',
                timer: 1500,
                showConfirmButton: false,
            });

            window.location.assign(`/applications/${response.data.id}`);
        } catch (error) {
            console.error('[Create Application]', error);
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: validationMessage(error),
                confirmButtonText: 'Tutup',
            });
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
                        <td>
                            <span class="${safeStatusClass(status)}" data-status="${escapeHtml(status)}">
                                ${getApplicationStatusLabel(status)}
                            </span>
                        </td>
                        <td>${escapeHtml(safeDate(app.created_at))}</td>
                        <td>
                            <div class="applications-actions application-actions">
                                <a class="applications-action-btn applications-detail-btn application-action-btn application-detail-btn" href="/applications/${app.id}">
                                    Detail
                                </a>

                                <a class="applications-action-btn applications-edit-btn application-action-btn application-edit-btn" href="/applications/${app.id}/edit">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('')
            : '<tr><td colspan="6">Belum ada aplikasi.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="6">Gagal memuat aplikasi.</td></tr>';
        safePageMessage(validationMessage(error), 'error');
    }
}

async function loadA1Courses(applicationId) {
    const target = document.querySelector('[data-a1-courses-body]');

    if (!target) {
        return;
    }

    try {
        const response = await apiRequest(`/applicant/applications/${applicationId}/a1-courses`);
        const courses = collection(response);

        target.innerHTML = courses.length
            ? courses.map((course) => `
                <tr>
                    <td>${escapeHtml(course.course_code || '-')}</td>
                    <td>${escapeHtml(course.course_name || '-')}</td>
                    <td>${escapeHtml(course.credits || '-')}</td>
                    <td>${escapeHtml(course.grade || '-')}</td>
                    <td>${escapeHtml(course.institution_name || '-')}</td>
                    <td>
                        <div class="applications-actions application-actions">
                            <button
                                class="applications-action-btn applications-edit-btn application-action-btn application-edit-btn"
                                type="button"
                                data-edit-a1-course="${course.id}"
                                data-course-code="${escapeHtml(course.course_code || '')}"
                                data-course-name="${escapeHtml(course.course_name || '')}"
                                data-credits="${escapeHtml(course.credits || '')}"
                                data-grade="${escapeHtml(course.grade || '')}"
                                data-institution-name="${escapeHtml(course.institution_name || '')}"
                            >
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('')
            : '<tr><td colspan="6">Belum ada data A1 course.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="6">Gagal memuat A1 courses.</td></tr>';
        safePageMessage(validationMessage(error), 'error');
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
                const startDate = exp.start_date ? safeDate(exp.start_date) : '-';
                const endDate = exp.end_date ? safeDate(exp.end_date) : (exp.is_ongoing ? 'Ongoing' : '-');

                return `
                    <tr>
                        <td>${escapeHtml(exp.title || '-')}</td>
                        <td>${escapeHtml(exp.experience_type || '-')}</td>
                        <td>${escapeHtml(exp.organization_name || '-')}</td>
                        <td>${escapeHtml(`${startDate} - ${endDate}`)}</td>
                    </tr>
                `;
            }).join('')
            : '<tr><td colspan="4">Belum ada data learning experience.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="4">Gagal memuat learning experiences.</td></tr>';
        safePageMessage(validationMessage(error), 'error');
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
                    <td>${escapeHtml(doc.document_name || '-')}</td>
                    <td>${escapeHtml(doc.document_type || '-')}</td>
                    <td>${escapeHtml(doc.file_size || '-')}</td>
                    <td>${escapeHtml(safeDate(doc.created_at))}</td>
                    <td>
                        <div class="applications-actions application-actions">
                            <button
                                class="applications-action-btn applications-detail-btn application-action-btn application-detail-btn"
                                type="button"
                                data-download-document="${doc.id}"
                                data-file-name="${escapeHtml(doc.file_name || doc.document_name || 'document')}"
                            >
                                Download
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('')
            : '<tr><td colspan="5">Belum ada dokumen.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="5">Gagal memuat dokumen.</td></tr>';
        safePageMessage(validationMessage(error), 'error');
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
            console.error('[Download Document]', error);
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: validationMessage(error),
                confirmButtonText: 'Tutup',
            });
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

        try {
            const response = await apiRequest(`/applicant/applications/${applicationId}/documents`, {
                method: 'POST',
                body: formData
            });

            await Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message || 'Dokumen berhasil diunggah.',
                timer: 1500,
                showConfirmButton: false,
            });
            form.reset();
            loadDocuments(applicationId);
        } catch (error) {
            console.error('[Upload Document]', error);
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: validationMessage(error),
                confirmButtonText: 'Tutup',
            });
        } finally {
            button.disabled = false;
        }
    });
}

async function submitApplication(applicationId) {
    const confirm = await Swal.fire({
        icon: 'warning',
        title: 'Submit Aplikasi?',
        text: 'Aplikasi ini akan disubmit. Tidak dapat diubah setelah submit.',
        showCancelButton: true,
        confirmButtonText: 'Ya, Submit',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#1565C0',
    });

    if (!confirm.isConfirmed) {
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

        await Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: response.message || 'Aplikasi berhasil disubmit.',
            timer: 1500,
            showConfirmButton: false,
        });

        window.location.assign('/applications');
    } catch (error) {
        console.error('[Submit Application]', error);
        await Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: validationMessage(error),
            confirmButtonText: 'Tutup',
        });

        if (button) {
            button.disabled = false;
        }
    }
}

async function loadApplicationDetail() {
    const applicationId = currentResourceId();

    if (!applicationId) {
        return;
    }

    try {
        const response = await apiRequest(`/applicant/applications/${applicationId}`);
        const app = response.data || {};
        const allowed = allowedApplicationSections(app.rpl_type);

        setText('[data-application-title]', `Application ${app.application_number || '-'}`);
        setText('[data-application-number]', app.application_number || 'Application Number');
        setText('[data-application-status-badge]', getApplicationStatusLabel(app.status || 'draft'));

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
        safePageMessage(validationMessage(error), 'error');
    }
}

async function loadApplicationEdit() {
    const applicationId = currentResourceId();

    if (!applicationId) {
        return;
    }

    try {
        const response = await apiRequest(`/applicant/applications/${applicationId}`);
        const app = response.data || {};
        const allowed = allowedApplicationSections(app.rpl_type);

        setText('[data-application-title]', `Edit ${app.application_number || '-'}`);
        setText('[data-application-number]', app.application_number || 'Application Number');
        setText('[data-application-status-badge]', getApplicationStatusLabel(app.status || 'draft'));

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
        safePageMessage(validationMessage(error), 'error');
    }
}

function bindSubmitApplication() {
    const button = document.querySelector('[data-submit-application]');

    if (!button || button.dataset.boundSubmit === 'true') {
        return;
    }

    button.dataset.boundSubmit = 'true';

    button.addEventListener('click', () => {
        const applicationId = currentResourceId();

        if (applicationId) {
            submitApplication(applicationId);
        }
    });
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
        safePageMessage(validationMessage(error), 'error');
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

        if (button) {
            button.disabled = true;
        }

        try {
            const response = await apiRequest('/applicant/profile', {
                method: 'PUT',
                body: JSON.stringify(payload),
            });

            await Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message || 'Profil berhasil disimpan.',
                timer: 1500,
                showConfirmButton: false,
            });

            window.location.assign('/profile');
        } catch (error) {
            console.error('[Save Profile]', error);
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: validationMessage(error),
                confirmButtonText: 'Tutup',
            });
        } finally {
            if (button) {
                button.disabled = false;
            }
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
    const modal = document.querySelector('[data-modal="a1-course"]');
    const form = document.querySelector('[data-a1-course-form]');
    const saveButton = document.querySelector('[data-save-a1-course]');
    const title = document.querySelector('[data-a1-course-modal-title]');

    if (!modal || !form || !saveButton) {
        return;
    }

    const openCreateModal = () => {
        form.reset();
        delete form.dataset.courseId;

        if (title) {
            title.textContent = 'Tambah Mata Kuliah A1';
        }

        saveButton.textContent = 'Simpan';
        setMessage(form, '', 'info');
        modal.hidden = false;
    };

    const openEditModal = (button) => {
        form.reset();
        form.dataset.courseId = button.dataset.editA1Course;

        if (form.elements.course_code) {
            form.elements.course_code.value = button.dataset.courseCode || '';
        }

        if (form.elements.course_name) {
            form.elements.course_name.value = button.dataset.courseName || '';
        }

        if (form.elements.credits) {
            form.elements.credits.value = button.dataset.credits || '';
        }

        if (form.elements.grade) {
            form.elements.grade.value = button.dataset.grade || '';
        }

        if (form.elements.institution_name) {
            form.elements.institution_name.value = button.dataset.institutionName || '';
        }

        if (title) {
            title.textContent = 'Edit Mata Kuliah A1';
        }

        saveButton.textContent = 'Update';
        setMessage(form, '', 'info');
        modal.hidden = false;
    };

    const addButton = document.querySelector('[data-add-a1-course]');

    if (addButton) {
        addButton.addEventListener('click', () => {
            openCreateModal();
        });
    }

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

            await Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message || 'A1 course berhasil disimpan.',
                timer: 1500,
                showConfirmButton: false,
            });
            form.reset();
            delete form.dataset.courseId;

            modal.hidden = true;
            loadA1Courses(applicationId);
        } catch (error) {
            console.error('[Save A1 Course]', error);
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: validationMessage(error),
                confirmButtonText: 'Tutup',
            });
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

        try {
            const response = await apiRequest(`/applicant/applications/${applicationId}/a2-learning-experiences`, {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            await Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message || 'Pengalaman pembelajaran berhasil ditambahkan.',
                timer: 1500,
                showConfirmButton: false,
            });
            form.reset();

            modal.hidden = true;
            loadA2LearningExperiences(applicationId);
        } catch (error) {
            console.error('[Save A2 Experience]', error);
            await Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: validationMessage(error),
                confirmButtonText: 'Tutup',
            });
        } finally {
            saveButton.disabled = false;
        }
    });
}

export function bootApplicantPages() {
    const page = document.body.dataset.page;

    bindModalHandlers();
    bindApplicantProfileForm();

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-tab-button]');

        if (button) {
            event.preventDefault();
            activateTab(button.dataset.tabButton);
        }
    });

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
        bindA1CourseModal();
        bindA2ExperienceModal();
    }

    if (page === 'application-edit') {
        loadApplicationEdit();
        bindA1CourseModal();
        bindA2ExperienceModal();
    }
}

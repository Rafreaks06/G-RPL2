import { apiRequest, downloadRequest } from './api.js';
import {
    escapeHtml, collection, currentResourceId, setMessage, validationMessage,
    pageMessage, getApplicationStatusLabel, getApplicationTypeLabel,
    allowedApplicationSections, syncApplicationSections
} from './utils.js';
import Swal from 'sweetalert2';

const assessmentState = {
    applicationId: null,
    assessmentId: null,
    rplType: null,
    a1Courses: [],
    a2Experiences: [],
    hasAssessment: false,
    usedA1SourceIds: new Set(),
    usedA2SourceIds: new Set(),
};

function currentAssessmentId() {
    const el = document.querySelector('[data-assessment-id]');
    return el ? el.dataset.assessmentId : null;
}

async function loadAssessments() {
    const target = document.querySelector('[data-assessments-body]');
    if (!target) return;

    try {
        const response = await apiRequest('/assessor/assessments');
        const assessments = collection(response);

        target.innerHTML = assessments.length
            ? assessments.map((app) => {
                const applicantName = app.applicant?.user?.name || '-';
                const studyProgram = app.study_program?.name || '-';
                const status = app.status || 'under_assessment';
                const submittedAt = app.submitted_at || app.created_at
                    ? new Date(app.submitted_at || app.created_at).toLocaleDateString('id-ID')
                    : '-';
                return `
                    <tr>
                        <td>${escapeHtml(app.application_number || '-')}</td>
                        <td>${escapeHtml(applicantName)}</td>
                        <td>${escapeHtml(studyProgram)}</td>
                        <td>${getApplicationTypeLabel(app.rpl_type)}</td>
                        <td><span class="status-badge" data-status="${escapeHtml(status)}">${getApplicationStatusLabel(status)}</span></td>
                        <td>${submittedAt}</td>
                        <td class="table-actions">
                            <a class="button button-small button-muted" href="/assessments/${app.id}">Detail</a>
                        </td>
                    </tr>
                `;
            }).join('')
            : '<tr><td colspan="7">Tidak ada assessment yang ditugaskan.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="7">Gagal memuat assessments.</td></tr>';
        pageMessage(validationMessage(error));
    }
}

async function loadAssessmentDetail() {
    const applicationId = currentResourceId();
    if (!applicationId) return;

    try {
        const response = await apiRequest(`/assessor/assessments/${applicationId}`);
        const app = response.data;
        const allowed = allowedApplicationSections(app.rpl_type);

        assessmentState.applicationId = applicationId;
        assessmentState.assessmentId = app.assessment?.id || null;
        assessmentState.rplType = app.rpl_type;
        assessmentState.hasAssessment = Boolean(app.assessment);

        if (app.a1_courses) {
            assessmentState.a1Courses = app.a1_courses;
        }
        if (app.a2_learning_experiences) {
            assessmentState.a2Experiences = app.a2_learning_experiences;
        }

        document.querySelector('[data-assessment-title]').textContent = `Assessment ${app.application_number}`;
        document.querySelector('[data-assessment-number]').textContent = app.application_number;
        document.querySelector('[data-assessment-status-badge]').textContent = getApplicationStatusLabel(app.status);

        document.querySelector('[data-detail-applicant-name]').textContent = app.applicant?.user?.name || '-';
        document.querySelector('[data-detail-applicant-email]').textContent = app.applicant?.user?.email || '-';
        document.querySelector('[data-detail-study-program]').textContent = app.study_program?.name || '-';
        document.querySelector('[data-detail-rpl-type]').textContent = getApplicationTypeLabel(app.rpl_type);
        document.querySelector('[data-detail-total-sks]').textContent = app.assessment?.total_converted_sks ?? '-';
        document.querySelector('[data-detail-recommendation]').textContent = app.assessment?.recommendation || '-';
        document.querySelector('[data-detail-notes]').textContent = app.assessment?.notes || app.notes || '-';
        document.querySelector('[data-detail-submitted-at]').textContent = app.created_at
            ? new Date(app.created_at).toLocaleDateString('id-ID')
            : '-';

        const shell = document.querySelector('[data-protected-shell]');
        if (shell) {
            shell.dataset.assessmentId = assessmentState.assessmentId || '';
        }

        syncApplicationSections(app.rpl_type);

        const hasAssessment = assessmentState.hasAssessment;
        const canSubmit = hasAssessment && app.status === 'under_assessment';

        const createBtn = document.querySelector('[data-create-assessment]');
        if (createBtn) {
            createBtn.hidden = hasAssessment;
        }

        const submitBtn = document.querySelector('[data-submit-assessment]');
        if (submitBtn) {
            submitBtn.hidden = !canSubmit;
        }

        const shouldShowMapping = hasAssessment && app.status === 'under_assessment';
        const mappingActions = document.querySelector('[data-mapping-actions]');
        if (mappingActions) {
            mappingActions.dataset.allowMapping = shouldShowMapping ? 'true' : 'false';
            mappingActions.hidden = true;
        }

        const addA1Btn = document.querySelector('[data-add-a1-mapping]');
        if (addA1Btn) {
            addA1Btn.hidden = !hasAssessment || app.status !== 'under_assessment' || !allowed.a1;
        }

        const addA2Btn = document.querySelector('[data-add-a2-mapping]');
        if (addA2Btn) {
            addA2Btn.hidden = !hasAssessment || app.status !== 'under_assessment' || !allowed.a2;
        }

        if (allowed.a1 && app.a1_courses) {
            renderA1CoursesStatic(app.a1_courses);
        }

        if (allowed.a2 && app.a2_learning_experiences) {
            renderA2ExperiencesStatic(app.a2_learning_experiences);
        }

        if (hasAssessment) {
            loadAssessmentMappings(app.assessment.id);
        }

        if (app.documents) {
            renderAssessmentDocuments(app.documents, applicationId);
        }

        // sync visibility mapping actions berdasarkan tab aktif
        if (window.syncMappingActionsVisibility) {
            window.syncMappingActionsVisibility();
        }

    } catch (error) {

        if (
            error?.status === 404 ||
            error?.status === 403
        ) {
            Swal.fire({
                title: 'Akses Ditolak',
                text: 'Assessment tidak ditemukan',
                icon: 'error'
            }).then(() => {
                window.location.replace('/assessments');
            });

            return;
        }

        pageMessage(validationMessage(error));
    }
}

function renderA1CoursesStatic(courses) {
    const target = document.querySelector('[data-a1-courses-body]');
    if (!target) return;

    target.innerHTML = courses.length
        ? courses.map((course) => `
            <tr data-course-id="${escapeHtml(course.id)}">
                <td>${escapeHtml(course.course_code)}</td>
                <td>${escapeHtml(course.course_name)}</td>
                <td>${escapeHtml(course.credits)}</td>
                <td>${escapeHtml(course.grade)}</td>
                <td>${escapeHtml(course.institution_name)}</td>
            </tr>
        `).join('')
        : '<tr><td colspan="5">Tidak ada data A1 course.</td></tr>';
}

function renderA2ExperiencesStatic(experiences) {
    const target = document.querySelector('[data-a2-experiences-body]');
    if (!target) return;

    target.innerHTML = experiences.length
        ? experiences.map((exp) => {
            const startDate = exp.start_date ? new Date(exp.start_date).toLocaleDateString('id-ID') : '-';
            const endDate = exp.end_date ? new Date(exp.end_date).toLocaleDateString('id-ID') : (exp.is_ongoing ? 'Ongoing' : '-');
            return `
                <tr data-experience-id="${escapeHtml(exp.id)}">
                    <td>${escapeHtml(exp.title)}</td>
                    <td>${escapeHtml(exp.experience_type)}</td>
                    <td>${escapeHtml(exp.organization_name)}</td>
                    <td>${startDate} - ${endDate}</td>
                    <td>${escapeHtml(exp.description || '-')}</td>
                </tr>
            `;
        }).join('')
        : '<tr><td colspan="5">Tidak ada data learning experience.</td></tr>';
}

async function loadAssessmentMappings(assessmentId) {
    const target = document.querySelector('[data-assessment-mappings-body]');
    if (!target) return;

    try {
        const response = await apiRequest(`/assessor/assessments/${assessmentId}/mappings`);
        const mappings = collection(response);

        assessmentState.usedA1SourceIds = new Set(
            mappings
                .filter(m => m.application_a1_course_id)
                .map(m => String(m.application_a1_course_id))
        );

        assessmentState.usedA2SourceIds = new Set(
            mappings
                .filter(m => m.application_a2_learning_experience_id)
                .map(m => String(m.application_a2_learning_experience_id))
        );

        assessmentState.usedTargetCourseIds = new Set(
            mappings
                .filter(m => m.course_id)
                .map(m => String(m.course_id))
        );

        target.innerHTML = mappings.length
            ? mappings.map((m) => {
                const sourceName = m.application_a1_course?.course_name
                    || m.application_a2_learning_experience?.title
                    || '-';
                const sourceType = m.application_a1_course ? 'A1' : (m.application_a2_learning_experience ? 'A2' : '-');
                const courseName = m.course?.name || '-';
                const sks = m.course?.sks || '-';
                const recognized = m.is_recognited ?? m.is_recognized;
                return `
                    <tr>
                        <td>${escapeHtml(sourceName)}</td>
                        <td>${escapeHtml(sourceType)}</td>
                        <td>${escapeHtml(courseName)}</td>
                        <td>${escapeHtml(sks)}</td>
                        <td><span class="status-badge" data-status="${recognized ? 'active' : 'draft'}">${recognized ? 'Ya' : 'Tidak'}</span></td>
                        <td>${escapeHtml(m.notes || '-')}</td>
                    </tr>
                `;
            }).join('')
            : '<tr><td colspan="6">Belum ada mapping.</td></tr>';
    } catch (error) {
        target.innerHTML = '<tr><td colspan="6">Gagal memuat mapping.</td></tr>';
    }
}

function renderAssessmentDocuments(documents, applicationId) {
    const target = document.querySelector('[data-documents-body]');
    if (!target) return;

    target.innerHTML = documents.length
        ? documents.map((doc) => `
            <tr>
                <td>${escapeHtml(doc.document_name || doc.file_name || '-')}</td>
                <td>${escapeHtml(doc.document_type || doc.type || '-')}</td>
                <td class="table-actions">
                    <button class="button button-small button-muted" type="button" data-download-assessment-doc="${doc.id}" data-file-name="${escapeHtml(doc.file_name || doc.document_name || 'document')}">
                        Download
                    </button>
                </td>
            </tr>
        `).join('')
        : '<tr><td colspan="3">Tidak ada dokumen.</td></tr>';

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-download-assessment-doc]');
        if (!button) return;

        event.preventDefault();
        button.disabled = true;

        try {
            await downloadRequest(
                `/assessor/assessments/${applicationId}/documents/${button.dataset.downloadAssessmentDoc}/download`,
                button.dataset.fileName || 'document'
            );
        } catch (error) {
            pageMessage(validationMessage(error));
        } finally {
            button.disabled = false;
        }
    });
}

function bindAssessorActions() {
    const applicationId = currentResourceId();
    if (!applicationId) return;

    const createBtn = document.querySelector('[data-create-assessment]');
    if (createBtn) {
        createBtn.addEventListener('click', async () => {
            const confirm = await Swal.fire({
                title: 'Mulai Penilaian?',
                text: 'Penilaian untuk aplikasi ini akan dimulai. Lanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Mulai',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
            });

            if (!confirm.isConfirmed) return;
            createBtn.disabled = true;

            try {
                await apiRequest(`/assessor/assessments/${applicationId}`, {
                    method: 'POST',
                    body: JSON.stringify({ notes: '' })
                });

                await Swal.fire({
                    title: 'Penilaian Dimulai',
                    text: 'Penilaian berhasil dibuat. Silakan tambahkan mapping mata kuliah.',
                    icon: 'success',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });

                loadAssessmentDetail();
            } catch (error) {
                Swal.fire({
                    title: 'Gagal Memulai Penilaian',
                    text: 'Penilaian untuk aplikasi ini sudah pernah dibuat sebelumnya.',
                    icon: 'error',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });
            } finally {
                createBtn.disabled = false;
            }
        });
    }

    const submitBtn = document.querySelector('[data-submit-assessment]');
    if (submitBtn) {
        submitBtn.addEventListener('click', async () => {
            const confirm = await Swal.fire({
                title: 'Submit Penilaian?',
                text: 'Penilaian akan difinalisasi dan tidak dapat diubah setelah ini. Pastikan semua mapping sudah benar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Submit',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
            });

            if (!confirm.isConfirmed) return;
            submitBtn.disabled = true;

            try {
                const response = await apiRequest(`/assessor/assessments/${assessmentState.assessmentId}/submit`, {
                    method: 'POST',
                    body: JSON.stringify({})
                });

                const recommendation = response.data?.recommendation;
                const totalSks = response.data?.total_converted_sks ?? 0;

                if (recommendation === 'pass') {
                    await Swal.fire({
                        title: 'Penilaian Disetujui',
                        text: `Penilaian berhasil disubmit. Total ${totalSks} SKS berhasil dikonversi.`,
                        icon: 'success',
                        confirmButtonText: 'Oke',
                        confirmButtonColor: '#2563eb',
                    });
                } else {
                    await Swal.fire({
                        title: 'Penilaian Tidak Lolos',
                        text: 'Tidak ada mata kuliah yang diakui dalam penilaian ini. Aplikasi dikembalikan ke pemohon.',
                        icon: 'info',
                        confirmButtonText: 'Oke',
                        confirmButtonColor: '#2563eb',
                    });
                }

                window.location.replace('/assessments');
            } catch (error) {
                const status = error?.status;

                const message = status === 422
                    ? 'Penilaian tidak dapat disubmit. Pastikan minimal ada satu mapping dan penilaian belum pernah disubmit sebelumnya.'
                    : 'Terjadi kesalahan saat mengsubmit penilaian. Silakan coba beberapa saat lagi.';

                Swal.fire({
                    title: 'Gagal Submit Penilaian',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });
            } finally {
                submitBtn.disabled = false;
            }
        });
    }

    const addA1Btn = document.querySelector('[data-add-a1-mapping]');
    const addA2Btn = document.querySelector('[data-add-a2-mapping]');

    if (addA1Btn) {
        addA1Btn.addEventListener('click', () => openMappingModal('a1'));
    }

    if (addA2Btn) {
        addA2Btn.addEventListener('click', () => openMappingModal('a2'));
    }

    function openMappingModal(sourceType) {
        const isA1 = sourceType === 'a1';

        const sources = isA1 ? assessmentState.a1Courses : assessmentState.a2Experiences;
        const labelKey = isA1 ? 'course_name' : 'title';
        const idAttr = isA1 ? 'course_code' : 'experience_type';

        const usedSourceIds = isA1 ? assessmentState.usedA1SourceIds : assessmentState.usedA2SourceIds;
        const availableSources = sources.filter(s => !usedSourceIds.has(String(s.id)));

        const accentColor = isA1 ? '#2563eb' : '#7c3aed';
        const accentBg = isA1 ? '#eff6ff' : '#f5f3ff';
        const accentText = isA1 ? '#1e40af' : '#5b21b6';
        const sourceLabel = isA1 ? 'Mata Kuliah A1' : 'Pengalaman Belajar A2';
        const modalTitle = isA1
            ? 'Mapping Mata Kuliah — A1'
            : 'Mapping Mata Kuliah — A2';

        let allCourses = [];

        const renderSourceOptions = () => {
            const sourceSelect = document.getElementById('swal-mapping-source');
            if (!sourceSelect) return;
            sourceSelect.innerHTML = availableSources.length
                ? `<option value="">— Pilih ${escapeHtml(sourceLabel)} —</option>` +
                availableSources.map(s =>
                    `<option value="${s.id}">${escapeHtml(s[labelKey] || s[idAttr] || 'Item ' + s.id)}</option>`
                ).join('')
                : '<option value="">Semua sumber sudah mapping</option>';
        };

        // A1: dropdown biasa, exclude used target courses
        const renderCourseOptionsDropdown = (courses) => {
            const courseSelect = document.getElementById('swal-mapping-course');
            if (!courseSelect) return;
            const filtered = courses.filter(c => !assessmentState.usedTargetCourseIds.has(String(c.id)));
            courseSelect.innerHTML = filtered.length
                ? '<option value="">— Pilih Mata Kuliah Tujuan —</option>' +
                filtered.map(c =>
                    `<option value="${c.id}">${escapeHtml(c.code || '')} ${escapeHtml(c.name)} - Sem ${escapeHtml(String(c.semester))} (${escapeHtml(String(c.sks))} SKS)</option>`
                ).join('')
                : '<option value="">Tidak ada course tersedia</option>';
        };

        // A2: checkbox list di dalam dropdown panel
        const renderCourseCheckboxes = (courses) => {
            const container = document.getElementById('swal-course-checkbox-list');
            if (!container) return;
            const filtered = courses.filter(c => !assessmentState.usedTargetCourseIds.has(String(c.id)));
            container.innerHTML = filtered.length
                ? filtered.map(c => `
                <label class="sm-checkbox-item">
                    <input type="checkbox" class="sm-course-checkbox" value="${c.id}">
                    <span class="sm-checkbox-label">
                        <span class="sm-checkbox-code">${escapeHtml(c.code || '')}</span>
                        ${escapeHtml(c.name)}
                        <span class="sm-checkbox-meta">Sem ${escapeHtml(String(c.semester))} · ${escapeHtml(String(c.sks))} SKS</span>
                    </span>
                </label>
            `).join('')
                : '<p class="sm-empty-courses">Tidak ada course tersedia</p>';

            // Re-bind tag update setelah list di-render ulang
            syncMultiSelectTags();
            container.removeEventListener('change', syncMultiSelectTags);
            container.addEventListener('change', syncMultiSelectTags);
        };

        // Sync tags pada trigger A2 multi-select
        const syncMultiSelectTags = () => {
            const msTags = document.getElementById('sm-ms-tags');
            if (!msTags) return;
            const checked = [...document.querySelectorAll('.sm-course-checkbox:checked')];
            msTags.innerHTML = '';
            if (!checked.length) {
                msTags.innerHTML = '<span class="sm-ms-placeholder">— Pilih Mata Kuliah Tujuan —</span>';
                return;
            }
            checked.forEach(cb => {
                const itemLabel = cb.closest('.sm-checkbox-item')?.querySelector('.sm-checkbox-label');
                const code = itemLabel?.querySelector('.sm-checkbox-code')?.textContent?.trim() || '';
                const name = itemLabel
                    ? [...itemLabel.childNodes]
                        .filter(n => n.nodeType === 3 && n.textContent.trim())
                        .map(n => n.textContent.trim())
                        .join('') || ''
                    : cb.value;
                const tag = document.createElement('span');
                tag.className = 'sm-ms-tag';
                tag.dataset.val = cb.value;
                tag.innerHTML = `${escapeHtml(code)} ${escapeHtml(name)}<span class="sm-ms-tag-x" data-val="${cb.value}" aria-label="Hapus">×</span>`;
                msTags.appendChild(tag);
            });
        };

        const fetchCourses = (studyProgramId, semester) => {
            if (isA1) {
                const courseSelect = document.getElementById('swal-mapping-course');
                if (courseSelect) courseSelect.innerHTML = '<option value="">Memuat course...</option>';
            } else {
                const container = document.getElementById('swal-course-checkbox-list');
                if (container) container.innerHTML = '<p class="sm-empty-courses">Memuat course...</p>';
            }

            const params = new URLSearchParams();
            if (studyProgramId) params.set('study_program_id', studyProgramId);
            if (semester) params.set('semester', semester);

            const url = '/courses' + (params.toString() ? '?' + params.toString() : '');
            apiRequest(url)
                .then(response => {
                    allCourses = collection(response);
                    if (isA1) {
                        renderCourseOptionsDropdown(allCourses);
                    } else {
                        renderCourseCheckboxes(allCourses);
                    }
                })
                .catch(() => {
                    if (isA1) {
                        const courseSelect = document.getElementById('swal-mapping-course');
                        if (courseSelect) courseSelect.innerHTML = '<option value="">Gagal memuat course</option>';
                    } else {
                        const container = document.getElementById('swal-course-checkbox-list');
                        if (container) container.innerHTML = '<p class="sm-empty-courses">Gagal memuat course</p>';
                    }
                });
        };

        // HTML field A1: dropdown biasa (tidak berubah)
        // HTML field A2: trigger + panel dropdown multi-select
        const courseFieldHtml = isA1
            ? `<div class="sm-field" id="swal-mapping-course-wrapper" style="display:none;">
                <label class="sm-label">Mata Kuliah Tujuan</label>
                <select id="swal-mapping-course" class="sm-select">
                    <option value="">— Pilih Mata Kuliah Tujuan —</option>
                </select>
              </div>`
            : `<div class="sm-field" id="swal-mapping-course-wrapper" style="display:none;">
                <label class="sm-label">Mata Kuliah Tujuan <span style="font-weight:400;color:#94a3b8;">(pilih satu atau lebih)</span></label>
                <div id="sm-ms-trigger" class="sm-ms-trigger" role="button" tabindex="0" aria-haspopup="listbox" aria-expanded="false">
                    <div class="sm-ms-tags" id="sm-ms-tags">
                        <span class="sm-ms-placeholder">— Pilih Mata Kuliah Tujuan —</span>
                    </div>
                    <span class="sm-ms-chevron" id="sm-ms-chevron" aria-hidden="true">▾</span>
                </div>
                <div class="sm-ms-panel" id="sm-ms-panel" role="listbox">
                    <input type="text" id="sm-ms-search" class="sm-ms-search" placeholder="Cari mata kuliah...">
                    <div id="swal-course-checkbox-list" class="sm-checkbox-list"></div>
                </div>
              </div>`;

        Swal.fire({
            title: modalTitle,
            width: 480,
            padding: '1.25rem 1.5rem 1.5rem',
            customClass: { popup: 'swal-mapping-popup' },
            html: `
            <style>
                .swal-mapping-popup { font-size: 14px !important; }
                .swal-mapping-popup .swal2-title {
                    font-size: 17px !important;
                    padding-bottom: 0 !important;
                    margin-bottom: 0 !important;
                }
                .swal-mapping-popup .swal2-html-container {
                    margin: 0.5rem 0 0 !important;
                    padding: 0 !important;
                    overflow: visible !important;
                    text-align: left !important;
                }
                .sm-badge {
                    display: inline-block;
                    background: ${accentBg};
                    color: ${accentText};
                    font-size: 11px;
                    font-weight: 600;
                    padding: 2px 10px;
                    border-radius: 4px;
                    margin-bottom: 12px;
                }
                .sm-field { margin-bottom: 10px; }
                .sm-label {
                    display: block;
                    font-size: 12px;
                    font-weight: 500;
                    color: #64748b;
                    margin-bottom: 3px;
                }
                .sm-row { display: flex; gap: 8px; }
                .sm-row .sm-field { flex: 1; }
                .sm-select, .sm-textarea {
                    width: 100%;
                    box-sizing: border-box;
                    margin: 0 !important;
                    font-family: inherit;
                    font-size: 13px !important;
                    padding: 6px 10px !important;
                    border: 1px solid #e2e8f0 !important;
                    border-radius: 6px !important;
                    background: #fff !important;
                    color: #1e293b !important;
                    height: auto !important;
                }
                .sm-select:focus, .sm-textarea:focus {
                    outline: none !important;
                    border-color: ${accentColor} !important;
                    box-shadow: 0 0 0 2px ${accentBg} !important;
                }
                .sm-chip-group { display: flex; gap: 6px; }
                .sm-chip {
                    flex: 1;
                    padding: 6px 0;
                    border: 1px solid #e2e8f0;
                    border-radius: 6px;
                    background: #f8fafc;
                    color: #64748b;
                    font-size: 12px;
                    font-weight: 500;
                    text-align: center;
                    cursor: pointer;
                    transition: all 0.15s;
                    user-select: none;
                }
                .sm-chip:hover { border-color: #cbd5e1; background: #f1f5f9; }
                .sm-chip.active {
                    background: ${accentBg};
                    border-color: ${accentColor};
                    color: ${accentText};
                }

                /* ── A2 Multi-select dropdown ── */
                .sm-ms-trigger {
                    display: flex;
                    align-items: center;
                    min-height: 34px;
                    padding: 4px 8px 4px 10px;
                    gap: 6px;
                    border: 1px solid #e2e8f0;
                    border-radius: 6px;
                    background: #fff;
                    cursor: pointer;
                    box-sizing: border-box;
                    transition: border-color 0.15s, box-shadow 0.15s;
                }
                .sm-ms-trigger:hover { border-color: #cbd5e1; }
                .sm-ms-trigger.open {
                    border-color: ${accentColor};
                    box-shadow: 0 0 0 2px ${accentBg};
                    border-bottom-left-radius: 0;
                    border-bottom-right-radius: 0;
                }
                .sm-ms-tags {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 3px;
                    flex: 1;
                }
                .sm-ms-placeholder {
                    font-size: 13px;
                    color: #94a3b8;
                    line-height: 1.6;
                }
                .sm-ms-tag {
                    display: inline-flex;
                    align-items: center;
                    gap: 3px;
                    background: ${accentBg};
                    color: ${accentText};
                    font-size: 11px;
                    font-weight: 500;
                    padding: 2px 6px;
                    border-radius: 4px;
                    line-height: 1.5;
                    max-width: 180px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                .sm-ms-tag-x {
                    cursor: pointer;
                    font-size: 12px;
                    color: ${accentColor};
                    flex-shrink: 0;
                    line-height: 1;
                    padding: 0 1px;
                }
                .sm-ms-tag-x:hover { color: ${accentText}; }
                .sm-ms-chevron {
                    font-size: 13px;
                    color: #94a3b8;
                    flex-shrink: 0;
                    transition: transform 0.15s;
                    user-select: none;
                    line-height: 1;
                }
                .sm-ms-chevron.open { transform: rotate(180deg); }
                .sm-ms-panel {
                    border: 1px solid ${accentColor};
                    border-top: none;
                    border-bottom-left-radius: 6px;
                    border-bottom-right-radius: 6px;
                    background: #fff;
                    display: none;
                    overflow: hidden;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                }
                .sm-ms-panel.open { display: block; }
                .sm-ms-search {
                    width: 100%;
                    box-sizing: border-box;
                    border: none;
                    border-bottom: 1px solid #e2e8f0;
                    padding: 6px 10px;
                    font-size: 12px;
                    background: #f8fafc;
                    color: #1e293b;
                    font-family: inherit;
                }
                .sm-ms-search:focus { outline: none; background: #fff; }

                /* Checkbox list di dalam panel */
                .sm-checkbox-list {
                    max-height: 150px;
                    overflow-y: auto;
                    padding: 4px;
                }
                .sm-checkbox-item {
                    display: flex;
                    align-items: flex-start;
                    gap: 8px;
                    padding: 5px 8px;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: background 0.1s;
                }
                .sm-checkbox-item:hover { background: ${accentBg}; }
                .sm-course-checkbox {
                    margin-top: 2px;
                    flex-shrink: 0;
                    accent-color: ${accentColor};
                    width: 13px;
                    height: 13px;
                    cursor: pointer;
                }
                .sm-checkbox-label {
                    font-size: 13px;
                    color: #1e293b;
                    line-height: 1.4;
                    cursor: pointer;
                }
                .sm-checkbox-code {
                    font-weight: 600;
                    margin-right: 4px;
                }
                .sm-checkbox-meta {
                    display: block;
                    font-size: 11px;
                    color: #94a3b8;
                    margin-top: 1px;
                }
                .sm-empty-courses {
                    font-size: 12px;
                    color: #94a3b8;
                    text-align: center;
                    padding: 12px 0;
                    margin: 0;
                }
            </style>

            <div class="sm-badge">${escapeHtml(isA1 ? 'A1 · Mata Kuliah Sebelumnya' : 'A2 · Pengalaman Belajar')}</div>

            <div class="sm-field">
                <label class="sm-label">${escapeHtml(sourceLabel)}</label>
                <select id="swal-mapping-source" class="sm-select"></select>
            </div>

            <div class="sm-row" id="swal-filter-row" style="display:none;">
                <div class="sm-field">
                    <label class="sm-label">Program Studi</label>
                    <select id="swal-mapping-program" class="sm-select">
                        <option value="">Memuat prodi...</option>
                    </select>
                </div>
                <div class="sm-field">
                    <label class="sm-label">Semester</label>
                    <select id="swal-mapping-semester" class="sm-select">
                        <option value="">Semua</option>
                        ${[1, 2, 3, 4, 5, 6, 7, 8].map(s => `<option value="${s}">Sem ${s}</option>`).join('')}
                    </select>
                </div>
            </div>

            <div class="sm-field">
                <label class="sm-label">Status Pengakuan</label>
                <div class="sm-chip-group">
                    <div class="sm-chip active" id="chip-tidak" data-value="0">Tidak Diakui</div>
                    <div class="sm-chip" id="chip-diakui" data-value="1">Diakui</div>
                </div>
                <input type="hidden" id="swal-mapping-recognized" value="0">
            </div>

            ${courseFieldHtml}

            <div class="sm-field" style="margin-bottom:0;">
                <label class="sm-label">Catatan <span style="font-weight:400;">(opsional)</span></label>
                <textarea id="swal-mapping-notes" class="sm-textarea" style="height:60px;resize:none;" placeholder="Catatan tambahan..."></textarea>
            </div>
        `,
            icon: undefined,
            showCancelButton: true,
            confirmButtonText: 'Simpan Mapping',
            cancelButtonText: 'Batal',
            confirmButtonColor: accentColor,
            cancelButtonColor: '#64748b',
            focusConfirm: false,
            didOpen: () => {
                renderSourceOptions();

                const programSelect = document.getElementById('swal-mapping-program');
                const semesterSelect = document.getElementById('swal-mapping-semester');
                const recognizedInput = document.getElementById('swal-mapping-recognized');
                const courseWrapper = document.getElementById('swal-mapping-course-wrapper');
                const chipTidak = document.getElementById('chip-tidak');
                const chipDiakui = document.getElementById('chip-diakui');
                const filterRow = document.getElementById('swal-filter-row');

                apiRequest('/study-programs')
                    .then(response => {
                        const programs = collection(response);
                        programSelect.innerHTML = '<option value="">— Semua Prodi —</option>' +
                            programs.map(p => `<option value="${p.id}">${escapeHtml(p.name)}</option>`).join('');
                        fetchCourses(null, null);
                    })
                    .catch(() => {
                        programSelect.innerHTML = '<option value="">Gagal memuat prodi</option>';
                        fetchCourses(null, null);
                    });

                programSelect.addEventListener('change', () =>
                    fetchCourses(programSelect.value || null, semesterSelect.value || null)
                );
                semesterSelect.addEventListener('change', () =>
                    fetchCourses(programSelect.value || null, semesterSelect.value || null)
                );

                const setRecognized = (value) => {
                    recognizedInput.value = value;
                    const isRecognized = value === '1';
                    chipTidak.classList.toggle('active', !isRecognized);
                    chipDiakui.classList.toggle('active', isRecognized);
                    courseWrapper.style.display = isRecognized ? 'block' : 'none';
                    filterRow.style.display = isRecognized ? 'flex' : 'none';
                    if (!isRecognized) {
                        if (isA1) {
                            const courseSelect = document.getElementById('swal-mapping-course');
                            if (courseSelect) courseSelect.value = '';
                        } else {
                            document.querySelectorAll('.sm-course-checkbox').forEach(cb => cb.checked = false);
                            syncMultiSelectTags();
                            // Tutup panel kalau lagi buka
                            document.getElementById('sm-ms-panel')?.classList.remove('open');
                            document.getElementById('sm-ms-trigger')?.classList.remove('open');
                            document.getElementById('sm-ms-chevron')?.classList.remove('open');
                        }
                        programSelect.value = '';
                        semesterSelect.value = '';
                    }
                };

                chipTidak.addEventListener('click', () => setRecognized('0'));
                chipDiakui.addEventListener('click', () => setRecognized('1'));

                // ── Setup multi-select dropdown untuk A2 ──
                if (!isA1) {
                    const msTrigger = document.getElementById('sm-ms-trigger');
                    const msPanel = document.getElementById('sm-ms-panel');
                    const msChevron = document.getElementById('sm-ms-chevron');
                    const msSearch = document.getElementById('sm-ms-search');

                    let msOpen = false;

                    const openPanel = () => {
                        msOpen = true;
                        msTrigger.classList.add('open');
                        msTrigger.setAttribute('aria-expanded', 'true');
                        msPanel.classList.add('open');
                        msChevron.classList.add('open');
                        setTimeout(() => msSearch?.focus(), 30);
                    };

                    const closePanel = () => {
                        msOpen = false;
                        msTrigger.classList.remove('open');
                        msTrigger.setAttribute('aria-expanded', 'false');
                        msPanel.classList.remove('open');
                        msChevron.classList.remove('open');
                        if (msSearch) msSearch.value = '';
                        // Reset semua item yang tersembunyi saat search
                        document.querySelectorAll('.sm-checkbox-item').forEach(item => item.style.display = '');
                    };

                    msTrigger.addEventListener('click', (e) => {
                        // Klik X pada tag → hapus item, jangan toggle panel
                        if (e.target.classList.contains('sm-ms-tag-x')) {
                            const val = e.target.dataset.val;
                            const cb = document.querySelector(`.sm-course-checkbox[value="${val}"]`);
                            if (cb) { cb.checked = false; syncMultiSelectTags(); }
                            return;
                        }
                        msOpen ? closePanel() : openPanel();
                    });

                    // Tutup panel kalau klik di luar area modal ini
                    document.addEventListener('click', (e) => {
                        if (!msOpen) return;
                        if (!msTrigger.contains(e.target) && !msPanel.contains(e.target)) {
                            closePanel();
                        }
                    });

                    // Search filter
                    msSearch?.addEventListener('input', () => {
                        const q = msSearch.value.toLowerCase();
                        document.querySelectorAll('.sm-checkbox-item').forEach(item => {
                            item.style.display = item.textContent.toLowerCase().includes(q) ? '' : 'none';
                        });
                    });

                    // Keyboard: Enter / Space buka panel, Escape tutup
                    msTrigger.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); msOpen ? closePanel() : openPanel(); }
                        if (e.key === 'Escape') closePanel();
                    });

                    // Bind change event awal (sebelum course di-fetch)
                    document.getElementById('swal-course-checkbox-list')?.addEventListener('change', syncMultiSelectTags);
                }
            },
            preConfirm: () => {
                const sourceId = document.getElementById('swal-mapping-source').value;
                const recognized = document.getElementById('swal-mapping-recognized').value === '1';
                const notes = document.getElementById('swal-mapping-notes').value.trim();

                if (!sourceId) {
                    Swal.showValidationMessage(`Pilih ${sourceLabel} terlebih dahulu.`);
                    return false;
                }

                if (recognized) {
                    if (isA1) {
                        const courseId = document.getElementById('swal-mapping-course').value;
                        if (!courseId) {
                            Swal.showValidationMessage('Pilih mata kuliah tujuan untuk mapping yang diakui.');
                            return false;
                        }
                        return { sourceId, courseIds: [courseId], recognized, notes };
                    } else {
                        const checked = [...document.querySelectorAll('.sm-course-checkbox:checked')];
                        if (!checked.length) {
                            Swal.showValidationMessage('Pilih minimal satu mata kuliah tujuan untuk mapping yang diakui.');
                            return false;
                        }
                        return { sourceId, courseIds: checked.map(cb => cb.value), recognized, notes };
                    }
                }

                return { sourceId, courseIds: [], recognized, notes };
            }
        }).then(async (result) => {
            if (!result.isConfirmed || !result.value) return;

            if (!assessmentState.assessmentId) {
                Swal.fire({
                    title: 'Gagal Menyimpan Mapping',
                    text: 'Assessment belum dibuat.',
                    icon: 'error',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });
                return;
            }

            const { sourceId, courseIds, recognized, notes } = result.value;

            try {
                if (!recognized || courseIds.length === 0) {
                    const payload = { is_recognized: false, notes };
                    if (isA1) {
                        payload.application_a1_course_id = Number(sourceId);
                    } else {
                        payload.application_a2_learning_experience_id = Number(sourceId);
                    }
                    await apiRequest(`/assessor/assessments/${assessmentState.assessmentId}/mappings`, {
                        method: 'POST',
                        body: JSON.stringify(payload)
                    });
                } else {
                    for (const courseId of courseIds) {
                        const payload = { is_recognized: true, course_id: Number(courseId), notes };
                        if (isA1) {
                            payload.application_a1_course_id = Number(sourceId);
                        } else {
                            payload.application_a2_learning_experience_id = Number(sourceId);
                        }
                        await apiRequest(`/assessor/assessments/${assessmentState.assessmentId}/mappings`, {
                            method: 'POST',
                            body: JSON.stringify(payload)
                        });
                    }
                }

                await Swal.fire({
                    title: 'Mapping Tersimpan',
                    text: recognized && courseIds.length > 1
                        ? `${courseIds.length} mata kuliah berhasil di-mapping.`
                        : 'Mapping mata kuliah berhasil ditambahkan.',
                    icon: 'success',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });

                loadAssessmentMappings(assessmentState.assessmentId);
            } catch (error) {
                const status = error?.status;
                const message = status === 422
                    ? 'Mapping gagal disimpan. Kemungkinan mata kuliah tujuan sudah dipakai di mapping lain, atau sumber tidak valid.'
                    : 'Terjadi kesalahan saat menyimpan mapping. Silakan coba beberapa saat lagi.';

                Swal.fire({
                    title: 'Gagal Menyimpan Mapping',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });
            }
        });
    }
}

export function bootAssessorPages() {
    const page = document.body.dataset.page;

    if (page === 'assessments') {
        loadAssessments();
    }

    if (page === 'assessment-detail') {
        loadAssessmentDetail();
        bindAssessorActions();
    }
}

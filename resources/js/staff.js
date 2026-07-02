import { apiRequest, downloadRequest } from './api.js';
import {
    escapeHtml, collection, currentResourceId, setMessage, validationMessage,
    pageMessage, getApplicationTypeLabel, getApplicationStatusLabel,
    allowedApplicationSections, syncApplicationSections, formatFileSize
} from './utils.js';
import Swal from 'sweetalert2';

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
            renderDocuments(sub.documents);
        }
    } catch (error) {
        if ([403, 404].includes(error?.status)) {
            Swal.fire({
                title: 'Akses Ditolak',
                text: 'Submission tidak ditemukan atau tidak dapat diakses.',
                icon: 'error'
            }).then(() => {
                window.location.replace('/submissions');
            });

            return;
        }

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

function renderDocuments(documents) {
    const target = document.querySelector('[data-documents-body]');
    if (!target) return;

    target.innerHTML = documents.length
        ? documents.map((doc) => `
            <tr>
                <td>${escapeHtml(doc.document_name)}</td>
                <td>${escapeHtml(doc.document_type)}</td>
                <td>${escapeHtml(formatFileSize(doc.file_size))}</td>
                <td>${escapeHtml(new Date(doc.created_at).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        }))}</td>
                <td class="table-actions">
                    <button class="button button-small button-muted" type="button" data-download-document="${doc.id}" data-file-name="${escapeHtml(doc.file_name || doc.document_name || 'document')}">
                        Download
                    </button>
                </td>
            </tr>
        `).join('')
        : '<tr><td colspan="5">Tidak ada dokumen.</td></tr>';
}

function bindDocumentDownload(applicationId) {
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
            await Swal.fire({
                icon: 'error',
                title: 'Gagal Mengunduh Dokumen',
                text: 'Dokumen tidak ditemukan atau sudah tidak tersedia.',
                confirmButtonText: 'Tutup',
            });
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
            const confirm = await Swal.fire({
                title: 'Mulai Review?',
                text: 'Aplikasi akan dipindahkan ke status "Sedang Direview". Lanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Mulai Review',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
            });

            if (!confirm.isConfirmed) return;
            reviewBtn.disabled = true;

            try {
                await apiRequest(`/staff/submissions/${applicationId}/review`, {
                    method: 'PATCH',
                    body: JSON.stringify({})
                });

                await Swal.fire({
                    title: 'Review Dimulai',
                    text: 'Aplikasi berhasil dipindahkan ke status "Sedang Direview".',
                    icon: 'success',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });

                loadSubmissionDetail();
            } catch (error) {
                Swal.fire({
                    title: 'Gagal Memulai Review',
                    text: 'Hanya aplikasi dengan status "Submitted" yang bisa direview. Pastikan status aplikasi sudah benar.',
                    icon: 'error',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });
            } finally {
                reviewBtn.disabled = false;
            }
        });
    }

    const returnBtn = document.querySelector('[data-return-application]');
    if (returnBtn) {
        returnBtn.addEventListener('click', async () => {
            const { value: notes } = await Swal.fire({
                title: 'Kembalikan Aplikasi',
                width: 480,
                html: `
                    <textarea id="swal-review-notes" class="swal2-textarea"
                        placeholder="Catatan review wajib diisi"
                        style="height: 110px; width: 100%; max-width: 100%; margin: 0; box-sizing: border-box; font-family: inherit; font-size: 14px;"></textarea>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kembalikan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#64748b',
                focusConfirm: false,
                didOpen: () => {
                    document.getElementById('swal-review-notes').focus();
                },
                preConfirm: () => {
                    const notesValue = document.getElementById('swal-review-notes').value.trim();
                    if (!notesValue) {
                        Swal.showValidationMessage('Catatan review wajib diisi.');
                        return false;
                    }
                    return notesValue;
                }
            });

            if (!notes) return;

            try {
                await apiRequest(`/staff/submissions/${applicationId}/return`, {
                    method: 'PATCH',
                    body: JSON.stringify({ review_notes: notes })
                });

                await Swal.fire({
                    title: 'Aplikasi Dikembalikan',
                    text: 'Aplikasi berhasil dikembalikan ke pemohon untuk direvisi.',
                    icon: 'success',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });

                window.location.href = '/submissions';
            } catch (error) {
                Swal.fire({
                    title: 'Gagal Mengembalikan Aplikasi',
                    text: 'Pastikan status aplikasi masih "Sedang Direview".',
                    icon: 'error',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });
            }
        });
    }

    const assignBtn = document.querySelector('[data-assign-assessor]');
    if (assignBtn) {
        assignBtn.addEventListener('click', async () => {
            let selectedAssessorId = null;
            let allAssessors = [];

            const renderAssessorList = (items) => {
                const listEl = document.getElementById('swal-assessor-list');
                if (!listEl) return;

                if (!items.length) {
                    listEl.innerHTML = '<div class="swal-assessor-empty">Tidak ada assessor ditemukan.</div>';
                    return;
                }

                listEl.innerHTML = items.map((a) => {
                    const name = a.name || '-';
                    const nip = a.assessor?.nip || '';
                    const isSelected = String(a.id) === String(selectedAssessorId);
                    return `
                        <div class="swal-assessor-item${isSelected ? ' is-selected' : ''}" data-assessor-item="${a.id}">
                            <div class="swal-assessor-item-name">${escapeHtml(name)}</div>
                            ${nip ? `<div class="swal-assessor-item-nip">NIP: ${escapeHtml(nip)}</div>` : ''}
                        </div>
                    `;
                }).join('');
            };

            const { value: assessorId } = await Swal.fire({
                title: 'Tugaskan Assessor',
                width: 480,
                html: `
                    <style>
                        .swal-assessor-search {
                            width: 100%; box-sizing: border-box; margin: 0 0 10px 0;
                            font-family: inherit; font-size: 14px;
                        }
                        .swal-assessor-list-wrapper {
                            max-height: 260px; overflow-y: auto; text-align: left;
                            border: 1px solid #e2e8f0; border-radius: 8px;
                        }
                        .swal-assessor-item {
                            padding: 10px 14px; cursor: pointer;
                            border-bottom: 1px solid #f1f5f9;
                            border-left: 3px solid transparent;
                            transition: background-color .15s, border-left-color .15s;
                        }
                        .swal-assessor-item:last-child { border-bottom: none; }
                        .swal-assessor-item:hover { background-color: #f8fafc; }
                        .swal-assessor-item.is-selected {
                            background-color: #ecfdf5;
                            border-left-color: #10b981;
                        }
                        .swal-assessor-item-name {
                            font-size: 14px; font-weight: 500; color: #1e293b;
                        }
                        .swal-assessor-item-nip {
                            font-size: 12px; color: #64748b; margin-top: 2px;
                        }
                        .swal-assessor-empty {
                            padding: 18px; text-align: center; color: #94a3b8; font-size: 14px;
                        }
                    </style>
                    <input id="swal-assessor-search" type="text" class="swal2-input swal-assessor-search"
                        placeholder="Cari nama assessor...">
                    <div id="swal-assessor-list" class="swal-assessor-list-wrapper">
                        <div class="swal-assessor-empty">Memuat assessor...</div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tugaskan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                focusConfirm: false,
                didOpen: async () => {
                    const listEl = document.getElementById('swal-assessor-list');
                    const searchEl = document.getElementById('swal-assessor-search');

                    try {
                        const response = await apiRequest('/staff/assessors');
                        allAssessors = collection(response);
                        renderAssessorList(allAssessors);
                    } catch (error) {
                        listEl.innerHTML = '<div class="swal-assessor-empty">Gagal memuat assessor.</div>';
                    }

                    listEl.addEventListener('click', (event) => {
                        const item = event.target.closest('[data-assessor-item]');
                        if (!item) return;

                        selectedAssessorId = item.dataset.assessorItem;
                        listEl.querySelectorAll('[data-assessor-item]').forEach((el) => {
                            el.classList.toggle('is-selected', el.dataset.assessorItem === selectedAssessorId);
                        });
                    });

                    searchEl.addEventListener('input', () => {
                        const term = searchEl.value.trim().toLowerCase();
                        const filtered = allAssessors.filter((a) =>
                            (a.name || '').toLowerCase().includes(term) ||
                            (a.assessor?.nip || '').toLowerCase().includes(term)
                        );
                        renderAssessorList(filtered);
                    });
                },
                preConfirm: () => {
                    if (!selectedAssessorId) {
                        Swal.showValidationMessage('Pilih assessor terlebih dahulu.');
                        return false;
                    }
                    return selectedAssessorId;
                }
            });

            if (!assessorId) return;

            try {
                await apiRequest(`/staff/submissions/${applicationId}/assign-assessor`, {
                    method: 'PATCH',
                    body: JSON.stringify({ assessor_id: Number(assessorId) })
                });

                await Swal.fire({
                    title: 'Assessor Ditugaskan',
                    text: 'Assessor berhasil ditugaskan. Aplikasi akan dilanjutkan ke tahap penilaian.',
                    icon: 'success',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });

                window.location.href = '/submissions';
            } catch (error) {
                const errMsg = error?.response?.status === 422
                    ? 'User yang dipilih bukan assessor yang valid. Pilih assessor lain.'
                    : 'Gagal menugaskan assessor. Pastikan status aplikasi masih "Sedang Direview".';

                Swal.fire({
                    title: 'Gagal Menugaskan Assessor',
                    text: errMsg,
                    icon: 'error',
                    confirmButtonText: 'Oke',
                    confirmButtonColor: '#2563eb',
                });
            }
        });
    }
}

export function bootStaffPages() {
    const page = document.body.dataset.page;

    if (page === 'submissions') {
        loadSubmissions();
    }

    if (page === 'submission-detail') {
        loadSubmissionDetail();
        bindStaffActions();
        bindDocumentDownload(currentResourceId());
    }
}
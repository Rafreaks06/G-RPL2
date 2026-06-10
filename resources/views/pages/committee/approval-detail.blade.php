@extends('layouts.app')

@section('title', 'Approval Detail - G-RPL2')
@section('page', 'approval-detail')
@section('authRequired', 'true')
@section('roleRequired', 'committee')

@section('content')
    <section class="app-shell" data-protected-shell hidden>
        <aside class="sidebar">
            <p class="eyebrow">Committee</p>
            <h1 data-approval-title>Approval Detail</h1>
            <div class="sidebar-actions">
                <a class="button button-small button-muted" href="/approvals">Back</a>
                <button class="button button-small button-muted" type="button" data-approve-application hidden>Setujui</button>
            </div>
        </aside>
        <div class="workspace">
            <div class="workspace-header">
                <div>
                    <p class="eyebrow" data-approval-status-badge>Status</p>
                    <h2 data-approval-number>Application Number</h2>
                </div>
                <span class="connection-pill" data-api-status>Connecting</span>
            </div>
            <div data-page-message></div>

            <div class="detail-grid" data-approval-info>
                <div class="detail-item">
                    <span class="detail-label">Pemohon</span>
                    <span class="detail-value" data-detail-applicant-name>-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value" data-detail-applicant-email>-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Program Studi</span>
                    <span class="detail-value" data-detail-study-program>-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tipe RPL</span>
                    <span class="detail-value" data-detail-rpl-type>-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total SKS Dikonversi</span>
                    <span class="detail-value" data-detail-total-sks>-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Assessor</span>
                    <span class="detail-value" data-detail-assessor>-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Diajukan</span>
                    <span class="detail-value" data-detail-submitted-at>-</span>
                </div>
            </div>

            <div class="tabs" data-tabs>
                <button class="tab-button active" data-tab-button="course-mappings">Course Mappings</button>
                <button class="tab-button" data-tab-button="documents">Documents</button>
                <button class="tab-button" data-tab-button="documents-pdf" hidden>PDF Documents</button>
            </div>

            <div class="tab-content active" data-tab-content="course-mappings">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Mata Kuliah Asal</th>
                                <th>Tipe</th>
                                <th>Mata Kuliah Tujuan</th>
                                <th>SKS</th>
                                <th>Diakui</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody data-mappings-body>
                            <tr><td colspan="6">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-content" data-tab-content="documents">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Dokumen</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody data-documents-body>
                            <tr><td colspan="3">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-content" data-tab-content="documents-pdf">
                <div class="dashboard-grid" data-pdf-actions>
                    <div class="module-card">
                        <strong>SK Rektor</strong>
                        <span>Preview atau download Surat Keputusan Rektor.</span>
                        <div class="pdf-actions" style="margin-top:12px;display:flex;gap:8px;">
                            <button class="button button-small button-muted" type="button" data-preview-rector-decree>Preview</button>
                            <button class="button button-small button-muted" type="button" data-download-rector-decree>Download</button>
                        </div>
                    </div>
                    <div class="module-card">
                        <strong>Assessment Summary</strong>
                        <span>Preview atau download ringkasan hasil assessment.</span>
                        <div class="pdf-actions" style="margin-top:12px;display:flex;gap:8px;">
                            <button class="button button-small button-muted" type="button" data-preview-assessment-summary>Preview</button>
                            <button class="button button-small button-muted" type="button" data-download-assessment-summary>Download</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal" data-modal="approve-application" hidden>
        <div class="modal-content">
            <div class="modal-header">
                <h3>Setujui Aplikasi</h3>
                <button class="modal-close" type="button" data-close-modal="approve-application">&times;</button>
            </div>
            <div class="form-grid" style="padding:24px;">
                <form data-approve-form class="form-grid" style="border:0;background:transparent;box-shadow:none;padding:0;">
                    <div class="form-grid-full">
                        <label>
                            Catatan Review
                            <textarea name="notes" rows="4" placeholder="Tuliskan catatan persetujuan..."></textarea>
                        </label>
                    </div>
                    <div data-form-message></div>
                    <div class="modal-actions">
                        <button class="button button-muted" type="button" data-close-modal="approve-application">Batal</button>
                        <button class="button" type="button" data-submit-approve>Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Approval Detail - G-RPL2')
@section('page', 'approval-detail')
@section('authRequired', 'true')
@section('roleRequired', 'committee')

@section('content')
<style>
    :root {
        --committee-dark: #0f172a;
        --committee-dark-2: #111827;
        --committee-blue: #2563eb;
        --committee-blue-2: #1d4ed8;
        --committee-blue-soft: #dbeafe;
        --committee-gold: #f59e0b;
        --committee-green: #10b981;
        --committee-red: #ef4444;
        --committee-slate: #64748b;
        --committee-muted: #94a3b8;
        --committee-border: rgba(148, 163, 184, .28);
        --committee-card: rgba(255, 255, 255, .92);
        --committee-shadow: 0 24px 70px rgba(15, 23, 42, .1);
    }

    * {
        box-sizing: border-box;
    }

    .committee-workspace {
        min-width: 0;
    }

    .committee-container {
        width: 100%;
        min-width: 0;
    }

    .committee-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 24px;
        padding: 18px;
        border: 1px solid var(--committee-border);
        border-radius: 28px;
        background: rgba(255, 255, 255, .84);
        backdrop-filter: blur(18px);
        box-shadow: 0 18px 50px rgba(15, 23, 42, .075);
    }

    .committee-brand {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .committee-logo {
        width: 52px;
        height: 52px;
        flex: 0 0 52px;
        border-radius: 18px;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 14px;
        font-weight: 950;
        letter-spacing: .04em;
        background:
            linear-gradient(135deg, rgba(255,255,255,.2), transparent),
            linear-gradient(135deg, var(--committee-dark), var(--committee-blue) 58%, var(--committee-gold));
        box-shadow: 0 14px 32px rgba(37, 99, 235, .25);
    }

    .committee-brand-text {
        min-width: 0;
    }

    .committee-brand-text small {
        display: block;
        margin-bottom: 4px;
        color: var(--committee-gold);
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .09em;
    }

    .committee-brand-text h1 {
        margin: 0;
        color: var(--committee-dark);
        font-size: 24px;
        line-height: 1.08;
        font-weight: 950;
        letter-spacing: -.045em;
    }

    .committee-brand-text p {
        margin: 6px 0 0;
        color: var(--committee-slate);
        font-size: 13px;
        line-height: 1.45;
    }

    .committee-top-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
        flex: 0 0 auto;
    }

    .connection-pill {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 0 17px;
        border-radius: 999px;
        border: 1px solid #93c5fd;
        color: #1d4ed8;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        box-shadow:
            0 12px 28px rgba(15, 23, 42, .08),
            inset 0 1px 0 rgba(255, 255, 255, .65);
        font-size: 13px;
        line-height: 1;
        font-weight: 950;
        white-space: nowrap;
    }

    .connection-pill::before {
        content: "";
        width: 9px;
        height: 9px;
        flex: 0 0 9px;
        border-radius: 999px;
        background: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .15);
    }

    .connection-pill.is-connected {
        color: #14532d;
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-color: #4ade80;
        box-shadow:
            0 12px 28px rgba(34, 197, 94, .16),
            inset 0 1px 0 rgba(255, 255, 255, .72);
    }

    .connection-pill.is-connected::before {
        background: #16a34a;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, .18);
    }

    .connection-pill.is-error,
    .connection-pill.is-disconnected {
        color: #991b1b;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-color: #fca5a5;
        box-shadow:
            0 12px 28px rgba(239, 68, 68, .14),
            inset 0 1px 0 rgba(255, 255, 255, .65);
    }

    .connection-pill.is-error::before,
    .connection-pill.is-disconnected::before {
        background: #dc2626;
        box-shadow: 0 0 0 4px rgba(220, 38, 38, .16);
    }

    .committee-action-btn {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 16px;
        border-radius: 999px;
        border: 0;
        outline: none;
        cursor: pointer;
        text-decoration: none;
        font-size: 13px;
        line-height: 1;
        font-weight: 950;
        transition:
            transform .2s ease,
            box-shadow .2s ease,
            background .2s ease,
            color .2s ease,
            border-color .2s ease;
        white-space: nowrap;
    }

    .committee-action-btn.muted {
        color: #1d4ed8;
        background: #fff;
        border: 1px solid rgba(37, 99, 235, .20);
        box-shadow: 0 12px 26px rgba(15, 23, 42, .055);
    }

    .committee-action-btn.muted:hover {
        color: #fff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-color: transparent;
        box-shadow: 0 14px 30px rgba(37, 99, 235, .22);
        transform: translateY(-1px);
    }

    .committee-action-btn.approve {
        color: #fff;
        background: linear-gradient(135deg, #10b981, #047857);
        border: 1px solid transparent;
        box-shadow: 0 14px 30px rgba(16, 185, 129, .22);
    }

    .committee-action-btn.approve:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 40px rgba(16, 185, 129, .28);
    }

    .committee-page-card {
        border: 1px solid var(--committee-border);
        border-radius: 32px;
        background: var(--committee-card);
        box-shadow: var(--committee-shadow);
        overflow: hidden;
        margin-bottom: 22px;
    }

    .committee-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 22px;
        padding: 28px;
        border-bottom: 1px solid rgba(148, 163, 184, .22);
        background:
            linear-gradient(135deg, rgba(255, 255, 255, .94), rgba(248, 250, 252, .76)),
            radial-gradient(circle at top right, rgba(37, 99, 235, .11), transparent 36%);
    }

    .committee-title-group {
        display: flex;
        gap: 15px;
        align-items: flex-start;
        min-width: 0;
    }

    .committee-title-line {
        width: 10px;
        height: 76px;
        flex: 0 0 10px;
        border-radius: 999px;
        background: linear-gradient(180deg, var(--committee-blue), var(--committee-gold));
        box-shadow: 0 10px 22px rgba(37, 99, 235, .18);
    }

    .eyebrow {
        margin: 0 0 7px;
        color: var(--committee-gold);
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .09em;
    }

    .committee-card-header h2 {
        margin: 0;
        color: var(--committee-dark);
        font-size: 32px;
        line-height: 1.12;
        font-weight: 950;
        letter-spacing: -.05em;
        word-break: break-word;
    }

    .committee-subtitle {
        max-width: 720px;
        margin: 9px 0 0;
        color: var(--committee-slate);
        font-size: 14px;
        line-height: 1.65;
    }

    .committee-content {
        padding: 26px 28px 30px;
    }

    [data-page-message] {
        margin-bottom: 16px;
    }

    .committee-info-panel {
        margin-bottom: 22px;
        padding: 18px;
        border: 1px solid rgba(148, 163, 184, .32);
        border-radius: 24px;
        background:
            linear-gradient(135deg, rgba(248, 250, 252, .98), rgba(255, 255, 255, .98));
        box-shadow:
            0 16px 38px rgba(15, 23, 42, .075),
            inset 0 1px 0 rgba(255, 255, 255, .9);
    }

    .committee-info-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
    }

    .committee-info-header h3 {
        margin: 0;
        color: var(--committee-dark);
        font-size: 18px;
        font-weight: 950;
        letter-spacing: -.03em;
    }

    .committee-info-header p {
        margin: 5px 0 0;
        color: var(--committee-slate);
        font-size: 13px;
        line-height: 1.55;
    }

    .committee-mini-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 7px 12px;
        border-radius: 999px;
        color: #92400e;
        background: #fef3c7;
        border: 1px solid rgba(245, 158, 11, .24);
        font-size: 12px;
        font-weight: 950;
        white-space: nowrap;
    }

    .committee-detail-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .committee-detail-item {
        position: relative;
        overflow: hidden;
        min-height: 96px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
        padding: 18px;
        border-radius: 22px;
        background:
            linear-gradient(135deg, rgba(248, 250, 252, .98), rgba(255, 255, 255, .98));
        border: 1px solid rgba(148, 163, 184, .26);
        box-shadow:
            0 12px 30px rgba(15, 23, 42, .055),
            inset 0 1px 0 rgba(255, 255, 255, .9);
    }

    .committee-detail-item::after {
        content: "";
        position: absolute;
        width: 72px;
        height: 72px;
        right: -38px;
        bottom: -38px;
        border-radius: 999px;
        background: rgba(37, 99, 235, .07);
    }

    .committee-detail-item .detail-label {
        position: relative;
        z-index: 1;
        color: var(--committee-slate);
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .committee-detail-item .detail-value {
        position: relative;
        z-index: 1;
        color: var(--committee-dark);
        font-size: 15px;
        font-weight: 950;
        line-height: 1.45;
        word-break: break-word;
    }

    .committee-tabs-card {
        border: 1px solid var(--committee-border);
        border-radius: 32px;
        background: var(--committee-card);
        box-shadow: var(--committee-shadow);
        overflow: hidden;
        margin-bottom: 22px;
    }

    .committee-tabs {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 20px;
        border-bottom: 1px solid rgba(148, 163, 184, .18);
        background:
            linear-gradient(135deg, rgba(248, 250, 252, .95), rgba(255, 255, 255, .95));
        overflow-x: auto;
    }

    .committee-tabs .tab-button {
        min-height: 42px;
        padding: 0 16px;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, .30);
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        font-size: 13px;
        font-weight: 950;
        white-space: nowrap;
        transition:
            transform .2s ease,
            color .2s ease,
            background .2s ease,
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .committee-tabs .tab-button:hover {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: rgba(37, 99, 235, .20);
        transform: translateY(-1px);
    }

    .committee-tabs .tab-button.active {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 14px 28px rgba(37, 99, 235, .20);
    }

    .committee-tab-content {
        display: none;
        padding: 24px;
    }

    .committee-tab-content.active {
        display: block;
    }

    .committee-section-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 16px;
    }

    .committee-section-header h3 {
        margin: 0;
        color: var(--committee-dark);
        font-size: 18px;
        font-weight: 950;
        letter-spacing: -.03em;
    }

    .committee-section-header p {
        margin: 5px 0 0;
        color: var(--committee-slate);
        font-size: 13px;
        line-height: 1.55;
    }

    .table-container {
        overflow: hidden;
        border-radius: 24px;
        border: 1px solid rgba(148, 163, 184, .30);
        background: #fff;
        box-shadow:
            0 18px 44px rgba(15, 23, 42, .075),
            inset 0 1px 0 rgba(255, 255, 255, .92);
    }

    .table-wrap,
    .committee-table-container {
        width: 100%;
        overflow-x: auto;
    }

    .data-table,
    .committee-data-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
    }

    .data-table thead,
    .committee-data-table thead {
        background: linear-gradient(180deg, #f8fafc, #f1f5f9);
    }

    .data-table th,
    .committee-data-table th {
        padding: 16px;
        color: #64748b;
        font-size: 12px;
        font-weight: 950;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .045em;
        border-bottom: 1px solid rgba(148, 163, 184, .24);
        white-space: nowrap;
    }

    .data-table td,
    .committee-data-table td {
        padding: 16px;
        color: #1e293b;
        font-size: 14px;
        line-height: 1.5;
        border-bottom: 1px solid rgba(148, 163, 184, .15);
        vertical-align: middle;
        font-weight: 700;
        background: #fff;
    }

    .data-table tbody tr:last-child td,
    .committee-data-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .data-table tbody tr,
    .committee-data-table tbody tr {
        transition: .18s ease;
    }

    .data-table tbody tr:hover td,
    .committee-data-table tbody tr:hover td {
        background: #f8fafc;
    }

    .data-table td[colspan],
    .committee-empty-cell {
        padding: 34px 18px !important;
        color: var(--committee-slate);
        text-align: center;
        font-weight: 850;
    }

    .committee-pdf-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .committee-pdf-card {
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 20px;
        border-radius: 24px;
        background:
            radial-gradient(circle at top right, rgba(37, 99, 235, .10), transparent 36%),
            linear-gradient(135deg, rgba(248, 250, 252, .98), rgba(255, 255, 255, .98));
        border: 1px solid rgba(148, 163, 184, .26);
        box-shadow:
            0 16px 38px rgba(15, 23, 42, .075),
            inset 0 1px 0 rgba(255, 255, 255, .9);
    }

    .committee-pdf-card::after {
        content: "";
        position: absolute;
        width: 90px;
        height: 90px;
        right: -45px;
        bottom: -45px;
        border-radius: 999px;
        background: rgba(245, 158, 11, .10);
    }

    .committee-pdf-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: grid;
        place-items: center;
        background: #dbeafe;
        font-size: 24px;
        position: relative;
        z-index: 1;
    }

    .committee-pdf-card > div:not(.committee-pdf-icon),
    .committee-pdf-actions {
        position: relative;
        z-index: 1;
    }

    .committee-pdf-card strong {
        display: block;
        color: var(--committee-dark);
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 6px;
    }

    .committee-pdf-card span {
        display: block;
        color: var(--committee-slate);
        font-size: 13px;
        line-height: 1.5;
        font-weight: 700;
    }

    .committee-pdf-actions {
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .status-badge {
        min-height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        border-radius: 999px;
        color: #1d4ed8;
        background: #dbeafe;
        border: 1px solid rgba(37, 99, 235, .18);
        font-size: 12px;
        font-weight: 950;
        white-space: nowrap;
    }

    .status-badge[data-status="approved"],
    .status-badge[data-status="assessed"],
    .status-badge[data-status="active"] {
        color: #047857;
        background: #d1fae5;
        border-color: rgba(16, 185, 129, .24);
    }

    .status-badge[data-status="pending"],
    .status-badge[data-status="submitted"],
    .status-badge[data-status="under_review"] {
        color: #1d4ed8;
        background: #dbeafe;
        border-color: rgba(37, 99, 235, .18);
    }

    .status-badge[data-status="rejected"],
    .status-badge[data-status="returned"] {
        color: #b91c1c;
        background: #fee2e2;
        border-color: rgba(239, 68, 68, .25);
    }

    .table-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .button,
    .button-small,
    .button-muted {
        text-decoration: none;
        border: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        font-weight: 900;
        transition: .2s ease;
    }

    .button-small {
        min-height: 34px;
        padding: 8px 12px;
        font-size: 12px;
    }

    .button-muted {
        color: #1d4ed8;
        background: #dbeafe;
        border: 1px solid rgba(37, 99, 235, .18);
        box-shadow: none;
    }

    .button-muted:hover {
        color: #fff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-color: transparent;
        box-shadow: 0 12px 24px rgba(37, 99, 235, .2);
        transform: translateY(-1px);
    }

    .committee-modal {
        position: fixed;
        inset: 0;
        z-index: 999;
        display: grid;
        place-items: center;
        padding: 18px;
    }

    .committee-modal[hidden] {
        display: none !important;
    }

    .committee-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, .62);
        backdrop-filter: blur(7px);
    }

    .committee-modal-content {
        position: relative;
        z-index: 1;
        width: min(560px, 100%);
        max-height: min(720px, 92vh);
        overflow: auto;
        border-radius: 28px;
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, .8);
        box-shadow: 0 30px 90px rgba(15, 23, 42, .35);
    }

    .committee-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        padding: 24px;
        border-bottom: 1px solid rgba(148, 163, 184, .18);
        background:
            radial-gradient(circle at top left, rgba(37, 99, 235, .10), transparent 40%),
            #ffffff;
    }

    .committee-modal-header .eyebrow {
        margin: 0 0 7px;
        color: var(--committee-gold);
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .09em;
    }

    .committee-modal-header h3 {
        margin: 0;
        color: var(--committee-dark);
        font-size: 24px;
        font-weight: 950;
        letter-spacing: -.04em;
    }

    .committee-modal-header span {
        display: block;
        margin-top: 6px;
        color: var(--committee-slate);
        font-size: 13px;
        line-height: 1.5;
        font-weight: 700;
    }

    .committee-modal-close {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, .22);
        background: #f8fafc;
        color: #0f172a;
        cursor: pointer;
        font-size: 25px;
        line-height: 1;
        display: grid;
        place-items: center;
        transition: .2s ease;
    }

    .committee-modal-close:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .committee-modal-body {
        padding: 24px;
    }

    .committee-approve-form {
        border: 0;
        background: transparent;
        box-shadow: none;
        padding: 0;
        display: grid;
        gap: 16px;
    }

    .committee-approve-form label {
        display: grid;
        gap: 9px;
        color: #334155;
        font-size: 13px;
        font-weight: 900;
    }

    .committee-approve-form textarea {
        width: 100%;
        resize: vertical;
        min-height: 130px;
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, .32);
        outline: none;
        padding: 14px 15px;
        color: #0f172a;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.6;
        background: #f8fafc;
        transition: .2s ease;
    }

    .committee-approve-form textarea:focus {
        background: #ffffff;
        border-color: rgba(37, 99, 235, .48);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
    }

    .committee-modal-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    @media (max-width: 1100px) {
        .committee-detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .committee-topbar,
        .committee-card-header,
        .committee-info-header,
        .committee-section-header {
            align-items: stretch;
            flex-direction: column;
        }

        .committee-top-actions {
            justify-content: flex-start;
        }

        .connection-pill {
            width: fit-content;
        }

        .committee-detail-grid {
            grid-template-columns: 1fr;
        }

        .committee-pdf-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .committee-topbar {
            border-radius: 22px;
            padding: 16px;
        }

        .committee-brand {
            align-items: flex-start;
        }

        .committee-logo {
            width: 46px;
            height: 46px;
            flex-basis: 46px;
            border-radius: 16px;
        }

        .committee-brand-text h1 {
            font-size: 21px;
        }

        .committee-brand-text p {
            font-size: 12px;
        }

        .committee-top-actions {
            display: grid;
            grid-template-columns: 1fr;
            width: 100%;
        }

        .connection-pill,
        .committee-action-btn {
            width: 100%;
        }

        .committee-page-card,
        .committee-tabs-card {
            border-radius: 24px;
        }

        .committee-card-header {
            padding: 22px 18px;
        }

        .committee-title-line {
            height: 66px;
        }

        .committee-card-header h2 {
            font-size: 25px;
        }

        .committee-subtitle {
            font-size: 13px;
        }

        .committee-content {
            padding: 18px;
        }

        .committee-info-panel,
        .committee-detail-item {
            padding: 14px;
            border-radius: 20px;
        }

        .committee-tab-content {
            padding: 18px;
        }

        .table-container {
            border-radius: 20px;
        }

        .data-table,
        .committee-data-table {
            min-width: 900px;
        }

        .committee-tabs {
            padding: 14px;
        }

        .committee-tabs .tab-button {
            min-height: 40px;
            font-size: 12px;
        }

        .committee-modal {
            padding: 12px;
        }

        .committee-modal-content {
            border-radius: 24px;
        }

        .committee-modal-header,
        .committee-modal-body {
            padding: 18px;
        }

        .committee-modal-actions {
            display: grid;
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="committee-shell app-shell" data-protected-shell hidden>

    {{-- Sidebar Committee Manual --}}
    <x-committee-sidebar />

    {{-- Main Workspace --}}
    <div class="committee-workspace workspace">
        <div class="committee-container">

            <header class="committee-topbar">
                <div class="committee-brand">
                    <div class="committee-logo">RPL</div>

                    <div class="committee-brand-text">
                        <small>Committee Panel</small>
                        <h1>Detail Pengajuan</h1>
                        <p>Detail pengajuan RPL untuk ditinjau sebagai validasi akhir oleh Komite RPL.</p>
                    </div>
                </div>

                <div class="committee-top-actions">
                    <span class="connection-pill" data-api-status>Connecting</span>

                    <a class="committee-action-btn muted" href="/approvals">
                        ← Kembali
                    </a>

                    <button
                        class="committee-action-btn approve"
                        type="button"
                        data-approve-application
                        hidden
                    >
                        Setujui
                    </button>
                </div>
            </header>

            <main class="committee-page-card">
                <div class="committee-card-header">
                    <div class="committee-title-group">
                        <span class="committee-title-line"></span>

                        <div>
                            <p class="eyebrow" data-approval-status-badge>Status</p>
                            <h2 data-approval-number>Application Number</h2>
                            <p class="committee-subtitle">
                                Periksa informasi Calon Mahasiswa, hasil assessment, dokumen pendukung, dan hasil konversi SKS
                                sebelum menyelesaikan tahap akhir RPL.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="committee-content">
                    <div data-page-message></div>

                    <section class="committee-info-panel">
                        <div class="committee-info-header">
                            <div>
                                <h3 data-approval-title>Approval Detail</h3>
                                <p>Informasi utama pemohon, program studi, assessor, dan hasil konversi SKS.</p>
                            </div>

                            <span class="committee-mini-badge">
                                Data Calon Mahasiswa
                            </span>
                        </div>

                        <div class="committee-detail-grid detail-grid" data-approval-info>
                            <div class="committee-detail-item detail-item">
                                <span class="detail-label">Pemohon</span>
                                <span class="detail-value" data-detail-applicant-name>-</span>
                            </div>

                            <div class="committee-detail-item detail-item">
                                <span class="detail-label">Email</span>
                                <span class="detail-value" data-detail-applicant-email>-</span>
                            </div>

                            <div class="committee-detail-item detail-item">
                                <span class="detail-label">Program Studi</span>
                                <span class="detail-value" data-detail-study-program>-</span>
                            </div>

                            <div class="committee-detail-item detail-item">
                                <span class="detail-label">Tipe RPL</span>
                                <span class="detail-value" data-detail-rpl-type>-</span>
                            </div>

                            <div class="committee-detail-item detail-item">
                                <span class="detail-label">Total SKS Dikonversi</span>
                                <span class="detail-value" data-detail-total-sks>-</span>
                            </div>

                            <div class="committee-detail-item detail-item">
                                <span class="detail-label">Assessor</span>
                                <span class="detail-value" data-detail-assessor>-</span>
                            </div>

                            <div class="committee-detail-item detail-item">
                                <span class="detail-label">Diajukan</span>
                                <span class="detail-value" data-detail-submitted-at>-</span>
                            </div>
                        </div>
                    </section>
                </div>
            </main>

            <section class="committee-tabs-card">
                <div class="committee-tabs tabs" data-tabs>
                    <button class="tab-button active" data-tab-button="course-mappings" type="button">
                        Matakuliah Konversi
                    </button>

                    <button class="tab-button" data-tab-button="documents" type="button">
                        Documents
                    </button>

                    <button class="tab-button" data-tab-button="documents-pdf" type="button" hidden>
                        Cetak Hasil Asssessment
                    </button>
                </div>

                <div class="committee-tab-content tab-content active" data-tab-content="course-mappings">
                    <div class="committee-section-header">
                        <div>
                            <h3>Konversi Matakuliah</h3>
                            <p>Daftar mata kuliah asal yang dinilai dan dikonversi ke mata kuliah tujuan.</p>
                        </div>

                        <span class="committee-mini-badge">
                            Mapping Result
                        </span>
                    </div>

                    <div class="table-container">
                        <div class="table-wrap committee-table-container">
                            <table class="data-table committee-data-table">
                                <thead>
                                    <tr>
                                        <th>Mata Kuliah Asal</th>
                                        <th>Tipe</th>
                                        <th>Mata Kuliah Tujuan</th>
                                        <th>SKS</th>
                                        <th>Diakui</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody data-mappings-body>
                                    <tr>
                                        <td colspan="6" class="committee-empty-cell">
                                            Memuat data...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="committee-tab-content tab-content" data-tab-content="documents">
                    <div class="committee-section-header">
                        <div>
                            <h3>Documents</h3>
                            <p>Dokumen pendukung yang dikirimkan oleh pemohon untuk proses penilaian RPL.</p>
                        </div>

                        <span class="committee-mini-badge">
                            Supporting Files
                        </span>
                    </div>

                    <div class="table-container">
                        <div class="table-wrap committee-table-container">
                            <table class="data-table committee-data-table">
                                <thead>
                                    <tr>
                                        <th>Nama Dokumen</th>
                                        <th>Jenis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody data-documents-body>
                                    <tr>
                                        <td colspan="3" class="committee-empty-cell">
                                            Memuat data...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="committee-tab-content tab-content" data-tab-content="documents-pdf">
                    <div class="committee-section-header">
                        <div>
                            <h3>Cetak Hasil Assessment</h3>
                            <p>Preview atau download dokumen hasil assessment yang sudah melalui proses validasi akhir.</p>
                        </div>

                        <span class="committee-mini-badge">
                            Document Output
                        </span>
                    </div>

                    <div class="committee-pdf-grid dashboard-grid" data-pdf-actions>
                        <div class="committee-pdf-card module-card">
                            <div class="committee-pdf-icon">📜</div>

                            <div>
                                <strong>SK Rektor</strong>
                                <span>Preview atau download Surat Keputusan Rektor.</span>
                            </div>

                            <div class="pdf-actions committee-pdf-actions">
                                <button
                                    class="committee-action-btn muted"
                                    type="button"
                                    data-preview-rector-decree
                                >
                                    Preview
                                </button>

                                <button
                                    class="committee-action-btn approve"
                                    type="button"
                                    data-download-rector-decree
                                >
                                    Download
                                </button>
                            </div>
                        </div>

                        <div class="committee-pdf-card module-card">
                            <div class="committee-pdf-icon">📑</div>

                            <div>
                                <strong>Ringkasan Assessment</strong>
                                <span>Preview atau download ringkasan hasil assessment.</span>
                            </div>

                            <div class="pdf-actions committee-pdf-actions">
                                <button
                                    class="committee-action-btn muted"
                                    type="button"
                                    data-preview-assessment-summary
                                >
                                    Preview
                                </button>

                                <button
                                    class="committee-action-btn approve"
                                    type="button"
                                    data-download-assessment-summary
                                >
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</section>

{{-- Modal Approve --}}
<div class="committee-modal modal" data-modal="approve-application" hidden>
    <div class="committee-modal-backdrop" data-close-modal="approve-application"></div>

    <div class="committee-modal-content modal-content">
        <div class="committee-modal-header modal-header">
            <div>
                <p class="eyebrow">Final Approval</p>
                <h3>Setujui Aplikasi</h3>
                <span>Tambahkan catatan review sebelum aplikasi disetujui.</span>
            </div>

            <button
                class="committee-modal-close modal-close"
                type="button"
                data-close-modal="approve-application"
                aria-label="Close modal"
            >
                &times;
            </button>
        </div>

        <div class="committee-modal-body">
            <form data-approve-form class="committee-approve-form form-grid">
                <div class="form-grid-full">
                    <label>
                        Catatan Review
                        <textarea
                            name="notes"
                            rows="4"
                            placeholder="Tuliskan catatan persetujuan..."
                        ></textarea>
                    </label>
                </div>

                <div data-form-message></div>

                <div class="committee-modal-actions modal-actions">
                    <button
                        class="committee-action-btn muted"
                        type="button"
                        data-close-modal="approve-application"
                    >
                        Batal
                    </button>

                    <button
                        class="committee-action-btn approve"
                        type="button"
                        data-submit-approve
                    >
                        Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const apiStatus = document.querySelector('[data-api-status]');

        function normalizeText(value) {
            return String(value || '').trim().toLowerCase();
        }

        function refreshApiStatusClass() {
            if (!apiStatus) return;

            const text = normalizeText(apiStatus.textContent);

            apiStatus.classList.remove(
                'is-connected',
                'is-connecting',
                'is-error',
                'is-disconnected'
            );

            if (text.includes('connected') && !text.includes('disconnect')) {
                apiStatus.classList.add('is-connected');
                return;
            }

            if (
                text.includes('connecting') ||
                text.includes('loading') ||
                text.includes('memuat')
            ) {
                apiStatus.classList.add('is-connecting');
                return;
            }

            if (
                text.includes('error') ||
                text.includes('failed') ||
                text.includes('offline') ||
                text.includes('disconnected') ||
                text.includes('gagal')
            ) {
                apiStatus.classList.add('is-error');
                return;
            }

            apiStatus.classList.add('is-connecting');
        }

        if (apiStatus) {
            const statusObserver = new MutationObserver(refreshApiStatusClass);

            statusObserver.observe(apiStatus, {
                childList: true,
                subtree: true,
                characterData: true
            });

            refreshApiStatusClass();
        }
    });
</script>
@endsection
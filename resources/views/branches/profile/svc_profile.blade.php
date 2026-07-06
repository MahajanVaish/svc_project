@extends('admin.layouts.layouts')

@section('title', 'Patient Profile')

@section('content')
    <style>
        .profile-container {
            max-width: 1742px;
            margin: 0 auto;
            padding: 40px;
            background: var(--bg-main);
            min-height: 100vh;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-subtle);
        }

        .profile-title {
            font-family: 'Poppins', sans-serif !important;
            font-size: 20px;
            font-weight: bold;
            color: #006637 !important;
        }

        .profile-content {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }

        .profile-sidebar {
            flex: 1;
            background: var(--bg-card);
            padding: 25px;
            border-radius: 10px;
            box-shadow: var(--shadow-md);
            text-align: center;
            color: var(--text-primary);
        }

        .profile-main {
            flex: 2;
            background: var(--bg-card);
            padding: 25px;
            border-radius: 10px;
            box-shadow: var(--shadow-md);
            color: var(--text-primary);
            max-height: 800px;
            overflow-y: auto;
        }

        .profile-image {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-image svg {
            width: 200px;
            height: 200px;
            fill: #6c757d;
        }

        .patient-info {
            text-align: left;
            margin-bottom: 25px;
        }

        .info-group {
            margin-bottom: 15px;
        }

        .info-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .info-value {
            font-size: 16px;
            color: var(--text-primary);
            font-weight: 500;
        }

        .edit-profile-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }

        .edit-profile-btn:hover {
            background: #006637;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            color: white;
        }

        .edit-profile-align-btn {
            display: flex;
            justify-content: end;
            align-items: end;
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0px !important;
        }

        .data-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 14px;
            white-space: nowrap;
        }

        .data-table th {
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            color: white;
            background: #006637;
            font-size: 16px;
        }

        .data-table td {
            border-bottom: 1px solid var(--border-subtle);
            padding: 12px 8px;
            text-align: left;
            font-size: 14px;
            color: var(--text-primary);
        }

        .data-table tr:nth-child(even) {
            background: var(--bg-main);
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .pagination-info {
            color: #6c757d;
            font-size: 13px;
        }

        .pagination-buttons {
            display: flex;
            gap: 8px;
        }

        .pagination-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
            text-decoration: none;
            display: inline-block;
        }

        .pagination-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .pagination-btn:hover:not(:disabled) {
            background: #5a6268;
            color: white;
        }

        .patient-type {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .type-badge {
            background: #e9ecef;
            color: #495057;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        .back-button {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background: #5a6268;
            color: white;
        }

        .empty-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
            font-size: 14px;
        }

        .section-divider {
            border-top: 1px solid #dee2e6;
            margin: 20px 0;
        }

        .medicine-prescription {
            font-size: 13px;
            line-height: 1.6;
            white-space: normal !important;
        }

        .prescription-item {
            margin-bottom: 12px;
            padding: 12px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            background: var(--bg-main);
        }

        .prescription-item:last-child {
            margin-bottom: 0;
        }

        .medicine-name {
            font-weight: bold;
            color: var(--accent-solid);
            margin-bottom: 8px;
            font-size: 14px;
            padding-bottom: 5px;
            border-bottom: 1px dashed var(--border-subtle);
        }

        .medicine-detail {
            display: flex;
            align-items: center;
            margin-bottom: 4px;
            color: #495057;
        }

        .medicine-label {
            font-weight: 600;
            min-width: 60px;
            color: #6c757d;
        }

        .medicine-value {
            margin-left: 8px;
        }

        .medicine-note {
            color: #ff4d4d;
            font-size: 12px;
            margin-top: 6px;
            padding: 8px;
            background: rgba(220, 53, 69, 0.1);
            border-radius: 4px;
            border-left: 3px solid #dc3545;
        }

        .lab-table-container {
            overflow-x: auto;
            margin-bottom: 15px;
        }

        .follow-up-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .follow-up-btn:hover {
            background: #006637;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            color: white;
        }

        .treatment-section {
            margin-bottom: 25px;
        }

        .treatment-icon {
            margin-right: 8px;
            font-size: 16px;
        }

        .no-treatment {
            text-align: center;
            color: var(--text-muted);
            font-style: italic;
            padding: 30px;
            background: var(--bg-main);
            border-radius: 8px;
            border: 2px dashed var(--border-subtle);
        }

        .compact-table {
            font-size: 12px;
        }

        .compact-table th,
        .compact-table td {
            padding: 12px 15px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
        }

        .edit-btn {
            color: var(--color-primary);
        }

        .edit-btn:hover {
            color: var(--color-primary);
            background: rgba(37, 99, 235, 0.15);
        }

        .delete-btn {
            color: var(--color-danger);
        }

        .delete-btn:hover {
            color: var(--color-danger);
            background: rgba(239, 68, 68, 0.15);
        }

        .btn-zoom-start,
        .btn-zoom-join,
        .btn-copy,
        .btn-zoom-create {
            color: var(--icon-on-color);
        }

        .btn-zoom-start {
            background: var(--color-success);
        }

        .btn-zoom-join {
            background: var(--color-primary);
        }

        .btn-copy {
            background: var(--color-info);
        }

        .btn-zoom-create {
            background: var(--text-secondary);
            color: var(--icon-on-color);
        }

        .action-btn:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .empty-field {
            color: #6c757d;
            font-style: italic;
        }

        .delete-form {
            display: inline;
            margin: 0;
            padding: 0;
        }

        .profile_txt_color {
            color: var(--accent-solid);
        }

        .fnf-title {
            font-weight: 600;
            color: var(--accent-solid);
            padding-bottom: 0;
            line-height: 1.3em;
            margin-bottom: 0px !important;
            font-size: 20px !important;
        }

        .data-table thead th {
            background-color: #006637 !important;
            color: white;
        }

        .profile-image-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
        }

        .profile-image-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid var(--border-subtle);
            background: var(--bg-main);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-image-container i {
            font-size: 60px;
            color: #adb5bd;
        }

        .upload-label {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #007bff;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: all 0.2s;
        }

        .upload-label:hover {
            background: #0056b3;
            transform: scale(1.1);
        }

        .save-profile-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 13px;
            margin-top: 10px;
            display: none;
        }

        /* ===== INDOOR TREATMENT MODAL REDESIGN ===== */
        #indoorTreatmentModal .modal-dialog {
            max-width: 860px !important;
        }

        #indoorTreatmentModal .modal-content {
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        #indoorTreatmentModal .modal-header {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 20px 28px;
        }

        #indoorTreatmentModal .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #indoorTreatmentModal .modal-title i {
            color: #006637;
            font-size: 20px;
        }

        #indoorTreatmentModal .modal-body {
            background: #f8f9fa;
            padding: 24px 28px;
            overflow-y: auto !important;
            max-height: 68vh;
        }

        #indoorTreatmentModal .modal-footer {
            background: #fff;
            border-top: 1px solid #e9ecef;
            padding: 16px 28px;
        }

        /* Patient Info Card inside modal */
        .indoor-patient-info {
            background: #f0f7f2;
            border: 1px solid #c8e6d4;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 24px;
        }

        .indoor-patient-info .info-item {
            font-size: 14px;
            color: #333;
        }

        .indoor-patient-info .info-item strong {
            color: #006637;
            font-weight: 600;
            margin-right: 6px;
        }

        /* Add New Date Slot Button */
        .add-slot-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: 1px solid #006637;
            color: #006637;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 18px;
        }

        .add-slot-btn:hover {
            background: #f0f7f2;
        }

        /* Date Slot Card */
        .date-slot-card {
            background: #fff;
            border-radius: 12px;
            overflow: visible;
            margin-bottom: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .date-slot-header {
            background: #006637;
            color: white;
            padding: 11px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 12px 12px 0 0;
            flex-wrap: wrap;
        }

        .date-slot-header label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
            white-space: nowrap;
        }

        .date-slot-header input[type="date"],
        .date-slot-header input[type="time"] {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 13px;
            outline: none;
            max-width: 155px;
        }

        .date-slot-header input[type="date"]:focus,
        .date-slot-header input[type="time"]:focus {
            border-color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.25);
        }

        .date-slot-header input[type="date"]::-webkit-calendar-picker-indicator,
        .date-slot-header input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .date-slot-header input::placeholder {
            color: rgba(255, 255, 255, 0.65);
        }

        .slot-at-separator {
            color: rgba(255, 255, 255, 0.75);
            font-size: 13px;
            font-weight: 500;
        }

        .medicine-count-badge {
            background: rgba(255, 255, 255, 0.22);
            color: white;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .remove-slot-btn {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.45);
            color: white;
            border-radius: 6px;
            padding: 5px 13px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .remove-slot-btn:hover {
            background: rgba(220, 53, 69, 0.65);
            border-color: rgba(220, 53, 69, 0.8);
        }

        /* Medicine rows */
        .date-slot-body {
            padding: 14px 18px 16px;
        }

        .medicines-header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding-bottom: 6px;
            margin-bottom: 6px;
            border-bottom: 1px solid #f0f0f0;
        }

        .medicines-header span {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
        }

        .medicine-row {
            display: grid;
            grid-template-columns: 1fr 1fr 36px;
            gap: 10px;
            margin-bottom: 8px;
            align-items: center;
        }

        .medicine-row input {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 7px 10px;
            font-size: 13px;
            width: 100%;
            color: #333;
            background: #fff;
            transition: border-color 0.2s;
        }

        .medicine-row input:focus {
            outline: none;
            border-color: #006637;
            box-shadow: 0 0 0 3px rgba(0, 102, 55, 0.1);
        }

        .medicine-row input::placeholder {
            color: #adb5bd;
        }

        .delete-medicine-btn {
            width: 32px;
            height: 32px;
            background: #fff0f0;
            border: 1px solid #ffcccc;
            color: #dc3545;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .delete-medicine-btn:hover {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        /* Add Medicine Button */
        .add-medicine-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: transparent;
            border: 1.5px solid #006637;
            color: #006637;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 6px;
            transition: all 0.2s;
        }

        .add-medicine-btn:hover {
            background: #f0f7f2;
        }

        /* Modal footer buttons */
        .btn-cancel-indoor {
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
            padding: 9px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel-indoor:hover {
            background: #e9ecef;
        }

        .btn-save-indoor {
            background: #006637;
            color: white;
            border: none;
            padding: 9px 26px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-save-indoor:hover {
            background: #004d29;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 102, 55, 0.3);
        }

        /* ===== MOBILE RESPONSIVENESS ===== */
        @media (max-width: 991px) {
            .profile-container {
                padding: 15px !important;
            }

            .profile-header {
                flex-direction: column !important;
                align-items: center !important;
                text-align: center;
                gap: 20px;
                padding-bottom: 20px;
            }

            .header-left {
                width: 100%;
                text-align: center;
            }

            .header-right {
                width: 100%;
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap !important;
                justify-content: center !important;
                gap: 10px !important;
            }

            .header-right .follow-up-btn {
                margin-right: 0 !important;
                flex: 1 1 auto;
                min-width: 140px;
                justify-content: center;
                font-size: 14px;
                padding: 10px;
            }

            .edit-profile-align-btn {
                justify-content: center !important;
            }

            .card-body {
                padding: 15px !important;
            }

            .profile-content {
                flex-direction: column !important;
                gap: 0 !important;
                padding: 0 !important;
            }

            .profile-sidebar,
            .profile-main {
                width: 100% !important;
                max-height: none !important;
                padding: 0 !important;
                box-shadow: none !important;
                background: transparent !important;
            }

            .row {
                margin: 0 !important;
            }

            .col-lg-4,
            .col-lg-8,
            .col-lg-12 {
                padding: 0 !important;
            }

            .data-table {
                display: block !important;
                width: 100% !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }

            /* Modal Responsiveness */
            #indoorTreatmentModal .modal-dialog {
                margin: 10px !important;
                max-width: calc(100% - 20px) !important;
            }

            .indoor-patient-info {
                grid-template-columns: 1fr !important;
                gap: 5px !important;
            }

            .date-slot-header {
                padding: 10px !important;
                gap: 8px !important;
            }

            .date-slot-header input {
                max-width: 100% !important;
                flex: 1;
            }

            .medicine-row {
                grid-template-columns: 1fr !important;
                gap: 5px !important;
            }

            .medicine-row input {
                width: 100% !important;
            }

            .delete-medicine-btn {
                width: 100% !important;
                margin-top: 5px;
            }

            .fnf-title {
                font-size: 18px !important;
            }

            /* Modal specific button fixes */
            .add-slot-btn {
                width: 100% !important;
                justify-content: center !important;
                padding: 12px !important;
                font-size: 14px !important;
            }

            .remove-slot-btn {
                width: 100% !important;
                justify-content: center !important;
                padding: 8px !important;
                margin-top: 5px !important;
            }

            .date-slot-header {
                padding: 15px !important;
            }

            .modal-footer {
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                padding: 15px !important;
                gap: 10px !important;
            }

            .modal-footer .btn-cancel-indoor,
            .modal-footer .btn-save-indoor {
                flex: 1 !important;
                margin: 0 !important;
                font-size: 14px !important;
                padding: 12px 5px !important;
                text-align: center !important;
                justify-content: center !important;
                display: flex !important;
                align-items: center !important;
            }
        }

        /* ── CLINICAL PRINT SYSTEM ── */
        .print-view {
            display: none;
            background: white;
            color: #1e293b;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            line-height: 1.5;
        }

        @media print {
            @page {
                margin: 0.8cm;
                size: A4;
            }

            /* Hide ALL UI elements and layout containers */
            nav,
            aside,
            footer,
            .main-header,
            .main-sidebar,
            .main-footer,
            .back-button,
            .profile-header,
            .row,
            .card,
            .profile-content,
            .modal,
            .pagination,
            .action-buttons,
            .no-print {
                display: none !important;
            }

            /* Reset the flow for parent containers but keep them visible for context */
            body,
            html,
            .wrapper,
            .content-wrapper,
            .content,
            .profile-container {
                display: block !important;
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
                border: none !important;
            }

            /* Ensure ONLY the print view is visible */
            .print-view {
                display: block !important;
                visibility: visible !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                position: relative !important;
            }

            .print-view * {
                visibility: visible !important;
            }

            /* Background colors for print */
            .print-header {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .patient-print-card {
                background: #f8fafc !important;
                print-color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
                border: 1px solid #e2e8f0 !important;
            }

            .print-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                border-bottom: 3px solid #006637;
                padding-bottom: 15px;
                margin-bottom: 25px;
            }

            .clinic-info h2 {
                color: #006637;
                font-size: 26px;
                font-weight: 800;
                margin-bottom: 5px;
            }

            .clinic-info p {
                font-size: 13px;
                margin: 0;
                color: #475569;
            }

            .print-data-row {
                display: flex;
                margin-bottom: 8px;
                font-size: 14px;
            }

            .print-data-label {
                font-weight: 700;
                color: #64748b;
                width: 140px;
                flex-shrink: 0;
            }

            .print-data-value {
                color: #1e293b;
                font-weight: 600;
            }

            .print-section-title {
                font-size: 16px;
                font-weight: 800;
                color: #006637;
                border-left: 5px solid #006637;
                padding-left: 12px;
                margin: 30px 0 15px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .prescription-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 30px;
            }

            .prescription-table th {
                background: #f1f5f9 !important;
                color: #475569;
                font-weight: 700;
                text-align: left;
                padding: 12px;
                border-bottom: 2px solid #cbd5e1;
                font-size: 12px;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .prescription-table td {
                padding: 12px;
                border-bottom: 1px solid #e2e8f0;
                font-size: 13px;
                vertical-align: top;
            }

            .print-footer {
                margin-top: 60px;
                display: flex;
                justify-content: space-between;
                padding-top: 20px;
                border-top: 1px solid #e2e8f0;
            }

            .sig-box {
                text-align: center;
                min-width: 200px;
            }

            .sig-line {
                margin-top: 40px;
                border-top: 1.5px solid #94a3b8;
                padding-top: 5px;
                font-size: 11px;
                color: #64748b;
            }
        }
    </style>

    @php
        function formatValue($value)
        {
            return ($value === null || $value === 'null' || $value === '' || $value === '0.00' || $value === '0') ? '' : $value;
        }
    @endphp

    <div class="profile-container">
        <a href="{{ route('svc-patient') }}" class="back-button">
            <i class="bi bi-arrow-left"></i> Back to Patients
        </a>

        <div class="profile-header">
            <div class="header-left">
                <div class="profile-title">SVC Patient Profile</div>
            </div>
            <div class="header-right">

                @if($patient->getMeta('pt_status') === 'IPD')
                <button type="button" class="follow-up-btn" data-bs-toggle="modal" data-bs-target="#indoorTreatmentModal"
                    style="background-color: #007bff; border-color: #007bff; margin-right: 10px;">
                    <i class="bi bi-hospital"></i> Indoor Treatment
                </button>
                @endif

                <button type="button" class="follow-up-btn" id="addIndoorPatientBtn"
                    onclick="openProfileIndoorModal()"
                    style="background-color: #17a2b8; border-color: #17a2b8; margin-right: 10px;">
                    <i class="bi bi-hospital-fill"></i> Add Indoor Patient
                </button>

                <a href="{{ route('add.follow.up', ['patient_id' => $patient->patient_id]) }}" class="follow-up-btn">Add
                    Follow Up</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 p-0">
                <div class="card mb-4">
                    <div class="card-body py-4 text-center">
                        <form action="{{ route('svc.profile.update-image', $patient->id) }}" method="POST"
                            enctype="multipart/form-data" id="profileImageForm">
                            @csrf
                            <div class="profile-image-wrapper">
                                <div class="profile-image-container" id="profileImagePreview">
                                    @php $profileImage = $patient->getMeta('profile_image'); @endphp
                                    @if($profileImage && file_exists(public_path($profileImage)))
                                        <img src="{{ asset($profileImage) }}" alt="Profile Image">
                                    @else
                                        <i class="bi bi-person-fill"></i>
                                    @endif
                                </div>
                                <label for="profile_image_input" class="upload-label" title="Change Profile Image">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input type="file" name="profile_image" id="profile_image_input" class="d-none"
                                    accept="image/*" onchange="previewPatientImage(this)">
                            </div>
                            <div id="imageSaveContainer" class="text-center">
                                <button type="submit" class="save-profile-btn" id="saveImageBtn">Save Image</button>
                            </div>
                        </form>
                        <p class="my-3 profile_txt_color mb-2 pb-0" style="font-weight: 600;">
                            {{ $patient->patient_name ?? 'N/A' }}
                        </p>
                        <p class="text-muted small">Hospital ID: {{ $patient->patient_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 pr-0">
                <div class="card mb-4">
                    <div class="card-body py-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 profile_txt_color">Full Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $patient->patient_name ?? '' }}</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 profile_txt_color">Address</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $patient->address ?? '' }}</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 profile_txt_color">Assigned Doctor</p>
                            </div>
                            <div class="col-sm-9">
                                @php
                                    $doctorId = $meta['doctor_id'] ?? null;
                                    $doctor = null;
                                    if ($doctorId) {
                                        $doctor = \App\Models\User::find($doctorId);
                                    }
                                @endphp
                                <p class="text-muted mb-0">{{ $doctor ? $doctor->name : 'Not Assigned' }}</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 profile_txt_color">Age</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $patient->age ?? '' }}</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 profile_txt_color">Gender</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $patient->getMeta('gender') ?? '' }}</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 profile_txt_color">Reference By</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $patient->getMeta('reference_by') ?? '' }}</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 profile_txt_color">Client Type</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ ucfirst($patient->getMeta('client_type') ?? 'New') }}</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 profile_txt_color">Programs Detail</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">
                                    @php
                                        $svcPrograms = $patient->getMeta('program_name');
                                        $svcPrograms = is_array($svcPrograms)
                                            ? $svcPrograms
                                            : (json_decode($svcPrograms, true) ?? [$svcPrograms]);
                                    @endphp
                                    {{ !empty(array_filter((array)$svcPrograms)) ? implode(', ', array_filter((array)$svcPrograms)) : '-' }}
                                </p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="edit-profile-align-btn">
                            <button class="edit-profile-btn"
                                onclick="window.location.href='{{ route('edit.svc.inquiry', $patient->id) }}'">Edit
                                Profile</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-content">
                <div class="profile-main">

                    <!-- Inquiry Details -->
                    <!-- <div class="row">
                                                                                <div class="col-lg-12 p-0">
                                                                                    <div class="card-header mb-2">
                                                                                        <div class="section-title">
                                                                                            <h3 class="bold font-up fnf-title">Inquiry Details</h3>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card mb-4">
                                                                                        <div class="card-body py-2">
                                                                                            @php $excludedMetaKeys = ['profile_image']; @endphp
                                                                                            @if(!empty($meta) && is_array($meta))
                                                                                                @foreach($meta as $k => $v)
                                                                                                    @continue(in_array($k, $excludedMetaKeys, true))
                                                                                                    @php
                                                                                                        $label = ucwords(str_replace(['_', '-'], ' ', (string) $k));
                                                                                                        if (is_array($v)) {
                                                                                                            $displayValue = implode(', ', array_filter(array_map(function ($x) {
                                                                                                                if (is_array($x))
                                                                                                                    return json_encode($x);
                                                                                                                return (string) $x;
                                                                                                            }, $v), function ($x) {
                                                                                                                return $x !== ''; }));
                                                                                                        } else {
                                                                                                            $displayValue = (string) $v;
                                                                                                        }
                                                                                                        $displayValue = trim($displayValue);
                                                                                                    @endphp
                                                                                                    @if($displayValue !== '')
                                                                                                        <div class="row">
                                                                                                            <div class="col-sm-4">
                                                                                                                <p class="mb-0 profile_txt_color">{{ $label }}</p>
                                                                                                            </div>
                                                                                                            <div class="col-sm-8">
                                                                                                                <p class="text-muted mb-0">{{ $displayValue }}</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <hr class="my-2">
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @else
                                                                                                <p class="text-muted mb-0">No inquiry details found.</p>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div> -->

                    <!-- Payment Section -->
                    <div class="row">
                        <div class="col-lg-12 p-0">
                            <div class="card-header mb-2">
                                <div class="section-title">
                                    <h3 class="bold font-up fnf-title">Payment</h3>
                                </div>
                            </div>
                            @php
                                $allPayments = collect();
                                $initialCash = $patient->getMeta('cash_payment');
                                $initialTotal = $patient->getMeta('total_payment');
                                $initialDiscount = $patient->getMeta('discount_payment');
                                $initialGiven = $patient->getMeta('given_payment');
                                $initialDue = $patient->getMeta('due_payment');
                                $initialGp = $patient->getMeta('gp_payment');
                                $initialCheque = $patient->getMeta('cheque_payment');
                                $hasInitialPaymentData = $initialCash || $initialTotal || $initialDiscount || $initialGiven || $initialDue || $initialGp || $initialCheque;
                                if ($hasInitialPaymentData) {
                                    $allPayments->push([
                                        'date' => $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : '',
                                        'payment_method' => $initialCash ? 'Cash: ' . $initialCash : ($initialGp ? 'GPay: ' . $initialGp : ($initialCheque ? 'Cheque: ' . $initialCheque : '')),
                                        'total' => $initialTotal ?? '',
                                        'discount' => $initialDiscount ?? '',
                                        'given' => $initialGiven ?? '',
                                        'due' => $initialDue ?? '',
                                        'type' => 'initial'
                                    ]);
                                }
                                $followUps = $patient->followups()->with('metas')->orderBy('followup_date', 'desc')->get();
                                foreach ($followUps as $followUp) {
                                    $followUpMetas = [];
                                    foreach ($followUp->metas as $meta) {
                                        $followUpMetas[$meta->meta_key] = $meta->meta_value;
                                    }
                                    $followUpCash = $followUpMetas['cash_payment'] ?? '';
                                    $followUpTotal = $followUpMetas['total_payment'] ?? '';
                                    $followUpDiscount = $followUpMetas['discount_payment'] ?? '';
                                    $followUpGiven = $followUpMetas['given_payment'] ?? '';
                                    $followUpDue = $followUpMetas['due_payment'] ?? '';
                                    $followUpGp = $followUpMetas['gp_payment'] ?? '';
                                    $followUpCheque = $followUpMetas['cheque_payment'] ?? '';
                                    $hasFollowUpPaymentData = $followUpCash || $followUpTotal || $followUpDiscount || $followUpGiven || $followUpDue || $followUpGp || $followUpCheque;
                                    if ($hasFollowUpPaymentData) {
                                        $paymentMethod = '';
                                        if ($followUpCash)
                                            $paymentMethod = 'Cash: ' . $followUpCash;
                                        elseif ($followUpGp)
                                            $paymentMethod = 'GPay: ' . $followUpGp;
                                        elseif ($followUpCheque)
                                            $paymentMethod = 'Cheque: ' . $followUpCheque;
                                        $allPayments->push([
                                            'date' => $followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->format('d/m/Y') : '',
                                            'payment_method' => $paymentMethod,
                                            'total' => $followUpTotal,
                                            'discount' => $followUpDiscount,
                                            'given' => $followUpGiven,
                                            'due' => $followUpDue,
                                            'type' => 'followup'
                                        ]);
                                    }
                                }
                                $currentPaymentPage = request()->get('payment_page', 1);
                                $paymentPerPage = 3;
                                $paymentChunks = $allPayments->chunk($paymentPerPage);
                                $currentPaymentChunk = $paymentChunks[$currentPaymentPage - 1] ?? collect();
                                $totalPaymentPages = count($paymentChunks);
                            @endphp

                            @if($allPayments->count() > 0)
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Payment Method</th>
                                            <th>Total</th>
                                            <th>Discount</th>
                                            <th>Given</th>
                                            <th>Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($currentPaymentChunk->count() > 0)
                                            @foreach($currentPaymentChunk as $index => $payment)
                                                <tr>
                                                    <td>{{ ($currentPaymentPage - 1) * $paymentPerPage + $index + 1 }}</td>
                                                    <td>{{ formatValue($payment['date']) }}</td>
                                                    <td>{{ formatValue($payment['payment_method']) }}</td>
                                                    <td>{{ formatValue($payment['total']) }}</td>
                                                    <td>{{ formatValue($payment['discount']) }}</td>
                                                    <td>{{ formatValue($payment['given']) }}</td>
                                                    <td>{{ formatValue($payment['due']) }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="empty-data">No payment records found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    <div class="pagination-info">
                                        @if($currentPaymentChunk->count() > 0)
                                            Showing {{ ($currentPaymentPage - 1) * $paymentPerPage + 1 }} to
                                            {{ min($currentPaymentPage * $paymentPerPage, $allPayments->count()) }} of
                                            {{ $allPayments->count() }} entries
                                        @else
                                            Showing 0 to 0 of 0 entries
                                        @endif
                                    </div>
                                    <div class="pagination-buttons">
                                        @if($currentPaymentPage <= 1)
                                            <button class="pagination-btn" disabled>Previous</button>
                                        @else
                                            <a href="?payment_page={{ $currentPaymentPage - 1 }}"
                                                class="pagination-btn">Previous</a>
                                        @endif
                                        @if($currentPaymentPage >= $totalPaymentPages)
                                            <button class="pagination-btn" disabled>Next</button>
                                        @else
                                            <a href="?payment_page={{ $currentPaymentPage + 1 }}" class="pagination-btn">Next</a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="empty-data" style="padding: 40px; text-align: center;">No payment records found
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Follow Up Section -->
                    <div class="row pt-5">
                        <div class="col-lg-12 p-0">
                            <div class="card-header mb-2">
                                <div class="section-title">
                                    <h3 class="bold font-up fnf-title">Follow Up</h3>
                                </div>
                            </div>
                            @php
                                $allFollowUps = collect();
                                $initialFollowUp = [
                                    'id' => 'initial_' . $patient->id,
                                    'type' => 'initial',
                                    'followup_date' => $patient->inquiry_date,
                                    'weight' => $patient->getMeta('weight'),
                                    'diagnosis' => $patient->diagnosis,
                                    'complain' => $patient->getMeta('complain'),
                                    'bp' => $patient->getMeta('blood_pressure'),
                                    'investigation' => $patient->getMeta('investigation'),
                                    'rbs' => $patient->getMeta('rbs'),
                                    'pt_status' => $patient->getMeta('pt_status'),
                                    'notes' => $patient->getMeta('notes'),
                                    'next_follow_date' => $patient->next_follow_date,
                                    'doctor_id' => $meta['doctor_id'] ?? null,
                                    'doctor_name' => isset($meta['doctor_id']) ? (\App\Models\User::find($meta['doctor_id'])->name ?? 'N/A') : 'N/A',
                                    'zoom_meeting_id' => $meta['zoom_meeting_id'] ?? null,
                                    'zoom_start_url' => $meta['zoom_start_url'] ?? null,
                                    'zoom_join_url' => $meta['zoom_join_url'] ?? null,
                                    'created_at' => $patient->created_at
                                ];
                                $allFollowUps->push((object) $initialFollowUp);
                                $followUps = $patient->followups()->with(['metas', 'doctor'])->orderBy('followup_date', 'desc')->get();
                                foreach ($followUps as $followUp) {
                                    $followUpMetas = [];
                                    foreach ($followUp->metas as $meta) {
                                        $followUpMetas[$meta->meta_key] = $meta->meta_value;
                                    }
                                    $followUpData = [
                                        'id' => $followUp->id,
                                        'type' => 'followup',
                                        'followup_date' => $followUp->followup_date,
                                        'weight' => $followUpMetas['weight'] ?? ($followUpMetas['weight_0'] ?? ''),
                                        'diagnosis' => $followUpMetas['diagnosis'] ?? ($followUpMetas['diagnosis_0'] ?? ''),
                                        'complain' => $followUpMetas['complain'] ?? '',
                                        'bp' => $followUpMetas['blood_pressure'] ?? ($followUpMetas['blood_pressure_0'] ?? ''),
                                        'investigation' => $followUpMetas['investigation'] ?? '',
                                        'rbs' => $followUpMetas['rbs'] ?? ($followUpMetas['rbs_0'] ?? ''),
                                        'pt_status' => $followUpMetas['pt_status'] ?? ($followUpMetas['pt_status_0'] ?? ''),
                                        'notes' => $followUpMetas['notes'] ?? '',
                                        'next_follow_date' => $followUp->next_follow_date,
                                        'doctor_id' => $followUp->doctor_id,
                                        'doctor_name' => $followUp->doctor ? $followUp->doctor->name : 'N/A',
                                        'zoom_meeting_id' => $followUp->zoom_meeting_id,
                                        'zoom_start_url' => $followUp->zoom_start_url,
                                        'zoom_join_url' => $followUp->zoom_join_url,
                                        'created_at' => $followUp->created_at
                                    ];
                                    $allFollowUps->push((object) $followUpData);
                                }
                                $currentPage = request()->get('followup_page', 1);
                                $perPage = 3;
                                $currentItems = $allFollowUps->slice(($currentPage - 1) * $perPage, $perPage)->all();
                                $totalPages = ceil($allFollowUps->count() / $perPage);
                            @endphp

                            <div>
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Weight</th>
                                            <th>Complain</th>
                                            <th>Diagnosis</th>
                                            <th>BP</th>
                                            <th>RBS</th>
                                            <th>Investigation</th>
                                            <th>PT Status</th>
                                            <th>Note</th>
                                            <th>Doctor</th>
                                            <th>Next Follow Date</th>
                                            <th width="120px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($currentItems) > 0)
                                            @php
                                                $groupedItems = collect($currentItems)->groupBy(function ($item) {
                                                    return $item->followup_date ? \Carbon\Carbon::parse($item->followup_date)->format('d/m/Y') : 'Initial';
                                                });
                                                $sn = ($currentPage - 1) * $perPage + 1;
                                            @endphp

                                            @foreach($groupedItems as $date => $entries)
                                                <tr style="background-color: #f8fafc; border-left: 4px solid #006637;">
                                                    <td colspan="13" class="py-2 px-3">
                                                        <strong style="color: #006637;"><i class="fas fa-calendar-alt mr-2"></i>
                                                            {{ $date }}</strong>
                                                        <span class="badge badge-pill badge-success ml-2"
                                                            style="font-size: 0.75rem;">{{ count($entries) }} Entry(s)</span>
                                                    </td>
                                                </tr>
                                                @foreach($entries as $followUp)
                                                    <tr>
                                                        <td>{{ $sn++ }}</td>
                                                        <td class="text-muted small">--</td>
                                                        <td>{{ formatValue($followUp->weight) }}</td>
                                                        <td>{{ formatValue($followUp->complain) }}</td>
                                                        <td>{{ formatValue($followUp->diagnosis) }}</td>
                                                        <td>{{ formatValue($followUp->bp) }}</td>
                                                        <td>{{ formatValue($followUp->rbs) }}</td>
                                                        <td>{{ formatValue($followUp->investigation) }}</td>
                                                        <td>{{ formatValue($followUp->pt_status) }}</td>
                                                        <td>{{ formatValue($followUp->notes) }}</td>
                                                        <td>{{ formatValue($followUp->doctor_name ?? 'N/A') }}</td>
                                                        <td>{{ formatValue($followUp->next_follow_date ? \Carbon\Carbon::parse($followUp->next_follow_date)->format('d/m/Y') : '') }}
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                @if($followUp->type === 'initial')
                                                                    <a href="{{ route('edit.svc.inquiry', $patient->id) }}"
                                                                        class="action-btn edit-btn" title="Edit Initial Inquiry">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('delete-inquiry', $patient->id) }}" method="POST"
                                                                        class="delete-form">
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit" class="action-btn delete-btn"
                                                                            title="Delete Entire Patient Record"
                                                                            onclick="return confirmDeletePatient()">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <a href="{{ route('edit.follow.up', ['patient_id' => $patient->patient_id, 'followup_id' => $followUp->id]) }}"
                                                                        class="action-btn edit-btn" title="Edit Follow Up">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('delete.follow.up', $followUp->id) }}"
                                                                        method="POST" class="delete-form">
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit" class="action-btn delete-btn"
                                                                            title="Delete Follow Up"
                                                                            onclick="return confirm('Are you sure you want to delete this follow-up record? This action cannot be undone.')">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif

                                                                @php
                                                                    if ($followUp->type === 'initial') {
                                                                        $createZoomRoute = route('zoom.meeting.create', $followUp->id);
                                                                    } else {
                                                                        $createZoomRoute = route('followup.create-zoom-meeting', $followUp->id);
                                                                    }
                                                                @endphp

                                                                @if($followUp->zoom_join_url)
                                                                    @php
                                                                        $waPhone = preg_replace('/[^0-9]/', '', $patient->getMeta('phone') ?? '');
                                                                        if (strlen($waPhone) == 10)
                                                                            $waPhone = '91' . $waPhone;
                                                                        $zoomJoinUrl = $followUp->zoom_join_url;
                                                                        $internalJoinUrl = $followUp->zoom_start_url ?? $zoomJoinUrl;
                                                                        $waMessage = "Hello " . ($patient->patient_name ?? 'Patient') . ", your video consultation is scheduled. You can join the meeting by clicking this link: " . $zoomJoinUrl;
                                                                        $waUrl = "https://wa.me/" . $waPhone . "?text=" . urlencode($waMessage);
                                                                    @endphp
                                                                    <button type="button" class="action-btn btn-zoom-join"
                                                                        title="Zoom Meeting Options"
                                                                        onclick="openZoomModal('{{ $internalJoinUrl }}', '{{ $zoomJoinUrl }}', '{{ $waUrl }}')">
                                                                        <i class="fas fa-video"></i>
                                                                    </button>
                                                                @elseif($followUp->doctor_id)
                                                                    <form action="{{ $createZoomRoute }}" method="POST"
                                                                        style="display: inline;">
                                                                        @csrf
                                                                        <button type="submit" class="action-btn btn-zoom-create"
                                                                            title="Create Zoom Meeting">
                                                                            <i class="fas fa-video-slash"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="13" class="empty-data">No follow-up records found.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            @if($totalPages > 1)
                                <div class="pagination">
                                    <div class="pagination-info">Showing {{ ($currentPage - 1) * $perPage + 1 }} to
                                        {{ min($currentPage * $perPage, $allFollowUps->count()) }} of
                                        {{ $allFollowUps->count() }} entries
                                    </div>
                                    <div class="pagination-buttons">
                                        @if($currentPage <= 1)
                                            <button class="pagination-btn" disabled>Previous</button>
                                        @else
                                            <a href="?followup_page={{ $currentPage - 1 }}" class="pagination-btn">Previous</a>
                                        @endif
                                        @if($currentPage >= $totalPages)
                                            <button class="pagination-btn" disabled>Next</button>
                                        @else
                                            <a href="?followup_page={{ $currentPage + 1 }}" class="pagination-btn">Next</a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="pagination">
                                    <div class="pagination-info">Showing {{ $allFollowUps->count() }} to
                                        {{ $allFollowUps->count() }} of {{ $allFollowUps->count() }} entries
                                    </div>
                                    <div class="pagination-buttons">
                                        <button class="pagination-btn" disabled>Previous</button>
                                        <button class="pagination-btn" disabled>Next</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Laboratory Investigation Section -->
                    <!-- <div class="row pt-5">
                                                                                <div class="col-lg-12 p-0">
                                                                                    <div class="card-header mb-2">
                                                                                        <div class="section-title"><h3 class="bold font-up fnf-title">Laboratory Investigation</h3></div>
                                                                                    </div>
                                                                                    @php
                                                                                        $allLabInvestigations = collect();
                                                                                        $initialLab = [
                                                                                            'date' => $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : '',
                                                                                            'hb' => $patient->getMeta('hb') ?? '', 'tc' => $patient->getMeta('tc') ?? '',
                                                                                            'pc' => $patient->getMeta('pc') ?? '', 'mp' => $patient->getMeta('MP') ?? '',
                                                                                            'hb1ac' => $patient->getMeta('HB1AC') ?? '', 'fbs' => $patient->getMeta('fbs') ?? '',
                                                                                            'pp2bs' => $patient->getMeta('pp2bs') ?? '', 's_widal' => $patient->getMeta('S_widal') ?? '',
                                                                                            'usg' => $patient->getMeta('USG') ?? '', 'x_ray' => $patient->getMeta('X_ray') ?? '',
                                                                                            'sgpt' => $patient->getMeta('SGPT') ?? '', 's_creatinine' => $patient->getMeta('s_creatinine') ?? '',
                                                                                            'ns1ag' => $patient->getMeta('NS1Ag') ?? '', 'dengue_igm' => $patient->getMeta('DengueIGM') ?? '',
                                                                                            's_cholesterol' => $patient->getMeta('s_cholesterol') ?? '', 's_triglyceride' => $patient->getMeta('STriglyceride') ?? '',
                                                                                            'hdl' => $patient->getMeta('HDL') ?? '', 'ldl' => $patient->getMeta('LDL') ?? '',
                                                                                            'vldl' => $patient->getMeta('VLDL') ?? '', 's_b12' => $patient->getMeta('SB12') ?? '',
                                                                                            's_d3' => $patient->getMeta('SD3') ?? '', 'urine' => $patient->getMeta('Urine') ?? '',
                                                                                            'crp' => $patient->getMeta('CRP') ?? '', 's_t3' => $patient->getMeta('St3') ?? '',
                                                                                            's_t4' => $patient->getMeta('St4') ?? '', 's_tsh' => $patient->getMeta('STSH') ?? '',
                                                                                            'esr' => $patient->getMeta('ESR') ?? '', 'specific_test' => $patient->getMeta('specific_test') ?? '',
                                                                                            'type' => 'initial'
                                                                                        ];
                                                                                        $hasInitialLabData = false;
                                                                                        foreach ($initialLab as $key => $value) {
                                                                                            if ($key !== 'date' && $key !== 'type' && !empty($value) && $value !== 'null') { $hasInitialLabData = true; break; }
                                                                                        }
                                                                                        if ($hasInitialLabData) { $allLabInvestigations->push($initialLab); }
                                                                                        foreach ($followUps as $followUp) {
                                                                                            $followUpMetas = [];
                                                                                            foreach ($followUp->metas as $meta) { $followUpMetas[$meta->meta_key] = $meta->meta_value; }
                                                                                            $followUpLab = [
                                                                                                'date' => $followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->format('d/m/Y') : '',
                                                                                                'hb' => $followUpMetas['hb'] ?? '', 'tc' => $followUpMetas['tc'] ?? '',
                                                                                                'pc' => $followUpMetas['pc'] ?? '', 'mp' => $followUpMetas['MP'] ?? '',
                                                                                                'hb1ac' => $followUpMetas['HB1AC'] ?? '', 'fbs' => $followUpMetas['fbs'] ?? '',
                                                                                                'pp2bs' => $followUpMetas['pp2bs'] ?? '', 's_widal' => $followUpMetas['S_widal'] ?? '',
                                                                                                'usg' => $followUpMetas['USG'] ?? '', 'x_ray' => $followUpMetas['X_ray'] ?? '',
                                                                                                'sgpt' => $followUpMetas['SGPT'] ?? '', 's_creatinine' => $followUpMetas['s_creatinine'] ?? '',
                                                                                                'ns1ag' => $followUpMetas['NS1Ag'] ?? '', 'dengue_igm' => $followUpMetas['DengueIGM'] ?? '',
                                                                                                's_cholesterol' => $followUpMetas['s_cholesterol'] ?? '', 's_triglyceride' => $followUpMetas['STriglyceride'] ?? '',
                                                                                                'hdl' => $followUpMetas['HDL'] ?? '', 'ldl' => $followUpMetas['LDL'] ?? '',
                                                                                                'vldl' => $followUpMetas['VLDL'] ?? '', 's_b12' => $followUpMetas['SB12'] ?? '',
                                                                                                's_d3' => $followUpMetas['SD3'] ?? '', 'urine' => $followUpMetas['Urine'] ?? '',
                                                                                                'crp' => $followUpMetas['CRP'] ?? '', 's_t3' => $followUpMetas['St3'] ?? '',
                                                                                                's_t4' => $followUpMetas['St4'] ?? '', 's_tsh' => $followUpMetas['STSH'] ?? '',
                                                                                                'esr' => $followUpMetas['ESR'] ?? '', 'specific_test' => $followUpMetas['specific_test'] ?? '',
                                                                                                'type' => 'followup'
                                                                                            ];
                                                                                            $hasFollowUpLabData = false;
                                                                                            foreach ($followUpLab as $key => $value) {
                                                                                                if ($key !== 'date' && $key !== 'type' && !empty($value) && $value !== 'null') { $hasFollowUpLabData = true; break; }
                                                                                            }
                                                                                            if ($hasFollowUpLabData) { $allLabInvestigations->push($followUpLab); }
                                                                                        }
                                                                                        $currentLabPage = request()->get('lab_page', 1);
                                                                                        $labPerPage = 3;
                                                                                        $labChunks = $allLabInvestigations->chunk($labPerPage);
                                                                                        $currentLabChunk = $labChunks[$currentLabPage - 1] ?? collect();
                                                                                        $totalLabPages = count($labChunks);
                                                                                    @endphp

                                                                                    @if($allLabInvestigations->count() > 0)
                                                                                        <div class="lab-table-container">
                                                                                            <table class="data-table compact-table">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>#</th><th>Date</th><th>HB</th><th>TC</th><th>PC</th><th>MP</th>
                                                                                                        <th>HB1Ac</th><th>FBS</th><th>PP2BS</th><th>S.widal</th><th>USG</th>
                                                                                                        <th>X-ray</th><th>SGPT</th><th>S.Creatinine</th><th>NS1Ag</th>
                                                                                                        <th>Dengue IGM</th><th>S.Cholesterol</th><th>S.Triglyceride</th>
                                                                                                        <th>HDL</th><th>LDL</th><th>VLDL</th><th>S.B12</th><th>S.D3</th>
                                                                                                        <th>Urine</th><th>CRP</th><th>S.T3</th><th>S.T4</th><th>S.TSH</th>
                                                                                                        <th>ESR</th><th>Any Specific Test</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    @if($currentLabChunk->count() > 0)
                                                                                                        @foreach($currentLabChunk as $index => $lab)
                                                                                                        <tr>
                                                                                                            <td>{{ ($currentLabPage - 1) * $labPerPage + $index + 1 }}</td>
                                                                                                            <td>{{ formatValue($lab['date']) }}</td>
                                                                                                            <td>{{ formatValue($lab['hb']) }}</td>
                                                                                                            <td>{{ formatValue($lab['tc']) }}</td>
                                                                                                            <td>{{ formatValue($lab['pc']) }}</td>
                                                                                                            <td>{{ formatValue($lab['mp']) }}</td>
                                                                                                            <td>{{ formatValue($lab['hb1ac']) }}</td>
                                                                                                            <td>{{ formatValue($lab['fbs']) }}</td>
                                                                                                            <td>{{ formatValue($lab['pp2bs']) }}</td>
                                                                                                            <td>{{ formatValue($lab['s_widal']) }}</td>
                                                                                                            <td>{{ formatValue($lab['usg']) }}</td>
                                                                                                            <td>{{ formatValue($lab['x_ray']) }}</td>
                                                                                                            <td>{{ formatValue($lab['sgpt']) }}</td>
                                                                                                            <td>{{ formatValue($lab['s_creatinine']) }}</td>
                                                                                                            <td>{{ formatValue($lab['ns1ag']) }}</td>
                                                                                                            <td>{{ formatValue($lab['dengue_igm']) }}</td>
                                                                                                            <td>{{ formatValue($lab['s_cholesterol']) }}</td>
                                                                                                            <td>{{ formatValue($lab['s_triglyceride']) }}</td>
                                                                                                            <td>{{ formatValue($lab['hdl']) }}</td>
                                                                                                            <td>{{ formatValue($lab['ldl']) }}</td>
                                                                                                            <td>{{ formatValue($lab['vldl']) }}</td>
                                                                                                            <td>{{ formatValue($lab['s_b12']) }}</td>
                                                                                                            <td>{{ formatValue($lab['s_d3']) }}</td>
                                                                                                            <td>{{ formatValue($lab['urine']) }}</td>
                                                                                                            <td>{{ formatValue($lab['crp']) }}</td>
                                                                                                            <td>{{ formatValue($lab['s_t3']) }}</td>
                                                                                                            <td>{{ formatValue($lab['s_t4']) }}</td>
                                                                                                            <td>{{ formatValue($lab['s_tsh']) }}</td>
                                                                                                            <td>{{ formatValue($lab['esr']) }}</td>
                                                                                                            <td>{{ formatValue($lab['specific_test']) }}</td>
                                                                                                        </tr>
                                                                                                        @endforeach
                                                                                                    @else
                                                                                                        <tr><td colspan="30" class="empty-data">No laboratory investigations found</td></tr>
                                                                                                    @endif
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                        <div class="pagination">
                                                                                            <div class="pagination-info">
                                                                                                @if($currentLabChunk->count() > 0)
                                                                                                    Showing {{ ($currentLabPage - 1) * $labPerPage + 1 }} to {{ min($currentLabPage * $labPerPage, $allLabInvestigations->count()) }} of {{ $allLabInvestigations->count() }} entries
                                                                                                @else
                                                                                                    Showing 0 to 0 of 0 entries
                                                                                                @endif
                                                                                            </div>
                                                                                            <div class="pagination-buttons">
                                                                                                @if($currentLabPage <= 1)
                                                                                                    <button class="pagination-btn" disabled>Previous</button>
                                                                                                @else
                                                                                                    <a href="?lab_page={{ $currentLabPage - 1 }}" class="pagination-btn">Previous</a>
                                                                                                @endif
                                                                                                @if($currentLabPage >= $totalLabPages)
                                                                                                    <button class="pagination-btn" disabled>Next</button>
                                                                                                @else
                                                                                                    <a href="?lab_page={{ $currentLabPage + 1 }}" class="pagination-btn">Next</a>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @else
                                                                                        <div class="empty-data" style="padding: 40px; text-align: center;">No laboratory investigations found</div>
                                                                                    @endif
                                                                                </div>
                                                                            </div> -->

                    <!-- Inside Treatment Section -->
                    <div class="row pt-5">
                        <div class="col-lg-12 p-0">
                            <div class="card-header mb-2">
                                <div class="section-title">
                                    <h3 class="bold font-up fnf-title">Inside Treatment</h3>
                                </div>
                            </div>
                            @php
                                $allInsideTreatments = collect();
                                $initialInsideTreatments = $treatments['inside'] ?? [];
                                foreach ($initialInsideTreatments as $treatment) {
                                    $allInsideTreatments->push(array_merge((array) $treatment, [
                                        'date' => $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : '',
                                        'sort_date' => $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->timestamp : 0,
                                        'type' => 'initial'
                                    ]));
                                }
                                foreach ($followUps as $followUp) {
                                    $followUpInsideTreatments = \App\Models\PatientTreatment::where('followup_id', $followUp->id)->where('type', 'inside')->get();
                                    foreach ($followUpInsideTreatments as $treatment) {
                                        $allInsideTreatments->push(array_merge($treatment->toArray(), [
                                            'date' => $followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->format('d/m/Y') : '',
                                            'sort_date' => $followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->timestamp : 0,
                                            'type' => 'followup'
                                        ]));
                                    }
                                }

                                $groupedInside = $allInsideTreatments->groupBy('date');
                                $currentInsidePage = request()->get('inside_page', 1);
                                $insidePerPage = 5; // Showing 5 dates per page
                                $insideChunks = $groupedInside->chunk($insidePerPage);
                                $currentInsideChunk = $insideChunks[$currentInsidePage - 1] ?? collect();
                                $totalInsidePages = count($insideChunks);
                            @endphp

                            @if($currentInsideChunk->count() > 0)
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="15%">Date</th>
                                            <th width="40%">Medicine</th>
                                            <th width="20%">Dose</th>
                                            <th width="20%">When</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sn = ($currentInsidePage - 1) * $insidePerPage + 1; @endphp
                                        @foreach($currentInsideChunk as $date => $medicines)
                                            <tr style="background: #f8fafc; border-left: 4px solid #006637;">
                                                <td colspan="5" class="py-2 px-3">
                                                    <strong style="color: #006637;"><i class="fas fa-calendar-day mr-2"></i>
                                                        {{ $date }}</strong>
                                                    <span class="badge badge-pill badge-primary ml-2">{{ count($medicines) }}
                                                        Medicine(s)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ $sn++ }}</td>
                                                <td class="text-muted small">--</td>
                                                <td colspan="3" style="padding: 0;">
                                                    <table style="width: 100%; margin: 0; border: none;">
                                                        @foreach($medicines as $m)
                                                            <tr style="border-bottom: 1px solid #eee;">
                                                                <td width="50%"
                                                                    style="border-right: 1px solid #eee; border-top: none; border-left: none;">
                                                                    {{ formatValue($m['medicine']) }}
                                                                </td>
                                                                <td width="25%" style="border-right: 1px solid #eee; border-top: none;">
                                                                    {{ formatValue($m['dose']) }}
                                                                </td>
                                                                <td width="25%" style="border-top: none; border-right: none;">
                                                                    {{ formatValue($m['timing'] ?? $m['timing_0'] ?? '') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    <div class="pagination-info">Showing {{ ($currentInsidePage - 1) * $insidePerPage + 1 }} to
                                        {{ min($currentInsidePage * $insidePerPage, $groupedInside->count()) }} of
                                        {{ $groupedInside->count() }} entries
                                    </div>
                                    <div class="pagination-buttons">
                                        @if($currentInsidePage <= 1)
                                            <button class="pagination-btn" disabled>Previous</button>
                                        @else
                                            <a href="?inside_page={{ $currentInsidePage - 1 }}" class="pagination-btn">Previous</a>
                                        @endif
                                        @if($currentInsidePage >= $totalInsidePages)
                                            <button class="pagination-btn" disabled>Next</button>
                                        @else
                                            <a href="?inside_page={{ $currentInsidePage + 1 }}" class="pagination-btn">Next</a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="no-treatment">No inside treatment prescribed</div>
                            @endif
                        </div>
                    </div>

                    <!-- Homeopathic Treatment Section -->
                    <div class="row pt-5">
                        <div class="col-lg-12 p-0">
                            <div class="card-header mb-2">
                                <div class="section-title">
                                    <h3 class="bold font-up fnf-title">Homeopathic Treatment</h3>
                                </div>
                            </div>
                            @php
                                $allHomeoTreatments = collect();
                                $initialHomeoTreatments = $treatments['homeo'] ?? [];
                                foreach ($initialHomeoTreatments as $treatment) {
                                    $allHomeoTreatments->push(array_merge($treatment, [
                                        'date' => $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : '',
                                        'sort_date' => $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->timestamp : 0,
                                        'type' => 'initial'
                                    ]));
                                }
                                foreach ($followUps as $followUp) {
                                    $followUpHomeoTreatments = \App\Models\PatientTreatment::where('followup_id', $followUp->id)->where('type', 'homeo')->get();
                                    foreach ($followUpHomeoTreatments as $treatment) {
                                        $allHomeoTreatments->push(array_merge($treatment->toArray(), [
                                            'date' => $followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->format('d/m/Y') : '',
                                            'sort_date' => $followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->timestamp : 0,
                                            'type' => 'followup'
                                        ]));
                                    }
                                }

                                $groupedHomeo = $allHomeoTreatments->groupBy('date');
                                $currentHomeoPage = request()->get('homeo_page', 1);
                                $homeoPerPage = 5;
                                $homeoChunks = $groupedHomeo->chunk($homeoPerPage);
                                $currentHomeoChunk = $homeoChunks[$currentHomeoPage - 1] ?? collect();
                                $totalHomeoPages = count($homeoChunks);
                            @endphp

                            @if($currentHomeoChunk->count() > 0)
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="15%">Date</th>
                                            <th width="45%">Medicine</th>
                                            <th width="15%">Days</th>
                                            <th width="20%">When</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sn = ($currentHomeoPage - 1) * $homeoPerPage + 1; @endphp
                                        @foreach($currentHomeoChunk as $date => $medicines)
                                            <tr style="background: #f8fafc; border-left: 4px solid #006637;">
                                                <td colspan="5" class="py-2 px-3">
                                                    <strong style="color: #006637;"><i class="fas fa-calendar-day mr-2"></i>
                                                        {{ $date }}</strong>
                                                    <span class="badge badge-pill badge-info ml-2">{{ count($medicines) }}
                                                        Medicine(s)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ $sn++ }}</td>
                                                <td class="text-muted small">--</td>
                                                <td colspan="3" style="padding: 0;">
                                                    <table style="width: 100%; margin: 0; border: none;">
                                                        @foreach($medicines as $m)
                                                            <tr style="border-bottom: 1px solid #eee;">
                                                                <td width="55%"
                                                                    style="border-right: 1px solid #eee; border-top: none; border-left: none;">
                                                                    {{ formatValue($m['medicine']) }}
                                                                </td>
                                                                <td width="18%" style="border-right: 1px solid #eee; border-top: none;">
                                                                    {{ formatValue($m['days'] ?? '') }}
                                                                </td>
                                                                <td width="27%" style="border-top: none; border-right: none;">
                                                                    {{ formatValue($m['timing'] ?? $m['timing_0'] ?? '') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    <div class="pagination-info">Showing {{ ($currentHomeoPage - 1) * $homeoPerPage + 1 }} to
                                        {{ min($currentHomeoPage * $homeoPerPage, $groupedHomeo->count()) }} of
                                        {{ $groupedHomeo->count() }} entries
                                    </div>
                                    <div class="pagination-buttons">
                                        @if($currentHomeoPage <= 1)
                                            <button class="pagination-btn" disabled>Previous</button>
                                        @else
                                            <a href="?homeo_page={{ $currentHomeoPage - 1 }}" class="pagination-btn">Previous</a>
                                        @endif
                                        @if($currentHomeoPage >= $totalHomeoPages)
                                            <button class="pagination-btn" disabled>Next</button>
                                        @else
                                            <a href="?homeo_page={{ $currentHomeoPage + 1 }}" class="pagination-btn">Next</a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="no-treatment">No homeopathic treatment prescribed</div>
                            @endif
                        </div>
                    </div>

                    <!-- Prescription Section -->
                    <div class="row pt-5">
                        <div class="col-lg-12 p-0">
                            <div class="card-header mb-2">
                                <div class="section-title">
                                    <h3 class="bold font-up fnf-title">Prescription</h3>
                                </div>
                            </div>
                            @php
                                $allPrescriptionTreatments = collect();
                                $initialPrescriptionTreatments = $treatments['prescription'] ?? [];
                                foreach ($initialPrescriptionTreatments as $treatment) {
                                    $allPrescriptionTreatments->push(array_merge($treatment, [
                                        'date' => $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : '',
                                        'sort_date' => $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->timestamp : 0,
                                        'type' => 'initial'
                                    ]));
                                }
                                foreach ($followUps as $followUp) {
                                    $followUpPrescriptionTreatments = \App\Models\PatientTreatment::where('followup_id', $followUp->id)->where('type', 'prescription')->get();
                                    foreach ($followUpPrescriptionTreatments as $treatment) {
                                        $allPrescriptionTreatments->push(array_merge($treatment->toArray(), [
                                            'date' => $followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->format('d/m/Y') : '',
                                            'sort_date' => $followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->timestamp : 0,
                                            'type' => 'followup'
                                        ]));
                                    }
                                }

                                $groupedPrescription = $allPrescriptionTreatments->groupBy('date');
                                $currentPrescriptionPage = request()->get('prescription_page', 1);
                                $prescriptionPerPage = 5;
                                $prescriptionChunks = $groupedPrescription->chunk($prescriptionPerPage);
                                $currentPrescriptionChunk = $prescriptionChunks[$currentPrescriptionPage - 1] ?? collect();
                                $totalPrescriptionPages = count($prescriptionChunks);
                            @endphp

                            @if($currentPrescriptionChunk->count() > 0)
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="15%">Date</th>
                                            <th width="35%">Medicine</th>
                                            <th width="15%">Days</th>
                                            <th width="15%">Dose</th>
                                            <th width="10%">When</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sn = ($currentPrescriptionPage - 1) * $prescriptionPerPage + 1; @endphp
                                        @foreach($currentPrescriptionChunk as $date => $medicines)
                                            @php
                                                // Build a JSON-safe array for this date's medicines
                                                $rxData = $medicines->map(fn($m) => [
                                                    'medicine' => $m['medicine'] ?? '',
                                                    'days' => $m['days'] ?? '',
                                                    'dose' => $m['dose'] ?? '',
                                                    'timing' => $m['timing'] ?? $m['timing_0'] ?? '',
                                                ])->values()->toArray();
                                            @endphp
                                            <tr style="background: #f8fafc; border-left: 4px solid #006637;">
                                                <td colspan="6" class="py-2 px-3">
                                                    <strong style="color: #006637;">
                                                        <i class="fas fa-calendar-day mr-2"></i> {{ $date }}
                                                    </strong>
                                                    <span class="badge badge-pill badge-primary ml-2">
                                                        {{ count($medicines) }} Medicine(s)
                                                    </span>
                                                </td>
                                                {{-- Per-date Print button --}}
                                                <td class="py-2 px-3 text-end">
                                                    <button type="button" onclick='printDatePrescription(
                                                                        "{{ addslashes($patient->patient_name) }}",
                                                                        "{{ addslashes($patient->age ?? '') }}",
                                                                        "{{ addslashes(ucfirst($patient->getMeta('gender') ?? '')) }}",
                                                                        "{{ $date }}",
                                                                        {{ json_encode($rxData) }}
                                                                    )'
                                                        style="background:transparent; border:1px solid #006637; color:#006637; border-radius:5px; padding:3px 10px; font-size:12px; font-weight:600; cursor:pointer; white-space:nowrap;">
                                                        <i class="bi bi-printer"></i> Print
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ $sn++ }}</td>
                                                <td class="text-muted small">--</td>
                                                <td colspan="5" style="padding: 0;">
                                                    <table style="width: 100%; margin: 0; border: none;">
                                                        @foreach($medicines as $m)
                                                            <tr style="border-bottom: 1px solid #eee;">
                                                                <td width="40%"
                                                                    style="border-right: 1px solid #eee; border-top: none; border-left: none;">
                                                                    {{ formatValue($m['medicine']) }}
                                                                </td>
                                                                <td width="15%" style="border-right: 1px solid #eee; border-top: none;">
                                                                    {{ formatValue($m['days'] ?? '') }}
                                                                </td>
                                                                <td width="20%" style="border-right: 1px solid #eee; border-top: none;">
                                                                    {{ formatValue($m['dose']) }}
                                                                </td>
                                                                <td width="25%" style="border-top: none; border-right: none;">
                                                                    {{ formatValue($m['timing'] ?? $m['timing_0'] ?? '') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    <div class="pagination-info">Showing
                                        {{ ($currentPrescriptionPage - 1) * $prescriptionPerPage + 1 }} to
                                        {{ min($currentPrescriptionPage * $prescriptionPerPage, $groupedPrescription->count()) }}
                                        of {{ $groupedPrescription->count() }} entries
                                    </div>
                                    <div class="pagination-buttons">
                                        @if($currentPrescriptionPage <= 1)
                                            <button class="pagination-btn" disabled>Previous</button>
                                        @else
                                            <a href="?prescription_page={{ $currentPrescriptionPage - 1 }}"
                                                class="pagination-btn">Previous</a>
                                        @endif
                                        @if($currentPrescriptionPage >= $totalPrescriptionPages)
                                            <button class="pagination-btn" disabled>Next</button>
                                        @else
                                            <a href="?prescription_page={{ $currentPrescriptionPage + 1 }}"
                                                class="pagination-btn">Next</a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="no-treatment">No prescription given</div>
                            @endif
                        </div>
                    </div>

                    <!-- Financial Summary & Quick Payment Section -->
                    <div class="row pt-5">
                        <div class="col-lg-12 p-0">
                            <div class="card-header mb-2">
                                <div class="section-title">
                                    <h3 class="bold font-up fnf-title">Financial Overview</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card mb-4" style="border-left: 5px solid #006637; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                                        <div class="card-body">
                                            <h6 class="text-muted small fw-bold text-uppercase mb-3">Billing Status</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Total Charges:</span>
                                                <span class="fw-bold text-dark">₹{{ $invoice ? number_format($invoice->total_payment, 2) : '0.00' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Total Paid:</span>
                                                <span class="fw-bold text-success">₹{{ $invoice ? number_format($invoice->given_payment, 2) : '0.00' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Total Discount:</span>
                                                <span class="fw-bold text-info">₹{{ $invoice ? number_format($invoice->discount, 2) : '0.00' }}</span>
                                            </div>
                                            <hr>
                                            @php
                                                $isFoc = !empty($meta['foc']) && strtolower($meta['foc']) === 'on';
                                                $isFocInquiry = !empty($meta['inquiry_foc']) && $meta['inquiry_foc'] == '1';
                                                $isFreeCharge = $isFoc || $isFocInquiry;
                                                $dueDisplay = $isFreeCharge ? 0 : ($invoice ? $invoice->due_payment : 0);
                                            @endphp
                                            @if($isFreeCharge)
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-success fw-bold">
                                                        <i class="fas fa-tag me-1"></i> FOC (Free of Charge)
                                                    </span>
                                                    <span class="badge bg-success">Free</span>
                                                </div>
                                            @endif
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold">Due Balance:</span>
                                                <span class="fw-bold {{ $dueDisplay > 0 ? 'text-danger' : 'text-success' }}">
                                                    ₹{{ number_format($dueDisplay, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card mb-4" style="border-left: 5px solid #28a745; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                                        <div class="card-body">
                                            <h6 class="text-muted small fw-bold text-uppercase mb-3">Quick Payment</h6>
                                            <form action="{{ route('svc.profile.add-payment', $patient->id) }}" method="POST">
                                                @csrf
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <input type="number" step="0.01" class="form-control form-control-sm" name="amount" placeholder="Amount" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" step="0.01" class="form-control form-control-sm" name="discount" placeholder="Discount">
                                                    </div>
                                                    <div class="col-12">
                                                        <select class="form-select form-select-sm" name="payment_method">
                                                            <option value="Cash">Cash</option>
                                                            <option value="Online">Online</option>
                                                            <option value="Cheque">Cheque</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-success btn-sm w-100 mt-1">
                                                            <i class="fas fa-plus-circle me-1"></i> Record Payment
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card mb-4" style="box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                                        <div class="card-body p-0" style="max-height: 185px; overflow-y: auto;">
                                            <table class="table table-sm table-hover mb-0" style="font-size: 0.75rem;">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th class="ps-2">Date</th>
                                                        <th>Amt</th>
                                                        <th class="pe-2 text-end">Method</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $recentPayments = \App\Models\PatientTransaction::where('patient_id', $patient->id)
                                                            ->whereIn('type', ['credit', 'discount'])
                                                            ->orderBy('created_at', 'desc')
                                                            ->take(5)
                                                            ->get();
                                                    @endphp
                                                    @forelse($recentPayments as $tx)
                                                        <tr>
                                                            <td class="ps-2">{{ $tx->created_at->format('d/m') }}</td>
                                                            <td class="fw-bold text-{{ $tx->type == 'credit' ? 'success' : 'info' }}">₹{{ number_format($tx->amount, 0) }}</td>
                                                            <td class="text-end pe-2 text-muted">
                                                                @if(str_contains($tx->description, 'Cash')) Cash
                                                                @elseif(str_contains($tx->description, 'Online')) Online
                                                                @else Pay
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="3" class="text-center py-3 text-muted">No payments</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($patient->getMeta('pt_status') === 'IPD')
                    <!-- Indoor Treatment Display Section -->
                    <div class="row pt-5">
                        <div class="col-lg-12 p-0">
                            <div class="card-header mb-2">
                                <div class="section-title">
                                    <h3 class="bold font-up fnf-title">Indoor Treatment</h3>
                                </div>
                            </div>
                            @php
                                $allIndoorTreatments = collect();
                                $initialIndoorTreatments = $treatments['indoor'] ?? [];
                                foreach ($initialIndoorTreatments as $treatment) {
                                    $allIndoorTreatments->push(array_merge($treatment, [
                                        'display_date' => !empty($treatment['date']) ? \Carbon\Carbon::parse($treatment['date'])->format('d/m/Y') : ($patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : ''),
                                        'display_time' => !empty($treatment['time']) ? \Carbon\Carbon::parse($treatment['time'])->format('h:i A') : '',
                                        'raw_time'     => !empty($treatment['time']) ? \Carbon\Carbon::parse($treatment['time'])->format('H:i') : '00:00',
                                        'sort_date'    => !empty($treatment['date']) ? \Carbon\Carbon::parse($treatment['date'])->timestamp : ($patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->timestamp : 0),
                                        'type' => 'initial'
                                    ]));
                                }
                                foreach ($followUps as $followUp) {
                                    $followUpIndoorTreatments = \App\Models\PatientTreatment::where('followup_id', $followUp->id)->where('type', 'indoor')->get();
                                    foreach ($followUpIndoorTreatments as $treatment) {
                                        $allIndoorTreatments->push(array_merge($treatment->toArray(), [
                                            'display_date' => !empty($treatment->date) ? \Carbon\Carbon::parse($treatment->date)->format('d/m/Y') : ($followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->format('d/m/Y') : ''),
                                            'display_time' => !empty($treatment->time) ? \Carbon\Carbon::parse($treatment->time)->format('h:i A') : '',
                                            'raw_time'     => !empty($treatment->time) ? \Carbon\Carbon::parse($treatment->time)->format('H:i') : '00:00',
                                            'sort_date'    => !empty($treatment->date) ? \Carbon\Carbon::parse($treatment->date)->timestamp : ($followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->timestamp : 0),
                                            'type' => 'followup'
                                        ]));
                                    }
                                }

                                // Group by date, then sub-group by time slot label
                                $groupedIndoor  = $allIndoorTreatments->groupBy('display_date');
                                $currentIndoorPage = request()->get('indoor_page', 1);
                                $indoorPerPage  = 5;
                                $indoorChunks   = $groupedIndoor->chunk($indoorPerPage);
                                $currentIndoorChunk = $indoorChunks[$currentIndoorPage - 1] ?? collect();
                                $totalIndoorPages   = count($indoorChunks);

                                // Helper: classify time into session
                                $getSession = function(string $raw): array {
                                    $h = (int) explode(':', $raw)[0];
                                    if ($h >= 5  && $h < 12) return ['label' => '🌅 Morning',   'color' => '#fff8e1', 'border' => '#f59e0b', 'text' => '#92400e', 'icon' => '🌅'];
                                    if ($h >= 12 && $h < 17) return ['label' => '☀️ Afternoon', 'color' => '#e8f5e9', 'border' => '#16a34a', 'text' => '#14532d', 'icon' => '☀️'];
                                    if ($h >= 17 && $h < 21) return ['label' => '🌆 Evening',   'color' => '#fce7f3', 'border' => '#db2777', 'text' => '#831843', 'icon' => '🌆'];
                                    return                           ['label' => '🌙 Night',     'color' => '#ede9fe', 'border' => '#7c3aed', 'text' => '#4c1d95', 'icon' => '🌙'];
                                };
                            @endphp

                            {{-- ─── Custom styles for this section ─── --}}
                            <style>
                                .indoor-date-card {
                                    border: 2px solid #006637;
                                    border-radius: 12px;
                                    margin-bottom: 20px;
                                    overflow: hidden;
                                    box-shadow: 0 3px 10px rgba(0,102,55,.1);
                                }
                                .indoor-date-header {
                                    background: #006637;
                                    color: #fff;
                                    padding: 10px 18px;
                                    display: flex;
                                    align-items: center;
                                    gap: 10px;
                                    font-weight: 700;
                                    font-size: 14px;
                                    letter-spacing: .3px;
                                }
                                .indoor-date-header .date-badge {
                                    background: rgba(255,255,255,.2);
                                    border-radius: 20px;
                                    padding: 2px 12px;
                                    font-size: 13px;
                                }
                                .indoor-date-header .count-pill {
                                    margin-left: auto;
                                    background: rgba(255,255,255,.15);
                                    border-radius: 20px;
                                    padding: 2px 10px;
                                    font-size: 12px;
                                }

                                /* Session band */
                                .session-band {
                                    border-left: 5px solid;
                                    margin: 0 14px 0 0;
                                }
                                .session-header {
                                    display: flex;
                                    align-items: center;
                                    gap: 8px;
                                    padding: 8px 14px;
                                    font-weight: 700;
                                    font-size: 13px;
                                    border-bottom: 1px solid rgba(0,0,0,.06);
                                }
                                .session-time-pill {
                                    margin-left: auto;
                                    font-size: 11px;
                                    font-weight: 600;
                                    padding: 2px 10px;
                                    border-radius: 20px;
                                    background: rgba(0,0,0,.06);
                                }

                                /* Medicine row inside session */
                                .indoor-med-row {
                                    display: grid;
                                    grid-template-columns: 2.5fr 1fr 1fr 2fr;
                                    gap: 0;
                                    border-bottom: 1px solid #f0f0f0;
                                    padding: 9px 14px;
                                    font-size: 13px;
                                    align-items: center;
                                    transition: background .15s;
                                }
                                .indoor-med-row:last-child { border-bottom: none; }
                                .indoor-med-row:hover { background: rgba(0,102,55,.03); }

                                .indoor-med-name { font-weight: 600; color: #1e293b; }
                                .indoor-med-dose { color: #475569; }
                                .indoor-med-days { color: #64748b; font-size: 12px; }
                                .indoor-med-note {
                                    color: #dc2626;
                                    font-size: 12px;
                                    background: rgba(220,38,38,.06);
                                    border-radius: 4px;
                                    padding: 2px 8px;
                                    border-left: 3px solid #dc2626;
                                }
                                .indoor-med-note:empty { display: none; }

                                /* Column header row */
                                .indoor-col-header {
                                    display: grid;
                                    grid-template-columns: 2.5fr 1fr 1fr 2fr;
                                    padding: 6px 14px;
                                    font-size: 11px;
                                    font-weight: 700;
                                    text-transform: uppercase;
                                    letter-spacing: .5px;
                                    color: #94a3b8;
                                    border-bottom: 1px solid #e2e8f0;
                                    background: #fafafa;
                                }

                                @media(max-width:600px){
                                    .indoor-med-row,
                                    .indoor-col-header { grid-template-columns: 1fr 1fr; }
                                    .indoor-med-days,
                                    .indoor-col-header span:nth-child(3){ display:none; }
                                }
                            </style>

                            @if($currentIndoorChunk->count() > 0)
                                @php $sn = ($currentIndoorPage - 1) * $indoorPerPage + 1; @endphp

                                @foreach($currentIndoorChunk as $date => $medicines)
                                    @php
                                        // Sub-group by session (Morning / Afternoon / Evening / Night)
                                        $sessions = [];
                                        foreach ($medicines as $m) {
                                            $sess = $getSession($m['raw_time'] ?? '00:00');
                                            $key  = $sess['label'];
                                            if (!isset($sessions[$key])) {
                                                $sessions[$key] = ['meta' => $sess, 'items' => []];
                                            }
                                            $sessions[$key]['items'][] = $m;
                                        }
                                        // Sort sessions chronologically
                                        $sessionOrder = ['🌅 Morning', '☀️ Afternoon', '🌆 Evening', '🌙 Night'];
                                        uksort($sessions, fn($a,$b) => array_search($a,$sessionOrder) - array_search($b,$sessionOrder));
                                        $totalMeds = count($medicines);
                                    @endphp

                                    <div class="indoor-date-card">
                                        {{-- Date Header --}}
                                        <div class="indoor-date-header">
                                            <i class="fas fa-calendar-day"></i>
                                            <span>{{ $sn++ }}.</span>
                                            <span class="date-badge">📅 {{ $date }}</span>
                                            <span class="count-pill">{{ $totalMeds }} medicine{{ $totalMeds !== 1 ? 's' : '' }}</span>
                                        </div>

                                        {{-- Sessions --}}
                                        @foreach($sessions as $sessLabel => $sessData)
                                            @php $s = $sessData['meta']; $items = $sessData['items']; @endphp
                                            <div class="session-band"
                                                 style="border-left-color:{{ $s['border'] }}; background:{{ $s['color'] }};">

                                                {{-- Session Header --}}
                                                <div class="session-header" style="color:{{ $s['text'] }};">
                                                    <span style="font-size:16px;">{{ $s['icon'] }}</span>
                                                    <span>{{ str_replace(['🌅 ','☀️ ','🌆 ','🌙 '], '', $sessLabel) }}</span>
                                                    @if(!empty($items[0]['display_time']))
                                                        <span class="session-time-pill"
                                                              style="color:{{ $s['text'] }};">{{ $items[0]['display_time'] }}</span>
                                                    @endif
                                                    <span style="margin-left:{{ empty($items[0]['display_time']) ? 'auto' : '6px' }};
                                                                 font-size:11px; opacity:.7;">
                                                        {{ count($items) }} item{{ count($items) !== 1 ? 's' : '' }}
                                                    </span>
                                                </div>

                                                {{-- Column labels --}}
                                                <div class="indoor-col-header">
                                                    <span>Medicine</span>
                                                    <span>Dose</span>
                                                    <span>Days</span>
                                                    <span>Note</span>
                                                </div>

                                                {{-- Medicine Rows --}}
                                                @foreach($items as $m)
                                                    <div class="indoor-med-row">
                                                        <span class="indoor-med-name">{{ formatValue($m['medicine']) }}</span>
                                                        <span class="indoor-med-dose">{{ formatValue($m['dose'] ?? '') ?: '—' }}</span>
                                                        <span class="indoor-med-days">{{ formatValue($m['days'] ?? '') ?: '—' }}</span>
                                                        <span class="indoor-med-note">{{ formatValue($m['note'] ?? '') }}</span>
                                                    </div>
                                                @endforeach

                                            </div>{{-- /session-band --}}

                                            {{-- Dark green divider between sessions --}}
                                            @if(!$loop->last)
                                                <div style="height:3px; background: linear-gradient(90deg,#006637 60%,transparent);
                                                            margin:0 0 0 0; opacity:.35;"></div>
                                            @endif
                                        @endforeach
                                    </div>{{-- /indoor-date-card --}}
                                @endforeach

                                <div class="pagination">
                                    <div class="pagination-info">Showing {{ ($currentIndoorPage - 1) * $indoorPerPage + 1 }} to
                                        {{ min($currentIndoorPage * $indoorPerPage, $groupedIndoor->count()) }} of
                                        {{ $groupedIndoor->count() }} entries
                                    </div>
                                    <div class="pagination-buttons">
                                        @if($currentIndoorPage <= 1)
                                            <button class="pagination-btn" disabled>Previous</button>
                                        @else
                                            <a href="?indoor_page={{ $currentIndoorPage - 1 }}" class="pagination-btn">Previous</a>
                                        @endif
                                        @if($currentIndoorPage >= $totalIndoorPages)
                                            <button class="pagination-btn" disabled>Next</button>
                                        @else
                                            <a href="?indoor_page={{ $currentIndoorPage + 1 }}" class="pagination-btn">Next</a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="no-treatment">No indoor treatment prescribed</div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Other Treatment Section -->
                    <div class="row pt-5">
                        <div class="col-lg-12 p-0">
                            <div class="card-header mb-2">
                                <div class="section-title">
                                    <h3 class="bold font-up fnf-title">Other Treatment</h3>
                                </div>
                            </div>
                            @php
                                $allOtherTreatments = collect();
                                $initialOtherTreatments = $treatments['other'] ?? [];
                                foreach ($initialOtherTreatments as $treatment) {
                                    $allOtherTreatments->push(array_merge($treatment, [
                                        'date' => $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->format('d/m/Y') : '',
                                        'sort_date' => $patient->inquiry_date ? \Carbon\Carbon::parse($patient->inquiry_date)->timestamp : 0,
                                        'type' => 'initial'
                                    ]));
                                }
                                foreach ($followUps as $followUp) {
                                    $followUpOtherTreatments = \App\Models\PatientTreatment::where('followup_id', $followUp->id)->where('type', 'other')->get();
                                    foreach ($followUpOtherTreatments as $treatment) {
                                        $allOtherTreatments->push(array_merge($treatment->toArray(), [
                                            'date' => $followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->format('d/m/Y') : '',
                                            'sort_date' => $followUp->followup_date ? \Carbon\Carbon::parse($followUp->followup_date)->timestamp : 0,
                                            'type' => 'followup'
                                        ]));
                                    }
                                }

                                $groupedOther = $allOtherTreatments->groupBy('date');
                                $currentOtherPage = request()->get('other_page', 1);
                                $otherPerPage = 5;
                                $otherChunks = $groupedOther->chunk($otherPerPage);
                                $currentOtherChunk = $otherChunks[$currentOtherPage - 1] ?? collect();
                                $totalOtherPages = count($otherChunks);
                            @endphp

                            @if($currentOtherChunk->count() > 0)
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="20%">Date</th>
                                            <th width="75%">Medicine & Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sn = ($currentOtherPage - 1) * $otherPerPage + 1; @endphp
                                        @foreach($currentOtherChunk as $date => $medicines)
                                            <tr>
                                                <td>{{ $sn++ }}</td>
                                                <td>{{ formatValue($date) }}</td>
                                                <td style="padding: 0;">
                                                    <table style="width: 100%; margin: 0; border: none;">
                                                        @foreach($medicines as $m)
                                                            <tr style="border-bottom: 1px solid #eee;">
                                                                <td width="65%"
                                                                    style="border-right: 1px solid #eee; border-top: none; border-left: none;">
                                                                    {{ formatValue($m['medicine']) }}
                                                                </td>
                                                                <td width="35%" style="border-top: none; border-right: none;">
                                                                    {{ formatValue($m['note']) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    <div class="pagination-info">Showing {{ ($currentOtherPage - 1) * $otherPerPage + 1 }} to
                                        {{ min($currentOtherPage * $otherPerPage, $groupedOther->count()) }} of
                                        {{ $groupedOther->count() }} entries
                                    </div>
                                    <div class="pagination-buttons">
                                        @if($currentOtherPage <= 1)
                                            <button class="pagination-btn" disabled>Previous</button>
                                        @else
                                            <a href="?other_page={{ $currentOtherPage - 1 }}" class="pagination-btn">Previous</a>
                                        @endif
                                        @if($currentOtherPage >= $totalOtherPages)
                                            <button class="pagination-btn" disabled>Next</button>
                                        @else
                                            <a href="?other_page={{ $currentOtherPage + 1 }}" class="pagination-btn">Next</a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="no-treatment">No other treatment prescribed</div>
                            @endif
                        </div>
                    </div>

                    <!-- Reference and Notes Section -->
                    <div class="row pt-5">
                        <div class="col-lg-12 p-0">
                            <div class="card-header mb-2">
                                <div class="section-title">
                                    <h3 class="bold font-up fnf-title">Reference & Notes</h3>
                                </div>
                            </div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Reference By</th>
                                        <th>Refer To</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ formatValue($patient->getMeta('reference_by')) }}</td>
                                        <td>{{ formatValue($patient->getMeta('referto')) }}</td>
                                        <td>{{ formatValue($patient->getMeta('notes')) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="image-preview-container">
                        <div class="default-image-preview">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="150" height="150">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"
                                    fill="currentColor" />
                            </svg>
                            <p class="text-muted mt-3">Profile Image</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
    INDOOR TREATMENT MODAL — NEW REDESIGNED VERSION
    ============================================================ --}}
    <div class="modal fade" id="indoorTreatmentModal" tabindex="-1" aria-labelledby="indoorTreatmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('svc.profile.indoor-treatment', $patient->id) }}" method="POST"
                    id="indoorTreatmentForm">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="modal-header">
                        <h5 class="modal-title" id="indoorTreatmentModalLabel">
                            <i class="bi bi-hospital-fill"></i> Add Indoor Treatment Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">

                        {{-- Patient Info --}}
                        <div class="indoor-patient-info">
                            <div class="info-item"><strong>Name:</strong> {{ $patient->patient_name }}</div>
                            <div class="info-item"><strong>Age:</strong> {{ $patient->age }}</div>
                            <div class="info-item"><strong>Diagnosis:</strong> {{ $patient->diagnosis ?? 'N/A' }}</div>
                            <div class="info-item"><strong>Complaints:</strong> {{ $patient->getMeta('complain') ?? 'N/A' }}
                            </div>
                        </div>

                        {{-- Add New Date Slot Button --}}
                        <button type="button" class="add-slot-btn" onclick="addIndoorDateSlot()">
                            <i class="bi bi-plus-lg"></i> Add New Date Slot
                        </button>

                        {{-- Slots Container --}}
                        <div id="indoorSlotsContainer">
                            @php
                                // Group existing indoor treatments by date+time
                                $indoorGroups = [];
                                if (isset($treatments['indoor']) && count($treatments['indoor']) > 0) {
                                    foreach ($treatments['indoor'] as $t) {
                                        $key = ($t['date'] ?? '') . '||' . ($t['time'] ?? '');
                                        $indoorGroups[$key][] = $t;
                                    }
                                }
                            @endphp

                            @if(!empty($indoorGroups))
                                @foreach($indoorGroups as $groupKey => $medicines)
                                    @php
                                        [$gDate, $gTime] = explode('||', $groupKey);
                                        $slotIndex = $loop->index;
                                    @endphp
                                    <div class="date-slot-card" data-slot="{{ $slotIndex }}">
                                        <div class="date-slot-header">
                                            <label>Date &amp; Time</label>
                                            <input type="date" name="slot_date[{{ $slotIndex }}]" value="{{ $gDate }}" required>
                                            <span class="slot-at-separator">@</span>
                                            <input type="time" name="slot_time[{{ $slotIndex }}]" value="{{ $gTime }}">
                                            <span class="medicine-count-badge">
                                                {{ count($medicines) }} {{ count($medicines) === 1 ? 'medicine' : 'medicines' }}
                                            </span>
                                            <button type="button" class="remove-slot-btn" onclick="removeIndoorSlot(this)">
                                                <i class="bi bi-x-lg"></i> Remove Slot
                                            </button>
                                        </div>
                                        <div class="date-slot-body">
                                            <div class="medicines-header">
                                                <span>Medicine</span>
                                                <span>Note</span>
                                            </div>
                                            <div class="medicine-rows-container">
                                                @foreach($medicines as $med)
                                                    <div class="medicine-row">
                                                        <input type="text" name="slot_medicine[{{ $slotIndex }}][]"
                                                            value="{{ $med['medicine'] ?? '' }}" placeholder="Medicine name"
                                                            autocomplete="off">
                                                        <input type="text" name="slot_note[{{ $slotIndex }}][]"
                                                            value="{{ $med['note'] ?? '' }}" placeholder="Note">
                                                        <button type="button" class="delete-medicine-btn"
                                                            onclick="deleteMedicineRow(this)" title="Remove">
                                                            <i class="bi bi-trash3-fill" style="font-size:12px;"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="add-medicine-btn"
                                                onclick="addMedicineRow(this, {{ $slotIndex }})">
                                                <i class="bi bi-plus-lg"></i> Add Medicine
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer justify-content-end gap-2">
                        <button type="button" class="btn-cancel-indoor" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save-indoor">
                            <i class="bi bi-check-lg me-1"></i> Save Treatment
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="zoomActionsModal" tabindex="-1" aria-labelledby="zoomActionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden"
                style="max-width: 500px !important; border-radius: 20px; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" id="zoomActionsModalLabel">Consultation Link Management</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    {{-- Doctor Section - Internal Use Only --}}
                    <div class="p-4 mx-3 mt-3 mb-3 border-0 rounded-4"
                        style="background: rgba(37, 99, 235, 0.04); border-radius: 16px;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="fas fa-user-md text-primary h5 mb-0"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-primary">Internal Access</h6>
                                <small class="text-muted">Join the meeting as host</small>
                            </div>
                        </div>
                        <a href="" id="modalZoomJoinBtn" target="_blank"
                            class="btn btn-primary w-100 d-flex align-items-center justify-content-center py-3 fw-bold"
                            style="background: var(--color-primary); border: none; border-radius: 12px; transition: transform 0.2s;">
                            <i class="fas fa-video me-2"></i> Join Meeting Now
                        </a>
                    </div>

                    {{-- Patient Section - Shareable --}}
                    <div class="p-4 mx-3 mb-4 rounded-4" style="background: rgba(16, 185, 129, 0.04); border-radius: 16px;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-success bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="fas fa-hospital-user text-success h5 mb-0"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-success">Share With Patient</h6>
                                <small class="text-muted">Standard participant access</small>
                            </div>
                        </div>
                        <div class="input-group mb-3"
                            style="background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; padding: 4px;">
                            <input type="text" id="modalZoomLinkInput" class="form-control border-0" readonly
                                style="font-size: 13px; background: transparent; color: var(--text-primary); box-shadow: none;">
                            <button class="btn btn-light border-0 px-3" type="button" onclick="copyModalZoomLink()"
                                title="Copy Link" style="border-radius: 10px;">
                                <i class="fas fa-copy text-muted"></i>
                            </button>
                        </div>
                        <a href="" id="modalZoomWaBtn" target="_blank"
                            class="btn btn-success w-100 d-flex align-items-center justify-content-center py-3 fw-bold shadow-sm"
                            style="background: #25D366; border: none; color: white; border-radius: 12px; transition: transform 0.2s;">
                            <i class="fab fa-whatsapp me-2"></i> Share via WhatsApp
                        </a>
                        <div class="mt-3 p-2 text-center rounded-3" style="background: rgba(16, 185, 129, 0.06);">
                            <p class="text-success small mb-0 fw-medium">
                                <i class="fas fa-check-circle me-1"></i> Guest link prepared for patient.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-link text-decoration-none text-muted fw-bold w-100"
                        data-bs-dismiss="modal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ===== INDOOR TREATMENT MODAL — SLOT-BASED LOGIC =====
        let indoorSlotCounter = {{ isset($indoorGroups) && !empty($indoorGroups) ? count($indoorGroups) : 0 }};

        /**
         * Add a new date slot card
         */
        function addIndoorDateSlot() {
            const container = document.getElementById('indoorSlotsContainer');
            const slotIndex = indoorSlotCounter++;

            const card = document.createElement('div');
            card.className = 'date-slot-card';
            card.dataset.slot = slotIndex;

            card.innerHTML = `
                                                                    <div class="date-slot-header">
                                                                        <label>Date &amp; Time</label>
                                                                        <input type="date" name="slot_date[${slotIndex}]" required>
                                                                        <span class="slot-at-separator">@</span>
                                                                        <input type="time" name="slot_time[${slotIndex}]">
                                                                        <span class="medicine-count-badge">1 medicine</span>
                                                                        <button type="button" class="remove-slot-btn" onclick="removeIndoorSlot(this)">
                                                                            <i class="bi bi-x-lg"></i> Remove Slot
                                                                        </button>
                                                                    </div>
                                                                    <div class="date-slot-body">
                                                                        <div class="medicines-header">
                                                                            <span>Medicine</span>
                                                                            <span>Note</span>
                                                                        </div>
                                                                        <div class="medicine-rows-container">
                                                                            <div class="medicine-row">
                                                                                <input type="text" name="slot_medicine[${slotIndex}][]" placeholder="Medicine name" autocomplete="off">
                                                                                <input type="text" name="slot_note[${slotIndex}][]" placeholder="Note">
                                                                                <button type="button" class="delete-medicine-btn" onclick="deleteMedicineRow(this)" title="Remove">
                                                                                    <i class="bi bi-trash3-fill" style="font-size:12px;"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <button type="button" class="add-medicine-btn" onclick="addMedicineRow(this, ${slotIndex})">
                                                                            <i class="bi bi-plus-lg"></i> Add Medicine
                                                                        </button>
                                                                    </div>
                                                                `;

            container.appendChild(card);
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        /**
         * Add a medicine row inside a slot
         */
        function addMedicineRow(btn, slotIndex) {
            const card = btn.closest('.date-slot-card');
            const rowsContainer = card.querySelector('.medicine-rows-container');

            const row = document.createElement('div');
            row.className = 'medicine-row';
            row.innerHTML = `
                                                                    <input type="text" name="slot_medicine[${slotIndex}][]" placeholder="Medicine name" autocomplete="off">
                                                                    <input type="text" name="slot_note[${slotIndex}][]" placeholder="Note">
                                                                    <button type="button" class="delete-medicine-btn" onclick="deleteMedicineRow(this)" title="Remove">
                                                                        <i class="bi bi-trash3-fill" style="font-size:12px;"></i>
                                                                    </button>
                                                                `;
            rowsContainer.appendChild(row);
            updateBadge(card);
            row.querySelector('input').focus();
        }

        /**
         * Delete a single medicine row
         */
        function deleteMedicineRow(btn) {
            const card = btn.closest('.date-slot-card');
            const rowsContainer = card.querySelector('.medicine-rows-container');
            const rows = rowsContainer.querySelectorAll('.medicine-row');

            if (rows.length > 1) {
                btn.closest('.medicine-row').remove();
                updateBadge(card);
            } else {
                // Last row: clear inputs instead of removing
                btn.closest('.medicine-row').querySelectorAll('input').forEach(i => i.value = '');
            }
        }

        /**
         * Remove an entire date slot card
         */
        function removeIndoorSlot(btn) {
            const container = document.getElementById('indoorSlotsContainer');
            const card = btn.closest('.date-slot-card');
            const slots = container.querySelectorAll('.date-slot-card');

            if (slots.length > 1) {
                Swal.fire({
                    ...getSwalConfig('question'),
                    title: 'Remove Slot?',
                    text: 'Are you sure you want to remove this date slot and all its medicines?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        card.remove();
                        Swal.fire({
                            ...getSwalConfig('success'),
                            title: 'Removed!',
                            text: 'The date slot has been removed.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            } else {
                // Last slot: clear all inputs
                card.querySelectorAll('input').forEach(i => i.value = '');
                updateBadge(card);
            }
        }

        /**
         * Update medicine count badge on a slot card
         */
        function updateBadge(card) {
            const rows = card.querySelectorAll('.medicine-rows-container .medicine-row');
            const badge = card.querySelector('.medicine-count-badge');
            if (!badge) return;
            const count = rows.length;
            badge.textContent = count + (count === 1 ? ' medicine' : ' medicines');
        }

        // ===== OTHER FUNCTIONS =====

        function openZoomModal(startUrl, joinUrl, waUrl) {
            const joinBtn = document.getElementById('modalZoomJoinBtn');
            const waBtn = document.getElementById('modalZoomWaBtn');
            const linkInput = document.getElementById('modalZoomLinkInput');
            if (joinBtn) joinBtn.href = startUrl;
            if (waBtn) waBtn.href = waUrl;
            if (linkInput) linkInput.value = joinUrl;
            const modalEl = document.getElementById('zoomActionsModal');
            if (modalEl) { const modal = new bootstrap.Modal(modalEl); modal.show(); }
        }

        function copyModalZoomLink() {
            const linkInput = document.getElementById('modalZoomLinkInput');
            if (!linkInput) return;
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            document.execCommand('copy');
        }

        function previewPatientImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var previewContainer = document.getElementById('profileImagePreview');
                    previewContainer.innerHTML = '<img src="' + e.target.result + '" alt="Profile Image">';
                    document.getElementById('saveImageBtn').style.display = 'inline-block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const editProfileBtn = document.querySelector('.edit-profile-btn');
            if (editProfileBtn) {
                editProfileBtn.addEventListener('click', function () {
                    window.location.href = "{{ route('edit.svc.inquiry', $patient->id) }}";
                });
            }
        });

        /**
         * Print prescription for a specific date only.
         * Opens a new window with a clean A4 prescription layout.
         */
        function printDatePrescription(patientName, age, gender, date, medicines) {
            const doctorName = "{{ $doctor ? addslashes($doctor->name) : 'Dr. Manish Akbari (BHMS)' }}";
            const patientId = "{{ $patient->patient_id ?? '' }}";
            const diagnosis = "{{ addslashes(is_array($patient->diagnosis ?? null) ? implode(', ', $patient->diagnosis) : ($patient->diagnosis ?? '—')) }}";
            const complaints = "{{ addslashes(is_array($patient->getMeta('complain') ?? null) ? implode(', ', $patient->getMeta('complain')) : ($patient->getMeta('complain') ?? '—')) }}";

            // Build medicine rows HTML
            let rows = '';
            medicines.forEach((m, i) => {
                rows += `
                        <tr>
                            <td style="padding:10px 12px; border-bottom:1px solid #e2e8f0; font-size:13px;">${i + 1}</td>
                            <td style="padding:10px 12px; border-bottom:1px solid #e2e8f0; font-size:13px; font-weight:600;">${m.medicine || '—'}</td>
                            <td style="padding:10px 12px; border-bottom:1px solid #e2e8f0; font-size:13px;">${m.days || '—'}</td>
                            <td style="padding:10px 12px; border-bottom:1px solid #e2e8f0; font-size:13px;">${m.dose || '—'}</td>
                            <td style="padding:10px 12px; border-bottom:1px solid #e2e8f0; font-size:13px;">${m.timing || '—'}</td>
                        </tr>`;
            });

            const html = `<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Prescription – ${patientName} – ${date}</title>
        <style>
            @page { margin: 1cm; size: A4; }
            * { box-sizing: border-box; margin: 0; padding: 0; }
            body { font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; background: white; }

            /* ── Header ── */
            .rx-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                border-bottom: 3px solid #006637;
                padding-bottom: 14px;
                margin-bottom: 20px;
            }
            .clinic-name { font-size: 22px; font-weight: 800; color: #006637; margin-bottom: 4px; }
            .clinic-sub  { font-size: 12px; color: #475569; line-height: 1.6; }
            .doctor-box  { text-align: right; }
            .doctor-name { font-size: 15px; font-weight: 700; color: #006637; }
            .doctor-sub  { font-size: 11px; color: #64748b; margin-top: 2px; }

            /* ── Patient card ── */
            .patient-card {
                background: #f0f7f2;
                border: 1px solid #c8e6d4;
                border-radius: 8px;
                padding: 14px 18px;
                margin-bottom: 20px;
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 8px 20px;
            }
            .patient-card .field { font-size: 13px; }
            .patient-card .field strong { color: #006637; font-weight: 600; margin-right: 4px; }

            /* ── Date badge ── */
            .rx-date-badge {
                display: inline-block;
                background: #006637;
                color: white;
                font-size: 13px;
                font-weight: 700;
                padding: 5px 16px;
                border-radius: 20px;
                margin-bottom: 14px;
            }

            /* ── Section title ── */
            .rx-section-title {
                font-size: 14px;
                font-weight: 800;
                color: #006637;
                border-left: 4px solid #006637;
                padding-left: 10px;
                margin-bottom: 12px;
                text-transform: uppercase;
                letter-spacing: 0.4px;
            }

            /* ── Table ── */
            .rx-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
            .rx-table thead tr { background: #006637; }
            .rx-table thead th {
                color: white;
                font-size: 12px;
                font-weight: 700;
                text-align: left;
                padding: 10px 12px;
                text-transform: uppercase;
                letter-spacing: 0.4px;
            }
            .rx-table tbody tr:nth-child(even) { background: #f8fafc; }

            /* ── Footer ── */
            .rx-footer {
                margin-top: 50px;
                display: flex;
                justify-content: space-between;
                border-top: 1px solid #e2e8f0;
                padding-top: 16px;
            }
            .sig-box { text-align: center; min-width: 180px; }
            .sig-line {
                margin-top: 40px;
                border-top: 1.5px solid #94a3b8;
                padding-top: 5px;
                font-size: 11px;
                color: #64748b;
            }
            .rx-disclaimer {
                margin-top: 30px;
                font-size: 10px;
                color: #94a3b8;
                text-align: center;
                border-top: 1px dashed #e2e8f0;
                padding-top: 10px;
            }
        \x3c/style\x3e
    \x3c/head\x3e
    <body>

        <!-- Header -->
        <div class="rx-header">
            <div>
                <div class="clinic-name">Shree Vallabh Clinic</div>
                <div class="clinic-sub">
                    Priyanka Intercity, Puna Kumbhariya Road, Magob, Surat<br>
                    📞 8758875020
                </div>
            </div>
            <div class="doctor-box">
                <div class="doctor-name">${doctorName}</div>
                <div class="doctor-sub">Registered Homoeopathic Practitioner<br>Reg. No: G-9088</div>
            </div>
        </div>

        <!-- Patient Info -->
        <div class="patient-card">
            <div class="field"><strong>Patient:</strong> ${patientName}</div>
            <div class="field"><strong>ID:</strong> ${patientId}</div>
            <div class="field"><strong>Age / Gender:</strong> ${age} / ${gender}</div>
            <div class="field"><strong>Diagnosis:</strong> ${diagnosis}</div>
            <div class="field"><strong>Complaints:</strong> ${complaints}</div>
            <div class="field"><strong>Print Date:</strong> ${new Date().toLocaleDateString('en-GB')}</div>
        </div>

        <!-- Date badge -->
        <div class="rx-date-badge">📅 Prescription Date: ${date}</div>

        <!-- Prescription table -->
        <div class="rx-section-title">Rx — Medicines Prescribed</div>
        <table class="rx-table">
            <thead>
                <tr>
                    <th style="width:5%">#</th>
                    <th style="width:35%">Medicine</th>
                    <th style="width:12%">Days</th>
                    <th style="width:22%">Dose</th>
                    <th style="width:26%">Instructions</th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>
        </table>

        <!-- Signatures -->
        <div class="rx-footer">
            <div class="sig-box">
                <div class="sig-line">Patient / Relative Signature</div>
            </div>
            <div class="sig-box">
                <div class="sig-line">Doctor Signature &amp; Stamp</div>
            </div>
        </div>

        <div class="rx-disclaimer">
            This is a computer-generated prescription. Not valid for medico-legal or insurance purposes.
        </div>

    \x3c/body\x3e
    \x3c/html\x3e`;

            const win = window.open('', '_blank', 'width=800,height=900');
            win.document.write(html);
            win.document.close();
            win.focus();
            // Small delay so styles render before print dialog opens
            setTimeout(() => { win.print(); }, 400);
        }
    </script>
    {{-- ── CLINICAL PRINT VIEW (Hidden on screen) ── --}}
    @php
        $meta = $patient->getAllMeta();
        $doctorId = $meta['doctor_id'] ?? null;
        $doctor = null;
        if ($doctorId) {
            $doctor = \App\Models\User::find($doctorId);
        }
    @endphp
    <div class="print-view">
        {{-- HEADER --}}
        <div class="print-header">
            <div class="clinic-info">
                <h2>Shree Vallabh Clinic</h2>
                <p>Priyanka Intercity, Puna Kumbhariya Road, Magob, Surat</p>
                <p>📞 8758875020 | 🌐 shreevallabhclinic.com</p>
            </div>
            <div class="doctor-info" style="text-align: right;">
                <h4 style="margin:0; color:#006637;">{{ $doctor ? $doctor->name : 'Dr. Manish Akbari (BHMS)' }}</h4>
                <p style="font-size:12px; margin:0; color:#64748b;">Registered Homoeopathic Practitioner</p>
                <p style="font-size:11px; margin:0; color:#64748b;">Reg. No: G-9088</p>
            </div>
        </div>

        {{-- PATIENT CARD --}}
        <div class="print-section-title">Patient Clinical Summary</div>
        <div class="patient-print-card" style="display: block; padding: 20px;">
            <div
                style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
                <div style="flex: 1;"><strong>Name:</strong> {{ $patient->patient_name }}</div>
                <div style="flex: 1;"><strong>Age:</strong> {{ $patient->age }}</div>
                <div style="flex: 1;"><strong>Gender:</strong> {{ ucfirst($patient->getMeta('gender') ?? '—') }}</div>
            </div>

            <div
                style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
                <div style="flex: 1;"><strong>Phone:</strong> {{ $patient->getMeta('phone') ?? $patient->phone_no ?? '—' }}
                </div>
                <div style="flex: 1;"><strong>Weight:</strong> {{ $patient->getMeta('weight') ?? '—' }} kg</div>
                <div style="flex: 1;"><strong>Height:</strong> {{ $patient->getMeta('height') ?? '—' }} cm</div>
            </div>

            <div
                style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
                <div style="flex: 1;"><strong>BMI:</strong> {{ $patient->getMeta('bmi') ?? '—' }}</div>
                <div style="flex: 1;"><strong>PT.Status:</strong> {{ $patient->getMeta('pt_status') ?? '—' }}</div>
                <div style="flex: 1;"><strong>Report Date:</strong> {{ now()->format('d/m/Y') }}</div>
            </div>

            <div
                style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
                <div style="flex: 1;"><strong>Temp:</strong> {{ $patient->getMeta('temperature') ?? '—' }} °C</div>
                <div style="flex: 1;"><strong>Pulse:</strong> {{ $patient->getMeta('pulse') ?? '—' }}</div>
                <div style="flex: 1;"><strong>BP:</strong> {{ $patient->getMeta('blood_pressure') ?? '—' }}</div>
                <div style="flex: 1;"><strong>SpO2:</strong> {{ $patient->getMeta('spo2') ?? '—' }}%</div>
            </div>

            <div
                style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
                <div style="flex: 1;"><strong>RBS:</strong> {{ $patient->getMeta('rbs') ?? '—' }}</div>
                <div style="flex: 2;"><strong>Diagnosis:</strong>
                    @php
                        $diagnosis = $patient->diagnosis ?? $patient->getMeta('diagnosis');
                        echo is_array($diagnosis) ? implode(', ', $diagnosis) : ($diagnosis ?? '—');
                    @endphp
                </div>
            </div>

            <div style="margin-bottom: 12px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px;">
                <strong>Complaints:</strong>
                @php
                    $complaints = $patient->getMeta('complain');
                    echo is_array($complaints) ? implode(', ', $complaints) : ($complaints ?? '—');
                @endphp
            </div>

            <div>
                <strong>Investigation:</strong> {{ $patient->getMeta('investigation') ?? '—' }}
            </div>
        </div>


        {{-- PRESCRIPTION SECTION --}}
        <div class="print-section-title">Prescription History</div>
        @if($allPrescriptionTreatments->count() > 0)
            <table class="prescription-table">
                <thead>
                    <tr>
                        <th style="width: 15%">Date</th>
                        <th style="width: 35%">Medicine Name</th>
                        <th style="width: 10%">Days</th>
                        <th style="width: 20%">Dosage</th>
                        <th style="width: 20%">Instructions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allPrescriptionTreatments->sortByDesc('sort_date') as $item)
                        <tr>
                            <td>{{ $item['date'] }}</td>
                            <td style="font-weight: 600;">{{ formatValue($item['medicine']) }}</td>
                            <td>{{ formatValue($item['days'] ?? '') }}</td>
                            <td>{{ formatValue($item['dose']) }}</td>
                            <td>{{ formatValue($item['timing'] ?? $item['timing_0'] ?? '') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div
                style="padding: 20px; text-align: center; color: #94a3b8; font-style: italic; border: 1px dashed #e2e8f0; border-radius: 8px;">
                No prescription records found for this patient.
            </div>
        @endif



        {{-- FOOTER --}}
        <div class="print-footer">
            <div class="sig-box">
                <div class="sig-line">Patient / Relative Signature</div>
            </div>
            <div class="sig-box">
                <div class="sig-line">Medical Officer Signature & Stamp</div>
            </div>
        </div>

        <div style="margin-top: 40px; font-size: 10px; color: #94a3b8; text-align: center;">
            This is a computer-generated medical record. Not valid for medico-legal purposes.
        </div>
    </div>

    {{-- ===== Add Indoor Patient Modal ===== --}}
    <div class="modal fade" id="profileIndoorModal" tabindex="-1" aria-labelledby="profileIndoorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('svc.profile.indoor-treatment', $patient->id) }}" method="POST" id="profileIndoorTreatmentForm">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="modal-header" style="background-color: #006637; color: white;">
                        <h5 class="modal-title" id="profileIndoorModalLabel" style="color: white;">
                            <i class="bi bi-hospital-fill"></i> Manage Indoor Treatment Logs
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">

                        {{-- Patient Info --}}
                        <div class="indoor-patient-info mb-4" style="background: #f8f9fa; border-left: 4px solid #006637; padding: 15px; border-radius: 6px;">
                            <div class="row g-3">
                                <div class="col-md-3"><strong>Name:</strong> {{ $patient->patient_name }}</div>
                                <div class="col-md-2"><strong>Age:</strong> {{ $patient->age }}</div>
                                <div class="col-md-3"><strong>Diagnosis:</strong> {{ $patient->diagnosis ?? 'N/A' }}</div>
                                <div class="col-md-4"><strong>Complaints:</strong> {{ $patient->getMeta('complain') ?? 'N/A' }}</div>
                            </div>
                        </div>

                        {{-- Section: Treatment History --}}
                        <div class="mb-4">
                            <h6 class="d-flex align-items-center gap-2 mb-3" style="color: #006637; font-weight: 700; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">
                                <i class="fas fa-history"></i> Past Treatment History
                            </h6>
                            <div id="profileIndoorHistoryContainer">
                                @php
                                    $indoorTreatments = \App\Models\PatientTreatment::where('patient_id', $patient->patient_id)
                                        ->where('type', 'indoor')
                                        ->orderBy('date', 'desc')
                                        ->orderBy('time', 'desc')
                                        ->get();
                                    $grouped = $indoorTreatments->groupBy(function($t) {
                                        return ($t->date ?? 'No Date') . '||' . ($t->time ?? 'No Time');
                                    });
                                @endphp

                                @forelse($grouped as $key => $items)
                                    @php
                                        [$gDate, $gTime] = explode('||', $key);
                                        $displayDate = $gDate !== 'No Date' ? \Carbon\Carbon::parse($gDate)->format('d/m/Y') : 'No Date';
                                        $displayTime = $gTime !== 'No Time' ? \Carbon\Carbon::createFromFormat('H:i:s', $gTime)->format('h:i A') : 'No Time';
                                    @endphp
                                    <div class="card mb-2 border-0 shadow-sm" style="border-left: 3px solid #17a2b8 !important;">
                                        <div class="card-header py-1 px-3 d-flex justify-content-between align-items-center" style="background: #f8f9fa; border-bottom: none;">
                                            <span style="font-size: 12px; font-weight: 600; color: #006637;">
                                                <i class="bi bi-calendar-event me-1"></i> {{ $displayDate }} &nbsp;|&nbsp; <i class="bi bi-clock me-1"></i> {{ $displayTime }}
                                            </span>
                                            <span class="badge bg-light text-dark border">{{ $items->count() }} {{ $items->count() === 1 ? 'item' : 'items' }}</span>
                                        </div>
                                        <div class="card-body py-2 px-3">
                                            @foreach($items as $t)
                                                <div class="d-flex justify-content-between align-items-center py-1" style="font-size: 13px; border-bottom: 1px solid #f0f0f0;">
                                                    <span style="font-weight: 500; color: #333;"><i class="bi bi-capsule me-2" style="color: #006637;"></i>{{ $t->medicine ?? '-' }}</span>
                                                    <span class="text-muted" style="font-size: 12px; font-style: italic;">{{ $t->note ?? '' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-muted text-center py-2" style="font-size: 13px;">No past treatment logs recorded yet.</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Section: Add New Treatment Entry --}}
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3" style="border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">
                                <h6 class="m-0" style="color: #006637; font-weight: 700;">
                                    <i class="bi bi-plus-circle-fill"></i> Add New Treatment Entry
                                </h6>
                                <button type="button" class="add-slot-btn btn btn-sm" onclick="addProfileIndoorSlot()" style="background-color: #006637; color: white;">
                                    <i class="bi bi-plus-lg"></i> Add Another Slot
                                </button>
                            </div>

                            {{-- Slots Container --}}
                            <div id="profileIndoorSlotsContainer">
                                {{-- Slots added dynamically --}}
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer justify-content-end gap-2" style="background-color: #f8f9fa;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #006637; border-color: #006637;">
                            <i class="bi bi-check-lg me-1"></i> Submit New Logs
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        let profileIndoorSlotCounter = 0;

        function openProfileIndoorModal() {
            const container = document.getElementById('profileIndoorSlotsContainer');
            container.innerHTML = '';
            profileIndoorSlotCounter = 0;

            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const hh = String(now.getHours()).padStart(2, '0');
            const min = String(now.getMinutes()).padStart(2, '0');

            createProfileIndoorSlot(`${yyyy}-${mm}-${dd}`, `${hh}:${min}`, profileIndoorSlotCounter++);

            const modal = new bootstrap.Modal(document.getElementById('profileIndoorModal'));
            modal.show();
        }

        function createProfileIndoorSlot(date, time, slotIndex) {
            const container = document.getElementById('profileIndoorSlotsContainer');

            const slot = document.createElement('div');
            slot.className = 'date-slot-card mb-3 p-3';
            slot.style.border = '1px solid #ced4da';
            slot.style.borderRadius = '8px';
            slot.style.background = '#fff';
            slot.setAttribute('data-slot', slotIndex);

            slot.innerHTML = `
                <div class="date-slot-header d-flex align-items-center gap-2 mb-3 pb-2" style="border-bottom: 1px solid #dee2e6;">
                    <label style="font-weight: 600; font-size: 13px; color: #495057;">Date &amp; Time:</label>
                    <input type="date" class="form-control form-control-sm w-auto" name="slot_date[${slotIndex}]" value="${date}" required>
                    <span class="slot-at-separator">@</span>
                    <input type="time" class="form-control form-control-sm w-auto" name="slot_time[${slotIndex}]" value="${time}">
                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto d-flex align-items-center gap-1" onclick="removeProfileIndoorSlot(this)" style="padding: 2px 8px; font-size: 12px;">
                        <i class="bi bi-trash"></i> Drop Slot
                    </button>
                </div>
                <div class="date-slot-body">
                    <div class="medicine-rows-container">
                        <div class="medicine-row d-flex gap-2 mb-2">
                            <input type="text" class="form-control form-control-sm flex-grow-1" name="slot_medicine[${slotIndex}][]" placeholder="Enter medicine name / action" autocomplete="off" required>
                            <input type="text" class="form-control form-control-sm w-25" name="slot_note[${slotIndex}][]" placeholder="Dosage / Note">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deleteProfileMedicineRow(this)" title="Remove Row">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" onclick="addProfileMedicineRow(this, ${slotIndex})" style="font-size: 12px;">
                        <i class="bi bi-plus"></i> Add Item Row
                    </button>
                </div>
            `;

            container.appendChild(slot);
        }

        function addProfileIndoorSlot() {
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            const hh = String(now.getHours()).padStart(2, '0');
            const min = String(now.getMinutes()).padStart(2, '0');
            createProfileIndoorSlot(`${yyyy}-${mm}-${dd}`, `${hh}:${min}`, profileIndoorSlotCounter++);
        }

        function addProfileMedicineRow(btn, slotIndex) {
            const card = btn.closest('.date-slot-card');
            const rowsContainer = card.querySelector('.medicine-rows-container');
            const row = document.createElement('div');
            row.className = 'medicine-row d-flex gap-2 mb-2';
            row.innerHTML = `
                <input type="text" class="form-control form-control-sm flex-grow-1" name="slot_medicine[${slotIndex}][]" placeholder="Enter medicine name / action" autocomplete="off" required>
                <input type="text" class="form-control form-control-sm w-25" name="slot_note[${slotIndex}][]" placeholder="Dosage / Note">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deleteProfileMedicineRow(this)" title="Remove Row">
                    <i class="bi bi-x-lg"></i>
                </button>
            `;
            rowsContainer.appendChild(row);
            row.querySelector('input').focus();
        }

        function deleteProfileMedicineRow(btn) {
            const card = btn.closest('.date-slot-card');
            const rowsContainer = card.querySelector('.medicine-rows-container');
            const rows = rowsContainer.querySelectorAll('.medicine-row');
            if (rows.length > 1) {
                btn.closest('.medicine-row').remove();
            } else {
                btn.closest('.medicine-row').querySelectorAll('input').forEach(i => i.value = '');
            }
        }

        function removeProfileIndoorSlot(btn) {
            const container = document.getElementById('profileIndoorSlotsContainer');
            const slots = container.querySelectorAll('.date-slot-card');
            if (slots.length > 1) {
                btn.closest('.date-slot-card').remove();
            } else {
                btn.closest('.date-slot-card').querySelectorAll('input[type="text"]').forEach(i => i.value = '');
            }
        }
    </script>

@endsection
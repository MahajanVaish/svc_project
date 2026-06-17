@extends('admin.layouts.layouts')

@section('title', 'Patient Profile - ' . ($patient->patient_name ?? 'Patient'))

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        label.form-label {
            font-weight: 600;
            color: #5a6268;
            display: block;
            margin-bottom: 4px;
            font-size: 13px;
        }

        .form-control,
        .form-select {
            padding: 6px 10px;
            font-size: 13px;
            border-radius: 6px;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        /* Diet Plan Action Buttons */
        .diet-plan-actions {
            display: flex;
            gap: 5px;
        }

        .diet-plan-actions .btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .diet-plan-actions .btn i {
            font-size: 12px;
        }

        .btn-info.print-diet-btn {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .btn-info.print-diet-btn:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        /* Meal Row in Edit Modal */
        .meal-row {
            background: var(--bg-main);
            transition: all 0.3s ease;
        }

        .meal-row:hover {
            background: var(--bg-hover);
        }

        .meal-row .remove-meal-row {
            width: 24px;
            height: 24px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .meal-row .remove-meal-row:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }


        .transformations-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .image-card {
            background: var(--bg-card);
            border-radius: 8px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-subtle);
            overflow: hidden;
        }

        .image-card-header {
            background: var(--bg-main);
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .image-card-header h5 {
            margin: 0;
            font-size: 16px;
            color: var(--accent-solid);
        }

        .image-card-body {
            padding: 15px;
        }

        .image-container {
            position: relative;
            margin-bottom: 10px;
        }

        .gallery-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--border-subtle);
        }

        .image-details {
            background: var(--bg-main);
            padding: 10px;
            border-radius: 4px;
            font-size: 13px;
            border: 1px solid var(--border-subtle);
        }

        .dimension-label {
            font-weight: 600;
            color: var(--text-muted);
            margin-right: 5px;
        }

        .dimension-value {
            color: var(--accent-solid);
            font-weight: 500;
        }

        .no-image-placeholder {
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-main);
            border: 2px dashed var(--border-subtle);
            border-radius: 4px;
            color: var(--text-muted);
            font-style: italic;
        }

        /* Modal styles for image zoom */
        .image-modal .modal-dialog {
            max-width: 90%;
            max-height: 90vh;
        }

        .image-modal .modal-body {
            text-align: center;
            padding: 0;
        }

        .zoomed-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }

        /* Grid layout for multiple images */
        .images-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .images-grid {
                grid-template-columns: 1fr;
            }

            .transformations-gallery {
                grid-template-columns: 1fr;
            }
        }

        /* Image tags */
        .image-tags {
            margin-top: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .image-tag {
            background: #e9ecef;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            color: #495057;
        }


        .card.profile_cart {
            box-shadow: none;
        }

        .mb-5,
        .my-5 {
            margin-bottom: 3rem !important;
        }

        .card {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-subtle);
        }

        .card-header {
            background-color: var(--bg-main);
            border-bottom: 1px solid var(--border-subtle);
        }

        .heading-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile_cart .fnf-title {
            font-size: 18px;
        }

        .fnf-title {
            font-weight: 600;
            color: var(--accent-solid);
            padding-bottom: 0;
            line-height: 1.3em;
            margin-bottom: 0px !important;
        }

        .profile_txt_color {
            color: var(--accent-solid);
            font-weight: 600;
        }

        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid var(--border-subtle);
        }

        .dataTables_wrapper {
            position: relative;
            clear: both;
        }

        .entry-content table:not(.variations) {
            border: 1px solid var(--border-subtle);
            margin: 0 0 15px;
            text-align: left;
            width: 100%;
            background: var(--bg-card);
        }

        table thead {
            background: #006637;
        }

        table tr th,
        table tr td {
            padding: 10px 15px !important;
            font-size: 13px;
        }

        table thead th {
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: inherit;
        }

        .dataTables_wrapper .dataTables_info {
            clear: both;
            float: left;
            padding-top: .755em;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            cursor: default;
            color: #666 !important;
            border: 1px solid transparent;
            background: transparent;
            box-shadow: none;
        }

        .dataTables_wrapper .dataTables_paginate a.previous,
        .dataTables_wrapper .dataTables_paginate a.next {
            background: none !important;
            color: #666 !important;
            border: none !important;
            padding: 0 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            box-sizing: border-box;
            display: inline-block;
            min-width: 1.5em;
            padding: .5em 1em;
            margin-left: 2px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            color: inherit !important;
            border: 1px solid transparent;
            border-radius: 2px;
            background: transparent;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin: 0.5em !important;
            padding: 4px 10px !important;
            border: none !important;
        }

        .dataTables_wrapper .dataTables_paginate a.paginate_button.current {
            color: #fff !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: #006637 !important;
            color: #fff !important;
            border: none !important;
            margin: 0.5em;
            padding: 4px 10px;
        }

        .dataTables_wrapper .dataTables_info {
            clear: both;
            float: left;
            padding-top: .755em;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
            text-align: right;
            padding-top: .25em;
        }

        .dub_tab_field {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .download_img_zip,
        .add_progressBtn_div,
        .add_diet_div {
            background: #006637;
            padding: 4px 16px;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }

        table.dataTable td.dataTables_empty {
            text-align: center;
        }

        .bg-white {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        .card_toggle {
            position: relative;
        }

        .diet_bg {
            background: var(--bg-main);
        }

        .show_details {
            position: relative;
            cursor: pointer;
        }

        .add_call_record {
            color: #4cb034;
            background: none;
            border: 1px solid #4cb034;
            padding: 4px 16px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }

        .call-history-section {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .time-slot-badge {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
        }

        .slot-morning {
            background-color: #fff3cd;
            color: #856404;
        }

        .slot-afternoon {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .slot-evening {
            background-color: #d4edda;
            color: #155724;
        }

        .slot-night {
            background-color: #f8d7da;
            color: #721c24;
        }

        .slot-midnight {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .main_content {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
            padding-top: 30px;
            padding-bottom: 30px;
            background: var(--bg-main);
        }

        a {
            color: #4cb034;
        }

        .card_custom {
            border: 1px solid var(--border-subtle);
            border-radius: 0.375rem;
            background-color: var(--bg-card);
        }

        .label-text {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 5px;
            font-weight: 600;
        }

        .input-field {
            border: none;
            border-bottom: 1px solid var(--border-subtle);
            background: transparent;
            padding: 5px 0;
            width: 100%;
            color: var(--text-primary);
        }

        .input-field:focus {
            outline: none;
            border-bottom-color: #006637;
        }

        .patient_data_box {
            margin-bottom: 20px;
        }

        .toggle-icon {
            cursor: pointer;
            float: right;
            transition: transform 0.3s;
        }

        .rotate-icon {
            transform: rotate(180deg);
        }

        .profile-table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        .profile-table th,
        .profile-table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid var(--border-subtle);
            color: var(--text-primary);
        }

        .profile-table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid var(--border-subtle);
            background: #006637;
            color: white;
        }

        .profile-table tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.02);
        }

        /* Add button styles */
        .btn-success {
            background-color: #006637;
            border-color: #006637;
        }

        .btn-success:hover {
            background-color: #00502d;
            border-color: #00502d;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-edit {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-edit:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dub_tab_field {
                flex-direction: column;
            }

            .card-body {
                padding: 15px;
            }

            table tr th,
            table tr td {
                padding: 8px 10px !important;
                font-size: 12px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }
        }

        /* Modal Styles */
        .assessment-edit-container {
            max-width: 100%;
            padding: 15px;
        }

        .assessment-edit-container .section-card {
            background: var(--bg-card);
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid var(--border-subtle);
        }

        .assessment-edit-container .section-header {
            color: var(--text-primary);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .assessment-edit-container .section-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background-color: #086838;
            color: white;
            border-radius: 50%;
            margin-right: 10px;
            font-size: 12px;
            font-weight: 600;
        }

        .assessment-edit-container .measurement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 12px;
        }

        .assessment-edit-container .measurement-item {
            margin-bottom: 12px;
        }

        .assessment-edit-container .measurement-label {
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
        }

        .assessment-edit-container .measurement-input-group {
            border: 1px solid #ced4da;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
        }

        .assessment-edit-container .measurement-input {
            flex: 1;
            border: none;
            padding: 8px 10px;
            font-size: 13px;
            background-color: var(--bg-main);
            color: var(--text-primary);
        }

        .assessment-edit-container .measurement-unit {
            background-color: var(--bg-main);
            padding: 8px 12px;
            font-size: 13px;
            color: var(--text-muted);
            border-left: 1px solid var(--border-subtle);
            min-width: 50px;
        }

        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #086838;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Transformations section */
        .treatment_box_container {
            margin-top: 30px;
        }

        /* Call Follow-ups table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #006637;
            color: white;
            padding: 10px 15px;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 10px 15px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-primary);
        }

        table tr:hover {
            background-color: var(--bg-hover);
        }

        /* Progress Reports specific styles */
        .progress-section {
            margin-bottom: 30px;
            background: var(--bg-card);
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            color: var(--text-primary);
            border: 1px solid var(--border-subtle);
        }

        .progress-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #006637;
        }

        .progress-section-title {
            font-size: 18px;
            font-weight: 600;
            color: #006637;
            margin: 0;
        }

        .add-progress-btn {
            background: #006637;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .add-progress-btn:hover {
            background: #00502d;
            color: white;
            text-decoration: none;
        }

        /* No data message */
        .no-data-message {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
        }

        /* Table pagination */
        .table-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
        }

        .pagination-info {
            color: #6c757d;
            font-size: 14px;
        }

        .pagination-buttons {
            display: flex;
            gap: 5px;
        }

        .paginate_button {
            padding: 5px 10px;
            border: 1px solid var(--border-subtle);
            background: var(--bg-card);
            color: var(--accent-solid);
            cursor: pointer;
            text-decoration: none;
        }

        .paginate_button:hover:not(.disabled) {
            background: #006637;
            color: white;
        }

        .paginate_button.disabled {
            color: var(--text-muted);
            cursor: not-allowed;
            background: var(--bg-main);
        }

        .paginate_button.current {
            background: #006637;
            color: white;
        }

        /* Payment Program Modal Styles */
        .payment-edit-modal .modal-dialog {
            max-width: 800px;
        }

        .program-form-section {
            background: var(--bg-card);
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid var(--border-subtle);
        }

        .program-form-section .section-title {
            color: var(--text-primary);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
        }

        .program-form-section .section-title i {
            color: #006637;
            margin-right: 10px;
            font-size: 14px;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
        }

        .payment-field {
            margin-bottom: 15px;
        }

        .payment-field label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
            display: block;
        }

        .payment-input-group {
            position: relative;
        }

        .payment-input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid var(--border-subtle);
            background-color: var(--bg-main);
            color: var(--text-primary);
            border-radius: 4px;
            font-size: 13px;
            transition: border-color 0.15s ease-in-out;
        }

        .payment-input:focus {
            outline: none;
            border-color: #006637;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 55, 0.25);
        }

        .payment-input-group .currency {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 13px;
        }


        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }


        .modal-footer .btn {
            min-width: 80px;
        }


        @media (max-width: 768px) {
            .payment-grid {
                grid-template-columns: 1fr;
            }

            .payment-edit-modal .modal-dialog {
                margin: 10px;
            }
        }


        .program-form-section textarea.payment-input {
            min-height: 80px;
            resize: vertical;
        }


        .modal-body input[type="date"] {
            font-family: inherit;
        }

        .modal-body input[type="time"] {
            font-family: inherit;
        }


        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }


        .payment-field {
            margin-bottom: 15px;
        }

        .payment-field label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }


        .payment-input:focus {
            border-color: #006637;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 55, 0.25);
        }


        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }



        .diet-plans-container {
            margin-top: 15px;
        }

        .diet-plan-card {
            background: var(--bg-card);
            border-radius: 8px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid var(--border-subtle);
        }

        .diet-plan-header {
            background: var(--bg-main);
            padding: 15px 20px;
            border-bottom: 2px solid var(--accent-solid);
        }

        .diet-plan-header h5 {
            color: var(--accent-solid);
            font-weight: 600;
            margin: 0;
        }

        .diet-plan-header small {
            font-size: 13px;
            color: var(--text-muted);
        }

        .diet-plan-actions .btn {
            padding: 4px 10px;
            transition: all 0.3s ease;
        }

        .diet-plan-actions .btn i {
            transition: transform 0.3s ease;
        }

        .diet-plan-actions .btn.active i {
            transform: rotate(180deg);
        }

        .diet-plan-body {
            padding: 20px;
            background: var(--bg-card);
            color: var(--text-primary);
        }

        .diet-schedule-table {
            margin-bottom: 0;
        }

        .diet-schedule-table thead {
            background: #006637 !important;
        }

        .diet-schedule-table thead th {
            font-weight: 600;
            font-size: 14px;
            padding: 12px;
            border-bottom: 1px solid var(--border-subtle);
            color: #ffffff !important;
            text-align: left;
        }

        .diet-schedule-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .diet-schedule-table tbody tr:hover {
            background-color: var(--bg-hover);
        }

        .diet-schedule-table tbody td {
            padding: 12px;
            vertical-align: middle;
            border-color: var(--border-subtle);
            color: var(--text-primary);
        }

        .time-cell {
            text-align: center;
            font-weight: 500;
        }

        .time-cell .badge {
            font-size: 13px;
            padding: 6px 12px;
        }

        .menu-cell {
            line-height: 1.8;
        }

        .recipe-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .recipe-badge {
            display: inline-flex;
            align-items: center;
            background: var(--bg-main);
            color: var(--text-primary);
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            border-left: 3px solid var(--accent-solid);
            height: 33px;
            /* Fixed height for alignment */
        }

        .recipe-badge i {
            font-size: 6px;
            color: var(--accent-solid);
        }

        .quantity-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .quantity-badge {
            display: flex;
            align-items: center;
            padding: 6px 0;
            font-size: 13px;
            height: 33px;
            /* Match recipe-badge height */
            line-height: 1.2;
        }

        .notes-cell {
            font-size: 13px;
            color: var(--text-muted);
        }

        .general-notes {
            background: var(--bg-main);
            border-left: 4px solid var(--accent-solid);
            padding: 15px;
            border-radius: 4px;
            border: 1px solid var(--border-subtle);
        }

        .general-notes h6 {
            color: var(--accent-solid);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .notes-content {
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.6;
        }

        .follow-up-date {
            text-align: right;
        }

        .follow-up-date .badge {
            font-size: 13px;
            padding: 8px 15px;
        }

        .no-data-message {
            background: var(--bg-main);
            border-radius: 8px;
            padding: 40px 20px;
            border: 1px dashed var(--border-subtle);
            color: var(--text-muted);
        }

        .no-data-message i {
            opacity: 0.5;
        }

        .toggle-diet-details {
            border-radius: 4px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .diet-plan-header {
                padding: 12px 15px;
            }

            .diet-plan-header h5 {
                font-size: 16px;
            }

            .diet-schedule-table {
                font-size: 12px;
            }

            .recipe-badge {
                font-size: 12px;
                padding: 4px 8px;
            }

            .time-cell .badge {
                font-size: 11px;
                padding: 4px 8px;
            }


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
            z-index: 10;
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
    </style>

    @php

        function formatValue($value)
        {
            return $value === null || $value === 'null' || $value === '' || $value === '0.00' || $value === '0'
                ? '-'
                : $value;
        }

        $beforeProfilePhoto = $optMeta['before_profile_photo'] ?? null;
        $afterProfilePhoto = $optMeta['after_profile_photo'] ?? null;
        $profileImage = $optMeta['profile_image'] ?? null;

        $profileImagePath = null;

        if ($profileImage) {
            $profileImagePath = asset($profileImage);
        } elseif ($beforeProfilePhoto && file_exists(public_path('before/' . $beforeProfilePhoto))) {
            $profileImagePath = asset('before/' . $beforeProfilePhoto);
        } elseif ($afterProfilePhoto && file_exists(public_path('after/' . $afterProfilePhoto))) {
            $profileImagePath = asset('after/' . $afterProfilePhoto);
        }

        $perPage = 10;
        $currentPage = request()->get('payment_page', 1);
        $totalPrograms = count($programDetails ?? []);
        $totalProgramPages = ceil($totalPrograms / $perPage);
        $programStart = ($currentPage - 1) * $perPage;
        $currentPrograms = array_slice($programDetails ?? [], $programStart, $perPage);

        $statusHistory = is_array($patient->status_history) ? $patient->status_history : (json_decode($patient->status_history, true) ?: []);
        $isPendingOnly = in_array('Pending', $statusHistory) && !in_array('Diet Chart', $statusHistory) && !in_array('Joined', $statusHistory) && empty($optData);
    @endphp

    <div class="main_content">
        <div class="card profile_cart mb-5">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h3 class="bold font-up fnf-title mb-0">Patient Profile</h3>
                    <div class="d-flex align-items-center gap-2">
                        @php
                            $zoomJoinUrl = $optData ? $optData->getMetaValue('zoom_join_url') : null;
                            $zoomStartUrl = $optData ? $optData->getMetaValue('zoom_start_url') : null;
                            $internalJoinUrl = $zoomStartUrl ?? $zoomJoinUrl;
                            $createZoomRoute = route('zoom.meeting.create', 'acc_initial_' . $patient->id);

                            $waPhone = preg_replace('/[^0-9]/', '', $patient->phone_no ?? '');
                            if (strlen($waPhone) == 10)
                                $waPhone = '91' . $waPhone;
                            $waMessage = "Hello " . ($patient->patient_name ?? 'Patient') . ", your video consultation is scheduled. You can join the meeting by clicking this link: " . $zoomJoinUrl;
                            $waUrl = "https://wa.me/" . $waPhone . "?text=" . urlencode($waMessage);
                        @endphp

                        @if($zoomJoinUrl)
                            <button type="button" class="btn btn-success shadow-sm d-flex align-items-center"
                                style="border-radius: 8px; background-color: #25D366; border: none;"
                                title="Zoom Meeting Options"
                                onclick="openZoomModal('{{ $internalJoinUrl }}', '{{ $zoomJoinUrl }}', '{{ $waUrl }}')">
                                <i class="fas fa-video me-2"></i> Zoom Consultation
                            </button>
                        @else
                            <form action="{{ $createZoomRoute }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-success shadow-sm d-flex align-items-center"
                                    style="border-radius: 8px;" title="Create Zoom Meeting">
                                    <i class="fas fa-video-slash me-2"></i> Create Zoom Meeting
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('diet.chart') }}" class="btn btn-outline-secondary shadow-sm"
                            style="border-radius: 8px;">
                            <i class="fas fa-arrow-left me-1"></i> Back to Diet Chart
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-5">
                <div class="data_detail">
                    <div class="patient_profile">
                        <section>
                            <div class="row">
                                <div class="col-lg-4 p-0">
                                    <div class="card mb-4">
                                        <div class="card-body py-4 text-center">
                                            <form action="{{ route('patient.profile.update-image', $patient->id) }}"
                                                method="POST" enctype="multipart/form-data" id="profileImageForm">
                                                @csrf
                                                <div class="profile-image-wrapper">
                                                    <div class="profile-image-container" id="profileImagePreview">
                                                        @if ($profileImagePath)
                                                            <img src="{{ $profileImagePath }}" alt="Profile Photo"
                                                                class="img-fluid profile_open mx-auto d-block">
                                                        @else
                                                            <img src="https://care.figurenfit.com/wp-content/plugins/figurenfit/images/default-profile.png"
                                                                alt="avatar" class="img-fluid profile_open mx-auto d-block">
                                                        @endif
                                                    </div>
                                                    <label for="profile_image_input" class="upload-label"
                                                        title="Change Profile Image">
                                                        <i class="fas fa-camera"></i>
                                                    </label>
                                                    <input type="file" name="profile_image" id="profile_image_input"
                                                        class="d-none" accept="image/*"
                                                        onchange="previewPatientImageDirect(this)">
                                                </div>
                                                <div id="imageSaveContainer" class="text-center">
                                                    <button type="submit" class="save-profile-btn" id="saveImageBtn">Save
                                                        Image</button>
                                                </div>
                                            </form>
                                            <h5 class="my-3 profile_txt_color mb-2 pb-0">
                                                {{ $patient->patient_name ?? 'N/A' }}
                                            </h5>
                                            <p class="text-muted mb-1 pb-0">Patient ID: {{ $patient->patient_id ?? 'N/A' }}
                                            </p>
                                            <p class="text-muted mb-1 pb-0">Status:
                                                {{ is_array($patient->user_status) ? implode(', ', $patient->user_status) : ($patient->user_status ?? 'N/A') }}
                                            </p>
                                            <p class="text-muted mb-1 pb-0">Mo: {{ $patient->phone_no ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-8 pr-0">
                                    <div class="card mb-3">
                                        <div class="card-body py-2">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Full Name</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $patient->patient_f_name ?? '-' }} {{ $patient->patient_l_name ?? '-' }} {{ $patient->patient_m_name ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Email</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $patient->email ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Address</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $patient->address ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Age</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $patient->age ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Gender</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $patient->gender ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Refrance By</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $patient->refrance ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Patient Status</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">
                                                        {{ is_array($patient->user_status) ? implode(', ', $patient->user_status) : ($patient->user_status ?? '-') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <hr class="my-2">

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0 profile_txt_color">Inquiry Payment</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $patient->payment ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(!$isPendingOnly)
                                    <!-- Diet Plans Nutritional Information Section -->
                                    <div class="patient_data_box mb-4">
                                        <div class="row">
                                            <div class="col-lg-12 p-0">
                                                <div class="card-header mb-2">
                                                    <div class="heading-action">
                                                        <h3 class="bold font-up fnf-title">Diet Plans Nutritional Information</h3>
                                                        <small class="text-muted">Showing {{ count($dietPlansWithNutrition ?? []) }}
                                                            Diet
                                                            Plan(s)</small>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @forelse($dietPlansWithNutrition as $plan)
                                                                                    <div class="diet-plan-nutrition mb-4">
                                                                                        <div class="diet-plan-header">
                                                                                            <h5 class="mb-2" style="color: #086838;">
                                                                                                <i class="fas fa-utensils me-2"></i>{{ $plan['diet_name'] }}
                                                                                            </h5>
                                                                                            <small class="text-muted">Date: {{
                                                        \Carbon\Carbon::parse($plan['date'])->format('d/m/Y') }}</small>
                                                                                        </div>

                                                                                        <div class="row mt-3">
                                                                                            <div class="col-md-12">
                                                                                                <h6 class="mb-3" style="color: #333; font-weight: 600;">Total
                                                                                                    Nutrition</h6>
                                                                                                <div class="row">
                                                                                                    <div class="col-md-2 col-sm-4 mb-2">
                                                                                                        <div class="nutrition-summary">
                                                                                                            <span class="nutrition-label">Protein</span>
                                                                                                            <span class="nutrition-value">{{
                                                        number_format(
                                                            $plan['total_nutrition']['protein'],
                                                            1
                                                        ) }}g</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-md-2 col-sm-4 mb-2">
                                                                                                        <div class="nutrition-summary">
                                                                                                            <span class="nutrition-label">Folates</span>
                                                                                                            <span class="nutrition-value">{{
                                                        number_format(
                                                            $plan['total_nutrition']['total_folates'],
                                                            1
                                                        ) }}mcg</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-md-2 col-sm-4 mb-2">
                                                                                                        <div class="nutrition-summary">
                                                                                                            <span class="nutrition-label">Carbs</span>
                                                                                                            <span class="nutrition-value">{{
                                                        number_format(
                                                            $plan['total_nutrition']['carbohydrate'],
                                                            1
                                                        ) }}g</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-md-2 col-sm-4 mb-2">
                                                                                                        <div class="nutrition-summary">
                                                                                                            <span class="nutrition-label">Calcium</span>
                                                                                                            <span class="nutrition-value">{{
                                                        number_format(
                                                            $plan['total_nutrition']['calcium'],
                                                            0
                                                        ) }}mg</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-md-2 col-sm-4 mb-2">
                                                                                                        <div class="nutrition-summary">
                                                                                                            <span class="nutrition-label">Fiber</span>
                                                                                                            <span class="nutrition-value">{{
                                                        number_format(
                                                            $plan['total_nutrition']['insoluable_fiber'],
                                                            1
                                                        ) }}g</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        @if(!empty($plan['menu_items']))
                                                                                            <div class="row mt-3">
                                                                                                <div class="col-md-12">
                                                                                                    <h6 class="mb-3" style="color: #333; font-weight: 600;">Menu Items
                                                                                                        Breakdown</h6>
                                                                                                    <div class="menu-items-grid">
                                                                                                        @foreach($plan['menu_items'] as $item)
                                                                                                                                                <div class="menu-item-card">
                                                                                                                                                    <div class="menu-item-name">{{ $item['name'] }}
                                                                                                                                                        @if(
                                                                                                                                                                isset($item['quantity']) && $item['quantity'] >
                                                                                                                                                                1
                                                                                                                                                            )<span class="badge bg-secondary ms-1">{{
                                                                                                                                                        $item['quantity'] }}x</span>@endif
                                                                                                                                                    </div>
                                                                                                                                                    <div class="menu-item-nutrition">
                                                                                                                                                        <div class="nutrition-row">
                                                                                                                                                            <span>P:
                                                                                                                                                                {{ number_format($item['protein'], 1)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }}g</span>
                                                                                                                                                            <span>F:
                                                                                                                                                                {{ number_format($item['total_folates'], 1)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }}mcg</span>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="nutrition-row">
                                                                                                                                                            <span>C:
                                                                                                                                                                {{ number_format($item['carbohydrate'], 1)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }}g</span>
                                                                                                                                                            <span>Ca:
                                                                                                                                                                {{ number_format($item['calcium'], 0)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }}mg</span>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="nutrition-row">
                                                                                                                                                            <span>Fiber: {{
                                                                                                            number_format($item['insoluable_fiber'], 1)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }}g</span>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif

                                                                                        <hr class="mt-4">
                                                                                    </div>
                                                    @empty
                                                        <div class="text-center py-4">
                                                            <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                                                            <h5 class="text-muted">No Diet Plans Found</h5>
                                                            <p class="text-muted">This patient doesn't have any diet plans with
                                                                nutritional data.</p>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment & Program Details Section -->
                                    {{-- <div class="patient_data_box mb-4">
                                        <div class="row">
                                            <div class="col-lg-12 p-0">
                                                <div class="card-header mb-2">
                                                    <div class="heading-action">
                                                        <h3 class="bold font-up fnf-title">Payment & Program Details</h3>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table caption-top table-striped profile-table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Program Name</th>
                                                                <th>Session</th>
                                                                <th>Months</th>
                                                                <th>Payment Date</th>
                                                                <th>Payment Method</th>
                                                                <th>Total</th>
                                                                <th>Discount</th>
                                                                <th>Given</th>
                                                                <th>Due</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (count($programDetails ?? []) > 0)
                                                            @foreach ($currentPrograms as $index => $program)
                                                            @php
                                                            $serialNumber = $programStart + $index + 1;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $serialNumber }}</td>
                                                                <td>{{ formatValue($program['program_name']) }}</td>
                                                                <td>{{ formatValue($program['session']) }}</td>
                                                                <td>{{ formatValue($program['months']) }}</td>
                                                                <td>{{ formatValue($program['payment_date']) }}</td>
                                                                <td>{{ formatValue($program['payment_method']) }}</td>
                                                                <td>₹{{ formatValue($program['total']) }}</td>
                                                                <td>₹{{ formatValue($program['discount']) }}</td>
                                                                <td>₹{{ formatValue($program['given']) }}</td>
                                                                <td>₹{{ formatValue($program['due']) }}</td>
                                                                <td>
                                                                    <div class="action-buttons">
                                                                        <button class="btn btn-sm btn-edit edit-payment-btn"
                                                                            data-id="{{ $patient->id }}"
                                                                            data-index="{{ $programStart + $index }}"
                                                                            title="Edit Program">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        <button class="btn btn-sm btn-danger delete-payment-btn"
                                                                            data-id="{{ $patient->id }}"
                                                                            data-index="{{ $programStart + $index }}"
                                                                            title="Delete Program">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            @else
                                                            <tr>
                                                                <td colspan="11" class="text-center text-muted py-3">
                                                                    No payment program records found
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>

                                                    @if ($totalPrograms > $perPage)
                                                    <div class="table-pagination">
                                                        <div class="pagination-info">
                                                            Showing {{ $programStart + 1 }} to
                                                            {{ min($programStart + $perPage, $totalPrograms) }} of
                                                            {{ $totalPrograms }} entries
                                                        </div>
                                                        <div class="pagination-buttons">
                                                            @if ($currentPage <= 1) <span class="paginate_button previous disabled">
                                                                Previous</span>
                                                                @else
                                                                <a href="?payment_page={{ $currentPage - 1 }}"
                                                                    class="paginate_button previous">Previous</a>
                                                                @endif

                                                                @for ($i = 1; $i <= $totalProgramPages; $i++) @if ($i==$currentPage)
                                                                    <span class="paginate_button current">{{ $i }}</span>
                                                                    @else
                                                                    <a href="?payment_page={{ $i }}" class="paginate_button">{{ $i
                                                                        }}</a>
                                                                    @endif
                                                                    @endfor

                                                                    @if ($currentPage >= $totalProgramPages)
                                                                    <span class="paginate_button next disabled">Next</span>
                                                                    @else
                                                                    <a href="?payment_page={{ $currentPage + 1 }}"
                                                                        class="paginate_button next">Next</a>
                                                                    @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <!-- Patient Details Toggle Section -->
                                    @if ($optData && !empty($optMeta))
                                        <div class="row">
                                            <div class="col-lg-12 p-0">
                                                <div class="card_custom mt-4 rounded-3 bg-white border">
                                                    <div class="card-header" id="patientDetailsHeader">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <b><span class="me-1 p-2">Patient Details</span></b>
                                                            <div class="d-flex align-items-center gap-3">
                                                                <span class="toggle-icon" id="toggleIcon">
                                                                    <i class="fas fa-angle-down"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="patient_opd_details p-4" id="patientDetailsContent"
                                                        style="display: none;">
                                                        <div class="row">
                                                            <!-- Medical Information & Laboratory Investigation Fields -->
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">HEIGHT</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['pod_data'] ?? $patient->height ?? '') }}"
                                                                    disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">WEIGHT</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['pod_bdy_weight'] ?? $patient->weight) }}"
                                                                    disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">BMI</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['bmi'] ?? $patient->bmi ?? '') }}"
                                                                    disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">BMR</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['pod_bmr'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">CALORIES REQ.</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['pod_calories'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">UNDER WEIGHT</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['under_weight'] ?? $optMeta['pod_undr_weight'] ?? '') }}"
                                                                    disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">OVER WEIGHT</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['over_weight'] ?? $optMeta['pod_ovr_weight'] ?? '') }}">
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">IDEAL BODY WEIGHT</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['lead_body_weight'] ?? $optMeta['pod_trg_weight'] ?? '') }}">
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">TARGET WEIGHT</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['target_weight'] ?? $optMeta['pod_trg_weight'] ?? '') }}"
                                                                    disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text"> LMP</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['pod_bdy_lmp'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">HABITS</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['habit'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">ALCOHOL</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['alcohol'] ?? '') }}" disabled>
                                                            </div>

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">HB</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_hb'] ?? '') }}" disabled>
                                                                                                                                                                                            </div> -->

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">BLOOD GROUP</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['bg_rh'] ?? $optData->blood_group ?? '-') }}"
                                                                    disabled>
                                                            </div>

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">BIRTH DATE</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($patient->dob ?? '-') }}" disabled>
                                                                                                                                                                                            </div> -->

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">VALID DATE</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_vld_date'] ?? '') }}"
                                                                                                                                                                                                    disabled>
                                                                                                                                                                                            </div> -->

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">HB1AC</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_hbac'] ?? '') }}"
                                                                                                                                                                                                    disabled>
                                                                                                                                                                                            </div> -->

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">PA/H</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['pa_h'] ?? $optMeta['pod_pah'] ?? '') }}"
                                                                    disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">F/H</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['pod_fh'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">ANY MEDICATION</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['pod_medication'] ?? '') }}"
                                                                    disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">S.CHOLESTEROL</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['s_cholesterol'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">S.TRIGLYCERIDES</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['s_triglycerides'] ?? '') }}"
                                                                    disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">HDL</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['hdl'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">LDL</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['ldl'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">VLDL</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['vldl'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">NON-HDL C</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['non_hdl_c'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">CHOL/HDL</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['chol_hdl_ratio'] ?? '') }}"
                                                                    disabled>
                                                            </div>

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">S.TSH</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_tsh'] ?? '') }}" disabled>
                                                                                                                                                                                            </div>

                                                                                                                                                                                            <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">S.T3</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_t3'] ?? '') }}" disabled>
                                                                                                                                                                                            </div> -->
                                                            <!-- 
                                                                                                                                                                                            <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">S.T4</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_t4'] ?? '') }}" disabled>
                                                                                                                                                                                            </div> -->

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">S.B12</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_b12'] ?? '') }}" disabled>
                                                                                                                                                                                            </div> -->

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">S.VIT.D3</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_vit_d3'] ?? '') }}"
                                                                                                                                                                                                    disabled>
                                                                                                                                                                                            </div> -->

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">BP</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_vit_bp'] ?? '') }}"
                                                                                                                                                                                                    disabled>
                                                                                                                                                                                            </div> -->

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">RBS</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_sugar_rbs'] ?? '') }}"
                                                                                                                                                                                                    disabled>
                                                                                                                                                                                            </div> -->

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">S. INSULIN</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['s_insulin'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">SGPT</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['sgpt'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">S. CREATININE</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['s_creatinine'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">S. URIC ACID</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['s_uric_acid'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">RA TEST</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['ra_test'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">USG ABDOMEN</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['usg_abdomen'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">CHEST X-RAY</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['chest_xray'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">MRI-CT SCAN</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['mri_ct_scan'] ?? '') }}" disabled>
                                                            </div>

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">FBS</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_sugar_fbs'] ?? '') }}"
                                                                                                                                                                                                    disabled>
                                                                                                                                                                                            </div> -->

                                                            <!-- <div class="col-md-3 py-3">
                                                                                                                                                                                                <div class="label-text">PP2BS</div>
                                                                                                                                                                                                <input class="input-field" type="text"
                                                                                                                                                                                                    value="{{ formatValue($optMeta['pod_sugar_pp2bs'] ?? '') }}"
                                                                                                                                                                                                    disabled>
                                                                                                                                                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Assigned Programs Section -->
                                    @php
                                        $programsArray = [];

                                        // Only read from the LATEST Opt record.
                                        // Each new joining/diet-chart save creates a new Opt record that already
                                        // contains the full current programs_array. Reading ALL records causes the
                                        // same program to appear once per Opt record (i.e. duplicates on every save).
                                        $latestOpt = \App\Models\Opt::with('meta')
                                            ->where('patient_id', $patient->patient_id)
                                            ->where(function ($q) {
                                                $q->whereNull('delete_status')
                                                    ->orWhere('delete_status', '0');
                                            })
                                            ->orderBy('id', 'desc')
                                            ->first();

                                        if ($latestOpt) {
                                            $metaData = $latestOpt->meta->pluck('meta_value', 'meta_key')->toArray();

                                            if (!empty($metaData['programs_array'])) {
                                                $progs = json_decode($metaData['programs_array'], true);
                                                if (is_array($progs)) {
                                                    foreach ($progs as $p) {
                                                        if (!empty($p['program'])) {
                                                            $pTotal = $p['total'] ?? '';
                                                            $pMethod = $p['payment_method'] ?? '';
                                                            $pDate = $p['payment_date'] ?? '';

                                                            if (empty($pTotal) && count($progs) === 1 && !empty($metaData['total_payment'])) {
                                                                $pTotal = $metaData['total_payment'];
                                                            }
                                                            if (empty($pMethod) && count($progs) === 1 && !empty($metaData['payment_method'])) {
                                                                $pMethod = $metaData['payment_method'];
                                                            }
                                                            if (empty($pDate)) {
                                                                $pDate = !empty($metaData['payment_date']) ? $metaData['payment_date'] : ($latestOpt->created_at ? $latestOpt->created_at->format('Y-m-d') : '');
                                                            }

                                                            $progTotal = is_numeric($pTotal) ? (float) $pTotal : 0;
                                                            $discount = isset($metaData['discount_payment']) && is_numeric($metaData['discount_payment']) ? (float) $metaData['discount_payment'] : 0;
                                                            $totalPayment = isset($metaData['total_payment']) && is_numeric($metaData['total_payment']) ? (float) $metaData['total_payment'] : 0;

                                                            if ($discount > 0) {
                                                                if (count($progs) === 1) {
                                                                    $progTotal = max(0, $progTotal - $discount);
                                                                } elseif ($totalPayment > 0) {
                                                                    $progTotal = max(0, $progTotal - ($progTotal / $totalPayment * $discount));
                                                                }
                                                            }

                                                            $programsArray[] = [
                                                                'program' => $p['program'],
                                                                'session' => !empty($p['session']) ? $p['session'] : 'N/A',
                                                                'months' => !empty($p['months']) ? $p['months'] : 'N/A',
                                                                'total' => $progTotal > 0 ? $progTotal : $pTotal,
                                                                'payment_method' => $pMethod,
                                                                'payment_date' => $pDate,
                                                            ];
                                                        }
                                                    }
                                                }
                                            } elseif (!empty($metaData['program_name']) || !empty($metaData['online_program_label']) || !empty($metaData['program_names'])) {
                                                $progNameRaw = $metaData['online_program_label'] ?? $metaData['program_names'] ?? $metaData['program_name'] ?? '';
                                                if (!empty($progNameRaw)) {
                                                    $progNameClean = preg_replace('/(\d),(\d)/', '$1$2', $progNameRaw);
                                                    $progList = array_map('trim', explode(',', $progNameClean));
                                                    $progCounts = array_count_values(array_filter($progList));

                                                    $totalPayment = isset($metaData['total_payment']) && is_numeric($metaData['total_payment']) ? (float) $metaData['total_payment'] : 0;
                                                    $discount = isset($metaData['discount_payment']) && is_numeric($metaData['discount_payment']) ? (float) $metaData['discount_payment'] : 0;
                                                    $finalTotal = max(0, $totalPayment - $discount);
                                                    $perProgramTotal = count($progCounts) > 0 ? $finalTotal / count($progCounts) : $finalTotal;

                                                    foreach ($progCounts as $name => $count) {
                                                        $months = 'N/A';
                                                        $session = 'N/A';
                                                        if (preg_match('/(\d+)\s*month/i', $name, $matches)) {
                                                            $months = $matches[1];
                                                            if ($count > 1) {
                                                                $session = $count;
                                                            }
                                                        } else {
                                                            $session = $count;
                                                        }

                                                        $programsArray[] = [
                                                            'program' => $name,
                                                            'session' => $session,
                                                            'months' => $months,
                                                            'total' => $perProgramTotal > 0 ? $perProgramTotal : ($metaData['total_payment'] ?? ''),
                                                            'payment_method' => $metaData['payment_method'] ?? '',
                                                            'payment_date' => '',
                                                        ];
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    @if(is_array($programsArray) && count($programsArray) > 0)
                                        <div class="row">
                                            <div class="col-lg-12 p-0">
                                                <div class="card_custom mt-4 rounded-3 bg-white border">
                                                    <div class="card-header" id="assignedProgramsHeader"
                                                        onclick="toggleSection('assignedProgramsContent', 'assignedProgramsToggleIcon')">
                                                        <b><span class="me-1 p-2">Assigned Programs</span></b>
                                                        <span class="toggle-icon" id="assignedProgramsToggleIcon">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </span>
                                                    </div>
                                                    <div class="patient_opd_details p-4" id="assignedProgramsContent"
                                                        style="display: none;">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-bordered mb-0">
                                                                <thead style="background-color: #f8f9fa !important;">
                                                                    <tr>
                                                                        <th
                                                                            style="color: #333 !important; padding: 12px 15px !important; font-weight: 700 !important; background-color: #f8f9fa !important;">
                                                                            Program Name</th>
                                                                        <th
                                                                            style="color: #333 !important; padding: 12px 15px !important; font-weight: 700 !important; width: 15% !important; background-color: #f8f9fa !important;">
                                                                            Session</th>
                                                                        <th
                                                                            style="color: #333 !important; padding: 12px 15px !important; font-weight: 700 !important; width: 15% !important; background-color: #f8f9fa !important;">
                                                                            Months</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($programsArray as $index => $prog)
                                                                        <tr class="program-row-toggle"
                                                                            data-target="prog-detail-{{ $index }}" style="cursor: pointer;">
                                                                            <td>
                                                                                <div
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <span>
                                                                                        <i class="fas fa-chevron-right me-2 text-success transition-transform"
                                                                                            id="prog-arrow-{{ $index }}"
                                                                                            style="transition: transform 0.2s ease; font-size: 11px;"></i>
                                                                                        <strong
                                                                                            class="text-dark">{{ $prog['program'] ?? 'N/A' }}</strong>
                                                                                    </span>
                                                                                    <span
                                                                                        class="badge bg-success-light text-success ms-2 px-2 py-1"
                                                                                        style="font-size: 10px; background-color: rgba(40, 167, 69, 0.1);">
                                                                                        <i class="fas fa-info-circle me-1"></i>Click to view
                                                                                        info
                                                                                    </span>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge bg-light text-dark border px-2 py-1">
                                                                                    <i
                                                                                        class="fas fa-clock me-1 text-muted"></i>{{ !empty($prog['session']) ? $prog['session'] : 'N/A' }}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge bg-light text-dark border px-2 py-1">
                                                                                    <i
                                                                                        class="fas fa-calendar-alt me-1 text-muted"></i>{{ !empty($prog['months']) ? $prog['months'] : 'N/A' }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id="prog-detail-{{ $index }}"
                                                                            style="display: none; background-color: #fbfcfc;">
                                                                            <td colspan="3" class="p-3 border-top-0">
                                                                                <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                                                                                    <div class="row w-100 m-0">
                                                                                        <div class="col-md-6 mb-2 mb-md-0 border-end-md">
                                                                                            <div class="d-flex align-items-center">
                                                                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                                                                    style="background-color: rgba(40, 167, 69, 0.1); width: 40px; height: 40px; min-width: 40px;">
                                                                                                    <i class="fas fa-rupee-sign text-success"
                                                                                                        style="font-size: 16px;"></i>
                                                                                                </div>
                                                                                                <div>
                                                                                                    <p class="mb-0 text-muted small fw-bold text-uppercase"
                                                                                                        style="font-size: 10px; letter-spacing: 0.5px;">
                                                                                                        Total Price</p>
                                                                                                    <h6 class="mb-0 fw-bold text-success"
                                                                                                        style="font-size: 15px;">
                                                                                                        {{ !empty($prog['total']) ? '₹' . number_format((float) $prog['total'], 2) : 'N/A' }}
                                                                                                    </h6>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6 mb-2 mb-md-0">
                                                                                            <div class="d-flex align-items-center">
                                                                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                                                                    style="background-color: rgba(23, 162, 184, 0.1); width: 40px; height: 40px; min-width: 40px;">
                                                                                                    <i class="fas fa-credit-card text-info"
                                                                                                        style="font-size: 16px;"></i>
                                                                                                </div>
                                                                                                <div>
                                                                                                    <p class="mb-0 text-muted small fw-bold text-uppercase"
                                                                                                        style="font-size: 10px; letter-spacing: 0.5px;">
                                                                                                        Payment Method</p>
                                                                                                    <h6 class="mb-0 fw-bold text-dark"
                                                                                                        style="font-size: 14px;">
                                                                                                        {{ !empty($prog['payment_method']) ? $prog['payment_method'] : 'N/A' }}
                                                                                                    </h6>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- Diet History Toggle Section -->
                                    @if (!empty($optMeta))
                                        <div class="row">
                                            <div class="col-lg-12 p-0">
                                                <div class="card_custom mt-4 rounded-3 bg-white border">
                                                    <div class="card-header" id="dietHistoryHeader">
                                                        <b><span class="me-1 p-2">Diet History</span></b>
                                                        <span class="toggle-icon" id="dietHistoryToggleIcon">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </span>
                                                    </div>

                                                    <div class="patient_opd_details p-4" id="dietHistoryContent" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">WAKING TIME</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['early_morning'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">SLEEPING TIME</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['sleeping_time'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">FOOD CHOICE</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['food_choices'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">FOOD CHOICE VALUE</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['food_choices'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">OCCUPATION</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['occupation'] ?? '') }}" disabled>
                                                            </div>

                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">TIME</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['time'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">BREAKFAST</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['breakfast'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">BRUNCH</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['brunch'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">LUNCH</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['lunch'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">SNACKS</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['snacks'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">DINNER</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['dinner'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">BED TIME</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['bed_time'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">MILK</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['milk'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">OIL</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['oil'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">SALT</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['salt'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">WATER INTAKE</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['water_intake'] ?? '') }} {{ formatValue($optMeta['water_unit'] ?? '') }}"
                                                                    disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">PHYSICAL ACTIVITY</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['physical_activity'] ?? '') }}"
                                                                    disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">WALKING TIME</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['walking_time'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">FASTING DAY</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['fasting_day'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">FAST FOOD/HOTEL FOOD</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['anything_else'] ?? '') }}" disabled>
                                                            </div>
                                                            <div class="col-md-3 py-3">
                                                                <div class="label-text">NOTE</div>
                                                                <input class="input-field" type="text"
                                                                    value="{{ formatValue($optMeta['food_allergy'] ?? '') }}" disabled>
                                                            </div>

                                                            @if(isset($optMeta['diet']) || isset($optMeta['exercise']) || isset($optMeta['sleep']) || isset($optMeta['water']))
                                                                <div class="col-12 mt-3">
                                                                    <h6 class="fw-bold mb-2 text-primary border-bottom pb-2">Health Metrics
                                                                    </h6>
                                                                    <div class="row">
                                                                        @if(isset($optMeta['diet']))
                                                                            <div class="col-md-3 py-2">
                                                                                <div class="label-text">DIETARY HABITS</div>
                                                                                <input class="input-field" type="text"
                                                                                    value="{{ formatValue($optMeta['diet'] ?? '') }}" disabled>
                                                                            </div>
                                                                        @endif
                                                                        @if(isset($optMeta['exercise']))
                                                                            <div class="col-md-3 py-2">
                                                                                <div class="label-text">EXERCISE REGIMEN</div>
                                                                                <input class="input-field" type="text"
                                                                                    value="{{ formatValue($optMeta['exercise'] ?? '') }}"
                                                                                    disabled>
                                                                            </div>
                                                                        @endif
                                                                        @if(isset($optMeta['sleep']))
                                                                            <div class="col-md-3 py-2">
                                                                                <div class="label-text">SLEEP PATTERN</div>
                                                                                <input class="input-field" type="text"
                                                                                    value="{{ formatValue($optMeta['sleep'] ?? '') }}" disabled>
                                                                            </div>
                                                                        @endif
                                                                        @if(isset($optMeta['water']))
                                                                            <div class="col-md-3 py-2">
                                                                                <div class="label-text">WATER INTAKE</div>
                                                                                <input class="input-field" type="text"
                                                                                    value="{{ formatValue($optMeta['water'] ?? '') }}" disabled>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Laboratory Investigation Section -->
                                    @if ($optData && !empty($optMeta) && (isset($optMeta['s_insulin']) || isset($optMeta['sgpt']) || isset($optMeta['s_creatinine']) || isset($optMeta['s_uric_acid']) || isset($optMeta['ra_test']) || isset($optMeta['usg_abdomen']) || isset($optMeta['chest_xray']) || isset($optMeta['hb']) || isset($optMeta['tc']) || isset($optMeta['pc']) || isset($optMeta['mp_lab']) || isset($optMeta['esr']) || isset($optMeta['crp']) || isset($optMeta['hb1ac']) || isset($optMeta['fbs']) || isset($optMeta['pp2bs']) || isset($optMeta['s_widal']) || isset($optMeta['ns1ag']) || isset($optMeta['dengue_igm']) || isset($optMeta['s_b12']) || isset($optMeta['s_d3']) || isset($optMeta['s_t3']) || isset($optMeta['s_t4']) || isset($optMeta['s_tsh']) || isset($optMeta['urine_lab']) || isset($optMeta['specific_test']) || isset($optMeta['mri_ct_scan'])))
                                                                                                            <div class="row">
                                                                                                                <div class="col-lg-12 p-0">
                                                                                                                    <div class="card_custom mt-4 rounded-3 bg-white border">
                                                                                                                        <div class="card-header">
                                                                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                                                                <b><span class="me-1 p-2"><i class="fas fa-flask me-2"></i>Laboratory Investigation</span></b>
                                                                                                                                <span class="toggle-icon" onclick="toggleSection('labInvestigationContent')">
                                                                                                                                    <i class="fas fa-angle-down"></i>
                                                                                                                                </span>
                                                                                                                            </div>
                                                                                                                        </div>

                                                                                                                        <div class="patient_opd_details p-4" id="labInvestigationContent" style="display: none;">
                                                                                                                            <div class="row">
                                                                                                                                @if(isset($optMeta['s_insulin']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">S. INSULIN</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['s_insulin']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif

                                                                                                                                @if(isset($optMeta['sgpt']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">SGPT</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['sgpt']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif

                                                                                                                                @if(isset($optMeta['s_creatinine']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">S. CREATININE</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['s_creatinine']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif

                                                                                                                                @if(isset($optMeta['s_uric_acid']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">S. URIC ACID</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['s_uric_acid']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif

                                                                                                                                @if(isset($optMeta['ra_test']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">RA TEST</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['ra_test']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif

                                                                                                                                @if(isset($optMeta['usg_abdomen']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">USG ABDOMEN</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['usg_abdomen']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif

                                                                                                                                @if(isset($optMeta['chest_xray']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">CHEST X-RAY</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['chest_xray']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['hb']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">HB</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['hb']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['tc']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">TC</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['tc']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['pc']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">PC</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['pc']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['mp_lab']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">MP</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['mp_lab']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['esr']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">ESR</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['esr']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['crp']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">CRP</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['crp']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['hb1ac']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">HB1AC</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['hb1ac']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['fbs']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">FBS</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['fbs']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['pp2bs']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">PP2BS</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['pp2bs']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['s_widal']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">S. WIDAL</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['s_widal']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['ns1ag']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">NS1 AG</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['ns1ag']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['dengue_igm']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">DENGUE IGM</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['dengue_igm']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['s_b12']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">S. B12</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['s_b12']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['s_d3']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">S. D3</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['s_d3']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['s_t3']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">S. T3</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['s_t3']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['s_t4']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">S. T4</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['s_t4']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['s_tsh']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">S. TSH</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['s_tsh']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['urine_lab']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">URINE</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['urine_lab']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['specific_test']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">ANY SPECIFIC TEST</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['specific_test']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                                @if(isset($optMeta['mri_ct_scan']))
                                                                                                                                <div class="col-md-3 py-3">
                                                                                                                                    <div class="label-text">MRI-CT SCAN</div>
                                                                                                                                    <input class="input-field" type="text" value="{{ formatValue($optMeta['mri_ct_scan']) }}" disabled>
                                                                                                                                </div>
                                                                                                                                @endif

                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            @endif 

                                    <!-- Monthly Assessment Section -->
                                    <div class="patient_data_box mb-4">
                                        <div class="row">
                                            <div class="col-lg-12 p-0 mt-4">
                                                <div class="card-header mb-2 d-flex justify-content-between align-items-center">
                                                    <div class="heading-action">
                                                        <h3 class="bold font-up fnf-title">Monthly Assessment</h3>
                                                    </div>
                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#addAssessmentModal">
                                                        <i class="fas fa-plus me-2"></i>Add Monthly Assessment
                                                    </button>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table caption-top table-striped profile-table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Date</th>
                                                                <th>Upper Waist (cm)</th>
                                                                <th>Middle Waist (cm)</th>
                                                                <th>Lower Waist (cm)</th>
                                                                <th>Hips (cm)</th>
                                                                <th>Thighs (cm)</th>
                                                                <th>Arms (cm)</th>
                                                                <th>Waist/Hips</th>
                                                                <th>Weight</th>
                                                                <th>BMI</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($monthlyAssessments) && count($monthlyAssessments) > 0)
                                                                @foreach ($monthlyAssessments as $index => $assessment)
                                                                    <tr>
                                                                        <th scope="row">{{ $index + 1 }}</th>
                                                                        <td>{{ date('d/m/Y', strtotime($assessment->assessment_date)) }}
                                                                        </td>
                                                                        <td>{{ formatValue($assessment->waist_upper) }}</td>
                                                                        <td>{{ formatValue($assessment->waist_middle) }}</td>
                                                                        <td>{{ formatValue($assessment->waist_lower) }}</td>
                                                                        <td>{{ formatValue($assessment->hips) }}</td>
                                                                        <td>{{ formatValue($assessment->thighs) }}</td>
                                                                        <td>{{ formatValue($assessment->arms) }}</td>
                                                                        <td>{{ formatValue($assessment->waist_hips_ratio) }}</td>
                                                                        <td>{{ formatValue($assessment->weight) }}</td>
                                                                        <td>{{ formatValue($assessment->bmi) }}</td>
                                                                        <td>
                                                                            <div class="action-buttons">
                                                                                <button class="btn btn-sm btn-edit edit-assessment-btn"
                                                                                    data-id="{{ $assessment->id }}" title="Edit">
                                                                                    <i class="fas fa-edit"></i>
                                                                                </button>
                                                                                <button class="btn btn-sm btn-danger delete-assessment-btn"
                                                                                    data-id="{{ $assessment->id }}"
                                                                                    data-patient-id="{{ $patient->id }}" title="Delete">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="12" class="text-center text-muted py-3">
                                                                        No monthly assessment records available
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subcutaneous Fat and Skeletal Muscle Mass (Side by Side) -->
                                    <div class="dub_tab_field">
                                        @if (!empty($monthlyAssessments) && count($monthlyAssessments) > 0)
                                            <div class="patient_data_box mb-4" style="flex: 1;">
                                                <div class="row">
                                                    <div class="col-lg-12 p-0 mt-4">
                                                        <div class="card-header mb-2">
                                                            <div class="heading-action">
                                                                <h3 class="bold font-up fnf-title">Subcutaneous Fat</h3>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table caption-top table-striped profile-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Date</th>
                                                                        <th>WBF</th>
                                                                        <th>Arms</th>
                                                                        <th>Trunk</th>
                                                                        <th>Legs</th>
                                                                        <th>T.F.</th>
                                                                        <th>V.F.</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($monthlyAssessments as $index => $assessment)
                                                                        <tr>
                                                                            <th scope="row">{{ $index + 1 }}</th>
                                                                            <td>{{ date('d/m/Y', strtotime($assessment->assessment_date)) }}
                                                                            </td>
                                                                            <td>{{ formatValue($assessment->bca_vbf) }}</td>
                                                                            <td>{{ formatValue($assessment->bca_arms) }}</td>
                                                                            <td>{{ formatValue($assessment->bca_trunk) }}</td>
                                                                            <td>{{ formatValue($assessment->bca_legs) }}</td>
                                                                            <td>{{ formatValue($assessment->bca_sf) }}</td>
                                                                            <td>{{ formatValue($assessment->bca_vf) }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="patient_data_box mb-4" style="flex: 1;">
                                                <div class="row">
                                                    <div class="col-lg-12 p-0 mt-4">
                                                        <div class="card-header mb-2">
                                                            <div class="heading-action">
                                                                <h3 class="bold font-up fnf-title">Skeletal Muscle Mass</h3>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table caption-top table-striped profile-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Date</th>
                                                                        <th>VBF</th>
                                                                        <th>Arms</th>
                                                                        <th>Trunk</th>
                                                                        <th>Legs</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($monthlyAssessments as $index => $assessment)
                                                                        <tr>
                                                                            <th scope="row">{{ $index + 1 }}</th>
                                                                            <td>{{ date('d/m/Y', strtotime($assessment->assessment_date)) }}
                                                                            </td>
                                                                            <td>{{ formatValue($assessment->muscle_vbf) }}</td>
                                                                            <td>{{ formatValue($assessment->muscle_arms) }}</td>
                                                                            <td>{{ formatValue($assessment->muscle_trunk) }}</td>
                                                                            <td>{{ formatValue($assessment->muscle_legs) }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    @php
                                        $userStatus = is_array($patient->user_status) ? $patient->user_status : [$patient->user_status];
                                        $isJoined = in_array('Joined', $userStatus) || in_array('joined', $userStatus) ||
                                            in_array('Diet Chart', $userStatus) || in_array('diet chart', $userStatus) ||
                                            in_array('InBody', $userStatus) || in_array('inbody', $userStatus);
                                    @endphp
                                    <!-- Progress Reports Section -->
                                    @if ($isJoined)
                                        <div class="patient_data_box mb-4">
                                            <div
                                                class="card-header mb-3 border-bottom pb-2 d-flex justify-content-between align-items-center">
                                                <h3 class="bold font-up fnf-title mb-0">Progress Reports</h3>
                                                <a href="{{ route('progress-reports', ['branch_id' => $patient->branch_id, 'patient_id' => $patient->id]) }}"
                                                    class="btn btn-success btn-sm">
                                                    <i class="fas fa-plus me-2"></i>Add Progress Report
                                                </a>
                                            </div>

                                            @if (!empty($progressReports) && count($progressReports) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-striped profile-table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Date & Time</th>
                                                                <th>Program Name</th>
                                                                <th>Body Part & Functions</th>
                                                                <th>Vitals</th>
                                                                <th>Measurements</th>
                                                                <th>Health Details</th>
                                                                <th>Notes & Exercise</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($progressReports as $index => $report)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>
                                                                        <div class="fw-bold">{{ date('d/m/Y', strtotime($report->date)) }}</div>
                                                                        <small class="text-muted">{{ $report->time }}</small>
                                                                    </td>
                                                                    <td>
                                                                        @if(!empty($report->face_program))
                                                                            <span class="badge bg-secondary">{{ $report->face_program }}</span>
                                                                        @else
                                                                            <span class="text-muted">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ formatValue($report->body_part) }}</td>
                                                                    <td>
                                                                        <div><strong>BP:</strong> {{ formatValue($report->bp_p) }}</div>
                                                                        <div><strong>Pulse:</strong> {{ formatValue($report->pulse) }}</div>
                                                                    </td>
                                                                    <td>
                                                                        @if(!empty($report->weight))
                                                                            <div><strong>Wt:</strong> {{ $report->weight }} kg</div>
                                                                        @endif
                                                                        @if(!empty($report->height))
                                                                            <div><strong>Ht:</strong> {{ $report->height }} cm</div>
                                                                        @endif
                                                                        @if(!empty($report->bmi))
                                                                            <div><strong>BMI:</strong> {{ $report->bmi }}</div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if(!empty($report->diet))
                                                                            <div><strong>Diet:</strong> {{ formatValue($report->diet) }}</div>
                                                                        @endif
                                                                        @if(!empty($report->sleep))
                                                                            <div><strong>Sleep:</strong> {{ formatValue($report->sleep) }}</div>
                                                                        @endif
                                                                        @if(!empty($report->water))
                                                                            <div><strong>Water:</strong> {{ formatValue($report->water) }}</div>
                                                                        @endif
                                                                        @if(!empty($report->medication))
                                                                            <div><strong>Med:</strong> {{ formatValue($report->medication) }}</div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if(!empty($report->councilor_doctor))
                                                                            <div><strong>Note:</strong> {{ formatValue($report->councilor_doctor) }}
                                                                        </div> @endif
                                                                        @if(!empty($report->exercise))
                                                                            <div><strong>Exercise:</strong> {{ formatValue($report->exercise) }}
                                                                        </div> @endif
                                                                    </td>
                                                                    <td>
                                                                        <div class="action-buttons">
                                                                            <button
                                                                                class="btn btn-sm btn-outline-primary edit-progress-report-btn me-1"
                                                                                data-id="{{ $report->id }}" data-patient-id="{{ $patient->id }}"
                                                                                title="Edit">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                            <button
                                                                                class="btn btn-sm btn-outline-danger delete-progress-report-btn"
                                                                                data-id="{{ $report->id }}" data-patient-id="{{ $patient->id }}"
                                                                                title="Delete">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="mt-3 d-flex justify-content-center">
                                                    {{ $progressReports->appends(request()->except('progress_page'))->links() }}
                                                </div>
                                            @else
                                                <div class="text-center py-4 text-muted">
                                                    <i class="fas fa-info-circle fa-2x mb-2 text-secondary"></i>
                                                    <p class="mb-0">No progress reports found for this patient.</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif




                                    <!-- Diet Chart Section -->
                                    <div class="patient_data_box mb-4">
                                        <div class="row">
                                            <div class="col-lg-12 p-0 mt-4">
                                                <div class="card-header mb-2">
                                                    <div class="heading-action">
                                                        <h3 class="bold font-up fnf-title">Diet Chart</h3>
                                                        {{-- <a
                                                            href="{{ route('diet.plan') }}?patient_id={{ $patient->id }}&patient_name={{ urlencode($patient->patient_name) }}"
                                                            class="btn btn-success btn-sm"> --}}
                                                            <a
                                                                href="{{ route('diet.plan') }}?patient_id={{ $patient->patient_id }}&branch_id={{ $patient->branch_id }}&patient_name={{ urlencode($patient->patient_name) }}">
                                                                <i class="fas fa-plus me-2"></i>Add Diet Plan
                                                            </a>
                                                    </div>
                                                </div>

                                                @if (!empty($dietPlans) && $dietPlans->count() > 0)
                                                    <div class="diet-plans-container">
                                                        @foreach ($dietPlans as $plan)
                                                            <div class="diet-plan-card mb-4">
                                                                <div class="diet-plan-header">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <h5 class="mb-1">
                                                                                <i class="fas fa-utensils me-2"></i>
                                                                                {{ $plan->diet_name }}
                                                                            </h5>
                                                                            <small class="text-muted">
                                                                                <i class="fas fa-calendar me-1"></i>
                                                                                {{ date('d/m/Y', strtotime($plan->date)) }}
                                                                            </small>
                                                                        </div>
                                                                        <div class="diet-plan-actions">
                                                                            <a href="{{ route('diet.plan.print', $plan->id) }}"
                                                                                class="btn btn-sm btn-info print-diet-btn" target="_blank"
                                                                                title="Print Diet Plan">
                                                                                <i class="fas fa-print"></i>
                                                                            </a>
                                                                            <button class="btn btn-sm btn-warning edit-diet-btn"
                                                                                data-plan-id="{{ $plan->id }}" title="Edit Diet Plan">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                            <button class="btn btn-sm btn-danger delete-diet-btn"
                                                                                data-plan-id="{{ $plan->id }}" title="Delete Diet Plan">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                            <button
                                                                                class="btn btn-sm btn-outline-primary toggle-diet-details"
                                                                                data-plan-id="{{ $plan->id }}">
                                                                                <i class="fas fa-chevron-down"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="diet-plan-body" id="diet-plan-{{ $plan->id }}"
                                                                    style="display: none;">
                                                                    @php
                                                                        // Parse time_search_menus correctly
                                                                        $timeSearchMenus = [];
                                                                        if ($plan->time_search_menus) {
                                                                            if (is_string($plan->time_search_menus)) {
                                                                                try {
                                                                                    $timeSearchMenus = json_decode($plan->time_search_menus, true);
                                                                                } catch (Exception $e) {
                                                                                    $timeSearchMenus = [];
                                                                                }
                                                                            } else {
                                                                                $timeSearchMenus = $plan->time_search_menus;
                                                                            }
                                                                        }
                                                                    @endphp

                                                                    @php
                                                                        $filteredMenus = [];
                                                                        if (!empty($timeSearchMenus) && is_array($timeSearchMenus)) {
                                                                            foreach ($timeSearchMenus as $menu) {
                                                                                $items = [];

                                                                                // 1. Check if 'recipes' key exists (new structured format)
                                                                                if (!empty($menu['recipes']) && is_array($menu['recipes'])) {
                                                                                    $items = $menu['recipes'];
                                                                                }
                                                                                // 2. Otherwise try parsing 'selected_recipes' or 'search_menu'
                                                                                else {
                                                                                    $recipesRaw = $menu['selected_recipes'] ?? $menu['search_menu'] ?? '';
                                                                                    if (!empty($recipesRaw)) {
                                                                                        if (is_string($recipesRaw) && (str_starts_with(trim($recipesRaw), '[') || str_starts_with(trim($recipesRaw), '{'))) {
                                                                                            $items = json_decode($recipesRaw, true) ?: [];
                                                                                        } else {
                                                                                            $names = array_filter(array_map('trim', explode(',', $recipesRaw)));
                                                                                            foreach ($names as $name) {
                                                                                                $items[] = ['name' => $name, 'qty' => ''];
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }

                                                                                if (!empty($items)) {
                                                                                    $menu['items_list'] = $items;
                                                                                    $filteredMenus[] = $menu;
                                                                                }
                                                                            }
                                                                        }
                                                                    @endphp

                                                                    @if (!empty($filteredMenus) && count($filteredMenus ?? []) > 0)
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered diet-schedule-table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th width="15%">
                                                                                            <i class="fas fa-clock me-1"></i>Time
                                                                                        </th>
                                                                                        <th width="45%">
                                                                                            <i class="fas fa-drumstick-bite me-1"></i>Menu
                                                                                        </th>
                                                                                        <th width="15%">
                                                                                            <i class="fas fa-sort-amount-up me-1"></i>Quantity
                                                                                        </th>
                                                                                        <th width="25%">
                                                                                            <i class="fas fa-sticky-note me-1"></i>Note
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($filteredMenus as $menu)
                                                                                        <tr>
                                                                                            <td class="time-cell">
                                                                                                @if (!empty($menu['time']))
                                                                                                    <span class="badge bg-success">
                                                                                                        {{ date('g:i A', strtotime($menu['time'])) }}
                                                                                                    </span>
                                                                                                @else
                                                                                                    <span class="text-muted">-</span>
                                                                                                @endif
                                                                                            </td>
                                                                                            <td class="menu-cell">
                                                                                                @if (!empty($menu['items_list']))
                                                                                                    <div class="recipe-list">
                                                                                                        @foreach ($menu['items_list'] as $item)
                                                                                                            <div class="recipe-badge mb-1">
                                                                                                                <i class="fas fa-circle me-1"></i>
                                                                                                                {{ is_array($item) ? ($item['name'] ?? '') : $item }}
                                                                                                            </div>
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                @else
                                                                                                    <span class="text-muted">No items specified</span>
                                                                                                @endif
                                                                                            </td>
                                                                                            <td class="quantity-cell">
                                                                                                @if (!empty($menu['items_list']))
                                                                                                    <div class="quantity-list">
                                                                                                        @foreach ($menu['items_list'] as $item)
                                                                                                            <div class="quantity-badge mb-1">
                                                                                                                @php
                                                                                                                    $qty = is_array($item) ? ($item['qty'] ?? '') : '';
                                                                                                                    // If per-item qty is empty, fallback to legacy row-level qty
                                                                                                                    if (empty($qty) && !empty($menu['quantity'])) {
                                                                                                                        $qty = $menu['quantity'];
                                                                                                                    }
                                                                                                                @endphp
                                                                                                                {{ !empty($qty) ? $qty : '-' }}
                                                                                                            </div>
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                @elseif(!empty($menu['quantity']))
                                                                                                    <span>{{ $menu['quantity'] }}</span>
                                                                                                @else
                                                                                                    <span class="text-muted">-</span>
                                                                                                @endif
                                                                                            </td>
                                                                                            <td class="notes-cell">
                                                                                                @if (!empty($menu['items_list']))
                                                                                                    <div class="notes-list">
                                                                                                        @foreach ($menu['items_list'] as $item)
                                                                                                            <div class="note-badge mb-1">
                                                                                                                @php
                                                                                                                    $note = is_array($item) ? ($item['notes'] ?? '') : '';
                                                                                                                    // Fallback to row-level note
                                                                                                                    if (empty($note) && !empty($menu['notes'])) {
                                                                                                                        $note = $menu['notes'];
                                                                                                                    }
                                                                                                                @endphp
                                                                                                                <small>{{ !empty($note) ? $note : '-' }}</small>
                                                                                                            </div>
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                @elseif(!empty($menu['notes']))
                                                                                                    <small>{{ $menu['notes'] }}</small>
                                                                                                @else
                                                                                                    <span class="text-muted">-</span>
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    @else
                                                                        <div class="text-center text-muted py-3">
                                                                            <i class="fas fa-info-circle me-2"></i>
                                                                            No meal schedule available for this diet plan
                                                                        </div>
                                                                    @endif

                                                                    @if (!empty($plan->diet) || !empty($plan->exercise) || !empty($plan->sleep) || !empty($plan->water))
                                                                        <div class="health-metrics mt-3">
                                                                            <h6 class="mb-2">
                                                                                <i class="fas fa-heartbeat me-2"></i>Health Metrics:
                                                                            </h6>
                                                                            <div class="row bg-light p-2 rounded">
                                                                                @if(!empty($plan->diet))
                                                                                    <div class="col-md-3"><strong>Diet:</strong> {{ $plan->diet }}
                                                                                    </div>
                                                                                @endif
                                                                                @if(!empty($plan->exercise))
                                                                                    <div class="col-md-3"><strong>Exercise:</strong>
                                                                                        {{ $plan->exercise }}</div>
                                                                                @endif
                                                                                @if(!empty($plan->sleep))
                                                                                    <div class="col-md-3"><strong>Sleep:</strong> {{ $plan->sleep }}
                                                                                    </div>
                                                                                @endif
                                                                                @if(!empty($plan->water))
                                                                                    <div class="col-md-3"><strong>Water:</strong> {{ $plan->water }}
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if (!empty($plan->general_notes))
                                                                        <div class="general-notes mt-3">
                                                                            <h6 class="mb-2">
                                                                                <i class="fas fa-clipboard me-2"></i>General Notes:
                                                                            </h6>
                                                                            <div class="notes-content">
                                                                                {{ $plan->general_notes }}
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if (!empty($plan->next_follow_up_date))
                                                                        <div class="follow-up-date mt-3">
                                                                            <span class="badge bg-info">
                                                                                <i class="fas fa-calendar-check me-1"></i>
                                                                                Next Follow-up:
                                                                                {{ date('d/m/Y', strtotime($plan->next_follow_up_date)) }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="no-data-message text-center py-5">
                                                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                                        <p class="text-muted">No diet plans created yet</p>
                                                        <a href="{{ route('diet.plan') }}?patient_id={{ $patient->patient_id }}&branch_id={{ $patient->branch_id }}&patient_name={{ urlencode($patient->patient_name) }}"
                                                            class="btn btn-success">
                                                            <i class="fas fa-plus me-2"></i>Create First Diet Plan
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-4 d-flex justify-content-center">
                                            {{ $dietPlans->links() }}
                                        </div>

                                    </div>





                                    <!-- Transformations Section -->

                                    <!-- Transformations Section -->
                                    <div class="treatment_box_container pt-0">
                                        <div class="row mt-5">
                                            <div class="col-12 pl-0">
                                                <div class="card-header mb-2">
                                                    <div class="heading-action">
                                                        <h3 class="bold font-up fnf-title">Transformations</h3>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Before Images Section -->
                                            <div class="col-lg-6 col-12 pl-0">
                                                <div class="card mb-4 mb-md-0">
                                                    <div class="card-body py-2">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                                            <h5 class="mb-0"><b>Before Transformations</b></h5>
                                                            <span class="badge bg-secondary">{{ count($beforeImages ?? []) }}
                                                                images</span>
                                                        </div>

                                                        @if (count($beforeImages ?? []) > 0)
                                                            <div class="transformations-gallery">
                                                                @foreach ($beforeImages as $image)
                                                                    <div class="image-card">
                                                                        <div class="image-card-header">
                                                                            <h5>Before Picture {{ $loop->iteration }}</h5>
                                                                            @if ($image['date'] || $image['weight'] || $image['height'])
                                                                                <div class="image-tags">
                                                                                    @if ($image['date'])
                                                                                        <span class="image-tag">Date:
                                                                                            {{ $image['date'] }}</span>
                                                                                    @endif
                                                                                    @if ($image['weight'])
                                                                                        <span class="image-tag">Weight:
                                                                                            {{ $image['weight'] }} kg</span>
                                                                                    @endif
                                                                                    @if ($image['height'])
                                                                                        <span class="image-tag">Height:
                                                                                            {{ $image['height'] }} cm</span>
                                                                                    @endif
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="image-card-body">
                                                                            <div class="image-container">
                                                                                <img src="{{ $image['path'] }}"
                                                                                    alt="Before Picture {{ $loop->iteration }}"
                                                                                    class="gallery-image" data-bs-toggle="modal"
                                                                                    data-bs-target="#imageModal"
                                                                                    data-image="{{ $image['path'] }}"
                                                                                    data-title="Before Picture {{ $loop->iteration }}"
                                                                                    style="cursor: pointer;">
                                                                            </div>
                                                                            @if ($image['notes'])
                                                                                <div class="image-details mt-2">
                                                                                    <small>{{ $image['notes'] }}</small>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="no-image-placeholder">
                                                                <p>No before images uploaded</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- After Images Section -->
                                            <div class="col-lg-6 col-12 pl-0">
                                                <div class="card mb-4 mb-md-0">
                                                    <div class="card-body py-2">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                                            <h5 class="mb-0"><b>After Transformations</b></h5>
                                                            <span class="badge bg-secondary">{{ count($afterImages ?? []) }}
                                                                images</span>
                                                        </div>

                                                        @if (count($afterImages ?? []) > 0)
                                                            <div class="transformations-gallery">
                                                                @foreach ($afterImages as $image)
                                                                    <div class="image-card">
                                                                        <div class="image-card-header">
                                                                            <h5>After Picture {{ $loop->iteration }}</h5>
                                                                            @if ($image['date'] || $image['weight'] || $image['height'])
                                                                                <div class="image-tags">
                                                                                    @if ($image['date'])
                                                                                        <span class="image-tag">Date:
                                                                                            {{ $image['date'] }}</span>
                                                                                    @endif
                                                                                    @if ($image['weight'])
                                                                                        <span class="image-tag">Weight:
                                                                                            {{ $image['weight'] }} kg</span>
                                                                                    @endif
                                                                                    @if ($image['height'])
                                                                                        <span class="image-tag">Height:
                                                                                            {{ $image['height'] }} cm</span>
                                                                                    @endif
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="image-card-body">
                                                                            <div class="image-container">
                                                                                <img src="{{ $image['path'] }}"
                                                                                    alt="After Picture {{ $loop->iteration }}"
                                                                                    class="gallery-image" data-bs-toggle="modal"
                                                                                    data-bs-target="#imageModal"
                                                                                    data-image="{{ $image['path'] }}"
                                                                                    data-title="After Picture {{ $loop->iteration }}"
                                                                                    style="cursor: pointer;">
                                                                            </div>
                                                                            @if ($image['notes'])
                                                                                <div class="image-details mt-2">
                                                                                    <small>{{ $image['notes'] }}</small>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="no-image-placeholder">
                                                                <p>No after images uploaded</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Image Modal for Zoom -->
                                    <div class="modal fade image-modal" id="imageModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img id="modalImage" src="" class="zoomed-image" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </section>

                                {{-- Unified Zoom Actions Modal (same as SVC profile) --}}
                                <div class="modal fade" id="zoomActionsModal" tabindex="-1" aria-labelledby="zoomActionsModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="max-width: 500px !important;">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="zoomActionsModalLabel">Zoom Meeting Options</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold mb-2 text-primary">Internal Access</label>
                                                    <a href="" id="modalZoomJoinBtn" target="_blank"
                                                        class="btn btn-primary w-100 d-flex align-items-center justify-content-center py-2"
                                                        style="background: var(--color-primary); border: none;">
                                                        <i class="fas fa-video me-2"></i> Join Meeting Now
                                                    </a>
                                                </div>

                                                <hr class="my-4">

                                                <div>
                                                    <label class="form-label fw-bold mb-2 text-primary">Share With Patient</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" id="modalZoomLinkInput" class="form-control" readonly
                                                            style="font-size: 13px; background: var(--bg-main); color: var(--text-primary);">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            onclick="copyModalZoomLink()" title="Copy Link">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </div>

                                                    <a href="" id="modalZoomWaBtn" target="_blank"
                                                        class="btn btn-success w-100 d-flex align-items-center justify-content-center py-2"
                                                        style="background: #25D366; border: none; color: white;">
                                                        <i class="fab fa-whatsapp me-2"></i> Share via WhatsApp
                                                    </a>
                                                    <p class="text-muted small mt-2 text-center">
                                                        <i class="fas fa-info-circle me-1"></i> This will open WhatsApp with a
                                                        pre-filled message.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ========== EDIT PROGRESS REPORT MODALS ========== -->





                                <!-- Edit Progress Report Modal -->
                                <div class="modal fade" id="editProgressReportModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                style="background: var(--bg-main); color: var(--text-primary); border-bottom: 2px solid var(--accent-solid);">
                                                <h5 class="modal-title">Edit Progress Report</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form id="editProgressReportForm">
                                                @csrf
                                                <input type="hidden" name="report_id" id="edit_progress_report_id">
                                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                                <input type="hidden" name="report_type" value="progress">

                                                <div class="modal-body">
                                                    <div class="program-form-section">
                                                        <h3 class="section-title">
                                                            <i class="fas fa-user"></i>
                                                            Patient Information
                                                        </h3>
                                                        <div class="payment-grid">
                                                            <div class="payment-field">
                                                                <label>FNF ST</label>
                                                                <input type="text" class="payment-input"
                                                                    value="{{ $patient->patient_id }}" disabled>
                                                            </div>
                                                            <div class="payment-field">
                                                                <label>Name</label>
                                                                <input type="text" class="payment-input"
                                                                    value="{{ $patient->patient_name }}" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="program-form-section">
                                                        <h3 class="section-title">
                                                            <i class="fas fa-calendar-alt"></i>
                                                            Date & Time
                                                        </h3>
                                                        <div class="payment-grid">
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_date">Date</label>
                                                                <input type="date" class="payment-input"
                                                                    id="edit_progress_report_date" name="date" required>
                                                            </div>
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_time">Time</label>
                                                                <input type="time" class="payment-input"
                                                                    id="edit_progress_report_time" name="time" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="program-form-section">
                                                        <h3 class="section-title">
                                                            <i class="fas fa-stethoscope"></i>
                                                            Program & Treatment Details
                                                        </h3>
                                                        <div class="payment-grid">
                                                            <div class="payment-field">
                                                                <label for="edit_face_program">Program Name</label>
                                                                <input type="text" class="payment-input" id="edit_face_program"
                                                                    name="face_program">
                                                            </div>
                                                            <div class="payment-field">
                                                                <label for="edit_body_part">Body Part & Functions</label>
                                                                <textarea class="payment-input" id="edit_body_part" name="body_part"
                                                                    rows="2"></textarea>
                                                            </div>
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_medication">Medication</label>
                                                                <textarea class="payment-input" id="edit_progress_report_medication"
                                                                    name="medication" rows="2"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="program-form-section">
                                                        <h3 class="section-title">
                                                            <i class="fas fa-heartbeat"></i>
                                                            Vital Signs
                                                        </h3>
                                                        <div class="payment-grid">
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_bp">BP</label>
                                                                <input type="text" class="payment-input"
                                                                    id="edit_progress_report_bp" name="bp_p">
                                                            </div>
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_pulse">Pulse</label>
                                                                <input type="text" class="payment-input"
                                                                    id="edit_progress_report_pulse" name="pulse">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="program-form-section">
                                                        <h3 class="section-title">
                                                            <i class="fas fa-weight-scale"></i>
                                                            Measurements
                                                        </h3>
                                                        <div class="payment-grid">
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_weight">Weight (kg)</label>
                                                                <input type="number" class="payment-input"
                                                                    id="edit_progress_report_weight" name="weight" step="0.1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="program-form-section">
                                                        <h3 class="section-title">
                                                            <i class="fas fa-notes-medical"></i>
                                                            Notes
                                                        </h3>
                                                        <div class="payment-grid">
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_councilor">Counselor/Doctor
                                                                    Note</label>
                                                                <textarea class="payment-input" id="edit_progress_report_councilor"
                                                                    name="councilor_doctor" rows="3"></textarea>
                                                            </div>
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_exercise">Exercise</label>
                                                                <textarea class="payment-input" id="edit_progress_report_exercise"
                                                                    name="exercise" rows="3"></textarea>
                                                            </div>
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_diet">Diet</label>
                                                                <textarea class="payment-input" id="edit_progress_report_diet"
                                                                    name="diet" rows="3"></textarea>
                                                            </div>
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_sleep">Sleep</label>
                                                                <textarea class="payment-input" id="edit_progress_report_sleep"
                                                                    name="sleep" rows="3"></textarea>
                                                            </div>
                                                            <div class="payment-field">
                                                                <label for="edit_progress_report_water">Water</label>
                                                                <textarea class="payment-input" id="edit_progress_report_water"
                                                                    name="water" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" id="updateProgressReportBtn">
                                                        <i class="fas fa-save me-2"></i>Update Progress Report
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- ========== DELETE CONFIRMATION MODALS ========== -->

                                <!-- Delete Progress Report Confirmation Modal -->
                                <div class="modal fade" id="deleteProgressReportModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                style="background: rgba(220, 53, 69, 0.1); color: #dc3545; border-bottom: 1px solid rgba(220, 53, 69, 0.2);">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this Progress Report?</p>
                                                <p class="text-muted">This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-danger"
                                                    id="confirmDeleteProgressReportBtn">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endif

                        <!-- Call History Section -->
                        @php
                            $callLogs = \App\Models\CallLog::where('inquiry_id', $patient->id)->latest()->get();
                        @endphp
                        <div class="program-form-section call-history-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="section-title mb-0">
                                    <i class="fas fa-phone-alt"></i>
                                    Call History
                                </h3>
                                <a href="{{ route('call.log.create', $patient->id) }}" class="add_call_record">
                                    <i class="fas fa-plus"></i> Record Call
                                </a>
                            </div>

                            @if($callLogs->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="profile-table">
                                        <thead>
                                            <th>Date & Time</th>
                                            <th>Branch</th>
                                            <th>Time Slot</th>
                                            <th>Recorded By</th>
                                            <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($callLogs as $log)
                                                <tr>
                                                    <td>
                                                        {{ date('d/m/Y', strtotime($log->call_date)) }}<br>
                                                        <small
                                                            class="text-muted">{{ date('h:i A', strtotime($log->call_time)) }}</small>
                                                    </td>
                                                    <td>{{ $log->branch }}</td>
                                                    <td>
                                                        @php
                                                            $slotClass = 'slot-' . strtolower($log->time_slot);
                                                        @endphp
                                                        <span class="time-slot-badge {{ $slotClass }}">
                                                            {{ ucfirst($log->time_slot) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $log->user->name ?? 'N/A' }}</td>
                                                    <td>{{ $log->remarks ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4 bg-light rounded">
                                    <p class="text-muted mb-0">No call records found for this patient.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Payment Program Modal -->
    <div class="modal fade payment-edit-modal" id="editPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: var(--bg-main); color: var(--text-primary); border-bottom: 2px solid var(--accent-solid);">
                    <h5 class="modal-title">Edit Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPaymentForm">
                    @csrf
                    <input type="hidden" name="patient_id" id="edit_payment_patient_id">
                    <input type="hidden" name="payment_index" id="edit_payment_index">

                    <div class="modal-body">
                        <div class="program-form-section">
                            <h3 class="section-title">
                                <i class="fas fa-calendar-alt"></i>
                                Program Details
                            </h3>
                            <div class="payment-grid">
                                <div class="payment-field">
                                    <label for="edit_program_name">Program Name</label>
                                    <input type="text" class="payment-input" id="edit_program_name" name="program_name">
                                </div>

                                <div class="payment-field">
                                    <label for="edit_session">Session</label>
                                    <input type="text" class="payment-input" id="edit_session" name="session">
                                </div>

                                <div class="payment-field">
                                    <label for="edit_months">Months</label>
                                    <input type="text" class="payment-input" id="edit_months" name="months">
                                </div>

                                <div class="payment-field">
                                    <label for="edit_payment_date">Payment Date</label>
                                    <input type="date" class="payment-input" id="edit_payment_date" name="payment_date">
                                </div>
                            </div>
                        </div>

                        <div class="program-form-section">
                            <h3 class="section-title">
                                <i class="fas fa-credit-card"></i>
                                Payment Details
                            </h3>
                            <div class="payment-grid">
                                <div class="payment-field">
                                    <label for="edit_total">Total Amount</label>
                                    <div class="payment-input-group">
                                        <input type="number" class="payment-input" id="edit_total" name="total" step="0.01">
                                        <span class="currency">₹</span>
                                    </div>
                                </div>

                                <div class="payment-field">
                                    <label for="edit_discount">Discount</label>
                                    <div class="payment-input-group">
                                        <input type="number" class="payment-input" id="edit_discount" name="discount"
                                            step="0.01">
                                        <span class="currency">₹</span>
                                    </div>
                                </div>

                                <div class="payment-field">
                                    <label for="edit_cash">Cash</label>
                                    <div class="payment-input-group">
                                        <input type="number" class="payment-input" id="edit_cash" name="cash_payment" step="0.01" placeholder="0.00">
                                        <span class="currency">₹</span>
                                    </div>
                                </div>

                                <div class="payment-field">
                                    <label for="edit_gpay">G-Pay</label>
                                    <div class="payment-input-group">
                                        <input type="number" class="payment-input" id="edit_gpay" name="gpay_payment" step="0.01" placeholder="0.00">
                                        <span class="currency">₹</span>
                                    </div>
                                </div>

                                <div class="payment-field">
                                    <label for="edit_cheque">Cheque</label>
                                    <div class="payment-input-group">
                                        <input type="number" class="payment-input" id="edit_cheque" name="cheque_payment" step="0.01" placeholder="0.00">
                                        <span class="currency">₹</span>
                                    </div>
                                </div>

                                <div class="payment-field">
                                    <label for="edit_given">Paid Amount</label>
                                    <div class="payment-input-group">
                                        <input type="number" class="payment-input" id="edit_given" name="given" step="0.01" readonly style="background-color: #f8f9fa;">
                                        <span class="currency">₹</span>
                                    </div>
                                </div>

                                <div class="payment-field">
                                    <label for="edit_due">Due Amount</label>
                                    <div class="payment-input-group">
                                        <input type="number" class="payment-input" id="edit_due" name="due" step="0.01"
                                            readonly>
                                        <span class="currency">₹</span>
                                    </div>
                                </div>

                                <div class="payment-field">
                                    <label for="edit_due_date">Due Date</label>
                                    <input type="date" class="payment-input" id="edit_due_date" name="due_date">
                                </div>
                            </div>
                        </div>

                        <div class="program-form-section">
                            <h3 class="section-title">
                                <i class="fas fa-file-invoice"></i>
                                Payment Status
                            </h3>
                            <div class="payment-grid">
                                <div class="payment-field">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_payment_status"
                                            name="payment_status" value="1">
                                        <label class="form-check-label" for="edit_payment_status">
                                            Mark as Paid
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updatePaymentBtn">
                            <i class="fas fa-save me-2"></i>Update Program
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Payment Program Confirmation Modal -->
    <div class="modal fade" id="deletePaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: rgba(220, 53, 69, 0.1); color: #dc3545; border-bottom: 1px solid rgba(220, 53, 69, 0.2);">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this payment program?</p>
                    <p id="deleteProgramDetails" class="text-muted"></p>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeletePaymentBtn">Delete Program</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Monthly Assessment Modal -->
    <div class="modal fade" id="addAssessmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: var(--bg-main); color: var(--text-primary); border-bottom: 2px solid var(--accent-solid);">
                    <h5 class="modal-title">Add Monthly Assessment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addAssessmentForm">
                    @csrf
                    <input type="hidden" name="branch_id" value="{{ $patient->branch_id }}">
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    <input type="hidden" name="patient_code" value="{{ $patient->patient_id }}">
                    <input type="hidden" name="status" value="submitted">

                    <div class="modal-body">
                        <div class="assessment-edit-container">
                            <!-- Patient Information Section -->
                            <div class="program-form-section mb-4">
                                <h3 class="section-title">
                                    <i class="fas fa-user"></i>
                                    Patient Information
                                </h3>
                                <div class="payment-grid">
                                    <div class="payment-field">
                                        <label>FNF ST</label>
                                        <input type="text" class="payment-input" value="{{ $patient->patient_id }}"
                                            disabled>
                                    </div>
                                    <div class="payment-field">
                                        <label>Name</label>
                                        <input type="text" class="payment-input" value="{{ $patient->patient_name }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- Header Section -->
                            <div class="header-section mb-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="add_assessment_date" class="form-label">Date</label>
                                            <input type="date" class="form-control" id="add_assessment_date"
                                                name="assessment_date" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 1: Measurement -->
                            <div class="section-card mb-4">
                                <h2 class="section-header">
                                    <span class="section-number">1</span>
                                    Measurement
                                </h2>
                                <div class="measurement-grid">
                                    @php
                                        $addMeasurements = [
                                            [
                                                'name' => 'Waist Upper',
                                                'unit' => 'cm',
                                                'icon' => 'fa-ruler-horizontal',
                                                'field' => 'waist_upper',
                                            ],
                                            [
                                                'name' => 'Waist Middle',
                                                'unit' => 'cm',
                                                'icon' => 'fa-ruler-horizontal',
                                                'field' => 'waist_middle',
                                            ],
                                            [
                                                'name' => 'Waist Lower',
                                                'unit' => 'cm',
                                                'icon' => 'fa-ruler-horizontal',
                                                'field' => 'waist_lower',
                                            ],
                                            [
                                                'name' => 'Hips',
                                                'unit' => 'cm',
                                                'icon' => 'fa-person-dress',
                                                'field' => 'hips',
                                            ],
                                            [
                                                'name' => 'Thighs',
                                                'unit' => 'cm',
                                                'icon' => 'fa-person-walking',
                                                'field' => 'thighs',
                                            ],
                                            [
                                                'name' => 'Arms',
                                                'unit' => 'cm',
                                                'icon' => 'fa-hand',
                                                'field' => 'arms',
                                            ],
                                            [
                                                'name' => 'Waist/Hips',
                                                'unit' => 'ratio',
                                                'icon' => 'fa-divide',
                                                'field' => 'waist_hips',
                                                'step' => 'any',
                                            ],
                                            [
                                                'name' => 'Weight',
                                                'unit' => 'kg',
                                                'icon' => 'fa-weight-scale',
                                                'field' => 'weight',
                                            ],
                                            [
                                                'name' => 'BMI',
                                                'unit' => 'kg/m²',
                                                'icon' => 'fa-chart-line',
                                                'field' => 'bmi',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($addMeasurements as $item)
                                        <div class="measurement-item">
                                            <div class="measurement-label">
                                                <i class="fas {{ $item['icon'] }} me-2"></i>
                                                {{ $item['name'] }}
                                            </div>
                                            <div class="measurement-input-group">
                                                <input type="number" class="measurement-input" placeholder="0.0" step="{{ $item['step'] ?? '0.1' }}"
                                                    name="measurements[{{ $item['field'] }}]">
                                                <span class="measurement-unit">{{ $item['unit'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Section 2: BCA Subcutaneous Fat -->
                            <div class="section-card mb-4">
                                <h2 class="section-header">
                                    <span class="section-number">2</span>
                                    BCA Subcutaneous Fat
                                </h2>
                                <div class="measurement-grid">
                                    @php
                                        $addBcaMeasurements = [
                                            [
                                                'name' => 'VBF',
                                                'unit' => '%',
                                                'icon' => 'fa-percent',
                                                'field' => 'bca_vbf',
                                            ],
                                            [
                                                'name' => 'Arms',
                                                'unit' => '%',
                                                'icon' => 'fa-hand',
                                                'field' => 'bca_arms',
                                            ],
                                            [
                                                'name' => 'Trunk',
                                                'unit' => '%',
                                                'icon' => 'fa-user',
                                                'field' => 'bca_trunk',
                                            ],
                                            [
                                                'name' => 'Legs',
                                                'unit' => '%',
                                                'icon' => 'fa-person-walking',
                                                'field' => 'bca_legs',
                                            ],
                                            [
                                                'name' => 'T.F.',
                                                'unit' => '%',
                                                'icon' => 'fa-droplet',
                                                'field' => 'bca_sf',
                                            ],
                                            [
                                                'name' => 'V.F.',
                                                'unit' => '%',
                                                'icon' => 'fa-droplet',
                                                'field' => 'bca_vf',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($addBcaMeasurements as $item)
                                        <div class="measurement-item">
                                            <div class="measurement-label">
                                                <i class="fas {{ $item['icon'] }} me-2"></i>
                                                {{ $item['name'] }}
                                            </div>
                                            <div class="measurement-input-group">
                                                <input type="number" class="measurement-input" placeholder="0.0" step="0.1"
                                                    name="measurements[{{ $item['field'] }}]">
                                                <span class="measurement-unit">{{ $item['unit'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Section 3: Skeletal Muscle Mass -->
                            <div class="section-card mb-4">
                                <h2 class="section-header">
                                    <span class="section-number">3</span>
                                    Skeletal Muscle Mass
                                </h2>
                                <div class="measurement-grid">
                                    @php
                                        $addMuscleMeasurements = [
                                            [
                                                'name' => 'VBF',
                                                'unit' => 'kg',
                                                'icon' => 'fa-weight-hanging',
                                                'field' => 'muscle_vbf',
                                            ],
                                            [
                                                'name' => 'Arms',
                                                'unit' => 'kg',
                                                'icon' => 'fa-hand-fist',
                                                'field' => 'muscle_arms',
                                            ],
                                            [
                                                'name' => 'Trunk',
                                                'unit' => 'kg',
                                                'icon' => 'fa-user',
                                                'field' => 'muscle_trunk',
                                            ],
                                            [
                                                'name' => 'Legs',
                                                'unit' => 'kg',
                                                'icon' => 'fa-person-running',
                                                'field' => 'muscle_legs',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($addMuscleMeasurements as $item)
                                        <div class="measurement-item">
                                            <div class="measurement-label">
                                                <i class="fas {{ $item['icon'] }} me-2"></i>
                                                {{ $item['name'] }}
                                            </div>
                                            <div class="measurement-input-group">
                                                <input type="number" class="measurement-input" placeholder="0.0" step="0.1"
                                                    name="measurements[{{ $item['field'] }}]">
                                                <span class="measurement-unit">{{ $item['unit'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="saveAssessmentBtn">
                            <i class="fas fa-save me-2"></i>Save Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Monthly Assessment Modal -->
    <div class="modal fade" id="editAssessmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: var(--bg-main); color: var(--text-primary); border-bottom: 2px solid var(--accent-solid);">
                    <h5 class="modal-title">Edit Monthly Assessment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editAssessmentForm">
                    @csrf
                    <input type="hidden" name="assessment_id" id="edit_assessment_id">
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    <div class="modal-body">
                        <div class="assessment-edit-container">
                            <!-- Header Section -->
                            <div class="header-section mb-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_assessment_date" class="form-label">Date</label>
                                            <input type="date" class="form-control" id="edit_assessment_date"
                                                name="assessment_date" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 1: Measurement -->
                            <div class="section-card mb-4">
                                <h2 class="section-header">
                                    <span class="section-number">1</span>
                                    Measurement
                                </h2>
                                <div class="measurement-grid">
                                    @php
                                        $measurements = [
                                            [
                                                'name' => 'Waist Upper',
                                                'unit' => 'cm',
                                                'icon' => 'fa-ruler-horizontal',
                                                'id' => 'edit_waist_upper',
                                                'field' => 'waist_upper',
                                            ],
                                            [
                                                'name' => 'Waist Middle',
                                                'unit' => 'cm',
                                                'icon' => 'fa-ruler-horizontal',
                                                'id' => 'edit_waist_middle',
                                                'field' => 'waist_middle',
                                            ],
                                            [
                                                'name' => 'Waist Lower',
                                                'unit' => 'cm',
                                                'icon' => 'fa-ruler-horizontal',
                                                'id' => 'edit_waist_lower',
                                                'field' => 'waist_lower',
                                            ],
                                            [
                                                'name' => 'Hips',
                                                'unit' => 'cm',
                                                'icon' => 'fa-person-dress',
                                                'id' => 'edit_hips',
                                                'field' => 'hips',
                                            ],
                                            [
                                                'name' => 'Thighs',
                                                'unit' => 'cm',
                                                'icon' => 'fa-person-walking',
                                                'id' => 'edit_thighs',
                                                'field' => 'thighs',
                                            ],
                                            [
                                                'name' => 'Arms',
                                                'unit' => 'cm',
                                                'icon' => 'fa-hand',
                                                'id' => 'edit_arms',
                                                'field' => 'arms',
                                            ],
                                            [
                                                'name' => 'Waist/Hips',
                                                'unit' => 'ratio',
                                                'icon' => 'fa-divide',
                                                'id' => 'edit_waist_hips',
                                                'field' => 'waist_hips_ratio',
                                                'step' => 'any',
                                            ],
                                            [
                                                'name' => 'Weight',
                                                'unit' => 'kg',
                                                'icon' => 'fa-weight-scale',
                                                'id' => 'edit_weight',
                                                'field' => 'weight',
                                            ],
                                            [
                                                'name' => 'BMI',
                                                'unit' => 'kg/m²',
                                                'icon' => 'fa-chart-line',
                                                'id' => 'edit_bmi',
                                                'field' => 'bmi',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($measurements as $item)
                                        <div class="measurement-item">
                                            <div class="measurement-label">
                                                <i class="fas {{ $item['icon'] }} me-2"></i>
                                                {{ $item['name'] }}
                                            </div>
                                            <div class="measurement-input-group">
                                                <input type="number" class="measurement-input" placeholder="0.0" step="{{ $item['step'] ?? '0.1' }}"
                                                    id="{{ $item['id'] }}" name="{{ $item['field'] }}">
                                                <span class="measurement-unit">{{ $item['unit'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Section 2: BCA Subcutaneous Fat -->
                            <div class="section-card mb-4">
                                <h2 class="section-header">
                                    <span class="section-number">2</span>
                                    BCA Subcutaneous Fat
                                </h2>
                                <div class="measurement-grid">
                                    @php
                                        $bcaMeasurements = [
                                            [
                                                'name' => 'VBF',
                                                'unit' => '%',
                                                'icon' => 'fa-percent',
                                                'id' => 'edit_bca_vbf',
                                                'field' => 'bca_vbf',
                                            ],
                                            [
                                                'name' => 'Arms',
                                                'unit' => '%',
                                                'icon' => 'fa-hand',
                                                'id' => 'edit_bca_arms',
                                                'field' => 'bca_arms',
                                            ],
                                            [
                                                'name' => 'Trunk',
                                                'unit' => '%',
                                                'icon' => 'fa-user',
                                                'id' => 'edit_bca_trunk',
                                                'field' => 'bca_trunk',
                                            ],
                                            [
                                                'name' => 'Legs',
                                                'unit' => '%',
                                                'icon' => 'fa-person-walking',
                                                'id' => 'edit_bca_legs',
                                                'field' => 'bca_legs',
                                            ],
                                            [
                                                'name' => 'T.F.',
                                                'unit' => '%',
                                                'icon' => 'fa-droplet',
                                                'id' => 'edit_bca_sf',
                                                'field' => 'bca_sf',
                                            ],
                                            [
                                                'name' => 'V.F.',
                                                'unit' => '%',
                                                'icon' => 'fa-droplet',
                                                'id' => 'edit_bca_vf',
                                                'field' => 'bca_vf',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($bcaMeasurements as $item)
                                        <div class="measurement-item">
                                            <div class="measurement-label">
                                                <i class="fas {{ $item['icon'] }} me-2"></i>
                                                {{ $item['name'] }}
                                            </div>
                                            <div class="measurement-input-group">
                                                <input type="number" class="measurement-input" placeholder="0.0" step="0.1"
                                                    id="{{ $item['id'] }}" name="{{ $item['field'] }}">
                                                <span class="measurement-unit">{{ $item['unit'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Section 3: Skeletal Muscle Mass -->
                            <div class="section-card mb-4">
                                <h2 class="section-header">
                                    <span class="section-number">3</span>
                                    Skeletal Muscle Mass
                                </h2>
                                <div class="measurement-grid">
                                    @php
                                        $muscleMeasurements = [
                                            [
                                                'name' => 'VBF',
                                                'unit' => 'kg',
                                                'icon' => 'fa-weight-hanging',
                                                'id' => 'edit_muscle_vbf',
                                                'field' => 'muscle_vbf',
                                            ],
                                            [
                                                'name' => 'Arms',
                                                'unit' => 'kg',
                                                'icon' => 'fa-hand-fist',
                                                'id' => 'edit_muscle_arms',
                                                'field' => 'muscle_arms',
                                            ],
                                            [
                                                'name' => 'Trunk',
                                                'unit' => 'kg',
                                                'icon' => 'fa-user',
                                                'id' => 'edit_muscle_trunk',
                                                'field' => 'muscle_trunk',
                                            ],
                                            [
                                                'name' => 'Legs',
                                                'unit' => 'kg',
                                                'icon' => 'fa-person-running',
                                                'id' => 'edit_muscle_legs',
                                                'field' => 'muscle_legs',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($muscleMeasurements as $item)
                                        <div class="measurement-item">
                                            <div class="measurement-label">
                                                <i class="fas {{ $item['icon'] }} me-2"></i>
                                                {{ $item['name'] }}
                                            </div>
                                            <div class="measurement-input-group">
                                                <input type="number" class="measurement-input" placeholder="0.0" step="0.1"
                                                    id="{{ $item['id'] }}" name="{{ $item['field'] }}">
                                                <span class="measurement-unit">{{ $item['unit'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateAssessmentBtn">
                            <i class="fas fa-save me-2"></i>Update Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteAssessmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: rgba(220, 53, 69, 0.1); color: #dc3545; border-bottom: 1px solid rgba(220, 53, 69, 0.2);">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this assessment?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDietPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: var(--bg-main); color: var(--text-primary); border-bottom: 2px solid var(--accent-solid);">
                    <h5 class="modal-title">Edit Diet Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDietPlanForm">
                    @csrf
                    <input type="hidden" id="edit_diet_plan_id" name="diet_plan_id">

                    <div class="modal-body">
                        <!-- Date & Diet Name -->
                        <div class="program-form-section">
                            <h3 class="section-title">
                                <i class="fas fa-calendar-alt"></i>
                                Basic Information
                            </h3>
                            <div class="payment-grid">
                                <div class="payment-field">
                                    <label for="edit_diet_date">Date</label>
                                    <input type="date" class="payment-input" id="edit_diet_date" name="date" required>
                                </div>
                                <div class="payment-field">
                                    <label for="edit_diet_name">Diet Name</label>
                                    <input type="text" class="payment-input" id="edit_diet_name" name="diet_name" required>
                                </div>
                            </div>
                        </div>

                        <!-- Time Search Menus Section (Dynamic) -->
                        <div class="program-form-section">
                            <h3 class="section-title">
                                <i class="fas fa-clock"></i>
                                Meal Schedule
                                <button type="button" class="btn btn-sm btn-success ms-2" id="add-new-meal-row">
                                    <i class="fas fa-plus"></i> Add Meal
                                </button>
                            </h3>
                            <div id="edit_time_search_container">
                                <!-- Dynamic rows will be added here -->
                            </div>
                        </div>

                        <!-- General Notes & Follow-up -->
                        <div class="program-form-section">
                            <h3 class="section-title">
                                <i class="fas fa-notes-medical"></i>
                                Additional Information
                            </h3>
                            <div class="payment-grid">
                                <div class="payment-field">
                                    <label for="edit_general_notes">General Notes</label>
                                    <textarea class="payment-input" id="edit_general_notes" name="general_notes"
                                        rows="3"></textarea>
                                </div>
                                <div class="payment-field">
                                    <label for="edit_next_follow_up_date">Next Follow-up Date</label>
                                    <input type="date" class="payment-input" id="edit_next_follow_up_date"
                                        name="next_follow_up_date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateDietPlanBtn">
                            <i class="fas fa-save me-2"></i>Update Diet Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal for Diet Plan -->
    <div class="modal fade" id="deleteDietPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: rgba(220, 53, 69, 0.1); color: #dc3545; border-bottom: 1px solid rgba(220, 53, 69, 0.2);">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this diet plan?</p>
                    <p id="deleteDietPlanDetails" class="text-muted"></p>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteDietPlanBtn">Delete Diet
                        Plan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & jQuery -->
    {{--
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{--
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    <!-- Updated JavaScript Section -->
    <script>
        function openZoomModal(startUrl, joinUrl, waUrl) {
            const joinBtn = document.getElementById('modalZoomJoinBtn');
            const waBtn = document.getElementById('modalZoomWaBtn');
            const linkInput = document.getElementById('modalZoomLinkInput');

            if (joinBtn) joinBtn.href = startUrl;
            if (waBtn) waBtn.href = waUrl;
            if (linkInput) linkInput.value = joinUrl;

            const modalEl = document.getElementById('zoomActionsModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        }

        function copyModalZoomLink() {
            const linkInput = document.getElementById('modalZoomLinkInput');
            if (!linkInput) return;
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            document.execCommand('copy');
        }
        $(document).ready(function () {
            // Global variables
            let currentDietPlanId = null;
            let currentAssessmentId = null;
            let currentPaymentPatientId = null;
            let currentPaymentIndex = null;
            let currentPaymentOriginalIndex = null;
            let paymentPrograms = @json($programDetails ?? []);
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Report type specific variables
            let currentProgressReportId = null;

            // Debug: Log data
            console.log('CSRF Token:', csrfToken);
            console.log('Payment Programs:', paymentPrograms);
            console.log('Number of programs:', paymentPrograms.length);

            // ========== HELPER FUNCTIONS ==========

            // Helper function to show alerts
            function showAlert(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertHtml = `
                                                                                <div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 9999;">
                                                                                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} me-2"></i>
                                                                                    ${message}
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                                                </div>
                                                                            `;
                $('body').append(alertHtml);

                // Auto remove after 3 seconds
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 3000);
            }

            // Calculate due amount
            function calculateDueAmount() {
                const total = parseFloat($('#edit_total').val()) || 0;
                const discount = parseFloat($('#edit_discount').val()) || 0;
                const given = parseFloat($('#edit_given').val()) || 0;

                const due = Math.max(0, total - discount - given);
                $('#edit_due').val(due.toFixed(2));
            }

            function calculateGivenAmountFromSplit() {
                const cash = parseFloat($('#edit_cash').val()) || 0;
                const gpay = parseFloat($('#edit_gpay').val()) || 0;
                const cheque = parseFloat($('#edit_cheque').val()) || 0;
                
                const totalGiven = cash + gpay + cheque;
                $('#edit_given').val(totalGiven.toFixed(2));
                calculateDueAmount();
            }

            // Add CSS for loading spinner
            if (!$('#loading-spinner-style').length) {
                $('head').append(`
                                                                                <style id="loading-spinner-style">
                                                                                    .loading-spinner {
                                                                                        display: inline-block;
                                                                                        width: 16px;
                                                                                        height: 16px;
                                                                                        border: 2px solid #f3f3f3;
                                                                                        border-top: 2px solid #086838;
                                                                                        border-radius: 50%;
                                                                                        animation: spin 1s linear infinite;
                                                                                        margin-right: 8px;
                                                                                    }

                                                                                    @keyframes spin {
                                                                                        0% { transform: rotate(0deg); }
                                                                                        100% { transform: rotate(360deg); }
                                                                                    }

                                                                                    .diet-plan-actions {
                                                                                        display: flex;
                                                                                        gap: 5px;
                                                                                    }

                                                                                    .diet-plan-actions .btn {
                                                                                        width: 32px;
                                                                                        height: 32px;
                                                                                        display: flex;
                                                                                        align-items: center;
                                                                                        justify-content: center;
                                                                                        padding: 0;
                                                                                    }

                                                                                    .diet-plan-actions .btn i {
                                                                                        font-size: 12px;
                                                                                    }

                                                                                    .meal-row {
                                                                                        background: #f8f9fa;
                                                                                        transition: all 0.3s ease;
                                                                                    }

                                                                                    .meal-row:hover {
                                                                                        background: #e9ecef;
                                                                                    }

                                                                                    .meal-row .remove-meal-row {
                                                                                        width: 24px;
                                                                                        height: 24px;
                                                                                        padding: 0;
                                                                                        display: flex;
                                                                                        align-items: center;
                                                                                        justify-content: center;
                                                                                    }

                                                                                    .meal-row .remove-meal-row:disabled {
                                                                                        opacity: 0.5;
                                                                                        cursor: not-allowed;
                                                                                    }

                                                                                    .rotate-icon {
                                                                                        transform: rotate(180deg);
                                                                                        transition: transform 0.3s ease;
                                                                                    }
                                                                                </style>
                                                                            `);
            }

            // ========== TOGGLE FUNCTIONALITY ==========

            // Toggle diet plan details
            $(document).on('click', '.toggle-diet-details', function () {
                const planId = $(this).data('plan-id');
                const detailsDiv = $('#diet-plan-' + planId);
                const icon = $(this).find('i');

                if (detailsDiv.is(':visible')) {
                    detailsDiv.slideUp(300);
                    icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                    $(this).removeClass('active');
                } else {
                    detailsDiv.slideDown(300);
                    icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                    $(this).addClass('active');
                }
            });

            // ========== IMAGE GALLERY FUNCTIONALITY ==========
            const patientDetailsHeader = document.getElementById('patientDetailsHeader');
            const patientDetailsContent = document.getElementById('patientDetailsContent');
            const toggleIcon = document.getElementById('toggleIcon');

            if (patientDetailsHeader && patientDetailsContent && toggleIcon) {
                patientDetailsHeader.addEventListener('click', function () {
                    if (patientDetailsContent.style.display === 'none') {
                        patientDetailsContent.style.display = 'block';
                        toggleIcon.classList.add('rotate-icon');
                    } else {
                        patientDetailsContent.style.display = 'none';
                        toggleIcon.classList.remove('rotate-icon');
                    }
                });
            }

            // Diet History Toggle
            const dietHistoryHeader = document.getElementById('dietHistoryHeader');
            const dietHistoryContent = document.getElementById('dietHistoryContent');
            const dietHistoryToggleIcon = document.getElementById('dietHistoryToggleIcon');

            if (dietHistoryHeader && dietHistoryContent && dietHistoryToggleIcon) {
                dietHistoryHeader.addEventListener('click', function () {
                    if (dietHistoryContent.style.display === 'none') {
                        dietHistoryContent.style.display = 'block';
                        dietHistoryToggleIcon.classList.add('rotate-icon');
                    } else {
                        dietHistoryContent.style.display = 'none';
                        dietHistoryToggleIcon.classList.remove('rotate-icon');
                    }
                });
            }

            // ========== IMAGE GALLERY FUNCTIONALITY ==========

            $(document).on('click', '.gallery-image', function () {
                const imageUrl = $(this).data('image');
                const imageTitle = $(this).data('title');

                $('#imageModalLabel').text(imageTitle);
                $('#modalImage').attr('src', imageUrl);
            });

            // ========== DIET PLAN FUNCTIONALITY ==========

            // Edit diet plan button click — single handler only
            $(document).on('click', '.edit-diet-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                currentDietPlanId = $(this).data('plan-id');
                console.log('Edit diet plan clicked:', currentDietPlanId);

                // Fetch data FIRST, then show modal once data is ready
                fetchDietPlanData(currentDietPlanId, function () {
                    const editModal = new bootstrap.Modal(document.getElementById('editDietPlanModal'));
                    editModal.show();
                });
            });

            // Fetch diet plan data for editing
            function fetchDietPlanData(dietPlanId, onReady) {
                console.log('Fetching diet plan data for ID:', dietPlanId);

                // Show loading state
                $('#updateDietPlanBtn').html('<span class="loading-spinner"></span>Loading...');
                $('#updateDietPlanBtn').prop('disabled', true);

                $.ajax({
                    url: '/diet-plan/' + dietPlanId + '/edit',
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Diet plan data response:', response);
                        if (response.success) {
                            populateDietEditForm(response.data);
                            $('#updateDietPlanBtn').html('<i class="fas fa-save me-2"></i>Update Diet Plan');
                            $('#updateDietPlanBtn').prop('disabled', false);
                            // Show modal AFTER data is populated
                            if (typeof onReady === 'function') onReady();
                        } else {
                            alert('Failed to load diet plan data: ' + (response.message || 'Unknown error'));
                            $('#editDietPlanModal').modal('hide');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching diet plan data:', error, xhr.responseText);
                        alert('Error loading diet plan data. Check console for details.');
                        $('#editDietPlanModal').modal('hide');
                    }
                });
            }

            // Populate diet plan edit form
            function populateDietEditForm(data) {
                console.log('Populating diet form with data:', data);

                // Set basic fields
                $('#edit_diet_plan_id').val(data.id);

                // date cast returns ISO string like "2024-07-26T00:00:00.000000Z"
                // <input type="date"> only accepts YYYY-MM-DD — extract just the date part
                if (data.date) {
                    const dateOnly = data.date.toString().split('T')[0].substring(0, 10);
                    $('#edit_diet_date').val(dateOnly);
                } else {
                    $('#edit_diet_date').val('');
                }

                $('#edit_diet_name').val(data.diet_name);
                $('#edit_general_notes').val(data.general_notes || '');

                // Same fix for next_follow_up_date
                if (data.next_follow_up_date) {
                    const nfuDate = data.next_follow_up_date.toString().split('T')[0].substring(0, 10);
                    $('#edit_next_follow_up_date').val(nfuDate);
                } else {
                    $('#edit_next_follow_up_date').val('');
                }

                // Clear and populate time search menus
                const container = $('#edit_time_search_container');
                container.empty();

                let timeSearchMenus = [];

                // Parse time_search_menus
                if (data.time_search_menus) {
                    if (typeof data.time_search_menus === 'string') {
                        try {
                            timeSearchMenus = JSON.parse(data.time_search_menus);
                        } catch (e) {
                            console.error('Error parsing time_search_menus:', e);
                            timeSearchMenus = [];
                        }
                    } else {
                        timeSearchMenus = data.time_search_menus;
                    }
                }

                console.log('Time search menus:', timeSearchMenus);

                // Add rows for each menu item
                if (timeSearchMenus && timeSearchMenus.length > 0) {
                    timeSearchMenus.forEach((menu, index) => {
                        addEditMealRow(index, menu);
                    });
                } else {
                    // Add one empty row
                    addEditMealRow(0, {
                        time: '',
                        search_menu: '',
                        selected_recipes: '',
                        notes: ''
                    });
                }
            }

            // Add meal row to edit form
            function addEditMealRow(index, menu = {
                time: '',
                search_menu: '',
                selected_recipes: '',
                quantity: '',
                notes: ''
            }) {
                const recipes = menu.selected_recipes || menu.search_menu || '';

                const rowHtml = `
                                                                                <div class="meal-row mb-3 p-3 border rounded" data-index="${index}">
                                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                        <h6 class="mb-0">Meal #${index + 1}</h6>
                                                                                        <button type="button" class="btn btn-sm btn-danger remove-meal-row" ${index === 0 ? 'disabled' : ''}>
                                                                                            <i class="fas fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="payment-grid">
                                                                                        <div class="payment-field">
                                                                                            <label>Time</label>
                                                                                            <input type="time" class="payment-input meal-time" name="time_search_menus[${index}][time]"
                                                                                                value="${menu.time || ''}">
                                                                                        </div>
                                                                                        <div class="payment-field">
                                                                                            <label>Recipes (comma separated)</label>
                                                                                            <input type="text" class="payment-input meal-recipes"
                                                                                                name="time_search_menus[${index}][selected_recipes]"
                                                                                                value="${recipes}" placeholder="e.g., Recipe 1, Recipe 2">
                                                                                        </div>
                                                                                        <div class="payment-field">
                                                                                            <label>Quantity</label>
                                                                                            <input type="text" class="payment-input meal-quantity"
                                                                                                name="time_search_menus[${index}][quantity]"
                                                                                                value="${menu.quantity || ''}" placeholder="Qty">
                                                                                        </div>
                                                                                        <div class="payment-field">
                                                                                            <label>Notes</label>
                                                                                            <textarea class="payment-input meal-notes"
                                                                                                    name="time_search_menus[${index}][notes]"
                                                                                                    rows="2">${menu.notes || ''}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            `;

                $('#edit_time_search_container').append(rowHtml);
            }

            // Add new meal row button
            $('#add-new-meal-row').on('click', function () {
                const container = $('#edit_time_search_container');
                const currentCount = container.find('.meal-row').length;
                addEditMealRow(currentCount);
            });

            // Remove meal row
            $(document).on('click', '.remove-meal-row', function () {
                if ($('#edit_time_search_container .meal-row').length > 1) {
                    $(this).closest('.meal-row').remove();
                    // Reindex rows
                    $('#edit_time_search_container .meal-row').each(function (index) {
                        $(this).attr('data-index', index);
                        $(this).find('h6').text('Meal #' + (index + 1));
                        $(this).find('.meal-time').attr('name', `time_search_menus[${index}][time]`);
                        $(this).find('.meal-recipes').attr('name', `time_search_menus[${index}][selected_recipes]`);
                        $(this).find('.meal-quantity').attr('name', `time_search_menus[${index}][quantity]`);
                        $(this).find('.meal-notes').attr('name', `time_search_menus[${index}][notes]`);

                        // Enable/disable remove button
                        if (index === 0) {
                            $(this).find('.remove-meal-row').prop('disabled', true);
                        } else {
                            $(this).find('.remove-meal-row').prop('disabled', false);
                        }
                    });
                }
            });

            // Submit edit diet plan form
            $('#editDietPlanForm').on('submit', function (e) {
                e.preventDefault();

                const formData = $(this).serialize();
                console.log('Diet plan form data:', formData);

                // Show loading
                $('#updateDietPlanBtn').html('<span class="loading-spinner"></span>Updating...');
                $('#updateDietPlanBtn').prop('disabled', true);

                $.ajax({
                    url: '/diet-plan/update',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Diet plan update response:', response);
                        if (response.success) {
                            // Hide modal
                            const editModal = bootstrap.Modal.getInstance(document.getElementById('editDietPlanModal'));
                            editModal.hide();

                            // Show success message
                            showAlert('Diet plan updated successfully!', 'success');

                            // Reload page after 1.5 seconds
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Update failed: ' + (response.message || 'Unknown error'));
                            $('#updateDietPlanBtn').html('<i class="fas fa-save me-2"></i>Update Diet Plan');
                            $('#updateDietPlanBtn').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Diet plan update error:', error, xhr.responseText);
                        let errorMessage = 'Update failed. Check console for details.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        }
                        alert(errorMessage);
                        $('#updateDietPlanBtn').html('<i class="fas fa-save me-2"></i>Update Diet Plan');
                        $('#updateDietPlanBtn').prop('disabled', false);
                    }
                });
            });

            // Delete diet plan button click
            $(document).on('click', '.delete-diet-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                currentDietPlanId = $(this).data('plan-id');
                const dietName = $(this).closest('.diet-plan-card').find('.diet-plan-header h5').text();

                console.log('Delete diet plan clicked:', currentDietPlanId);

                // Show confirmation modal
                $('#deleteDietPlanDetails').html(`
                                                                                <strong>Diet Plan:</strong> ${dietName.trim()}
                                                                            `);

                const deleteModal = new bootstrap.Modal(document.getElementById('deleteDietPlanModal'));
                deleteModal.show();
            });

            // Confirm delete diet plan
            $('#confirmDeleteDietPlanBtn').on('click', function (e) {
                e.preventDefault();

                if (!currentDietPlanId) {
                    alert('No diet plan selected for deletion.');
                    return;
                }

                console.log('Confirming delete for diet plan ID:', currentDietPlanId);

                $(this).html('<span class="loading-spinner"></span>Deleting...');
                $(this).prop('disabled', true);

                const deleteData = {
                    _token: csrfToken,
                    diet_plan_id: currentDietPlanId
                };

                console.log('Delete data:', deleteData);

                $.ajax({
                    url: '/diet-plan/delete',
                    method: 'POST',
                    data: deleteData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Delete diet plan response:', response);
                        if (response.success) {
                            // Hide modal
                            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteDietPlanModal'));
                            deleteModal.hide();

                            // Show success message
                            showAlert('Diet plan deleted successfully!', 'success');

                            // Reload page after 1.5 seconds
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Delete failed: ' + (response.message || 'Unknown error'));
                            $('#confirmDeleteDietPlanBtn').html('Delete Diet Plan');
                            $('#confirmDeleteDietPlanBtn').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Delete diet plan error:', error, xhr.responseText);
                        let errorMessage = 'Delete failed. Check console for details.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                        $('#confirmDeleteDietPlanBtn').html('Delete Diet Plan');
                        $('#confirmDeleteDietPlanBtn').prop('disabled', false);
                    }
                });
            });

            // ========== PAYMENT PROGRAM FUNCTIONALITY ==========

            // Edit payment program button click
            $(document).on('click', '.edit-payment-btn', function (e) {
                e.preventDefault();
                currentPaymentPatientId = $(this).data('id');
                currentPaymentIndex = $(this).data('index');

                console.log('Edit payment clicked:', {
                    patientId: currentPaymentPatientId,
                    tableIndex: currentPaymentIndex,
                    totalPrograms: paymentPrograms.length
                });

                // Get the program data
                if (paymentPrograms && paymentPrograms[currentPaymentIndex]) {
                    const program = paymentPrograms[currentPaymentIndex];
                    console.log('Found program:', program);

                    // Store the original index for update
                    currentPaymentOriginalIndex = program.original_index || currentPaymentIndex;

                    populatePaymentEditForm(program);

                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById('editPaymentModal'));
                    editModal.show();
                } else {
                    console.error('Program not found at index:', currentPaymentIndex);
                    alert('Program data not found!');
                }
            });

            // Populate payment edit form
            function populatePaymentEditForm(program) {
                console.log('Populating payment form with:', program);

                $('#edit_payment_patient_id').val(currentPaymentPatientId);
                $('#edit_payment_index').val(currentPaymentOriginalIndex);
                $('#edit_program_name').val(program.program_name || '');
                $('#edit_session').val(program.session || '');
                $('#edit_months').val(program.months || '');
                $('#edit_payment_date').val(program.payment_date || '');
                $('#edit_payment_method').val(program.payment_method || '');

                // Remove currency symbols if present
                const total = (program.total || '0').toString().replace('₹', '').trim();
                const discount = (program.discount || '0').toString().replace('₹', '').trim();
                const given = (program.given || '0').toString().replace('₹', '').trim();
                const due = (program.due || '0').toString().replace('₹', '').trim();
                
                const cash = (program.cash_payment || '0').toString().replace('₹', '').trim();
                const gpay = (program.gpay_payment || '0').toString().replace('₹', '').trim();
                const cheque = (program.cheque_payment || '0').toString().replace('₹', '').trim();

                $('#edit_total').val(total);
                $('#edit_discount').val(discount);
                $('#edit_given').val(given);
                $('#edit_due').val(due);
                $('#edit_cash').val(cash);
                $('#edit_gpay').val(gpay);
                $('#edit_cheque').val(cheque);
                $('#edit_due_date').val(program.due_date || '');

                // Set payment status checkbox
                const dueNum = parseFloat(due) || 0;
                $('#edit_payment_status').prop('checked', dueNum === 0);

                // Calculate due amount
                calculateDueAmount();
            }

            // Auto-calculate due amount when total, discount, or given changes
            $(document).on('input', '#edit_total, #edit_discount', function () {
                calculateDueAmount();
            });

            $(document).on('input', '#edit_cash, #edit_gpay, #edit_cheque', function () {
                calculateGivenAmountFromSplit();
            });

            // Payment form submit handler
            $('#editPaymentForm').on('submit', function (e) {
                e.preventDefault();

                const formData = $(this).serialize();
                console.log('Payment form data:', formData);

                // Show loading
                $('#updatePaymentBtn').html('<span class="loading-spinner"></span>Updating...');
                $('#updatePaymentBtn').prop('disabled', true);

                $.ajax({
                    url: '/payment-program/update',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Payment update response:', response);
                        if (response.success) {
                            // Hide modal
                            const editModal = bootstrap.Modal.getInstance(document
                                .getElementById('editPaymentModal'));
                            editModal.hide();

                            // Show success message
                            showAlert('Payment program updated successfully!', 'success');

                            // Reload page after 1.5 seconds
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Update failed: ' + (response.message || 'Unknown error'));
                            $('#updatePaymentBtn').html(
                                '<i class="fas fa-save me-2"></i>Update Program');
                            $('#updatePaymentBtn').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Payment update error:', error, xhr.responseText);
                        let errorMessage = 'Update failed. Check console for details.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        }
                        alert(errorMessage);
                        $('#updatePaymentBtn').html(
                            '<i class="fas fa-save me-2"></i>Update Program');
                        $('#updatePaymentBtn').prop('disabled', false);
                    }
                });
            });

            // Delete payment program button click
            $(document).on('click', '.delete-payment-btn', function (e) {
                e.preventDefault();
                const patientId = $(this).data('id');
                const tableIndex = $(this).data('index');

                currentPaymentPatientId = patientId;
                currentPaymentIndex = tableIndex;

                console.log('Delete payment clicked:', {
                    patientId: patientId,
                    tableIndex: tableIndex
                });

                if (paymentPrograms && paymentPrograms[tableIndex]) {
                    const program = paymentPrograms[tableIndex];

                    // Store original index for deletion
                    currentPaymentOriginalIndex = program.original_index || tableIndex;

                    // Show program details in confirmation modal
                    $('#deleteProgramDetails').html(`
                                                                                    <strong>Program:</strong> ${program.program_name || 'N/A'}<br>
                                                                                    <strong>Session:</strong> ${program.session || 'N/A'}<br>
                                                                                    <strong>Months:</strong> ${program.months || 'N/A'}<br>
                                                                                    <strong>Amount:</strong> ₹${program.total || '0.00'}<br>
                                                                                    <strong>Date:</strong> ${program.payment_date || 'N/A'}
                                                                                `);

                    // Show delete modal
                    const deleteModal = new bootstrap.Modal(document.getElementById('deletePaymentModal'));
                    deleteModal.show();
                } else {
                    alert('Program data not found!');
                }
            });

            // Confirm delete payment program
            $('#confirmDeletePaymentBtn').on('click', function (e) {
                e.preventDefault();

                if (!currentPaymentPatientId || currentPaymentOriginalIndex === null) {
                    alert('No program selected for deletion.');
                    return;
                }

                console.log('Confirming delete payment for:', {
                    patientId: currentPaymentPatientId,
                    originalIndex: currentPaymentOriginalIndex
                });

                $(this).html('<span class="loading-spinner"></span>Deleting...');
                $(this).prop('disabled', true);

                const deleteData = {
                    _token: csrfToken,
                    patient_id: currentPaymentPatientId,
                    payment_index: currentPaymentOriginalIndex
                };

                $.ajax({
                    url: '/payment-program/delete',
                    method: 'POST',
                    data: deleteData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Delete payment response:', response);
                        if (response.success) {
                            // Hide modal
                            const deleteModal = bootstrap.Modal.getInstance(document
                                .getElementById('deletePaymentModal'));
                            deleteModal.hide();

                            // Show success alert
                            showAlert('Payment program deleted successfully!', 'success');

                            // Reload page after 1.5 seconds
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Delete failed: ' + (response.message || 'Unknown error'));
                            $('#confirmDeletePaymentBtn').html('Delete Program');
                            $('#confirmDeletePaymentBtn').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Delete payment error:', error, xhr.responseText);
                        let errorMessage = 'Delete failed. Check console for details.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                        $('#confirmDeletePaymentBtn').html('Delete Program');
                        $('#confirmDeletePaymentBtn').prop('disabled', false);
                    }
                });
            });

            // ========== MONTHLY ASSESSMENT FUNCTIONALITY ==========

            // Edit button click handler
            $(document).on('click', '.edit-assessment-btn', function (e) {
                e.preventDefault();
                const assessmentId = $(this).data('id');
                currentAssessmentId = assessmentId;

                console.log('Edit clicked for assessment ID:', assessmentId);

                // Show modal
                const editModal = new bootstrap.Modal(document.getElementById('editAssessmentModal'));
                editModal.show();

                // Fetch assessment data
                fetchAssessmentData(assessmentId);
            });

            // Fetch assessment data for editing
            function fetchAssessmentData(assessmentId) {
                // Show loading state
                $('#updateAssessmentBtn').html('<span class="loading-spinner"></span>Loading...');
                $('#updateAssessmentBtn').prop('disabled', true);

                console.log('Fetching data for assessment ID:', assessmentId);

                $.ajax({
                    url: '/monthly-assessment/' + assessmentId + '/details',
                    method: 'GET',
                    success: function (response) {
                        console.log('Full API Response:', response);
                        if (response.success) {
                            console.log('Assessment data:', response.assessment);
                            populateEditForm(response.assessment);
                            $('#updateAssessmentBtn').html(
                                '<i class="fas fa-save me-2"></i>Update Assessment');
                            $('#updateAssessmentBtn').prop('disabled', false);
                        } else {
                            console.error('API Error:', response.message);
                            alert('Failed to load assessment data: ' + (response.message ||
                                'Unknown error'));
                            $('#editAssessmentModal').modal('hide');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', error, xhr.responseText);
                        alert('Error loading assessment data. Check console for details.');
                        $('#editAssessmentModal').modal('hide');
                    }
                });
            }

            // Populate edit form with data - CORRECTED VERSION
            function populateEditForm(data) {
                console.log('Populating form with data:', data);
                console.log('Assessment date:', data.assessment_date);
                console.log('Waist upper:', data.waist_upper);
                console.log('BMI:', data.bmi);

                // Set basic fields
                $('#edit_assessment_id').val(data.id || '');
                // Format date properly (remove time part)
                if (data.assessment_date) {
                    const dateObj = new Date(data.assessment_date);
                    const formattedDate = dateObj.toISOString().split('T')[0];
                    console.log('Formatted date:', formattedDate);
                    $('#edit_assessment_date').val(formattedDate);
                }

                // Set measurement fields with null check
                $('#edit_waist_upper').val(data.waist_upper || '');
                $('#edit_waist_middle').val(data.waist_middle || '');
                $('#edit_waist_lower').val(data.waist_lower || '');
                $('#edit_hips').val(data.hips || '');
                $('#edit_thighs').val(data.thighs || '');
                $('#edit_arms').val(data.arms || '');
                $('#edit_waist_hips').val(data.waist_hips_ratio || '');
                $('#edit_weight').val(data.weight || '');
                $('#edit_bmi').val(data.bmi || '');

                // Set BCA fields
                $('#edit_bca_vbf').val(data.bca_vbf || '');
                $('#edit_bca_arms').val(data.bca_arms || '');
                $('#edit_bca_trunk').val(data.bca_trunk || '');
                $('#edit_bca_legs').val(data.bca_legs || '');
                $('#edit_bca_sf').val(data.bca_sf || '');
                $('#edit_bca_vf').val(data.bca_vf || '');

                // Set muscle fields
                $('#edit_muscle_vbf').val(data.muscle_vbf || '');
                $('#edit_muscle_arms').val(data.muscle_arms || '');
                $('#edit_muscle_trunk').val(data.muscle_trunk || '');
                $('#edit_muscle_legs').val(data.muscle_legs || '');
            }

            // Form submit handler for assessment
            $('#editAssessmentForm').on('submit', function (e) {
                e.preventDefault();

                const formData = $(this).serialize();
                console.log('Form data to submit:', formData);

                // Show loading
                $('#updateAssessmentBtn').html('<span class="loading-spinner"></span>Updating...');
                $('#updateAssessmentBtn').prop('disabled', true);

                $.ajax({
                    url: '/monthly-assessment/update',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Update response:', response);
                        if (response.success) {
                            // Show success message
                            const editModal = bootstrap.Modal.getInstance(document
                                .getElementById('editAssessmentModal'));
                            editModal.hide();

                            // Show success alert
                            showAlert('Assessment updated successfully!', 'success');

                            // Reload page after 1.5 seconds
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Update failed: ' + (response.message || 'Unknown error'));
                            $('#updateAssessmentBtn').html(
                                '<i class="fas fa-save me-2"></i>Update Assessment');
                            $('#updateAssessmentBtn').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Update error:', error, xhr.responseText);
                        let errorMessage = 'Update failed. Check console for details.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        }
                        alert(errorMessage);
                        $('#updateAssessmentBtn').html(
                            '<i class="fas fa-save me-2"></i>Update Assessment');
                        $('#updateAssessmentBtn').prop('disabled', false);
                    }
                });
            });

            // Form submit handler for ADD assessment
            $('#addAssessmentForm').on('submit', function (e) {
                e.preventDefault();

                const formData = $(this).serialize();
                console.log('Add assessment form data to submit:', formData);

                // Show loading
                $('#saveAssessmentBtn').html('<span class="loading-spinner"></span>Saving...');
                $('#saveAssessmentBtn').prop('disabled', true);

                $.ajax({
                    url: '/monthly-assessment/store',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Save assessment response:', response);
                        if (response.success) {
                            // Show success message
                            const addModal = bootstrap.Modal.getInstance(document.getElementById('addAssessmentModal'));
                            if (addModal) {
                                addModal.hide();
                            } else {
                                $('#addAssessmentModal').modal('hide');
                            }

                            // Show success alert
                            showAlert('Assessment saved successfully!', 'success');

                            // Reload page after 1.5 seconds
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Save failed: ' + (response.message || 'Unknown error'));
                            $('#saveAssessmentBtn').html(
                                '<i class="fas fa-save me-2"></i>Save Assessment');
                            $('#saveAssessmentBtn').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Save error:', error, xhr.responseText);
                        let errorMessage = 'Save failed. Check console for details.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        }
                        alert(errorMessage);
                        $('#saveAssessmentBtn').html(
                            '<i class="fas fa-save me-2"></i>Save Assessment');
                        $('#saveAssessmentBtn').prop('disabled', false);
                    }
                });
            });

            // Delete button click handler for assessment
            $(document).on('click', '.delete-assessment-btn', function (e) {
                e.preventDefault();
                const assessmentId = $(this).data('id');
                const patientId = $(this).data('patient-id');

                currentAssessmentId = assessmentId;

                console.log('Delete clicked for assessment ID:', assessmentId, 'Patient ID:', patientId);

                // Show delete modal
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteAssessmentModal'));
                deleteModal.show();
            });

            // Confirm delete handler for assessment
            $('#confirmDeleteBtn').on('click', function (e) {
                e.preventDefault();

                if (!currentAssessmentId) {
                    alert('No assessment selected for deletion.');
                    return;
                }

                console.log('Confirming delete for assessment ID:', currentAssessmentId);

                $(this).html('<span class="loading-spinner"></span>Deleting...');
                $(this).prop('disabled', true);

                // Prepare data
                const deleteData = {
                    _token: csrfToken,
                    assessment_id: currentAssessmentId
                };

                console.log('Delete data:', deleteData);

                $.ajax({
                    url: '/monthly-assessment/delete',
                    method: 'POST',
                    data: deleteData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Delete response:', response);
                        if (response.success) {
                            // Hide modal
                            const deleteModal = bootstrap.Modal.getInstance(document
                                .getElementById('deleteAssessmentModal'));
                            deleteModal.hide();

                            // Show success alert
                            showAlert('Assessment deleted successfully!', 'success');

                            // Reload page after 1.5 seconds
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Delete failed: ' + (response.message || 'Unknown error'));
                            $('#confirmDeleteBtn').html('Delete');
                            $('#confirmDeleteBtn').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Delete error:', error, xhr.responseText);
                        let errorMessage = 'Delete failed. Check console for details.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                        $('#confirmDeleteBtn').html('Delete');
                        $('#confirmDeleteBtn').prop('disabled', false);
                    }
                });
            });



            // ========== PROGRESS REPORT EDIT/DELETE FUNCTIONALITY ==========

            // Edit Progress Report button click
            $(document).on('click', '.edit-progress-report-btn', function (e) {
                e.preventDefault();
                currentProgressReportId = $(this).data('id');

                console.log('Edit Progress Report clicked:', currentProgressReportId);

                const editModal = new bootstrap.Modal(document.getElementById('editProgressReportModal'));
                editModal.show();

                fetchProgressReportData(currentProgressReportId);
            });

            // Fetch Progress Report data
            function fetchProgressReportData(reportId) {
                $('#updateProgressReportBtn').html('<span class="loading-spinner"></span>Loading...');
                $('#updateProgressReportBtn').prop('disabled', true);

                $.ajax({
                    url: '/progress-report/' + reportId + '/details',
                    method: 'GET',
                    success: function (response) {
                        console.log('Progress Report API Response:', response);
                        let reportData = response.report || response;
                        if (reportData && reportData.id) {
                            populateProgressReportForm(reportData);
                            $('#updateProgressReportBtn').html('<i class="fas fa-save me-2"></i>Update Progress Report');
                            $('#updateProgressReportBtn').prop('disabled', false);
                        } else {
                            alert('Failed to load Progress Report data: ' + (response.message || 'Unknown error'));
                            $('#editProgressReportModal').modal('hide');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', error, xhr.responseText);
                        alert('Error loading Progress Report data.');
                        $('#editProgressReportModal').modal('hide');
                    }
                });
            }

            // Populate Progress Report form
            function populateProgressReportForm(data) {
                console.log('Populating Progress Report form with:', data);

                $('#edit_progress_report_id').val(data.id || '');

                if (data.date) {
                    const dateObj = new Date(data.date);
                    const formattedDate = dateObj.toISOString().split('T')[0];
                    $('#edit_progress_report_date').val(formattedDate);
                }

                $('#edit_progress_report_time').val(data.time || '');
                $('#edit_face_program').val(data.face_program || '');
                $('#edit_body_part').val(data.body_part || '');
                $('#edit_progress_report_medication').val(data.medication || '');
                $('#edit_progress_report_bp').val(data.bp_p || '');
                $('#edit_progress_report_pulse').val(data.pulse || '');
                $('#edit_progress_report_weight').val(data.weight || '');
                $('#edit_progress_report_councilor').val(data.councilor_doctor || '');
                $('#edit_progress_report_exercise').val(data.exercise || '');
                $('#edit_progress_report_diet').val(data.diet || '');
                $('#edit_progress_report_sleep').val(data.sleep || '');
                $('#edit_progress_report_water').val(data.water || '');
            }

            // Submit edit Progress Report form
            $('#editProgressReportForm').on('submit', function (e) {
                e.preventDefault();

                const formData = $(this).serialize();
                console.log('Progress Report form data:', formData);

                $('#updateProgressReportBtn').html('<span class="loading-spinner"></span>Updating...');
                $('#updateProgressReportBtn').prop('disabled', true);

                $.ajax({
                    url: '/progress-report/update',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Progress Report update response:', response);
                        if (response.success) {
                            const editModal = bootstrap.Modal.getInstance(document.getElementById('editProgressReportModal'));
                            editModal.hide();

                            showAlert('Progress Report updated successfully!', 'success');

                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Update failed: ' + (response.message || 'Unknown error'));
                            $('#updateProgressReportBtn').html('<i class="fas fa-save me-2"></i>Update Progress Report');
                            $('#updateProgressReportBtn').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Progress Report update error:', error, xhr.responseText);
                        alert('Update failed. Check console for details.');
                        $('#updateProgressReportBtn').html('<i class="fas fa-save me-2"></i>Update Progress Report');
                        $('#updateProgressReportBtn').prop('disabled', false);
                    }
                });
            });

            // Delete Progress Report button click
            $(document).on('click', '.delete-progress-report-btn', function (e) {
                e.preventDefault();
                currentProgressReportId = $(this).data('id');

                console.log('Delete Progress Report clicked:', currentProgressReportId);

                const deleteModal = new bootstrap.Modal(document.getElementById('deleteProgressReportModal'));
                deleteModal.show();
            });

            // Confirm delete Progress Report
            $('#confirmDeleteProgressReportBtn').on('click', function (e) {
                e.preventDefault();

                if (!currentProgressReportId) {
                    alert('No report selected for deletion.');
                    return;
                }

                console.log('Confirming delete for Progress Report ID:', currentProgressReportId);

                $(this).html('<span class="loading-spinner"></span>Deleting...');
                $(this).prop('disabled', true);

                const deleteData = {
                    _token: csrfToken,
                    report_id: currentProgressReportId,
                    report_type: 'progress'
                };

                $.ajax({
                    url: '/progress-report/delete',
                    method: 'POST',
                    data: deleteData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log('Delete response:', response);
                        if (response.success) {
                            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteProgressReportModal'));
                            deleteModal.hide();

                            showAlert('Progress Report deleted successfully!', 'success');

                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            alert('Delete failed: ' + (response.message || 'Unknown error'));
                            $('#confirmDeleteProgressReportBtn').html('Delete');
                            $('#confirmDeleteProgressReportBtn').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Delete error:', error, xhr.responseText);
                        alert('Delete failed. Check console for details.');
                        $('#confirmDeleteProgressReportBtn').html('Delete');
                        $('#confirmDeleteProgressReportBtn').prop('disabled', false);
                    }
                });
            });

            // ========== MODAL RESET HANDLERS ==========

            // Reset diet plan modals
            $('#editDietPlanModal').on('hidden.bs.modal', function () {
                $('#editDietPlanForm')[0].reset();
                $('#edit_time_search_container').empty();
                $('#updateDietPlanBtn').html('<i class="fas fa-save me-2"></i>Update Diet Plan');
                $('#updateDietPlanBtn').prop('disabled', false);
            });

            $('#deleteDietPlanModal').on('hidden.bs.modal', function () {
                $('#confirmDeleteDietPlanBtn').html('Delete Diet Plan');
                $('#confirmDeleteDietPlanBtn').prop('disabled', false);
                $('#deleteDietPlanDetails').html('');
            });

            // Reset payment modals
            $('#editPaymentModal').on('hidden.bs.modal', function () {
                $('#editPaymentForm')[0].reset();
                $('#updatePaymentBtn').html('<i class="fas fa-save me-2"></i>Update Program');
                $('#updatePaymentBtn').prop('disabled', false);
            });

            $('#deletePaymentModal').on('hidden.bs.modal', function () {
                $('#confirmDeletePaymentBtn').html('Delete Program');
                $('#confirmDeletePaymentBtn').prop('disabled', false);
                $('#deleteProgramDetails').html('');
            });

            // Reset assessment modals
            $('#editAssessmentModal').on('hidden.bs.modal', function () {
                $('#editAssessmentForm')[0].reset();
                $('#updateAssessmentBtn').html('<i class="fas fa-save me-2"></i>Update Assessment');
                $('#updateAssessmentBtn').prop('disabled', false);
            });

            $('#deleteAssessmentModal').on('hidden.bs.modal', function () {
                $('#confirmDeleteBtn').html('Delete');
                $('#confirmDeleteBtn').prop('disabled', false);
            });
            // Reset progress report edit modals
            $('#editProgressReportModal').on('hidden.bs.modal', function () {
                $('#editProgressReportForm')[0].reset();
                $('#updateProgressReportBtn').html('<i class="fas fa-save me-2"></i>Update Progress Report');
                $('#updateProgressReportBtn').prop('disabled', false);
            });

            // Reset progress report delete modals
            $('#deleteProgressReportModal').on('hidden.bs.modal', function () {
                $(this).find('.btn-danger').each(function () {
                    $(this).html('Delete');
                    $(this).prop('disabled', false);
                });
            });
        });

        // Toggle function for Laboratory Investigation section
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const icon = event.currentTarget.querySelector('i');

            if (section.style.display === 'none') {
                section.style.display = 'block';
                icon.classList.remove('fa-angle-down');
                icon.classList.add('fa-angle-up');
            } else {
                section.style.display = 'none';
                icon.classList.remove('fa-angle-up');
                icon.classList.add('fa-angle-down');
            }
        }
        function previewPatientImageDirect(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var previewContainer = document.getElementById('profileImagePreview');
                    previewContainer.innerHTML = '<img src="' + e.target.result + '" alt="Profile Image" class="img-fluid profile_open mx-auto d-block">';
                    document.getElementById('saveImageBtn').style.display = 'inline-block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('click', '.program-row-toggle', function () {
            const targetId = $(this).data('target');
            const $target = $('#' + targetId);
            const $arrow = $(this).find('.fa-chevron-right');

            $target.toggle();

            if ($target.is(':visible')) {
                $arrow.css('transform', 'rotate(90deg)');
            } else {
                $arrow.css('transform', 'rotate(0deg)');
            }
        });
    </script>

    <style>
        @media (min-width: 768px) {
            .border-end-md {
                border-right: 1px solid #dee2e6 !important;
            }
        }

        /* Nutritional Information Styles */
        .nutrition-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin-bottom: 10px;
        }

        .nutrition-label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .nutrition-value {
            font-weight: 700;
            color: #086838;
            font-size: 14px;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 4px;
        }

        .progress-bar {
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        /* Dark mode support */
        .dark .nutrition-item {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .dark .nutrition-label {
            color: #fff;
        }

        .dark .nutrition-value {
            color: #4ade80;
        }

        .dark .progress {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Diet Plans Nutrition Styles */
        .diet-plan-nutrition {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .diet-plan-header {
            border-bottom: 2px solid #086838;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .nutrition-summary {
            background-color: white;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #dee2e6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .nutrition-summary .nutrition-label {
            display: block;
            font-size: 11px;
            color: #666;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .nutrition-summary .nutrition-value {
            display: block;
            font-size: 14px;
            color: #086838;
            font-weight: 700;
        }

        .menu-items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .menu-item-card {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .menu-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .menu-item-name {
            font-weight: 600;
            color: #333;
            font-size: 13px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 5px;
        }

        .menu-item-nutrition {
            font-size: 11px;
        }

        .nutrition-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            color: #555;
        }

        .nutrition-row span {
            font-weight: 500;
        }

        /* Dark mode support */
        .dark .diet-plan-nutrition {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .dark .nutrition-summary {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .dark .nutrition-summary .nutrition-label {
            color: #ccc;
        }

        .dark .nutrition-summary .nutrition-value {
            color: #4ade80;
        }

        .dark .menu-item-card {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .dark .menu-item-name {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .dark .nutrition-row {
            color: #ccc;
        }
    </style>
@endsection
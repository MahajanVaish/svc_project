@extends('admin.layouts.layouts')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: #333; font-weight: 600; font-size: 24px;">
                Joined Patients
            </h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Add Inquiry Button -->
            <a href="{{ url('/add-inquiry') }}" class="btn btn-primary btn-sm"
                    style="font-size: 13px; padding: 6px 15px; border-radius: 4px;">
                    <i class="fas fa-plus me-1"></i> Add Inquiry
                </a>
            
            <!-- Export All Button -->
            <button class="btn btn-success btn-sm d-flex align-items-center gap-2"
                    style="font-size: 13px; padding: 6px 16px; border-radius: 4px; font-weight: 500;">
                <i class="fas fa-download"></i>
                <span>Export All</span>
            </button>
            
            <!-- Export Data Button -->
            <button class="btn btn-outline-success btn-sm d-flex align-items-center gap-2"
                    style="font-size: 13px; padding: 6px 16px; border-radius: 4px; font-weight: 500;">
                <i class="fas fa-file-export"></i>
                <span>Export Data</span>
            </button>
            
            <!-- Search Box -->
            <div class="position-relative ms-2">
                <div class="d-flex">
                    <input type="text" class="form-control form-control-sm" 
                           placeholder="Search" 
                           style="width: 250px; font-size: 13px; padding: 6px 12px; border-radius: 4px; border: 1px solid #ced4da;">
                    <button class="btn btn-sm btn-outline-secondary ms-1"
                            style="font-size: 13px; padding: 6px 12px; border-radius: 4px;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-0">
            <!-- Table Container -->
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size: 13px;">
                    <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <tr>
                            <th style="padding: 12px 16px; font-weight: 600; color: #333; border: none; width: 60px;">
                                Profile
                            </th>
                            <th style="padding: 12px 16px; font-weight: 600; color: #333; border: none;">
                                Patient Id
                            </th>
                            <th style="padding: 12px 16px; font-weight: 600; color: #333; border: none;">
                                Date
                            </th>
                            <th style="padding: 12px 16px; font-weight: 600; color: #333; border: none;">
                                Name
                            </th>
                            <th style="padding: 12px 16px; font-weight: 600; color: #333; border: none;">
                                Phone
                            </th>
                            <th style="padding: 12px 16px; font-weight: 600; color: #333; border: none;">
                                Address
                            </th>
                            <th style="padding: 12px 16px; font-weight: 600; color: #333; border: none;">
                                Diagnosis
                            </th>
                            <th style="padding: 12px 16px; font-weight: 600; color: #333; border: none;">
                                Diet H/O
                            </th>
                            <th style="padding: 12px 16px; font-weight: 600; color: #333; border: none; width: 80px;">
                                Edit
                            </th>
                            <th style="padding: 12px 16px; font-weight: 600; color: #333; border: none; width: 80px;">
                                Delete
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1 -->
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <!-- Profile -->
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <div class="d-flex justify-content-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor: pointer;">
                                            <span style="color: white; font-weight: bold; font-size: 14px;">
                                                C
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Patient ID -->
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                PP-00002097
                            </td>
                            
                            <!-- Date -->
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                22/11/2025
                            </td>
                            
                            <!-- Name -->
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Chandrikabem Vasifeda
                            </td>
                            
                            <!-- Phone -->
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                9924312563
                            </td>
                            
                            <!-- Address -->
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333; max-width: 200px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    Bhakatham Mandir pachar
                                </div>
                            </td>
                            
                            <!-- Diagnosis -->
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                -
                            </td>
                            
                            <!-- Diet H/O -->
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                No
                            </td>
                            
                            <!-- Edit Button -->
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-primary"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 60px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                            
                            <!-- Delete Button -->
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-danger"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 70px;">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 2 -->
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <div class="d-flex justify-content-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); cursor: pointer;">
                                            <span style="color: white; font-weight: bold; font-size: 14px;">
                                                P
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                PP-00002096
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                26/11/2025
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Priyanika Birbabilha Rajput
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                8866837094
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333; max-width: 200px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    302,Bathe kirahana pakes-Aretrāl
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                -
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                No
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-primary"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 60px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-danger"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 70px;">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 3 -->
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <div class="d-flex justify-content-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); cursor: pointer;">
                                            <span style="color: white; font-weight: bold; font-size: 14px;">
                                                T
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                PP-00002084
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                19/11/2025
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Tushan Desai
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                7465796553
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333; max-width: 200px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    A:255,Mahalami Society,Yegi chowk
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                -
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                No
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-primary"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 60px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-danger"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 70px;">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 4 -->
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <div class="d-flex justify-content-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); cursor: pointer;">
                                            <span style="color: white; font-weight: bold; font-size: 14px;">
                                                V
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                PP-00002081
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                13/11/2025
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Vaibal Vinuthhai Debanya
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                9714060705
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333; max-width: 200px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    27,Sajawat Bangbow, Pana patiya ,mageb
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                -
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                No
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-primary"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 60px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-danger"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 70px;">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 5 -->
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <div class="d-flex justify-content-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); cursor: pointer;">
                                            <span style="color: white; font-weight: bold; font-size: 14px;">
                                                D
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                BD-00002080
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                11/11/2025
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Dipallēren Princiālihai Patel
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                9974236681
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333; max-width: 200px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    33,Jasikunj Sec,near staumandi-school,Sastri road,bardoli
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                obesity grade 2
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                No
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-primary"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 60px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-danger"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 70px;">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 6 -->
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <div class="d-flex justify-content-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); cursor: pointer;">
                                            <span style="color: white; font-weight: bold; font-size: 14px;">
                                                M
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                BD-00002078
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                03/11/2025
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Meetbhai C Patel
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                9952417944
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333; max-width: 200px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    6-nets 5 Reisdeno,Bardoli
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                obesity grade 2
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                No
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-primary"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 60px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-danger"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 70px;">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 7 -->
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <div class="d-flex justify-content-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); cursor: pointer;">
                                            <span style="color: white; font-weight: bold; font-size: 14px;">
                                                A
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                ST-00002073
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                01/08/2025
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Archana Pratik Mandani
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                9952384050
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333; max-width: 200px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    A:701, Cassa Amorina, Parost Ratya
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                obesity grade 1
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                No
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-primary"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 60px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-danger"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 70px;">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 8 -->
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <div class="d-flex justify-content-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); cursor: pointer;">
                                            <span style="color: white; font-weight: bold; font-size: 14px;">
                                                P
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                ST-00002072
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                01/11/2025
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Pallavibala Moradiya
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                9955016453
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333; max-width: 200px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    B:1002, Virā Raita chawk, Near sarthana jakatraika
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                obesity grade 1
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                No
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-primary"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 60px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-danger"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 70px;">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 9 -->
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <div class="d-flex justify-content-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #5ee7df 0%, #b490ca 100%); cursor: pointer;">
                                            <span style="color: white; font-weight: bold; font-size: 14px;">
                                                K
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                BD-00002070
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                27/10/2025
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Kailashben Anilīhhai Patel
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                9537625527
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333; max-width: 200px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    Sahlant Mandil,Mandil
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                -
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                No
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-primary"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 60px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-danger"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 70px;">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Row 10 -->
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <div class="d-flex justify-content-center">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px; background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); cursor: pointer;">
                                            <span style="color: white; font-weight: bold; font-size: 14px;">
                                                D
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                BD-00002065
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                10/10/2025
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Divyaben Kamālihai Patel
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                9726697030
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333; max-width: 200px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    Partil,Arak
                                </div>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                Normal Weight
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle; color: #333;">
                                No
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-primary"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 60px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                            </td>
                            <td style="padding: 12px 16px; vertical-align: middle;">
                                <button class="btn btn-sm btn-outline-danger"
                                        style="font-size: 12px; padding: 4px 12px; border-radius: 4px; width: 70px;">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for additional functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make profile circles clickable with hover effect
        const profileCircles = document.querySelectorAll('.rounded-circle');
        profileCircles.forEach(circle => {
            circle.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
                this.style.transition = 'transform 0.2s ease';
            });
            
            circle.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
        
        // Search box focus effect
        const searchInput = document.querySelector('input[placeholder="Search"]');
        if (searchInput) {
            searchInput.addEventListener('focus', function() {
                this.style.borderColor = '#86b7fe';
                this.style.boxShadow = '0 0 0 0.25rem rgba(13, 110, 253, 0.25)';
            });
            
            searchInput.addEventListener('blur', function() {
                this.style.borderColor = '#ced4da';
                this.style.boxShadow = 'none';
            });
        }
        
        // Table row hover effect
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
        
        // Edit button click
        const editButtons = document.querySelectorAll('.btn-outline-primary');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                alert('Edit functionality will be implemented soon.');
            });
        });
        
        // Delete button click
        const deleteButtons = document.querySelectorAll('.btn-outline-danger');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this patient? This action cannot be undone.')) {
                    alert('Delete functionality will be implemented soon.');
                }
            });
        });
        
    });
</script>

<!-- Additional CSS -->
<style>
    /* Custom scrollbar for table */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Table cell styling */
    td {
        white-space: nowrap;
    }
    
    /* Pagination active state */
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
    
    /* Button hover effects */
    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }
    
    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .table-responsive {
            font-size: 12px;
        }
        
        input[placeholder="Search"] {
            width: 200px !important;
        }
        
        .d-flex.align-items-center.gap-2 {
            gap: 8px !important;
        }
    }
    
    @media (max-width: 992px) {
        .table-responsive {
            overflow-x: auto;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 15px;
        }
        
        .pagination {
            justify-content: center !important;
            margin-top: 10px;
        }
        
        .d-flex.align-items-center.gap-2 {
            flex-wrap: wrap;
            justify-content: center !important;
        }
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 15px !important;
        }
        
        input[placeholder="Search"] {
            width: 100% !important;
        }
        
        .btn-sm {
            padding: 3px 8px !important;
            font-size: 11px !important;
        }
        
        .d-flex.align-items-center.gap-2 {
            flex-direction: column;
            align-items: stretch !important;
            width: 100%;
        }
        
        .d-flex.align-items-center.gap-2 > button,
        .d-flex.align-items-center.gap-2 > .position-relative {
            width: 100%;
            margin-bottom: 8px;
        }
        
        .d-flex.align-items-center.gap-2 > button {
            justify-content: center;
        }
    }
    
    /* Print styles */
    @media print {
        .btn, .d-flex:not(.justify-content-center) {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        table {
            font-size: 11px !important;
        }
    }
    
    /* Button hover animations */
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        transition: all 0.2s ease;
    }
    
    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        transition: all 0.2s ease;
    }
    
    .btn-outline-success:hover {
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
</style>
@endsection
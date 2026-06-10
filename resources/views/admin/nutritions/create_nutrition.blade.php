@extends('admin.layouts.layouts')
@section('title', 'SVC Charges')
@section('content')
    <style>
        .fixed {
            position: fixed;
        }

        .top-4 {
            top: 1rem;
        }

        .right-4 {
            right: 1rem;
        }

        .bg-red-500 {
            background-color: #ef4444;
        }

        .bg-green-500 {
            background-color: #10b981;
        }

        .text-white {
            color: white;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .z-50 {
            z-index: 50;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .hidden {
            display: none;
        }

        /* Custom styles for exact match with image */
        .btn-primary-custom {
            background-color: #0d6efd !important;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
        }

        .btn-success-custom {
            background-color: #198754 !important;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
        }

        .table-custom {
            border-collapse: collapse;
            width: 100%;
        }

        .table-custom th {
            background-color: #198754;
            border: 1px solid #198754;
            padding: 8px 12px;
            font-weight: 600;
            font-size: 14px;
            text-align: left;
        }

        .table-custom td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            font-size: 14px;
        }

        .table-custom tr:hover {
            background-color: #f8f9fa;
        }

        .section-header {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin-bottom: 24px;
        }

        .upload-section {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        /* Better Modal Scroll Lock */
        .modal-active {
            overflow: hidden !important;
            height: 100vh !important;
            width: 100vw !important;
            position: fixed !important;
        }

        html.modal-active {
            overflow: hidden !important;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .action-btn:hover {
            background-color: #f8f9fa;
        }

        .file-input-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        /* Action Buttons Styling */
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.3s ease;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-edit-square {
            border-color: #16a34a;
            color: #16a34a;
            border: 1px solid #16a34a;
        }

        .btn-edit-square:hover {
            background-color: #16a34a;
            color: white;
        }

        .btn-delete-square {
            border-color: #dc3545;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .btn-delete-square:hover {
            background-color: #dc3545;
            color: white;
        }
    </style>


    <div class="container mx-auto px-4 py-8">
        <!-- Success Message -->
        @if (session('success'))
            <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
        <!-- Add this after your error message section -->
        <div id="successMessage"
            class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 hidden">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span></span>
            </div>
        </div>

        <!-- Header Section -->
        <div class="section-header">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Add Nutrition Info</h1>

            <!-- Upload Section -->
            <div class="upload-section">
                {{-- <h2 class="text-xl font-semibold text-gray-700 mb-3"></h2> --}}
                <form action="{{ route('nutrition.upload.csv') }}" method="POST" enctype="multipart/form-data"
                    id="csvUploadForm">
                    @csrf
                    <div class="file-input-wrapper" style="display: flex; flex-wrap: wrap; gap: 12px;">
                        <input type="file" id="csvFile" name="csv_file" class="hidden" accept=".csv">
                        <button type="button" onclick="document.getElementById('csvFile').click()"
                            class="btn btn-success-custom text-white">
                            Choose file
                        </button>
                        <span id="fileName" class="text-gray-600">No file chosen</span>
                    </div>
                    <button type="submit" class="btn btn-primary-custom text-white">
                        <i class="fas fa-upload mr-2"></i>Upload
                    </button>
                </form>

                <div class="mt-3 text-sm text-gray-600">
                    <p>For Download sample CSV file <a href="{{ route('nutrition.download.sample') }}"
                            class="text-blue-500 hover:underline">Click here</a></p>
                    <p class="text-red-500 mt-1">Note: Duplicate nutrition data will not add nutritious records.</p>
                </div>
            </div>

            <!-- Add Nutrition Button -->
            <button onclick="openModal()"
                class="px-4 py-2 rounded-lg bg-[#198754] hover:bg-[#157347] text-white font-semibold transition duration-200">
                <i class="fas fa-plus mr-2"></i>Add New Nutrition
            </button>

        </div>

        <!-- Manage Nutrition Section -->
        <div class="bg-white rounded-lg shadow-md p-6">

            <!-- Advanced Search Section -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Advanced Search</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search by Nutrition Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nutrition Name</label>
                        <input type="text" id="searchName" placeholder="Search by nutrition name..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Search by Protein Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Protein Range (g)</label>
                        <div class="flex gap-2">
                            <input type="number" id="searchProteinMin" placeholder="Min" step="0.01"
                                class="w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <input type="number" id="searchProteinMax" placeholder="Max" step="0.01"
                                class="w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Search by Energy Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Energy Range (kcal)</label>
                        <div class="flex gap-2">
                            <input type="number" id="searchEnergyMin" placeholder="Min" step="0.01"
                                class="w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <input type="number" id="searchEnergyMax" placeholder="Max" step="0.01"
                                class="w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Search Buttons -->
                <div class="flex gap-2 mt-4">
                    <button type="button" id="searchBtn" class="btn-primary-custom text-white px-4 py-2 rounded">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <button type="button" id="clearSearchBtn"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        <i class="fas fa-times mr-2"></i>Clear
                    </button>
                </div>
            </div>

            <!-- Nutrition Table -->
            <div class="overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Nutrition Name</th>
                            <th>Energy Kcal</th>
                            <th>Water</th>
                            <th>Fat</th>
                            <th>Carbohydrate</th>
                            <th>Protein</th>
                            <th>Vitamin C</th>
                            <th>Total Fiber</th>
                            <th>Insoluable Fiber</th>
                            <th>Soluable Fiber</th>
                            <th>Biotin</th>
                            <th>Total Folates</th>
                            <th>Calcium</th>
                            <th>CU</th>
                            <th>FE</th>
                            <th>MG</th>
                            <th>P</th>
                            <th>K</th>
                            <th>SE</th>
                            <th>NA</th>
                            <th>ZA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($nutritions->count() > 0)
                            @foreach ($nutritions as $nutrition)
                                <tr id="nutrition-row-{{ $nutrition->id }}">
                                    <td>{{ $nutrition->nutrition_name ?: '-' }}</td>
                                    <td>{{ $nutrition->energy_kcal ?: '-' }}</td>
                                    <td>{{ $nutrition->water ?: '-' }}</td>
                                    <td>{{ $nutrition->fat ?: '-' }}</td>
                                    <td>{{ $nutrition->carbohydrate ?: '-' }}</td>
                                    <td>{{ $nutrition->protein ?: '-' }}</td>
                                    <td>{{ $nutrition->vitamin_c ?: '-' }}</td>
                                    <td>{{ $nutrition->total_fiber ?: '-' }}</td>
                                    <td>{{ $nutrition->insoluable_fiber ?: '-' }}</td>
                                    <td>{{ $nutrition->soluable_fiber ?: '-' }}</td>
                                    <td>{{ $nutrition->biotin ?: '-' }}</td>
                                    <td>{{ $nutrition->total_folates ?: '-' }}</td>
                                    <td>{{ $nutrition->calcium ?: '-' }}</td>
                                    <td>{{ $nutrition->cu ?: '-' }}</td>
                                    <td>{{ $nutrition->fe ?: '-' }}</td>
                                    <td>{{ $nutrition->mg ?: '-' }}</td>
                                    <td>{{ $nutrition->p ?: '-' }}</td>
                                    <td>{{ $nutrition->k ?: '-' }}</td>
                                    <td>{{ $nutrition->se ?: '-' }}</td>
                                    <td>{{ $nutrition->na ?: '-' }}</td>
                                    <td>{{ $nutrition->za ?: '-' }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button onclick="openEditModal({{ $nutrition->id }})" class="action-btn btn-edit-square"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteNutrition({{ $nutrition->id }})"
                                                class="action-btn btn-delete-square" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="22" class="px-4 py-8 text-center text-gray-500 border border-gray-300">
                                    <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                    No nutrition data found. Click "Add New Nutrition" to add your first record.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $nutritions->links() }}
        </div>
    </div>

    <!-- Add Nutrition Modal -->
    <div id="nutritionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[9999]">
        <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-gray-300">
                <h3 class="text-2xl font-bold text-gray-800">Add Nutrition Information</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="nutritionForm" action="{{ route('nutrition.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Column 1 -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nutrition Name</label>
                            <input type="text" name="nutrition_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Energy Kcal</label>
                            <input type="text" name="energy_kcal"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Water</label>
                            <input type="text" name="water"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fat</label>
                            <input type="text" name="fat"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Fiber</label>
                            <input type="text" name="total_fiber"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carbohydrate</label>
                            <input type="text" name="carbohydrate"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Protein</label>
                            <input type="text" name="protein"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vitamin C</label>
                            <input type="text" name="vitamin_c"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Insoluable Fiber</label>
                            <input type="text" name="insoluable_fiber"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Soluable Fiber</label>
                            <input type="text" name="soluable_fiber"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Biotin</label>
                            <input type="text" name="biotin"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Folates</label>
                            <input type="text" name="total_folates"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Calcium</label>
                            <input type="text" name="calcium"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CU</label>
                            <input type="text" name="cu"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">FE</label>
                            <input type="text" name="fe"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">MG</label>
                            <input type="text" name="mg"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">P</label>
                            <input type="text" name="p"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">K</label>
                            <input type="text" name="k"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SE</label>
                            <input type="text" name="se"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NA</label>
                            <input type="text" name="na"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ZA</label>
                            <input type="text" name="za"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-save mr-2"></i>Save Nutrition
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Nutrition Modal -->
    <!-- Edit Nutrition Modal -->
    <div id="editNutritionModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[9999]">
        <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-gray-300">
                <h3 class="text-2xl font-bold text-gray-800">Edit Nutrition Information</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editNutritionForm" action="" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_nutrition_id" name="id">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Column 1 -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nutrition Name</label>
                            <input type="text" id="edit_nutrition_name" name="nutrition_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Energy Kcal</label>
                            <input type="text" id="edit_energy_kcal" name="energy_kcal"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Water</label>
                            <input type="text" id="edit_water" name="water"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fat</label>
                            <input type="text" id="edit_fat" name="fat"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Fiber</label>
                            <input type="text" id="edit_total_fiber" name="total_fiber"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carbohydrate</label>
                            <input type="text" id="edit_carbohydrate" name="carbohydrate"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Protein</label>
                            <input type="text" id="edit_protein" name="protein"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vitamin C</label>
                            <input type="text" id="edit_vitamin_c" name="vitamin_c"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Insoluable Fiber</label>
                            <input type="text" id="edit_insoluable_fiber" name="insoluable_fiber"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Soluable Fiber</label>
                            <input type="text" id="edit_soluable_fiber" name="soluable_fiber"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Biotin</label>
                            <input type="text" id="edit_biotin" name="biotin"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Folates</label>
                            <input type="text" id="edit_total_folates" name="total_folates"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Calcium</label>
                            <input type="text" id="edit_calcium" name="calcium"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CU</label>
                            <input type="text" id="edit_cu" name="cu"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">FE</label>
                            <input type="text" id="edit_fe" name="fe"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">MG</label>
                            <input type="text" id="edit_mg" name="mg"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">P</label>
                            <input type="text" id="edit_p" name="p"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">K</label>
                            <input type="text" id="edit_k" name="k"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SE</label>
                            <input type="text" id="edit_se" name="se"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NA</label>
                            <input type="text" id="edit_na" name="na"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ZA</label>
                            <input type="text" id="edit_za" name="za"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit" id="editSubmitBtn"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-save mr-2"></i>Update Nutrition
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function toggleScroll(lock) {
            if (lock) {
                document.body.classList.add('modal-active');
                document.documentElement.classList.add('modal-active');
            } else {
                document.body.classList.remove('modal-active');
                document.documentElement.classList.remove('modal-active');
            }
        }

        function openModal() {
            document.getElementById('nutritionModal').classList.remove('hidden');
            toggleScroll(true);
        }

        function closeModal() {
            document.getElementById('nutritionModal').classList.add('hidden');
            document.getElementById('nutritionForm').reset();
            toggleScroll(false);
        }

        // File input handler
        document.getElementById('csvFile').addEventListener('change', function (e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
            document.getElementById('fileName').textContent = fileName;
        });

        // Close modal when clicking outside
        document.getElementById('nutritionModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Auto-hide messages after 3 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('[class*="bg-green-500"], [class*="bg-red-500"]');
            messages.forEach(message => {
                if (message.style.position === 'fixed') {
                    message.style.display = 'none';
                }
            });
        }, 3000);

        // CSV file upload handling
        document.getElementById('csvUploadForm').addEventListener('submit', function (e) {
            const fileInput = document.getElementById('csvFile');
            if (!fileInput.files.length) {
                e.preventDefault();
                alert('Please select a CSV file to upload.');
                return;
            }
        });

        // Edit Nutrition Functions
        function openEditModal(id) {
            // Show loading
            document.getElementById('editSubmitBtn').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            document.getElementById('editSubmitBtn').disabled = true;

            // Prevent background scrolling
            toggleScroll(true);

            // Fetch nutrition data
            fetch(`/nutrition/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate form fields
                        document.getElementById('edit_nutrition_id').value = data.data.id;
                        document.getElementById('edit_nutrition_name').value = data.data.nutrition_name || '';
                        document.getElementById('edit_energy_kcal').value = data.data.energy_kcal || '';
                        document.getElementById('edit_water').value = data.data.water || '';
                        document.getElementById('edit_fat').value = data.data.fat || '';
                        document.getElementById('edit_total_fiber').value = data.data.total_fiber || '';
                        document.getElementById('edit_carbohydrate').value = data.data.carbohydrate || '';
                        document.getElementById('edit_protein').value = data.data.protein || '';
                        document.getElementById('edit_vitamin_c').value = data.data.vitamin_c || '';
                        document.getElementById('edit_insoluable_fiber').value = data.data.insoluable_fiber || '';
                        document.getElementById('edit_soluable_fiber').value = data.data.soluable_fiber || '';
                        document.getElementById('edit_biotin').value = data.data.biotin || '';
                        document.getElementById('edit_total_folates').value = data.data.total_folates || '';
                        document.getElementById('edit_calcium').value = data.data.calcium || '';
                        document.getElementById('edit_cu').value = data.data.cu || '';
                        document.getElementById('edit_fe').value = data.data.fe || '';
                        document.getElementById('edit_mg').value = data.data.mg || '';
                        document.getElementById('edit_p').value = data.data.p || '';
                        document.getElementById('edit_k').value = data.data.k || '';
                        document.getElementById('edit_se').value = data.data.se || '';
                        document.getElementById('edit_na').value = data.data.na || '';
                        document.getElementById('edit_za').value = data.data.za || '';

                        // Set form action
                        document.getElementById('editNutritionForm').action = `/nutrition/${id}`;

                        // Show modal
                        document.getElementById('editNutritionModal').classList.remove('hidden');
                    } else {
                        showErrorMessage('Error loading nutrition data.');
                        toggleScroll(false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorMessage('Error loading nutrition data.');
                    toggleScroll(false);
                })
                .finally(() => {
                    // Reset button
                    document.getElementById('editSubmitBtn').innerHTML =
                        '<i class="fas fa-save mr-2"></i>Update Nutrition';
                    document.getElementById('editSubmitBtn').disabled = false;
                });
        }

        function closeEditModal() {
            document.getElementById('editNutritionModal').classList.add('hidden');
            document.getElementById('editNutritionForm').reset();
            toggleScroll(false);
        }

        // Edit form submission
        document.getElementById('editNutritionForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('editSubmitBtn');
            const originalText = submitBtn.innerHTML;
            const nutritionId = document.getElementById('edit_nutrition_id').value;

            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
            submitBtn.disabled = true;

            // Create form data manually to ensure all fields are captured
            const formData = new FormData();

            // Add all form fields manually
            formData.append('_method', 'PUT');
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('nutrition_name', document.getElementById('edit_nutrition_name').value || '');
            formData.append('energy_kcal', document.getElementById('edit_energy_kcal').value || '');
            formData.append('water', document.getElementById('edit_water').value || '');
            formData.append('fat', document.getElementById('edit_fat').value || '');
            formData.append('total_fiber', document.getElementById('edit_total_fiber').value || '');
            formData.append('carbohydrate', document.getElementById('edit_carbohydrate').value || '');
            formData.append('protein', document.getElementById('edit_protein').value || '');
            formData.append('vitamin_c', document.getElementById('edit_vitamin_c').value || '');
            formData.append('insoluable_fiber', document.getElementById('edit_insoluable_fiber').value || '');
            formData.append('soluable_fiber', document.getElementById('edit_soluable_fiber').value || '');
            formData.append('biotin', document.getElementById('edit_biotin').value || '');
            formData.append('total_folates', document.getElementById('edit_total_folates').value || '');
            formData.append('calcium', document.getElementById('edit_calcium').value || '');
            formData.append('cu', document.getElementById('edit_cu').value || '');
            formData.append('fe', document.getElementById('edit_fe').value || '');
            formData.append('mg', document.getElementById('edit_mg').value || '');
            formData.append('p', document.getElementById('edit_p').value || '');
            formData.append('k', document.getElementById('edit_k').value || '');
            formData.append('se', document.getElementById('edit_se').value || '');
            formData.append('na', document.getElementById('edit_na').value || '');
            formData.append('za', document.getElementById('edit_za').value || '');

            // Use POST method with _method parameter
            fetch(`/nutrition/${nutritionId}`, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    // First, try to parse the response as JSON
                    return response.json().then(data => {
                        // Check if the response was successful
                        if (!response.ok) {
                            // If not successful, throw an error with the server message
                            throw new Error(data.message || 'Network response was not ok');
                        }
                        return data;
                    });
                })
                .then(data => {
                    if (data.success) {
                        showSuccessMessage('Nutrition data updated successfully!');
                        closeEditModal(); // Close the modal
                        // Reload the page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Handle server-side validation errors or other issues
                        showErrorMessage(data.message || 'Failed to update nutrition data.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Only show alert for network errors, not for server validation errors
                    if (error.message.includes('Network')) {
                        showErrorMessage('Network error. Please check your connection and try again.');
                    } else {
                        showErrorMessage(error.message || 'An error occurred while updating nutrition data.');
                    }
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });

        // Success message function - FIXED
        function showSuccessMessage(message) {
            // Use the existing success message element
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.querySelector('span').textContent = message;
                successMessage.classList.remove('hidden');

                // Auto-hide after 3 seconds
                setTimeout(() => {
                    successMessage.classList.add('hidden');
                }, 3000);
            } else {
                // Fallback: create temporary success message
                const tempSuccess = document.createElement('div');
                tempSuccess.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                tempSuccess.innerHTML = '<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i><span>' +
                    message + '</span></div>';
                document.body.appendChild(tempSuccess);

                setTimeout(() => {
                    tempSuccess.remove();
                }, 3000);
            }
        }

        // Error message function - FIXED
        function showErrorMessage(message) {
            // Create error message element
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            errorDiv.innerHTML = '<div class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i><span>' +
                message + '</span></div>';
            document.body.appendChild(errorDiv);

            // Auto-hide after 5 seconds
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.remove();
                }
            }, 5000);
        }

        // Delete Nutrition
        function deleteNutrition(id) {
            if (confirm('Are you sure you want to delete this nutrition record?')) {
                fetch(`/nutrition/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessMessage('Nutrition data deleted successfully!');
                            // Remove the row from table
                            const row = document.getElementById(`nutrition-row-${id}`);
                            if (row) {
                                row.remove();
                            }

                            // Check if table is empty and show message
                            const tbody = document.querySelector('tbody');
                            if (tbody && tbody.children.length === 0) {
                                window.location.reload();
                            }
                        } else {
                            showErrorMessage('Error: ' + (data.message || 'Failed to delete nutrition data.'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorMessage('Error deleting nutrition data. Please try again.');
                    });
            }
        }

        // Close edit modal when clicking outside
        document.getElementById('editNutritionModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Also add click outside for nutrition modal (if not already there)
        document.getElementById('nutritionModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Advanced Search Functionality
        const searchBtn = document.getElementById('searchBtn');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const searchName = document.getElementById('searchName');
        const searchProteinMin = document.getElementById('searchProteinMin');
        const searchProteinMax = document.getElementById('searchProteinMax');
        const searchEnergyMin = document.getElementById('searchEnergyMin');
        const searchEnergyMax = document.getElementById('searchEnergyMax');

        // Search function
        function performSearch() {
            const nameQuery = searchName.value.toLowerCase().trim();
            const proteinMin = parseFloat(searchProteinMin.value) || 0;
            const proteinMax = parseFloat(searchProteinMax.value) || Infinity;
            const energyMin = parseFloat(searchEnergyMin.value) || 0;
            const energyMax = parseFloat(searchEnergyMax.value) || Infinity;

            const rows = document.querySelectorAll('tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length > 0) {
                    const nutritionName = cells[0].textContent.toLowerCase();
                    const protein = parseFloat(cells[5].textContent) || 0; // Protein is in column 5
                    const energy = parseFloat(cells[1].textContent) || 0; // Energy is in column 1

                    // Check if row matches all search criteria
                    const nameMatch = !nameQuery || nutritionName.includes(nameQuery);
                    const proteinMatch = protein >= proteinMin && protein <= proteinMax;
                    const energyMatch = energy >= energyMin && energy <= energyMax;

                    if (nameMatch && proteinMatch && energyMatch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            // Show "no results" message if needed
            let noResultsMsg = document.getElementById('noResultsMsg');
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('tr');
                    noResultsMsg.id = 'noResultsMsg';
                    noResultsMsg.innerHTML = '<td colspan="22" class="text-center py-4 text-gray-500">No nutrition items found matching your search criteria.</td>';
                    document.querySelector('tbody').appendChild(noResultsMsg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        // Clear search function
        function clearSearch() {
            searchName.value = '';
            searchProteinMin.value = '';
            searchProteinMax.value = '';
            searchEnergyMin.value = '';
            searchEnergyMax.value = '';

            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                if (row.id !== 'noResultsMsg') {
                    row.style.display = '';
                }
            });

            const noResultsMsg = document.getElementById('noResultsMsg');
            if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        // Event listeners for search
        searchBtn.addEventListener('click', performSearch);
        clearSearchBtn.addEventListener('click', clearSearch);

        // Real-time search on name input
        searchName.addEventListener('input', performSearch);

        // Search on Enter key for all search inputs
        [searchName, searchProteinMin, searchProteinMax, searchEnergyMin, searchEnergyMax].forEach(input => {
            input.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });
        });
    </script>


@endsection
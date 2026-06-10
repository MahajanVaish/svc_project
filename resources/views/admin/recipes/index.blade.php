@extends('admin.layouts.layouts')

@section('title', 'recipes')

@section('content')

    <div class="recipe-section">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <h1 class="recipe-title">Add Recipe</h1>

        <form id="addRecipeForm" method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="recipeName">Recipe Name</label>
                <input type="text" id="recipeName" name="recipe_name" class="form-control" placeholder="Enter recipe name"
                    required>
            </div>

            <div class="form-group mb-4">
                <label for="recipeImage">Recipe Photo</label>
                <input type="file" id="recipeImage" name="recipe_image" class="form-control" accept="image/*">
                <div id="imagePreview" class="mt-2" style="display: none;">
                    <img src="" alt="Preview" style="max-height: 150px; border-radius: 8px; border: 1px solid var(--border-subtle);">
                </div>
            </div>

            <div id="nutritionContainer">
                <div class="nutrition-row" id="nutritionRow0">
                    <div class="nutrition-field">
                        <label for="nutritionSelect0">Select Nutrition</label>
                        <select id="nutritionSelect0" name="nutrition_data[0][nutrition_id]"
                            class="form-control nutrition-select" required>
                            <option value="">Select nutrition</option>
                            @foreach($nutritions as $nutrition)
                                <option value="{{ $nutrition->id }}" data-nutrition='@json($nutrition)'>
                                    {{ $nutrition->nutrition_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="nutrition-field">
                        <label for="quantity0">Quantity ml/gm</label>
                        <input type="number" step="0.01" id="quantity0" name="nutrition_data[0][quantity]"
                            class="form-control quantity-input" placeholder="Enter quantity" required>
                    </div>
                    <div class="nutrition-field" style="flex: 0 0 auto;">
                        <button type="button" class="btn-remove remove-nutrition-btn" data-row="0">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="action-buttons-row">
                <button type="button" id="addNutritionBtn" class="btn-add">
                    <i class="fas fa-plus"></i> Add More
                </button>
                <button type="submit" class="btn-primary" id="submitBtn">
                    <i class="fas fa-plus-circle"></i> Submit
                </button>
            </div>
        </form>

        <!-- Nutrition Data Table Section -->
        <div class="nutrition-data-section" id="nutritionDataSection">
            <div class="nutrition-header">Nutrition Data</div>

            <!-- Ingredient List -->
            <div class="ingredient-list" id="ingredientList">
                <h4>Ingredients:</h4>
                <ul id="ingredientListItems">
                    <!-- Ingredient list will be populated here -->
                </ul>
            </div>

            <div class="nutrition-data-table-container">
                <table class="nutrition-data-table" id="nutritionDataTable">
                    <thead>
                        <tr>
                            <th>Ingredient</th>
                            <th>Energy kcal</th>
                            <th>Water</th>
                            <th>Fat</th>
                            <th>Total fiber</th>
                            <th>Carbohydrate</th>
                            <th>Protein</th>
                            <th>Vitamin C</th>
                            <th>Insoluble fiber</th>
                            <th>Soluble fiber</th>
                            <th>Biotin</th>
                            <th>Total folates</th>
                            <th>Calcium</th>
                            <th>Cu</th>
                            <th>Fe</th>
                            <th>Mg</th>
                            <th>P</th>
                            <th>K</th>
                            <th>Se</th>
                            <th>Na</th>
                            <th>Zn</th>
                        </tr>
                    </thead>
                    <tbody id="nutritionDataBody">
                        <!-- Nutrition data will be populated here dynamically -->
                    </tbody>
                    <tfoot id="nutritionDataFooter">
                        <!-- Total row will be added here -->
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Advanced Search Section -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Advanced Search</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search by Recipe Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipe Name</label>
                    <input type="text" id="searchRecipeName" placeholder="Search by recipe name..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Search by Nutrition Ingredient -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nutrition Ingredient</label>
                    <input type="text" id="searchNutritionIngredient" placeholder="Search by nutrition ingredient..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Search by Recipe ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipe ID</label>
                    <input type="number" id="searchRecipeId" placeholder="Search by ID..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Search Buttons -->
            <div class="flex gap-2 mt-4">
                <button type="button" id="searchRecipeBtn" class="btn-primary-custom text-white px-4 py-2 rounded">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <button type="button" id="clearRecipeSearchBtn"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i>Clear
                </button>
            </div>
        </div>

        <h2 class="section-title">Edit/delete Recipe</h2>

        <!-- Recipe List Table -->
        <table class="recipe-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Recipe Name</th>
                    <th>Nutrition Names</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="recipeTableBody">
                @foreach($recipes as $index => $recipe)
                    <tr id="recipe-row-{{ $recipe->id }}">
                        <td>{{ ($recipes->currentPage() - 1) * $recipes->perPage() + $loop->iteration }}</td>
                        <td class="text-center">
                            @if($recipe->image)
                                <img src="{{ asset($recipe->image) }}" alt="{{ $recipe->name }}" width="60" style="border-radius: 4px; border: 1px solid var(--border-subtle);">
                            @else
                                <div style="width: 60px; height: 40px; background: var(--bg-dark); border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $recipe->name }}</td>
                        <td>
                            @php
                                $description = $recipe->description;
                                $decoded = json_decode($description, true);
                            @endphp
                            @if(is_array($decoded))
                                @foreach($decoded as $name => $quantity)
                                    {{ $name }}: {{ $quantity }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            @elseif($description && trim($description) !== '')
                                {{ $description }}
                            @else
                                <span class="text-muted">No nutrition data</span>
                            @endif
                        </td>
                        <td class="action-buttons">
                            <button class="action-btn btn-edit-square" onclick="editRecipe({{ $recipe->id }})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete-square" onclick="deleteRecipe({{ $recipe->id }})"
                                title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <!-- Pagination -->
        <div class="pagination-info">
            <!-- Showing {{ $recipes->firstItem() }} to {{ $recipes->lastItem() }} of {{ $recipes->total() }} entries -->
        </div>
        <div class="pagination">
            {{ $recipes->links() }}
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Image preview logic
            const recipeImage = document.getElementById('recipeImage');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = imagePreview.querySelector('img');

            if (recipeImage) {
                recipeImage.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            imagePreview.style.display = 'block';
                        }
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        imagePreview.style.display = 'none';
                    }
                });
            }

            let nutritionRowCount = 1;
            const nutritionData = @json($nutritions);
            let selectedNutritions = [];

            // Function to add a new nutrition row
            function addNutritionRow() {
                const nutritionContainer = document.getElementById('nutritionContainer');
                const newRow = document.createElement('div');
                newRow.className = 'nutrition-row';
                newRow.id = `nutritionRow${nutritionRowCount}`;

                newRow.innerHTML = `
                            <div class="nutrition-field">
                                <label for="nutritionSelect${nutritionRowCount}">Select Nutrition</label>
                                <select id="nutritionSelect${nutritionRowCount}" name="nutrition_data[${nutritionRowCount}][nutrition_id]" class="form-control nutrition-select" required>
                                    <option value="">Select nutrition</option>
                                    ${nutritionData.map(nutrition =>
                    `<option value="${nutrition.id}" data-nutrition='${JSON.stringify(nutrition)}'>${nutrition.nutrition_name}</option>`
                ).join('')}
                                </select>
                            </div>
                            <div class="nutrition-field">
                                <label for="quantity${nutritionRowCount}">Quantity ml/gm</label>
                                <input type="number" step="0.01" id="quantity${nutritionRowCount}" name="nutrition_data[${nutritionRowCount}][quantity]" class="form-control quantity-input" placeholder="Enter quantity" required>
                            </div>
                            <div class="nutrition-field" style="flex: 0 0 auto;">
                                <button type="button" class="btn-remove remove-nutrition-btn" data-row="${nutritionRowCount}">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        `;

                nutritionContainer.appendChild(newRow);

                // Add event listeners to new row
                const removeBtn = newRow.querySelector('.remove-nutrition-btn');
                removeBtn.addEventListener('click', function () {
                    removeNutritionRow(this.getAttribute('data-row'));
                });

                const selectElement = newRow.querySelector('.nutrition-select');
                selectElement.addEventListener('change', updateNutritionTable);

                const quantityInput = newRow.querySelector('.quantity-input');
                quantityInput.addEventListener('input', updateNutritionTable);

                nutritionRowCount++;
            }

            // Function to remove a nutrition row
            function removeNutritionRow(rowId) {
                const rowToRemove = document.getElementById(`nutritionRow${rowId}`);
                if (rowToRemove) {
                    rowToRemove.remove();
                    updateNutritionTable();
                }
            }

            // Function to update the nutrition table
            function updateNutritionTable() {
                const nutritionDataBody = document.getElementById('nutritionDataBody');
                const nutritionDataFooter = document.getElementById('nutritionDataFooter');
                const ingredientListItems = document.getElementById('ingredientListItems');

                // Clear previous data
                nutritionDataBody.innerHTML = '';
                nutritionDataFooter.innerHTML = '';
                ingredientListItems.innerHTML = '';

                selectedNutritions = [];
                const nutritionRows = document.querySelectorAll('.nutrition-row');

                // Initialize totals
                let totals = {
                    energy_kcal: 0, water: 0, fat: 0, total_fiber: 0, carbohydrate: 0,
                    protein: 0, vitamin_c: 0, insoluble_fiber: 0, soluble_fiber: 0,
                    biotin: 0, total_folates: 0, calcium: 0, cu: 0, fe: 0, mg: 0,
                    p: 0, k: 0, se: 0, na: 0, zn: 0
                };

                // Process each nutrition row
                nutritionRows.forEach((row, index) => {
                    const selectElement = row.querySelector('.nutrition-select');
                    const quantityInput = row.querySelector('.quantity-input');

                    if (selectElement.value && quantityInput.value) {
                        const nutritionId = selectElement.value;
                        const quantity = parseFloat(quantityInput.value) || 0;
                        const nutrition = nutritionData.find(n => n.id == nutritionId);

                        if (nutrition) {
                            selectedNutritions.push({ nutrition, quantity });

                            // Calculate values based on per 100g nutrition values
                            const calcValue = (field) => {
                                const value = parseFloat(nutrition[field]) || 0;
                                // Nutrition values are stored per 100g, so calculate based on actual quantity
                                return ((value / 100) * quantity).toFixed(2);
                            };

                            const numValue = (field) => parseFloat(calcValue(field));

                            // Note: Your database has 'insoluable_fiber' and 'soluable_fiber' (spelling differences)
                            const rowData = {
                                name: nutrition.nutrition_name,
                                energy_kcal: calcValue('energy_kcal'),
                                water: calcValue('water'),
                                fat: calcValue('fat'),
                                total_fiber: calcValue('total_fiber'),
                                carbohydrate: calcValue('carbohydrate'),
                                protein: calcValue('protein'),
                                vitamin_c: calcValue('vitamin_c'),
                                insoluble_fiber: calcValue('insoluable_fiber'), // Note spelling
                                soluble_fiber: calcValue('soluable_fiber'), // Note spelling
                                biotin: calcValue('biotin'),
                                total_folates: calcValue('total_folates'),
                                calcium: calcValue('calcium'),
                                cu: calcValue('cu'),
                                fe: calcValue('fe'),
                                mg: calcValue('mg'),
                                p: calcValue('p'),
                                k: calcValue('k'),
                                se: calcValue('se'),
                                na: calcValue('na'),
                                zn: calcValue('za')
                            };

                            // Add to totals
                            Object.keys(totals).forEach(key => {
                                if (key === 'zn') {
                                    totals[key] += numValue('za'); // Use 'za' for zinc
                                } else if (key === 'insoluble_fiber') {
                                    totals[key] += numValue('insoluable_fiber'); // Use correct field name
                                } else if (key === 'soluble_fiber') {
                                    totals[key] += numValue('soluable_fiber'); // Use correct field name
                                } else {
                                    totals[key] += numValue(key);
                                }
                            });

                            // Create table row
                            const rowElement = document.createElement('tr');
                            rowElement.innerHTML = `
                                        <td>${rowData.name}</td>
                                        <td>${rowData.energy_kcal}</td>
                                        <td>${rowData.water}</td>
                                        <td>${rowData.fat}</td>
                                        <td>${rowData.total_fiber}</td>
                                        <td>${rowData.carbohydrate}</td>

                                        <td>${rowData.protein}</td>
                                        <td>${rowData.vitamin_c}</td>
                                        <td>${rowData.insoluble_fiber}</td>
                                        <td>${rowData.soluble_fiber}</td>
                                        <td>${rowData.biotin}</td>
                                        <td>${rowData.total_folates}</td>
                                        <td>${rowData.calcium}</td>
                                        <td>${rowData.cu}</td>
                                        <td>${rowData.fe}</td>
                                        <td>${rowData.mg}</td>
                                        <td>${rowData.p}</td>
                                        <td>${rowData.k}</td>
                                        <td>${rowData.se}</td>
                                        <td>${rowData.na}</td>
                                        <td>${rowData.zn}</td>
                                    `;

                            nutritionDataBody.appendChild(rowElement);

                            // Add to ingredient list
                            const listItem = document.createElement('li');
                            listItem.textContent = `${nutrition.nutrition_name} - ${quantity} ml/gm`;
                            ingredientListItems.appendChild(listItem);
                        }
                    }
                });

                // Add total row if we have data
                if (selectedNutritions.length > 0) {
                    const totalRow = document.createElement('tr');
                    totalRow.className = 'total-row';
                    totalRow.innerHTML = `
                                <td>Total</td>
                                <td>${totals.energy_kcal.toFixed(2)}</td>
                                <td>${totals.water.toFixed(2)}</td>
                                <td>${totals.fat.toFixed(2)}</td>
                                <td>${totals.total_fiber.toFixed(2)}</td>
                                <td>${totals.carbohydrate.toFixed(2)}</td>
                                <td>${totals.protein.toFixed(2)}</td>
                                <td>${totals.vitamin_c.toFixed(2)}</td>
                                <td>${totals.insoluble_fiber.toFixed(2)}</td>
                                <td>${totals.soluble_fiber.toFixed(2)}</td>
                                <td>${totals.biotin.toFixed(2)}</td>
                                <td>${totals.total_folates.toFixed(2)}</td>
                                <td>${totals.calcium.toFixed(2)}</td>
                                <td>${totals.cu.toFixed(2)}</td>
                                <td>${totals.fe.toFixed(2)}</td>
                                <td>${totals.mg.toFixed(2)}</td>
                                <td>${totals.p.toFixed(2)}</td>
                                <td>${totals.k.toFixed(2)}</td>
                                <td>${totals.se.toFixed(2)}</td>
                                <td>${totals.na.toFixed(2)}</td>
                                <td>${totals.zn.toFixed(2)}</td>
                            `;

                    nutritionDataFooter.appendChild(totalRow);
                }

                // Show/hide nutrition data section
                const nutritionDataSection = document.getElementById('nutritionDataSection');
                nutritionDataSection.style.display = selectedNutritions.length > 0 ? 'block' : 'none';
            }

            // Add event listeners
            document.getElementById('addNutritionBtn').addEventListener('click', addNutritionRow);

            document.querySelectorAll('.nutrition-select').forEach(select => {
                select.addEventListener('change', updateNutritionTable);
            });

            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('input', updateNutritionTable);
            });

            document.querySelectorAll('.remove-nutrition-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    removeNutritionRow(this.getAttribute('data-row'));
                });
            });

            // Initialize nutrition table on page load if there are values
            updateNutritionTable();

            // FORM SUBMISSION HANDLER - FIXED
            document.getElementById('addRecipeForm').addEventListener('submit', function (e) {
                // Basic validation before allowing form to submit
                const recipeName = document.getElementById('recipeName').value;

                if (!recipeName.trim()) {
                    e.preventDefault();
                    alert('Please enter recipe name');
                    return false;
                }

                // Check if at least one nutrition is added
                const nutritionRows = document.querySelectorAll('.nutrition-row');
                let hasValidNutrition = false;

                nutritionRows.forEach(row => {
                    const selectElement = row.querySelector('.nutrition-select');
                    const quantityInput = row.querySelector('.quantity-input');

                    if (selectElement.value && quantityInput.value) {
                        hasValidNutrition = true;
                    }
                });

                if (!hasValidNutrition) {
                    e.preventDefault();
                    alert('Please add at least one nutrition with quantity');
                    return false;
                }

                // Check for duplicate nutrition selections
                const nutritionSelections = [];
                let hasDuplicate = false;

                nutritionRows.forEach(row => {
                    const selectElement = row.querySelector('.nutrition-select');
                    if (selectElement.value) {
                        if (nutritionSelections.includes(selectElement.value)) {
                            hasDuplicate = true;
                        }
                        nutritionSelections.push(selectElement.value);
                    }
                });

                if (hasDuplicate) {
                    e.preventDefault();
                    alert('Duplicate nutrition selection found. Please select different nutritions.');
                    return false;
                }

                // Show loading indicator on submit button
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                submitBtn.disabled = true;

                // Allow the form to submit normally
                return true;
            });
        });

        // Edit recipe function
        function editRecipe(recipeId) {
            // Redirect to edit page
            window.location.href = `/recipes/${recipeId}/edit`;
        }

        function getCsrfToken() {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                return csrfMeta.getAttribute('content');
            }


            const csrfInput = document.querySelector('input[name="_token"]');
            if (csrfInput) {
                return csrfInput.value;
            }


            console.warn('CSRF token not found');
            return '';
        }


        function deleteRecipe(recipeId) {
            if (confirm('Are you sure you want to delete this recipe?')) {

                const csrfToken = getCsrfToken();

                if (!csrfToken) {
                    alert('Security token not found. Please refresh the page and try again.');
                    return;
                }

                const deleteBtn = document.querySelector(`#recipe-row-${recipeId} .btn-delete`);
                const originalText = deleteBtn ? deleteBtn.innerHTML : '';
                if (deleteBtn) {
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                    deleteBtn.disabled = true;
                }

                fetch(`/recipes/${recipeId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {

                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        }
                        return response.text().then(text => {
                            try {
                                return JSON.parse(text);
                            } catch {
                                return { success: true, message: 'Recipe deleted successfully' };
                            }
                        });
                    })
                    .then(data => {
                        if (data.success) {

                            const rowToRemove = document.getElementById(`recipe-row-${recipeId}`);
                            if (rowToRemove) {
                                rowToRemove.style.transition = 'opacity 0.3s';
                                rowToRemove.style.opacity = '0';

                                setTimeout(() => {
                                    rowToRemove.remove();

                                    updateRecipeTableNumbers();
                                }, 300);
                            }


                            showAlert('success', 'Recipe deleted successfully!');

                        } else {
                            throw new Error(data.message || 'Error deleting recipe');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);


                        if (deleteBtn) {
                            deleteBtn.innerHTML = originalText;
                            deleteBtn.disabled = false;
                        }

                        showAlert('error', 'Error deleting recipe: ' + error.message);
                    });
            }
        }


        function updateRecipeTableNumbers() {
            const rows = document.querySelectorAll('#recipeTableBody tr');
            rows.forEach((row, index) => {
                const firstCell = row.querySelector('td:first-child');
                if (firstCell) {
                    firstCell.textContent = index + 1;
                }
            });
        }


        function showAlert(type, message) {

            const existingAlerts = document.querySelectorAll('.custom-alert');
            existingAlerts.forEach(alert => alert.remove());


            const alertDiv = document.createElement('div');
            alertDiv.className = `custom-alert ${type}`;
            alertDiv.innerHTML = `
                        <div class="alert-content">
                            <div class="alert-icon">${type === 'success' ? '✓' : '✗'}</div>
                            <div class="alert-message">${message}</div>
                            <button class="alert-close" onclick="this.parentElement.parentElement.remove()">×</button>
                        </div>
                    `;


            if (!document.querySelector('#alert-styles')) {
                const styleTag = document.createElement('style');
                styleTag.id = 'alert-styles';
                styleTag.textContent = `
                            .custom-alert {
                                position: fixed;
                                top: 20px;
                                right: 20px;
                                min-width: 300px;
                                max-width: 400px;
                                background: white;
                                border-radius: 8px;
                                box-shadow: 0 6px 20px rgba(0,0,0,0.15);
                                z-index: 9999;
                                border-left: 4px solid #4CAF50;
                                animation: slideIn 0.3s ease;
                            }
                            .custom-alert.error {
                                border-left-color: #f44336;
                            }
                            .alert-content {
                                display: flex;
                                align-items: center;
                                padding: 15px;
                                gap: 10px;
                            }
                            .alert-icon {
                                width: 30px;
                                height: 30px;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-weight: bold;
                                background-color: #4CAF50;
                                color: white;
                            }
                            .custom-alert.error .alert-icon {
                                background-color: #f44336;
                            }
                            .alert-message {
                                flex: 1;
                                color: #333;
                            }
                            .alert-close {
                                background: none;
                                border: none;
                                color: #999;
                                cursor: pointer;
                                font-size: 20px;
                                padding: 0;
                                width: 30px;
                                height: 30px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                border-radius: 50%;
                            }
                            .alert-close:hover {
                                background-color: #f5f5f5;
                                color: #333;
                            }
                            @keyframes slideIn {
                                from {
                                    transform: translateX(100%);
                                    opacity: 0;
                                }
                                to {
                                    transform: translateX(0);
                                    opacity: 1;
                                }
                            }
                        `;
                document.head.appendChild(styleTag);
            }

            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Recipe Advanced Search Functionality
        const searchRecipeBtn = document.getElementById('searchRecipeBtn');
        const clearRecipeSearchBtn = document.getElementById('clearRecipeSearchBtn');
        const searchRecipeName = document.getElementById('searchRecipeName');
        const searchNutritionIngredient = document.getElementById('searchNutritionIngredient');
        const searchRecipeId = document.getElementById('searchRecipeId');

        // Recipe search function
        function performRecipeSearch() {
            const nameQuery = searchRecipeName.value.toLowerCase().trim();
            const ingredientQuery = searchNutritionIngredient.value.toLowerCase().trim();
            const idQuery = searchRecipeId.value.trim();

            const rows = document.querySelectorAll('#recipeTableBody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length > 0 && row.id !== 'noRecipeResultsMsg') {
                    const recipeId = cells[0].textContent.trim();
                    const recipeName = cells[2].textContent.toLowerCase();
                    const nutritionNames = cells[3].textContent.toLowerCase();

                    // Check if row matches all search criteria
                    const idMatch = !idQuery || recipeId.includes(idQuery);
                    const nameMatch = !nameQuery || recipeName.includes(nameQuery);
                    const ingredientMatch = !ingredientQuery || nutritionNames.includes(ingredientQuery);

                    if (idMatch && nameMatch && ingredientMatch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            // Show "no results" message if needed
            let noResultsMsg = document.getElementById('noRecipeResultsMsg');
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('tr');
                    noResultsMsg.id = 'noRecipeResultsMsg';
                    noResultsMsg.innerHTML = '<td colspan="5" class="text-center py-4 text-gray-500">No recipes found matching your search criteria.</td>';
                    document.querySelector('#recipeTableBody').appendChild(noResultsMsg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        // Clear recipe search function
        function clearRecipeSearch() {
            searchRecipeName.value = '';
            searchNutritionIngredient.value = '';
            searchRecipeId.value = '';

            const rows = document.querySelectorAll('#recipeTableBody tr');
            rows.forEach(row => {
                if (row.id !== 'noRecipeResultsMsg') {
                    row.style.display = '';
                }
            });

            const noResultsMsg = document.getElementById('noRecipeResultsMsg');
            if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        // Event listeners for recipe search
        searchRecipeBtn.addEventListener('click', performRecipeSearch);
        clearRecipeSearchBtn.addEventListener('click', clearRecipeSearch);

        // Real-time search on recipe name and ingredient inputs
        searchRecipeName.addEventListener('input', performRecipeSearch);
        searchNutritionIngredient.addEventListener('input', performRecipeSearch);

        // Search on Enter key for all search inputs
        [searchRecipeName, searchNutritionIngredient, searchRecipeId].forEach(input => {
            input.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performRecipeSearch();
                }
            });
        });
    </script>

    <style>
        .dropdown {
            position: relative;
        }

        .dropdown>a {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dropdown>a::after {
            content: "▼";
            font-size: 10px;
            margin-left: 4px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background: white;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            border-radius: 4px;
            border: 1px solid #d1d7dd;
            top: 100%;
            left: 0;
        }

        .dropdown-content a {
            color: #6c757d;
            padding: 12px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            border-bottom: 1px solid #f1f1f1;
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .dropdown-content a:hover {
            background: #f8f9fa;
            color: rgb(8, 104, 56);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover>a {
            color: var(--accent-solid);
            background: var(--accent-glow);
        }

        .main-content {
            padding: 30px;
        }

        .section-title {
            color: var(--text-primary);
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .recipe-section {
            max-width: 1600px;
            margin: 0 auto;
            padding: 30px;
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .recipe-section {
                padding: 15px;
                margin-top: 10px;
                border-radius: 0;
                border-left: none;
                border-right: none;
            }

            .main-content {
                padding: 10px;
            }
        }


        .recipe-title {
            color: #086838;
            margin-bottom: 25px;
            text-align: center;
            font-size: 26px;
            font-weight: 700;
        }

        .divider {
            height: 1px;
            background-color: var(--border-subtle);
            margin: 35px 0;
        }

        .section-title {
            color: var(--text-primary);
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .form-group label,
        .nutrition-field label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 11px 16px;
            background-color: var(--bg-hover);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-solid);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .btn-primary {
            background-color: #086838;
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: fit-content;
            box-shadow: 0 4px 6px -1px rgba(8, 104, 56, 0.1);
        }

        .btn-primary:hover {
            background-color: #064e2b;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(8, 104, 56, 0.2);
        }

        .btn-primary:disabled {
            background-color: var(--bg-hover);
            color: var(--text-muted);
            cursor: not-allowed;
            opacity: 0.7;
        }

        .recipe-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid var(--border-subtle);
        }

        .recipe-table th,
        .recipe-table td {
            padding: 14px 18px;
            text-align: left;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-primary);
        }

        .recipe-table th {
            background-color: var(--bg-hover);
            font-weight: 700;
            color: var(--text-primary);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
        }

        .recipe-table tr:hover {
            background-color: var(--bg-hover);
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
        }

        .btn-edit-square:hover {
            background-color: #16a34a;
            color: white;
        }

        .btn-delete-square {
            border-color: #dc3545;
            color: #dc3545;
        }

        .btn-delete-square:hover {
            background-color: #dc3545;
            color: white;
        }

        .btn-primary-custom {
            background-color: #0d6efd !important;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
        }

        /* Nutrition Data Table Styles */
        .nutrition-data-section {
            margin-top: 35px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            overflow: hidden;
            display: none;
        }

        .nutrition-header {
            background-color: var(--accent-solid);
            color: white;
            padding: 15px 20px;
            font-weight: 700;
            font-size: 18px;
        }

        .nutrition-data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .nutrition-data-table th,
        .nutrition-data-table td {
            padding: 10px 12px;
            text-align: center;
            border: 1px solid var(--border-subtle);
            white-space: nowrap;
            color: var(--text-primary);
        }

        .nutrition-data-table th {
            background-color: var(--bg-hover);
            color: var(--text-primary);
            font-weight: 700;
        }

        .nutrition-data-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .nutrition-data-table tr:hover {
            background-color: var(--bg-hover);
        }

        .total-row {
            background-color: var(--bg-hover) !important;
            font-weight: 700;
        }

        .ingredient-list {
            margin: 20px;
            padding: 15px;
            background-color: var(--bg-hover);
            border-radius: 6px;
            border: 1px solid var(--border-subtle);
        }

        .ingredient-list h4 {
            margin-bottom: 12px;
            color: var(--text-primary);
            font-weight: 700;
        }

        .ingredient-list li {
            padding: 8px 0;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-primary);
        }

        .nutrition-row {
            display: flex;
            gap: 20px;
            align-items: flex-end;
            margin-bottom: 20px;
        }

        .nutrition-field {
            flex: 1;
        }

        .action-buttons-row {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .btn-add {
            background-color: #10b981;
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1);
        }

        .btn-add:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);
        }

        .btn-remove {
            background-color: #ef4444;
            border: none;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-remove:hover {
            background-color: #dc2626;
            transform: scale(1.05);
        }

        .pagination-info {
            margin-top: 20px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .pagination {
            display: flex;
            gap: 8px;
            margin-top: 15px;
            justify-content: flex-end;
        }

        .page-link {
            padding: 10px 18px;
            border: 1px solid var(--border-subtle);
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-primary);
            font-size: 14px;
            background: var(--bg-card);
            transition: all 0.3s ease;
        }

        .page-link.active {
            background-color: var(--accent-solid);
            color: white;
            border-color: var(--accent-solid);
        }

        .page-link:hover:not(.active) {
            background-color: var(--bg-hover);
        }

        /* Loading spinner styles */
        .fa-spinner {
            margin-right: 8px;
        }

        .fa-spin {
            animation: fa-spin 1s infinite linear;
        }

        @keyframes fa-spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Fade animation for deleted rows */
        .fade-out {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .nav-links {
                gap: 10px;
            }

            .nav-links a {
                font-size: 13px;
                padding: 6px 8px;
            }

            .nutrition-data-table {
                font-size: 11px;
            }

            .nutrition-data-table th,
            .nutrition-data-table td {
                padding: 6px 8px;
            }
        }

        @media (max-width: 992px) {
            .navbar {
                flex-direction: column;
                padding: 15px;
            }

            .navbar-brand {
                padding-left: 0;
                margin-bottom: 15px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .user-info {
                justify-content: center;
                margin-top: 10px;
            }

            .nutrition-data-table-container {
                overflow-x: auto;
            }
        }

        @media (max-width: 768px) {
            .recipe-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .action-buttons {
                display: flex !important;
                flex-direction: row !important;
                gap: 5px;
            }

            .nutrition-row {
                flex-direction: column;
                gap: 10px;
                padding: 15px;
                background: #fdfdfd;
                border: 1px solid #eee;
                border-radius: 8px;
            }

            .nutrition-field {
                width: 100%;
            }

            .btn-remove {
                width: 100%;
                justify-content: center;
                margin-top: 5px;
            }

            .action-buttons-row {
                flex-direction: column;
                width: 100%;
            }

            .btn-add,
            .btn-primary {
                width: 100% !important;
            }

            .nutrition-data-table {
                font-size: 10px;
            }

            .recipe-title {
                font-size: 20px;
            }

            .section-title {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .grid-cols-1 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }

            .flex.gap-2 {
                flex-direction: column;
            }

            #searchRecipeBtn,
            #clearRecipeSearchBtn {
                width: 100%;
            }
        }
    </style>
@endsection
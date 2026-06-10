@extends('admin.layouts.layouts')

@section('title', 'recipes')

@section('content')
    <style>
        .main-content {
            padding: 30px;
            background: var(--bg-dark);
            min-height: 100vh;
        }

        .recipe-section {
            max-width: 1600px;
            margin: 0 auto;
            padding: 30px;
            background: var(--bg-card);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-subtle);
        }

        .section-title {
            color: var(--accent-solid);
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group label, .nutrition-field label, #editRecipeForm h3 {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary) !important;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-subtle);
            background-color: var(--bg-dark);
            color: var(--text-primary);
            border-radius: 6px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-solid);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .btn-primary {
            background-color: var(--accent-solid);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
        }

        .btn-primary:hover {
            background-color: var(--accent-dark);
            transform: translateY(-1px);
        }

        .nutrition-row {
            display: flex;
            gap: 20px;
            align-items: flex-end;
            margin-bottom: 20px;
            padding: 20px;
            background: var(--bg-dark);
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
        }

        .nutrition-field {
            flex: 1;
        }

        .action-buttons-row {
            display: flex;
            gap: 15px;
            margin-top: 15px;
            margin-bottom: 25px;
        }

        .btn-add {
            background-color: var(--accent-solid);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-remove {
            background-color: #ef4444;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-remove:hover {
            background-color: #dc2626;
        }

        .nutrition-data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .nutrition-data-table th,
        .nutrition-data-table td {
            padding: 12px 10px;
            text-align: center;
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
        }

        .nutrition-data-table th {
            background-color: var(--bg-dark);
            font-weight: 700;
        }

        .nutrition-header {
            background-color: var(--accent-solid);
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            font-weight: 700;
            font-size: 18px;
        }

        .total-row {
            background-color: var(--bg-dark) !important;
            font-weight: 700;
        }

        .nutrition-data-table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: 1px solid var(--border-subtle);
            border-top: none;
            border-radius: 0 0 8px 8px;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }
            .recipe-section {
                padding: 20px 15px;
            }
            .nutrition-row {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
                padding: 15px;
            }
            .btn-primary {
                width: 100%;
                justify-content: center;
            }
            .section-title {
                font-size: 22px;
            }
        }

        .divider {
            height: 1px;
            background-color: var(--border-subtle);
            margin: 35px 0;
        }

        .btn-back {
            background-color: var(--bg-dark);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: var(--bg-card);
            border-color: var(--accent-solid);
            color: var(--accent-solid);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-success {
            background: rgba(22, 163, 74, 0.1);
            color: var(--accent-solid);
            border-color: rgba(22, 163, 74, 0.2);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.2);
        }

        @media (max-width: 992px) {
            .nutrition-row {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 15px;
            }
            .nutrition-field {
                width: 100%;
            }
            .btn-remove {
                width: 100%;
                display: flex;
                justify-content: center;
                margin-top: 10px;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 15px;
            }
            .recipe-section {
                padding: 15px;
            }
            .section-title {
                font-size: 20px;
            }
            .nutrition-header {
                font-size: 16px;
                padding: 12px 15px;
            }
            .btn-primary {
                width: 100%;
                justify-content: center;
            }
            .form-group {
                width: 100%;
            }
        }

        .nutrition-data-table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0 0 8px 8px;
            border: 1px solid var(--border-subtle);
        }

        .nutrition-data-table {
            min-width: 1200px; /* Ensure table remains readable and triggers horizontal scroll */
        }
    </style>

    <div class="main-content">
        <div class="recipe-section">
            <a href="{{ route('recipes.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Recipes
            </a>

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

            <h1 class="section-title">Update Recipe</h1>

            <form id="editRecipeForm" method="POST" action="{{ route('recipes.update', $recipe->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="recipeName">Recipe Name</label>
                    <input type="text" id="recipeName" name="recipe_name" class="form-control"
                        placeholder="Enter recipe name" value="{{ old('recipe_name', $recipe->name) }}" required>
                </div>

                <div class="form-group mb-4" style="margin-top: 20px;">
                    <label for="recipeImage">Recipe Photo</label>
                    @if($recipe->image)
                        <div class="mb-2">
                            <p class="small text-muted">Current Photo:</p>
                            <img src="{{ asset($recipe->image) }}" alt="Current" style="max-height: 100px; border-radius: 8px; border: 1px solid var(--border-subtle);">
                        </div>
                    @endif
                    <input type="file" id="recipeImage" name="recipe_image" class="form-control" accept="image/*">
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <p class="small text-muted">New Selection Preview:</p>
                        <img src="" alt="Preview" style="max-height: 150px; border-radius: 8px; border: 1px solid var(--border-subtle);">
                    </div>
                </div>

                <h3 style="margin-top: 25px; margin-bottom: 20px;">Ingredients</h3>

                <div id="nutritionContainer">
                    @foreach($recipe->ingredients as $index => $ingredient)
                    <div class="nutrition-row" id="nutritionRow{{ $index }}">
                        <div class="nutrition-field">
                            <label for="nutritionSelect{{ $index }}">Select Nutrition</label>
                            <select id="nutritionSelect{{ $index }}" name="nutrition_data[{{ $index }}][nutrition_id]"
                                class="form-control nutrition-select" required>
                                <option value="">Select nutrition</option>
                                @foreach($nutritions as $nutrition)
                                <option value="{{ $nutrition->id }}" 
                                    {{ $ingredient->nutrition_id == $nutrition->id ? 'selected' : '' }}
                                    data-nutrition='@json($nutrition)'>
                                    {{ $nutrition->nutrition_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="nutrition-field">
                            <label for="quantity{{ $index }}">Quantity (ml/gm)</label>
                            <input type="number" step="0.01" id="quantity{{ $index }}" 
                                name="nutrition_data[{{ $index }}][quantity]" class="form-control quantity-input" 
                                placeholder="Enter quantity" value="{{ old('nutrition_data.'.$index.'.quantity', $ingredient->quantity) }}" required>
                        </div>
                        <div class="nutrition-field" style="flex: 0 0 auto;">
                            <button type="button" class="btn-remove remove-nutrition-btn" data-row="{{ $index }}">
                                <i class="fas fa-minus"></i> 
                            </button>
                        </div>
                    </div>
                    @endforeach
                    
                    @if(count($recipe->ingredients) === 0)
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
                            <label for="quantity0">Quantity (ml/gm)</label>
                            <input type="number" step="0.01" id="quantity0" name="nutrition_data[0][quantity]"
                                class="form-control quantity-input" placeholder="Enter quantity" required>
                        </div>
                        <div class="nutrition-field" style="flex: 0 0 auto;">
                            <button type="button" class="btn-remove remove-nutrition-btn" data-row="0">
                                <i class="fas fa-minus"></i> 
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="action-buttons-row">
                    <button type="button" id="addNutritionBtn" class="btn-add">
                        <i class="fas fa-plus"></i> Add More 
                    </button>
                </div>

                <div class="divider"></div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Update Recipe
                </button>
                <br>
            </form>

            <!-- Nutrition Data Table Section -->
            <div class="nutrition-data-section" id="nutritionDataSection" style="margin-top: 30px;">
                <div class="nutrition-header">Nutrition Data Analysis</div>
                
                <div class="nutrition-data-table-container">
                    <table class="nutrition-data-table" id="nutritionDataTable">
                        <thead>
                            <tr>
                                <th>Nutrition Name</th>
                                <th>Energy Kcal</th>
                                <th>Water</th>
                                <th>Fat</th>
                                <th>Total Fiber</th>
                                <th>Carbohydrate</th>
                                <th>Protein</th>
                                <th>Vitamin C</th>
                                <th>Insoluble Fiber</th>
                                <th>Soluble Fiber</th>
                                <th>Biotin</th>
                                <th>Total Folates</th>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            let nutritionRowCount = {{ count($recipe->ingredients) }};
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
                        <label for="quantity${nutritionRowCount}">Quantity (ml/gm)</label>
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
                removeBtn.addEventListener('click', function() {
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
                if (rowToRemove && document.querySelectorAll('.nutrition-row').length > 1) {
                    rowToRemove.remove();
                    updateNutritionTable();
                }
            }

            // Function to update the nutrition table
            function updateNutritionTable() {
                const nutritionDataBody = document.getElementById('nutritionDataBody');
                const nutritionDataFooter = document.getElementById('nutritionDataFooter');
                
                // Clear previous data
                nutritionDataBody.innerHTML = '';
                nutritionDataFooter.innerHTML = '';
                
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
                            
                            const rowData = {
                                name: nutrition.nutrition_name,
                                energy_kcal: calcValue('energy_kcal'),
                                water: calcValue('water'),
                                fat: calcValue('fat'),
                                total_fiber: calcValue('total_fiber'),
                                carbohydrate: calcValue('carbohydrate'),
                                protein: calcValue('protein'),
                                vitamin_c: calcValue('vitamin_c'),
                                insoluble_fiber: calcValue('insoluable_fiber'),
                                soluble_fiber: calcValue('soluable_fiber'),
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
                                    totals[key] += numValue('za');
                                } else if (key === 'insoluble_fiber') {
                                    totals[key] += numValue('insoluable_fiber');
                                } else if (key === 'soluble_fiber') {
                                    totals[key] += numValue('soluable_fiber');
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
                        }
                    }
                });
                
                // Add total row if we have data
                if (selectedNutritions.length > 0) {
                    const totalRow = document.createElement('tr');
                    totalRow.className = 'total-row';
                    totalRow.innerHTML = `
                        <td><strong>Total</strong></td>
                        <td><strong>${totals.energy_kcal.toFixed(2)}</strong></td>
                        <td><strong>${totals.water.toFixed(2)}</strong></td>
                        <td><strong>${totals.fat.toFixed(2)}</strong></td>
                        <td><strong>${totals.total_fiber.toFixed(2)}</strong></td>
                        <td><strong>${totals.carbohydrate.toFixed(2)}</strong></td>
                        <td><strong>${totals.protein.toFixed(2)}</strong></td>
                        <td><strong>${totals.vitamin_c.toFixed(2)}</strong></td>
                        <td><strong>${totals.insoluble_fiber.toFixed(2)}</strong></td>
                        <td><strong>${totals.soluble_fiber.toFixed(2)}</strong></td>
                        <td><strong>${totals.biotin.toFixed(2)}</strong></td>
                        <td><strong>${totals.total_folates.toFixed(2)}</strong></td>
                        <td><strong>${totals.calcium.toFixed(2)}</strong></td>
                        <td><strong>${totals.cu.toFixed(2)}</strong></td>
                        <td><strong>${totals.fe.toFixed(2)}</strong></td>
                        <td><strong>${totals.mg.toFixed(2)}</strong></td>
                        <td><strong>${totals.p.toFixed(2)}</strong></td>
                        <td><strong>${totals.k.toFixed(2)}</strong></td>
                        <td><strong>${totals.se.toFixed(2)}</strong></td>
                        <td><strong>${totals.na.toFixed(2)}</strong></td>
                        <td><strong>${totals.zn.toFixed(2)}</strong></td>
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
                btn.addEventListener('click', function() {
                    removeNutritionRow(this.getAttribute('data-row'));
                });
            });

            // Initialize nutrition table on page load
            updateNutritionTable();
        });
    </script>
@endsection
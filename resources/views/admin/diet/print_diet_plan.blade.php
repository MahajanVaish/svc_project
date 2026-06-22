@extends('admin.layouts.layouts')
@section('title', 'Print Diet Plan')
@section('content')

    <div class="col-md-12 col-lg-10 m-auto p-0 print-container">
        <!-- Header/Brand Section -->
        <div class="card shadow-sm border-0 mb-4 overflow-hidden"
            style="border-radius: 16px; background: linear-gradient(135deg, #086838, #10b981);">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between text-white flex-wrap gap-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-large me-4 d-flex align-items-center justify-content-center fw-bold shadow-lg"
                            style="width: 80px; height: 80px; background: #fff; color: #086838; border-radius: 20px; font-size: 32px; border: 4px solid rgba(255,255,255,0.2);">
                            {{ substr(optional($patient)->patient_name ?? 'P', 0, 1) }}
                        </div>
                        <div>
                            <h1 class="mb-1 fw-bold h3">{{ optional($patient)->patient_name ?? 'N/A' }}</h1>
                            <div class="d-flex gap-2 align-items-center">
                                <span
                                    class="badge bg-white bg-opacity-25 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill small">
                                    <i class="fas fa-id-card me-1 small opacity-75"></i> {{ optional($patient)->patient_id ?? 'No ID'
                                    }}
                                </span>
                                <span class="small opacity-75 border-start border-white border-opacity-25 ps-2">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $dietPlan->branch->branch_name ?? 'Main'
                                    }} Branch
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="h4 fw-bold mb-0 text-white">{{ $dietPlan->diet_name }}</div>
                        <div class="text-uppercase small opacity-75 mt-1"
                            style="font-size: 10px; letter-spacing: 1px; font-weight: 700;">
                            Prescribed on: {{ is_string($dietPlan->date) ? date('d M, Y', strtotime($dietPlan->date)) : $dietPlan->date->format('d M, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Diet Plan Details Card -->
        <div class="card shadow-sm border-0 mb-5" style="border-radius: 16px; background: #fff;">
            <div class="card-header border-0 bg-transparent py-4 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0" style="color: #086838;">Personalized Diet Schedule</h5>
                    <p class="text-muted small mb-0">Follow this plan for optimal results</p>
                </div>
                <div class="actions no-print">
                    <button onclick="window.print()" class="btn btn-sm btn-print px-4 py-2 rounded-3 fw-bold">
                        <i class="fas fa-print me-2"></i>Print Diet Chart
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-4 text-muted text-uppercase small py-3"
                                    style="letter-spacing: 1px; width: 10%;">Time</th>
                                <th class="text-muted text-uppercase small" style="letter-spacing: 1px; width: 25%;">Menu
                                    Items</th>
                                <th class="text-center text-muted text-uppercase small"
                                    style="letter-spacing: 1px; width: 10%;">Qty</th>
                                <th class="text-end pe-4 text-muted text-uppercase small"
                                    style="letter-spacing: 1px; width: 55%;">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="diet-body">
                            @if(!empty($menus) && is_array($menus))
                                @foreach ($menus as $menu)
                                    <tr class="diet-row">
                                        <td class="ps-4 py-4">
                                            <div class="fw-bold text-dark">
                                                @if(!empty($menu['time']))
                                                    {{ date('h:i A', strtotime($menu['time'])) }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark mb-1">
                        @php
                                                    $recipeList = [];
                                                    $hasStructuredRecipes = !empty($menu['recipes']) && is_array($menu['recipes']);

                                                    if ($hasStructuredRecipes) {
                                                        $recipeList = $menu['recipes'];
                                                    } else {
                                                        // Fallback: try selected_recipes JSON, then search_menu plain text
                                                        $recipesStr = $menu['selected_recipes'] ?? $menu['search_menu'] ?? '';
                                                        if (!empty($recipesStr)) {
                                                            // Try JSON decode first
                                                            $jsonDecoded = @json_decode($recipesStr, true);
                                                            if (is_array($jsonDecoded) && !empty($jsonDecoded)) {
                                                                $recipeList = $jsonDecoded;
                                                            } else {
                                                                // Plain comma-separated text
                                                                $rawList = array_filter(array_map('trim', explode(',', $recipesStr)));
                                                                foreach ($rawList as $r) {
                                                                    $recipeList[] = ['name' => $r, 'qty' => 1];
                                                                }
                                                            }
                                                        }
                                                        // Last fallback: use search_menu even if selected_recipes was set
                                                        if (empty($recipeList) && !empty($menu['search_menu'])) {
                                                            $rawList = array_filter(array_map('trim', explode(',', $menu['search_menu'])));
                                                            foreach ($rawList as $r) {
                                                                $recipeList[] = ['name' => $r, 'qty' => 1];
                                                            }
                                                        }
                                                    }
                                                @endphp

                                                @if (!empty($recipeList))
                                                    <div class="recipe-tags d-flex flex-column align-items-start">
                                                        @foreach ($recipeList as $recipe)
                                                            <div class="badge bg-success bg-opacity-10 text-success fw-medium mb-1"
                                                                style="font-size: 11px; border: 1px solid rgba(8, 104, 56, 0.1); line-height: 1.8; text-align: left;">
                                                                <i class="fas fa-leaf me-1 opacity-50"></i> {{ $recipe['name'] }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if(!empty($recipeList))
                                                @foreach($recipeList as $recipe)
                                                    @php
                                                        $qty = $recipe['qty'] ?? '';
                                                        if (empty($qty) && !empty($menu['quantity'])) {
                                                            $qty = $menu['quantity'];
                                                        }
                                                    @endphp
                                                    <div class="fw-bold text-dark mb-1" style="font-size: 12px; line-height: 1.8;">
                                                        {{ !empty($qty) ? $qty : '-' }}
                                                    </div>
                                                @endforeach
                                            @elseif(!empty($menu['quantity']))
                                                <span class="fw-bold text-dark">{{ $menu['quantity'] }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            @if(!empty($recipeList))
                                                @foreach($recipeList as $recipe)
                                                    @php
                                                        $note = $recipe['notes'] ?? '';
                                                        if (empty($note) && !empty($menu['notes'])) {
                                                            $note = $menu['notes'];
                                                        }
                                                    @endphp
                                                    <div class="text-muted small mb-1" style="line-height: 1.8;">
                                                        {{ !empty($note) ? $note : '-' }}
                                                    </div>
                                                @endforeach
                                            @elseif(!empty($menu['notes']))
                                                <div class="text-muted small">{{ $menu['notes'] }}</div>
                                            @else
                                                <div class="text-muted small">-</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="opacity-25 pb-3">
                                            <i class="fas fa-utensils fa-4x text-muted"></i>
                                        </div>
                                        <p class="text-muted fw-medium">No meal items found in this diet plan.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            @if(!empty($dietPlan->general_notes))
                <div class="card-footer bg-light bg-opacity-25 border-top py-4 px-4">
                    <h6 class="fw-bold" style="color: #086838;"><i class="fas fa-info-circle me-2"></i>General Instructions:
                    </h6>
                    <div class="text-muted mt-2" style="white-space: pre-line; line-height: 1.6;">{{ $dietPlan->general_notes }}
                    </div>
                </div>
            @endif

            <div class="card-footer border-0 py-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3"
                style="background: #f8fbf9;">
                @if(!empty($dietPlan->next_follow_up_date))
                            <div>
                                <span class="badge px-3 py-2 rounded-pill bg-info bg-opacity-10 text-info fw-bold">
                                    <i class="fas fa-calendar-check me-2"></i>Next Follow-up: {{ is_string($dietPlan->next_follow_up_date) ? date('d M, Y', strtotime($dietPlan->next_follow_up_date)) : $dietPlan->next_follow_up_date->format('d M, Y') }}
                                </span>
                            </div>
                @endif
                <div class="text-muted small">
                    Generated on: {{ date('d M, Y h:i A') }}
                </div>
            </div>
        </div>

        <div class="text-center mb-5 no-print">
            @if($profileId)
            <a href="{{ route('patient.profile', ['id' => $profileId]) }}"
                class="btn btn-outline-secondary btn-sm px-4 py-2 rounded-pill border-0 shadow-none text-muted fw-bold">
                <i class="fas fa-arrow-left me-2"></i> Return to Patient Profile
            </a>
            @else
            <a href="{{ route('diet.plan') }}"
                class="btn btn-outline-secondary btn-sm px-4 py-2 rounded-pill border-0 shadow-none text-muted fw-bold">
                <i class="fas fa-arrow-left me-2"></i> Return to Diet Plans
            </a>
            @endif
        </div>
    </div>

    <style>
        :root {
            --primary: #086838;
            --secondary: #10b981;
        }

        .print-container {
            font-family: 'Inter', sans-serif;
        }

        .btn-print {
            background: rgba(8, 104, 56, 0.05);
            color: #086838;
            border: 1px solid rgba(8, 104, 56, 0.2);
            transition: all 0.2s ease;
        }

        .btn-print:hover {
            background: #086838;
            color: #fff;
            transform: translateY(-1px);
        }

        .diet-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.02) !important;
        }

        .diet-row:hover {
            background: rgba(8, 104, 56, 0.01) !important;
        }

        .recipe-tags .badge {
            padding: 6px 10px;
            border-radius: 6px;
        }

        @media print {
            @page {
                margin: 1cm;
            }

            body {
                background: #fff !important;
            }

            .main-wrapper {
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print,
            #sidebar-wrapper,
            .navbar,
            .card-footer:last-child {
                display: none !important;
            }

            .print-container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .card {
                border: 1px solid #eee !important;
                box-shadow: none !important;
                margin-bottom: 0 !important;
            }

            .card[style*="linear-gradient"] {
                background: #086838 !important;
                -webkit-print-color-adjust: exact;
            }

            .text-white {
                color: #000 !important;
            }

            .bg-opacity-25 {
                background-opacity: 1 !important;
                border: 1px solid #000 !important;
            }

            .avatar-large {
                border: 2px solid #fff !important;
                -webkit-print-color-adjust: exact;
            }

            table th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }

            .badge-success {
                border: 1px solid #086838 !important;
                color: #086838 !important;
                background: #fff !important;
            }
        }

        /* Dark Mode Handling (already handled by common layout, but ensuring print is clean) */
        html.dark .card {
            background: #1a222c !important;
            border: 1px solid #2d3748 !important;
        }

        html.dark .bg-light {
            background: #121821 !important;
        }

        html.dark .text-dark {
            color: #f3f4f6 !important;
        }

        html.dark .text-muted {
            color: #a0aec0 !important;
        }
    </style>

@endsection
@extends('admin.layouts.layouts')

@section('title', 'Payment Analytics')

@section('content')
    <div class="container-fluid display_data p-4">
        <!-- Header Section -->
        <div class="row mb-4 animate__animated animate__fadeIn">
            <div class="col-12">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-white dark:bg-slate-800 p-4 rounded-4 shadow-sm border border-slate-100 dark:border-slate-700 gap-4">
                    <div>
                        <h4 class="mb-1 text-slate-800 dark:text-slate-100 fw-bold tracking-tight">Financial Intelligence
                        </h4>
                        <p class="text-slate-500 dark:text-slate-400 mb-0 small">Real-time payment analytics and branch
                            performance metrics.</p>
                    </div>

                    <div
                        class="d-flex flex-wrap gap-2 align-items-center bg-slate-50 dark:bg-slate-900/50 p-2 rounded-3 border border-slate-100 dark:border-slate-800">
                        <div class="input-group input-group-sm" style="width: 140px;">
                            <span class="input-group-text bg-transparent border-0 text-slate-400"><i
                                    class="bi bi-calendar3"></i></span>
                            <select id="yearSelect"
                                class="form-select border-0 bg-transparent dark:text-slate-200 fw-medium">
                                <option value="">Year</option>
                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="vr dark:bg-slate-700 mx-1 d-none d-sm-block"></div>

                        <div class="input-group input-group-sm" style="width: 140px;">
                            <span class="input-group-text bg-transparent border-0 text-slate-400"><i
                                    class="bi bi-calendar-event"></i></span>
                            <select id="monthSelect"
                                class="form-select border-0 bg-transparent dark:text-slate-200 fw-medium">
                                <option value="">Month</option>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="vr dark:bg-slate-700 mx-1 d-none d-md-block"></div>

                        <div class="input-group input-group-sm" style="width: 180px;">
                            <span class="input-group-text bg-transparent border-0 text-slate-400"><i
                                    class="bi bi-building"></i></span>
                            <select id="branchSelect"
                                class="form-select border-0 bg-transparent dark:text-slate-200 fw-medium">
                                <option value="">All Branches</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->branch_id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="row mb-4 animate__animated animate__fadeInUp">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white dark:bg-slate-800">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stats-icon bg-teal-50 dark:bg-teal-900/20 text-teal-600 rounded-3 p-2">
                                <i class="bi bi-wallet2 fs-4"></i>
                            </div>
                            <span
                                class="badge bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300 rounded-pill px-3">Total
                                Estimated</span>
                        </div>
                        <h2 id="stat_total_revenue" class="fw-bold text-slate-800 dark:text-slate-100 mb-1">Rs 0</h2>
                        <p class="text-slate-500 dark:text-slate-400 small mb-0">Aggregate revenue potential</p>
                    </div>
                    <div class="progress rounded-0" style="height: 4px;">
                        <div class="progress-bar bg-teal-500" style="width: 100%"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div
                    class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white dark:bg-slate-800 border-start border-emerald-500 border-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stats-icon bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-3 p-2">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                            <span
                                class="badge bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 rounded-pill px-3">Total
                                Collected</span>
                        </div>
                        <h2 id="stat_total_paid" class="fw-bold text-slate-800 dark:text-slate-100 mb-1">Rs 0</h2>
                        <p class="text-slate-500 dark:text-slate-400 small mb-0">Successfull transactions</p>
                    </div>
                    <div class="progress rounded-0" style="height: 4px;">
                        <div id="paid_progress" class="progress-bar bg-emerald-500" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div
                    class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white dark:bg-slate-800 border-start border-rose-500 border-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stats-icon bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-3 p-2">
                                <i class="bi bi-exclamation-triangle fs-4"></i>
                            </div>
                            <span
                                class="badge bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 rounded-pill px-3">Pending
                                Dues</span>
                        </div>
                        <h2 id="stat_total_due" class="fw-bold text-slate-800 dark:text-slate-100 mb-1">Rs 0</h2>
                        <p class="text-slate-500 dark:text-slate-400 small mb-0">Awaiting clearance</p>
                    </div>
                    <div class="progress rounded-0" style="height: 4px;">
                        <div id="due_progress" class="progress-bar bg-rose-500" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Chart Section -->
        <div class="row mb-4 animate__animated animate__fadeInUp anim-delay-1">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="card border-0 shadow-sm rounded-4 bg-white dark:bg-slate-800 h-100">
                    <div
                        class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-slate-800 dark:text-slate-100 mb-0">Comparative Distribution</h5>
                        <div class="btn-group btn-group-sm shadow-sm rounded-3 overflow-hidden">
                            <button type="button"
                                class="btn btn-white dark:bg-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-600 active chart-toggle"
                                data-type="all">All</button>
                            <button type="button"
                                class="btn btn-white dark:bg-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-600 chart-toggle"
                                data-type="paid">Paid</button>
                            <button type="button"
                                class="btn btn-white dark:bg-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-600 chart-toggle"
                                data-type="due">Due</button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div style="height: 450px; position: relative;" id="chartContainer">
                            <canvas id="paymentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white dark:bg-slate-800 h-100">
                    <div class="card-header bg-transparent border-0 p-4 pb-0">
                        <h5 class="fw-bold text-slate-800 dark:text-slate-100 mb-0">Percentage Breakdown</h5>
                    </div>
                    <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                        <div style="height: 300px; width: 100%; position: relative;">
                            <canvas id="ratioChart"></canvas>
                        </div>
                        <div class="mt-4 w-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-medium text-slate-500">Collection Efficiency</span>
                                <span id="efficiency_label" class="small fw-bold text-emerald-600">0%</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div id="efficiency_bar" class="progress-bar bg-emerald-500 rounded-pill" style="width: 0%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dependencies -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function () {
            let mainChart;
            let ratioChart;
            let currentStatus = 'all';

            function formatCurrency(val) {
                return new Intl.NumberFormat('en-IN', {
                    style: 'currency',
                    currency: 'INR',
                    maximumFractionDigits: 0
                }).format(val);
            }

            function loadChartData() {
                const branchId = $('#branchSelect').val();
                const year = $('#yearSelect').val();
                const month = $('#monthSelect').val();

                $.ajax({
                    url: "{{ route('admin.payment.summary') }}",
                    type: "GET",
                    data: {
                        branch_id: branchId,
                        status: currentStatus,
                        year: year,
                        month: month
                    },
                    success: function (res) {
                        if (res.success) {
                            updateMainChart(res.data, branchId ? 'pie' : 'bar');
                            updateStats(res.totals);
                        }
                    }
                });
            }

            function updateStats(totals) {
                $('#stat_total_revenue').text(formatCurrency(totals.revenue));
                $('#stat_total_paid').text(formatCurrency(totals.paid));
                $('#stat_total_due').text(formatCurrency(totals.due));

                const paidPercent = totals.revenue > 0 ? (totals.paid / totals.revenue * 100) : 0;

                $('#paid_progress').css('width', paidPercent + '%');
                $('#due_progress').css('width', (totals.revenue > 0 ? (totals.due / totals.revenue * 100) : 0) + '%');
                $('#efficiency_label').text(Math.round(paidPercent) + '%');
                $('#efficiency_bar').css('width', paidPercent + '%');

                updateRatioChart(totals.paid, totals.due);
            }

            function updateMainChart(data, type) {
                const ctx = document.getElementById('paymentChart').getContext('2d');
                if (mainChart) mainChart.destroy();

                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#94a3b8' : '#64748b';
                const gridColor = isDark ? '#334155' : '#f1f5f9';

                mainChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.label),
                        datasets: [{
                            label: 'Amount (Rs)',
                            data: data.map(d => d.value),
                            backgroundColor: 'rgba(20, 184, 166, 0.7)',
                            borderColor: 'rgba(20, 184, 166, 1)',
                            borderWidth: 0,
                            borderRadius: 12,
                            barThickness: 32,
                            hoverBackgroundColor: 'rgba(20, 184, 166, 1)',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                padding: 16,
                                backgroundColor: isDark ? '#1e293b' : '#ffffff',
                                titleColor: isDark ? '#f1f5f9' : '#1e293b',
                                bodyColor: isDark ? '#94a3b8' : '#64748b',
                                borderColor: gridColor,
                                borderWidth: 1,
                                callbacks: {
                                    label: (ctx) => 'Amount: ' + formatCurrency(ctx.parsed.y)
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: gridColor, drawBorder: false },
                                ticks: {
                                    color: textColor,
                                    padding: 10,
                                    callback: (v) => v >= 1000 ? (v / 1000) + 'k' : v
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: textColor, padding: 10 }
                            }
                        }
                    }
                });
            }

            function updateRatioChart(paid, due) {
                const ctx = document.getElementById('ratioChart').getContext('2d');
                if (ratioChart) ratioChart.destroy();
                if (paid === 0 && due === 0) return;

                ratioChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Paid', 'Due'],
                        datasets: [{
                            data: [paid, due],
                            backgroundColor: ['#10b981', '#f43f5e'],
                            borderWidth: 0,
                            cutout: '80%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b'
                                }
                            }
                        }
                    }
                });
            }

            $('.chart-toggle').on('click', function () {
                $('.chart-toggle').removeClass('active btn-primary').addClass('btn-white');
                $(this).addClass('active').removeClass('btn-white');
                currentStatus = $(this).data('type');
                loadChartData();
            });

            $('#branchSelect, #yearSelect, #monthSelect').on('change', loadChartData);

            loadChartData();
        });
    </script>

    <style>
        .anim-delay-1 {
            animation-delay: 0.1s;
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-white {
            background: white;
            color: #64748b;
        }

        .dark .btn-white {
            background: #1e293b;
            color: #94a3b8;
            border-color: #334155;
        }

        .btn-white:hover {
            background: #f8fafc;
        }

        .active {
            background: #14b8a6 !important;
            color: white !important;
            border-color: #14b8a6 !important;
        }

        .form-select:focus {
            border-color: #14b8a6;
            box-shadow: none;
        }
    </style>
@endsection
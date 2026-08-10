@extends('admin.layouts.layouts')
@section('title', 'Financial Dashboard & Real-Time Analytics')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    body, .main-content, .card, input, select, button {
        font-family: 'Poppins', sans-serif !important;
    }

    .fin-card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        transition: all 0.25s ease-in-out;
    }

    .fin-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.08);
    }

    .stat-card-gradient-1 {
        background: linear-gradient(135deg, #006637 0%, #086838 60%, #10b981 100%);
        color: white;
    }

    .stat-card-gradient-2 {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 60%, #38bdf8 100%);
        color: white;
    }

    .stat-card-gradient-3 {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 60%, #f87171 100%);
        color: white;
    }

    .stat-card-gradient-4 {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 60%, #a78bfa 100%);
        color: white;
    }

    .filter-btn {
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        font-weight: 500;
        font-size: 13px;
        padding: 7px 14px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .filter-btn:hover, .filter-btn.active {
        background: #006637;
        color: white;
        border-color: #006637;
    }

    .pulse-indicator {
        display: inline-block;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-green 1.8s infinite;
    }

    @keyframes pulse-green {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    .table-custom th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
        padding: 12px 16px;
    }

    .table-custom td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 13px;
    }

    .trx-pill {
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .trx-pill.CREDIT {
        background: #dcfce7;
        color: #15803d;
    }

    .trx-pill.DEBIT {
        background: #fee2e2;
        color: #b91c1c;
    }

    .trx-pill.DISCOUNT {
        background: #f3e8ff;
        color: #6b21a8;
    }
</style>

<div class="container-fluid px-4 py-3">
    {{-- Header Row --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1" style="color: #006637 !important;">
                <i class="bi bi-graph-up-arrow me-2"></i> Financial Dashboard
            </h2>
            <p class="text-muted small mb-0">
                Real-Time Transaction Analytics, Revenue Streams &amp; Financial Intelligence
            </p>
        </div>

        <div class="d-flex align-items-center gap-3">
            <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white border shadow-sm small fw-medium text-muted">
                <span class="pulse-indicator"></span> Real-Time Auto Sync
            </span>
            <button id="refreshBtn" class="btn btn-light border btn-sm shadow-sm fw-bold px-3" title="Refresh Analytics Data">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </button>
        </div>
    </div>

    {{-- Filter Bar Card --}}
    <div class="card fin-card p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="fw-bold text-muted small me-2"><i class="bi bi-funnel me-1"></i> Date Filter:</span>
                <button class="filter-btn active" data-filter="all">All Time</button>
                <button class="filter-btn" data-filter="today">Today</button>
                <button class="filter-btn" data-filter="week">This Week</button>
                <button class="filter-btn" data-filter="month">This Month</button>
                <button class="filter-btn" data-filter="year">This Year</button>
                <button class="filter-btn" data-filter="custom">Custom Range</button>
            </div>

            @if($isSuperadmin)
                <div class="d-flex align-items-center gap-2">
                    <label class="fw-bold text-muted small mb-0 text-nowrap">Branch:</label>
                    <select id="branchFilter" class="form-select form-select-sm" style="width: 170px; border-radius: 8px;">
                        <option value="">All Branches</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->branch_id }}">{{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        {{-- Custom Date Range Inputs --}}
        <div id="customDateRow" class="row g-2 mt-3 pt-3 border-top" style="display: none;">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Start Date</label>
                <input type="date" id="startDateInput" class="form-control form-control-sm" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">End Date</label>
                <input type="date" id="endDateInput" class="form-control form-control-sm" value="{{ now()->endOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button id="applyCustomDateBtn" class="btn btn-sm text-white fw-bold w-100" style="background: #006637; height: 38px;">
                    Apply Range
                </button>
            </div>
        </div>
    </div>

    {{-- Top Summary Metrics Cards --}}
    <div class="row g-3 mb-4">
        {{-- Card 1: Total Billed --}}
        <div class="col-xl-3 col-md-6">
            <div class="card fin-card stat-card-gradient-1 p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50" style="font-size: 11px; letter-spacing: 1px;">Total Billed Revenue</div>
                        <h2 class="fw-extrabold mb-0 mt-1" id="statBilled">₹0.00</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-file-earmark-spreadsheet-fill fs-4 text-white"></i>
                    </div>
                </div>
                <div class="small text-white-50 mt-2">
                    <span id="statTrxCount">0</span> total transaction logs
                </div>
            </div>
        </div>

        {{-- Card 2: Cash Collections --}}
        <div class="col-xl-3 col-md-6">
            <div class="card fin-card stat-card-gradient-2 p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50" style="font-size: 11px; letter-spacing: 1px;">Cash Collections</div>
                        <h2 class="fw-extrabold mb-0 mt-1" id="statCollected">₹0.00</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-wallet2 fs-4 text-white"></i>
                    </div>
                </div>
                <div class="small text-white-50 mt-2">
                    Collection Rate: <strong class="text-white" id="statRate">0%</strong>
                </div>
            </div>
        </div>

        {{-- Card 3: Outstanding Dues --}}
        <div class="col-xl-3 col-md-6">
            <div class="card fin-card stat-card-gradient-3 p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50" style="font-size: 11px; letter-spacing: 1px;">Outstanding Dues</div>
                        <h2 class="fw-extrabold mb-0 mt-1" id="statDue">₹0.00</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-exclamation-octagon-fill fs-4 text-white"></i>
                    </div>
                </div>
                <div class="small text-white-50 mt-2">
                    Pending balance to collect
                </div>
            </div>
        </div>

        {{-- Card 4: Total Discounts --}}
        <div class="col-xl-3 col-md-6">
            <div class="card fin-card stat-card-gradient-4 p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50" style="font-size: 11px; letter-spacing: 1px;">Total Discounts</div>
                        <h2 class="fw-extrabold mb-0 mt-1" id="statDiscount">₹0.00</h2>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-percent fs-4 text-white"></i>
                    </div>
                </div>
                <div class="small text-white-50 mt-2">
                    Adjustments &amp; Fee Concessions
                </div>
            </div>
        </div>
    </div>

    {{-- Interactive Analytics Charts Row --}}
    <div class="row g-4 mb-4">
        {{-- Main Revenue Trend Chart --}}
        <div class="col-xl-8">
            <div class="card fin-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0" style="color: #006637 !important;">
                            <i class="bi bi-bar-chart-line-fill me-1"></i> Monthly Financial Performance
                        </h5>
                        <small class="text-muted">Billed Revenue vs Cash Collections vs Outstanding Dues</small>
                    </div>
                </div>
                <div style="position: relative; min-height: 320px;">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Payment Methods Doughnut Chart --}}
        <div class="col-xl-4">
            <div class="card fin-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0" style="color: #006637 !important;">
                            <i class="bi bi-pie-chart-fill me-1"></i> Payment Modes
                        </h5>
                        <small class="text-muted">Cash vs GPay/UPI vs Cheque vs Online</small>
                    </div>
                </div>
                <div style="position: relative; min-height: 320px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Month-by-Month Detailed Table & Real-Time Stream --}}
    <div class="row g-4 mb-5">
        {{-- Month by Month Breakdown Table --}}
        <div class="col-xl-7">
            <div class="card fin-card overflow-hidden h-100">
                <div class="p-4 border-bottom bg-light bg-opacity-50">
                    <h5 class="fw-bold mb-0 text-dark" style="color: #006637 !important;">
                        <i class="bi bi-calendar3 me-1"></i> Month-by-Month Financial Summary
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-end">Total Billed</th>
                                <th class="text-end">Collected</th>
                                <th class="text-end">Due</th>
                                <th class="text-center">Rate %</th>
                            </tr>
                        </thead>
                        <tbody id="monthlyTableBody">
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading financial data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Real-Time Recent Transactions Stream --}}
        <div class="col-xl-5">
            <div class="card fin-card overflow-hidden h-100">
                <div class="p-4 border-bottom bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark" style="color: #006637 !important;">
                        <i class="bi bi-activity me-1"></i> Real-Time Transactions Feed
                    </h5>
                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 small fw-bold">Live</span>
                </div>
                <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Patient / Date</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="recentTrxTableBody">
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading live stream...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Include Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let currentFilter = 'all';
    let trendChart = null;
    let paymentChart = null;

    // Filter Buttons Click Handler
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            currentFilter = this.getAttribute('data-filter');
            const customRow = document.getElementById('customDateRow');
            
            if (currentFilter === 'custom') {
                customRow.style.display = 'flex';
            } else {
                customRow.style.display = 'none';
                loadFinancialData();
            }
        });
    });

    document.getElementById('applyCustomDateBtn')?.addEventListener('click', function() {
        loadFinancialData();
    });

    document.getElementById('branchFilter')?.addEventListener('change', function() {
        loadFinancialData();
    });

    document.getElementById('refreshBtn')?.addEventListener('click', function() {
        loadFinancialData();
    });

    // Format Currency Helper
    function fmtCurr(val) {
        return '₹' + Number(val || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Load Data Function
    function loadFinancialData() {
        const branchId = document.getElementById('branchFilter') ? document.getElementById('branchFilter').value : '';
        const startDate = document.getElementById('startDateInput') ? document.getElementById('startDateInput').value : '';
        const endDate = document.getElementById('endDateInput') ? document.getElementById('endDateInput').value : '';

        const payload = {
            _token: '{{ csrf_token() }}',
            date_filter: currentFilter,
            branch_id: branchId,
            start_date: startDate,
            end_date: endDate
        };

        fetch('{{ route('financial.dashboard.data') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            // 1. Update Metrics Cards
            document.getElementById('statBilled').innerText = fmtCurr(data.metrics.total_billed);
            document.getElementById('statCollected').innerText = fmtCurr(data.metrics.total_collected);
            document.getElementById('statDue').innerText = fmtCurr(data.metrics.total_due);
            document.getElementById('statDiscount').innerText = fmtCurr(data.metrics.total_discount);
            document.getElementById('statRate').innerText = data.metrics.collection_rate + '%';
            document.getElementById('statTrxCount').innerText = data.metrics.transaction_count;

            // 2. Render Monthly Trend Chart
            renderMonthlyChart(data.monthly_trend);

            // 3. Render Payment Methods Chart
            renderPaymentChart(data.payment_methods);

            // 4. Render Monthly Table
            renderMonthlyTable(data.monthly_trend);

            // 5. Render Recent Transactions Stream
            renderRecentTransactions(data.recent_transactions);
        })
        .catch(err => console.error('Financial data load error:', err));
    }

    function renderMonthlyChart(trendData) {
        const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
        const labels = trendData.map(d => d.month_label);
        const billed = trendData.map(d => d.billed);
        const collected = trendData.map(d => d.collected);
        const due = trendData.map(d => d.due);

        if (trendChart) trendChart.destroy();

        trendChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Cash Collected',
                        data: collected,
                        backgroundColor: '#10b981',
                        borderRadius: 6
                    },
                    {
                        label: 'Outstanding Due',
                        data: due,
                        backgroundColor: '#f87171',
                        borderRadius: 6
                    },
                    {
                        label: 'Total Billed Revenue',
                        data: billed,
                        type: 'line',
                        borderColor: '#006637',
                        borderWidth: 3,
                        pointBackgroundColor: '#006637',
                        pointRadius: 4,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return '₹' + value.toLocaleString(); }
                        }
                    }
                }
            }
        });
    }

    function renderPaymentChart(methods) {
        const ctx = document.getElementById('paymentMethodsChart').getContext('2d');
        const labels = Object.keys(methods);
        const values = Object.values(methods);

        if (paymentChart) paymentChart.destroy();

        paymentChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: ['#10b981', '#0284c7', '#7c3aed', '#f59e0b', '#64748b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    function renderMonthlyTable(trendData) {
        const tbody = document.getElementById('monthlyTableBody');
        if (!trendData || trendData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">No monthly transaction data available.</td></tr>';
            return;
        }

        let html = '';
        trendData.slice().reverse().forEach(row => {
            const badgeClass = row.collection_rate >= 80 ? 'bg-success' : (row.collection_rate >= 50 ? 'bg-warning text-dark' : 'bg-danger');
            html += `<tr>
                <td class="fw-bold text-dark">${row.month_label}</td>
                <td class="text-end fw-semibold">${fmtCurr(row.billed)}</td>
                <td class="text-end text-success fw-bold">${fmtCurr(row.collected)}</td>
                <td class="text-end text-danger fw-bold">${fmtCurr(row.due)}</td>
                <td class="text-center"><span class="badge ${badgeClass} rounded-pill px-2.5 py-1.5">${row.collection_rate}%</span></td>
            </tr>`;
        });
        tbody.innerHTML = html;
    }

    function renderRecentTransactions(trxs) {
        const tbody = document.getElementById('recentTrxTableBody');
        if (!trxs || trxs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-muted">No recent transactions.</td></tr>';
            return;
        }

        let html = '';
        trxs.forEach(t => {
            const pillClass = t.type === 'CREDIT' ? 'CREDIT' : (t.type === 'DEBIT' ? 'DEBIT' : 'DISCOUNT');
            const sign = t.type === 'CREDIT' ? '+' : (t.type === 'DEBIT' ? '-' : '');
            const colorClass = t.type === 'CREDIT' ? 'text-success' : (t.type === 'DEBIT' ? 'text-danger' : 'text-purple');

            html += `<tr>
                <td>
                    <div class="fw-bold text-dark" style="font-size: 13px;">${t.patient_name} <span class="text-muted fw-normal">(${t.patient_id})</span></div>
                    <div class="text-muted small" style="font-size: 11px;">${t.date_formatted} &bull; ${t.description}</div>
                </td>
                <td class="text-end">
                    <div class="fw-bold ${colorClass}">${sign}${fmtCurr(t.amount)}</div>
                    <span class="trx-pill ${pillClass}">${t.type === 'CREDIT' ? 'RECEIVED' : t.type}</span>
                </td>
                <td class="text-center">
                    ${t.receipt_url !== '#' ? `<a href="${t.receipt_url}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-2 py-0" style="font-size: 11px;"><i class="bi bi-printer"></i> Receipt</a>` : '-'}
                </td>
            </tr>`;
        });
        tbody.innerHTML = html;
    }

    // Initial Load
    loadFinancialData();
});
</script>

@endsection

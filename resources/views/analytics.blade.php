@extends('admin.layouts.layouts')
@section('title', 'Patient Analytics')

@section('content')
<style>
.analytics-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.analytics-title  { font-size:22px; font-weight:700; color:#2c3e50; }

/* Summary cards */
.stat-cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:16px; margin-bottom:28px; }
.stat-card  { background:#fff; border-radius:12px; padding:20px 22px; box-shadow:0 2px 10px rgba(0,0,0,.07); border-left:4px solid #4bab35; }
.stat-card.blue   { border-left-color:#3b82f6; }
.stat-card.orange { border-left-color:#f59e0b; }
.stat-card.purple { border-left-color:#8b5cf6; }
.stat-card.red    { border-left-color:#ef4444; }
.stat-label { font-size:12px; font-weight:600; color:#888; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; }
.stat-value { font-size:28px; font-weight:800; color:#2c3e50; line-height:1; }
.stat-sub   { font-size:12px; color:#888; margin-top:5px; }
.stat-growth-up   { color:#16a34a; font-weight:700; }
.stat-growth-down { color:#dc2626; font-weight:700; }

/* Controls */
.analytics-controls { display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:24px; }
.year-btn { background:#f3f4f6; border:1px solid #e5e7eb; border-radius:8px; padding:7px 14px; cursor:pointer; font-weight:600; color:#374151; font-size:14px; transition:all .15s; }
.year-btn:hover, .year-btn.active { background:#4bab35; color:#fff; border-color:#4bab35; }
.year-select { padding:7px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; color:#374151; background:#fff; }

/* Chart area */
.chart-card { background:#fff; border-radius:12px; padding:24px; box-shadow:0 2px 10px rgba(0,0,0,.07); margin-bottom:24px; }
.chart-title { font-size:15px; font-weight:700; color:#2c3e50; margin-bottom:20px; display:flex; align-items:center; gap:8px; }

/* Bar chart */
.bar-chart { display:flex; align-items:flex-end; gap:6px; height:200px; padding-bottom:28px; position:relative; }
.bar-wrap  { flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; height:100%; justify-content:flex-end; }
.bar       { width:100%; border-radius:6px 6px 0 0; background:linear-gradient(180deg,#4bab35,#2d8f22); transition:height .4s ease; min-height:2px; position:relative; cursor:pointer; }
.bar:hover { background:linear-gradient(180deg,#3d9429,#1e6b18); }
.bar-tip   { position:absolute; top:-28px; left:50%; transform:translateX(-50%); background:#2c3e50; color:#fff; font-size:11px; font-weight:700; padding:3px 7px; border-radius:5px; white-space:nowrap; pointer-events:none; display:none; }
.bar:hover .bar-tip { display:block; }
.bar-label { font-size:11px; color:#888; font-weight:600; position:absolute; bottom:-20px; }
.bar-zero  { background:#e5e7eb; }

/* Month table */
.month-table { width:100%; border-collapse:collapse; font-size:13px; }
.month-table th { background:#f8f9fa; padding:10px 14px; text-align:left; font-weight:700; color:#555; border-bottom:2px solid #e5e7eb; }
.month-table td { padding:10px 14px; border-bottom:1px solid #f0f0f0; color:#374151; }
.month-table tr:hover td { background:#f9fffe; }
.month-bar-inline { height:8px; background:linear-gradient(90deg,#4bab35,#2d8f22); border-radius:4px; min-width:2px; display:inline-block; transition:width .4s; }
.badge-best { background:#fef3c7; color:#92400e; font-size:11px; padding:2px 8px; border-radius:10px; font-weight:700; margin-left:8px; }

/* Diagnosis pills */
.diag-grid { display:flex; flex-wrap:wrap; gap:10px; }
.diag-pill { background:#f0faf0; border:1px solid #c3e6cb; border-radius:20px; padding:6px 14px; font-size:13px; color:#155724; font-weight:600; display:flex; align-items:center; gap:6px; }
.diag-count { background:#4bab35; color:#fff; border-radius:10px; padding:1px 8px; font-size:11px; font-weight:800; }

/* Loading */
.analytics-loading { text-align:center; padding:60px; color:#888; }

/* Grid layout */
.two-col { display:grid; grid-template-columns:2fr 1fr; gap:20px; }
@media(max-width:768px){ .two-col { grid-template-columns:1fr; } }
</style>

<div class="analytics-header">
    <div>
        <div class="analytics-title">
            <i class="fas fa-chart-bar me-2" style="color:#4bab35;"></i> Patient Analytics
        </div>
        <div style="font-size:13px;color:#888;margin-top:3px;">Monthly patient growth & insights</div>
    </div>
    <div class="analytics-controls">
        @if($isSuperadmin && $branches->count() > 1)
        <select id="branchFilter" class="year-select">
            <option value="">All Branches</option>
            @foreach($branches as $b)
                <option value="{{ $b->branch_id }}">{{ $b->branch_name }}</option>
            @endforeach
        </select>
        @else
            <span style="font-size:13px;font-weight:600;color:#4bab35;">
                <i class="fas fa-map-marker-alt me-1"></i>{{ $branchName ?? 'Your Branch' }}
            </span>
        @endif
        <select id="yearFilter" class="year-select">
            @for($y = now()->year; $y >= now()->year - 4; $y--)
                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button id="loadBtn" style="background:#4bab35;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-weight:700;cursor:pointer;font-size:14px;">
            <i class="fas fa-sync-alt me-1"></i> Load
        </button>
    </div>
</div>

{{-- Summary Stats --}}
<div class="stat-cards" id="statCards">
    <div class="stat-card">
        <div class="stat-label">New Patients</div>
        <div class="stat-value" id="statTotal">—</div>
        <div class="stat-sub" id="statGrowth">vs last year</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Total Followups</div>
        <div class="stat-value" id="statFollowups">—</div>
        <div class="stat-sub">recorded this year</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-label">IPD Patients</div>
        <div class="stat-value" id="statIpd">—</div>
        <div class="stat-sub">indoor treatments</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-label">Best Month</div>
        <div class="stat-value" id="statBest">—</div>
        <div class="stat-sub" id="statBestCount">most new patients</div>
    </div>
</div>

<div class="two-col">
    {{-- Bar Chart --}}
    <div class="chart-card">
        <div class="chart-title d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <i class="fas fa-chart-bar" style="color:#4bab35;"></i>
                <span id="chartMetricTitle">Monthly Activity Chart</span>
                <span id="chartYearLabel" style="font-size:13px;color:#888;font-weight:500;"></span>
            </div>
            <div class="btn-group btn-group-sm" role="group" id="metricFilterGroup">
                <button type="button" class="btn btn-sm btn-outline-success active metric-btn" data-metric="all">All Activity</button>
                <button type="button" class="btn btn-sm btn-outline-success metric-btn" data-metric="new">New</button>
                <button type="button" class="btn btn-sm btn-outline-info metric-btn" data-metric="followups">Followups</button>
                <button type="button" class="btn btn-sm btn-outline-primary metric-btn" data-metric="ipd">IPD</button>
            </div>
        </div>
        <div id="barChart" class="bar-chart">
            <div class="analytics-loading"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
        </div>
    </div>

    {{-- Top Diagnoses --}}
    <div class="chart-card">
        <div class="chart-title">
            <i class="fas fa-stethoscope" style="color:#4bab35;"></i>
            Top Diagnoses
        </div>
        <div id="diagContainer" class="diag-grid">
            <div class="analytics-loading"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
        </div>
    </div>
</div>

{{-- Monthly Table --}}
<div class="chart-card">
    <div class="chart-title">
        <i class="fas fa-table" style="color:#4bab35;"></i>
        Monthly Breakdown
    </div>
    <table class="month-table" id="monthTable">
        <thead>
            <tr>
                <th>Month</th>
                <th>New Patients</th>
                <th>Followups</th>
                <th>IPD Patients</th>
                <th style="width:30%">Total Activity / Trend</th>
            </tr>
        </thead>
        <tbody id="monthTableBody">
            <tr><td colspan="5" class="analytics-loading"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let globalAnalyticsData = null;
    let selectedMetric = 'all';

    loadAnalytics();

    document.getElementById('loadBtn').addEventListener('click', loadAnalytics);
    document.getElementById('yearFilter').addEventListener('change', loadAnalytics);
    const branchFilter = document.getElementById('branchFilter');
    if (branchFilter) branchFilter.addEventListener('change', loadAnalytics);

    document.querySelectorAll('.metric-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.metric-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedMetric = this.getAttribute('data-metric');
            if (globalAnalyticsData) {
                renderBarChart(globalAnalyticsData);
            }
        });
    });

    function loadAnalytics() {
        const year     = document.getElementById('yearFilter').value;
        const branchEl = document.getElementById('branchFilter');
        const branchId = branchEl ? branchEl.value : '{{ $branchId ?? "" }}';

        document.getElementById('barChart').innerHTML = '<div class="analytics-loading"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
        document.getElementById('diagContainer').innerHTML = '<div class="analytics-loading"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
        document.getElementById('monthTableBody').innerHTML = '<tr><td colspan="5" class="analytics-loading"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>';

        fetch('{{ route("patient.analytics.data") }}?_t=' + new Date().getTime(), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ year: year, branch_id: branchId })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) { alert('Error: ' + data.message); return; }
            globalAnalyticsData = data;
            renderStats(data);
            renderBarChart(data);
            renderTable(data);
            renderDiagnoses(data);
        })
        .catch(e => console.error('Analytics error:', e));
    }

    function renderStats(data) {
        document.getElementById('statTotal').textContent     = (data.total_year || 0).toLocaleString();
        document.getElementById('statFollowups').textContent = (data.total_followups || 0).toLocaleString();
        document.getElementById('statIpd').textContent       = (data.total_ipd || 0).toLocaleString();
        document.getElementById('statBest').textContent      = data.best_month || '—';
        document.getElementById('statBestCount').textContent = (data.best_count || 0) + ' new patients';
        document.getElementById('chartYearLabel').textContent = '(' + data.year + ')';

        const growthEl = document.getElementById('statGrowth');
        if (data.growth !== null) {
            const up  = data.growth >= 0;
            growthEl.innerHTML = `<span class="${up ? 'stat-growth-up' : 'stat-growth-down'}">
                ${up ? '▲' : '▼'} ${Math.abs(data.growth)}%
            </span> vs ${data.year - 1}`;
        } else {
            growthEl.textContent = 'vs ' + (data.year - 1);
        }
    }

    function getValForMetric(m, metric) {
        if (metric === 'new') return m.new_patients ?? m.count ?? 0;
        if (metric === 'followups') return m.followups ?? 0;
        if (metric === 'ipd') return m.ipd_patients ?? 0;
        return (m.new_patients ?? m.count ?? 0) + (m.followups ?? 0) + (m.ipd_patients ?? 0);
    }

    function renderBarChart(data) {
        const vals = data.months.map(m => getValForMetric(m, selectedMetric));
        const max = Math.max(...vals, 1);
        let html  = '';

        data.months.forEach(m => {
            const val = getValForMetric(m, selectedMetric);
            const pct = Math.round((val / max) * 100);
            const isBest = m.label === data.best_month && val > 0;
            const barClass = val === 0 ? 'bar bar-zero' : 'bar';
            
            let color = '';
            if (selectedMetric === 'followups') color = 'background:linear-gradient(180deg,#0dcaf0,#0aa2c0);';
            else if (selectedMetric === 'ipd') color = 'background:linear-gradient(180deg,#0d6efd,#0a58ca);';
            else if (isBest) color = 'background:linear-gradient(180deg,#f59e0b,#d97706);';

            html += `<div class="bar-wrap">
                <div class="${barClass}" style="height:${Math.max(pct, 3)}%;${color}">
                    <div class="bar-tip" style="line-height: 1.3; text-align: center;">
                        <span style="font-weight:700;">${val}</span><br>
                        <span style="font-size:10px; opacity:0.85;">New:${m.new_patients || 0} | Fol:${m.followups || 0} | IPD:${m.ipd_patients || 0}</span>
                    </div>
                </div>
                <span class="bar-label">${m.label}</span>
            </div>`;
        });
        document.getElementById('barChart').innerHTML = html;
    }

    function renderTable(data) {
        const totalAct = data.total_activity || 1;
        const maxAct   = Math.max(...data.months.map(m => (m.new_patients || 0) + (m.followups || 0) + (m.ipd_patients || 0)), 1);
        let rows  = '';

        data.months.forEach(m => {
            const newCount = m.new_patients ?? m.count ?? 0;
            const folCount = m.followups ?? 0;
            const ipdCount = m.ipd_patients ?? 0;
            const monthTotal = newCount + folCount + ipdCount;
            const pct = Math.round((monthTotal / maxAct) * 100);
            const isBest = m.label === data.best_month && newCount > 0;

            rows += `<tr>
                <td><strong>${m.label} ${data.year}</strong>${isBest ? '<span class="badge-best">🏆 Best</span>' : ''}</td>
                <td><span class="badge bg-success text-white fw-bold px-2 py-1" style="font-size: 13px;">${newCount}</span></td>
                <td><span class="badge bg-info text-dark fw-bold px-2 py-1" style="font-size: 13px;">${folCount}</span></td>
                <td><span class="badge bg-primary text-white fw-bold px-2 py-1" style="font-size: 13px;">${ipdCount}</span></td>
                <td>
                    <span class="month-bar-inline" style="width:${pct}%;"></span>
                    <span style="font-size:11px;color:#888;margin-left:6px;font-weight:600;">${monthTotal} Total (${monthTotal > 0 ? Math.round((monthTotal / totalAct) * 100) + '%' : '0%'})</span>
                </td>
            </tr>`;
        });
        document.getElementById('monthTableBody').innerHTML = rows || '<tr><td colspan="5" style="text-align:center;color:#888;padding:20px;">No data for this period</td></tr>';
    }

    function renderDiagnoses(data) {
        const diags = data.top_diagnoses;
        const keys  = Object.keys(diags);
        if (!keys.length) {
            document.getElementById('diagContainer').innerHTML = '<p style="color:#888;font-size:13px;">No diagnosis data available.</p>';
            return;
        }
        let html = '';
        keys.forEach(d => {
            html += `<div class="diag-pill">
                ${d}
                <span class="diag-count">${diags[d]}</span>
            </div>`;
        });
        document.getElementById('diagContainer').innerHTML = html;
    }
});
</script>
@endsection

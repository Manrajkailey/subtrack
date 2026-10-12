<?php
require_once __DIR__ . '/bootstrap.php';

$currentUser = $authService->requireLogin();
$userId = $currentUser->id;
$pageTitle = 'Revenue Analytics';
$activeNav = 'revenue';

$mrr = $revenueCalculator->mrr($userId);
$arr = $revenueCalculator->arr($userId);
$arpu = $revenueCalculator->arpu($userId);
$churn = $revenueCalculator->churnRate($userId, 30);
$renewalRate = $revenueCalculator->renewalRate($userId, 30);
$totalRevenue = $revenueCalculator->totalRevenue($userId);

$months = 12;
$monthlySeries = $revenueCalculator->monthlyRevenueSeries($userId, $months);
$monthLabels = array_map(fn ($k) => (new DateTimeImmutable($k . '-01'))->format('M Y'), array_keys($monthlySeries));
$monthValues = array_values($monthlySeries);

$planPopularity = $revenueCalculator->planPopularity($userId);

require __DIR__ . '/partials/app_header.php';
?>

<div class="page-header">
    <div>
        <h1>Revenue Analytics</h1>
        <div class="sub">Recurring-revenue metrics calculated from your live subscription data</div>
    </div>
</div>

<div class="kpi-grid">
    <div class="kpi-card">
        <div class="label">MRR</div>
        <div class="value"><?= e(inr($mrr)) ?></div>
        <div class="delta text-dim">Monthly Recurring Revenue</div>
    </div>
    <div class="kpi-card">
        <div class="label">ARR</div>
        <div class="value"><?= e(inr($arr)) ?></div>
        <div class="delta text-dim">MRR × 12</div>
    </div>
    <div class="kpi-card">
        <div class="label">ARPU</div>
        <div class="value"><?= e(inr($arpu)) ?></div>
        <div class="delta text-dim">Average Revenue Per User</div>
    </div>
    <div class="kpi-card">
        <div class="label">Churn Rate (30d)</div>
        <div class="value"><?= e(number_format($churn, 1)) ?>%</div>
        <div class="delta <?= $churn > 5 ? 'down' : 'up' ?>">Renewal rate: <?= e(number_format($renewalRate, 1)) ?>%</div>
    </div>
    <div class="kpi-card">
        <div class="label">Total Revenue (all-time)</div>
        <div class="value"><?= e(inr($totalRevenue)) ?></div>
    </div>
</div>

<div class="panel-grid">
    <div class="panel">
        <h3>Monthly Revenue — last <?= $months ?> months</h3>
        <div class="chart-box">
            <canvas id="revenueTrendChart"></canvas>
        </div>
    </div>
    <div class="panel">
        <h3>Plan Popularity</h3>
        <div class="chart-box small">
            <canvas id="planChart"></canvas>
        </div>
    </div>
</div>

<div class="panel">
    <h3>How these numbers are calculated</h3>
    <p class="mt-0">
        <strong>MRR</strong> sums every active subscription's price, converting annual plans to a monthly
        figure (price ÷ 12). <strong>ARR</strong> is MRR × 12. <strong>ARPU</strong> is MRR divided by the
        number of active subscribers. <strong>Churn rate</strong> is subscriptions cancelled in the last
        30 days divided by (active subscribers + those cancellations).
    </p>
</div>

<script src="assets/js/chart.umd.min.js"></script>
<script>
(function () {
    var c = SubTrackColors.read();
    Chart.defaults.font.family = "Inter, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif";

    var trend = new Chart(document.getElementById('revenueTrendChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($monthLabels) ?>,
            datasets: [{
                label: 'Revenue',
                data: <?= json_encode($monthValues) ?>,
                backgroundColor: c.primary,
                borderRadius: 6,
                maxBarThickness: 38
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: c.tick } },
                y: { grid: { color: c.grid }, ticks: { color: c.tick }, beginAtZero: true }
            }
        }
    });

    var plans = new Chart(document.getElementById('planChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_map(fn ($p) => $p['plan']->planName, $planPopularity)) ?>,
            datasets: [{
                data: <?= json_encode(array_map(fn ($p) => $p['count'], $planPopularity)) ?>,
                backgroundColor: c.series,
                borderColor: c.surface,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: { legend: { position: 'bottom', labels: { color: c.tick, boxWidth: 12, usePointStyle: true } } }
        }
    });

})();
</script>

<?php require __DIR__ . '/partials/app_footer.php'; ?>

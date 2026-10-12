<?php
require_once __DIR__ . '/bootstrap.php';

$currentUser = $authService->requireLogin();
$userId = $currentUser->id;
$pageTitle = 'Renewals';
$activeNav = 'renewals';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'renew') {
        $subscriptionService->renew($userId, $id);
        flash('success', 'Subscription renewed and payment logged.');
    } elseif ($action === 'cancel') {
        $subscriptionService->cancel($userId, $id);
        flash('success', 'Subscription cancelled.');
    }
    redirect('renewals.php');
}

$upcoming = $subscriptionRepository->upcomingRenewals($userId, RENEWAL_WINDOW_DAYS);
$overdue = $subscriptionRepository->overdueRenewals($userId);
// upcomingRenewals already includes anything <= cutoff, including overdue ones;
// split them here so the page can show two clearly labeled lists.
$dueSoon = array_values(array_filter($upcoming, fn ($s) => $s->daysUntilRenewal() >= 0));

require __DIR__ . '/partials/app_header.php';
?>

<div class="page-header">
    <div>
        <h1>Renewals</h1>
        <div class="sub">Renewals due in the next <?= RENEWAL_WINDOW_DAYS ?> days, and anything already overdue</div>
    </div>
</div>

<div class="panel" style="margin-bottom:20px;">
    <h3>🔴 Overdue — needs action (<?= count($overdue) ?>)</h3>
    <?php if (empty($overdue)): ?>
        <p class="text-dim mt-0">Nothing overdue. Great job staying on top of renewals.</p>
    <?php else: ?>
        <?php foreach ($overdue as $sub): renewalRow($sub); endforeach; ?>
    <?php endif; ?>
</div>

<div class="panel">
    <h3>🟡 Renewal due soon (<?= count($dueSoon) ?>)</h3>
    <?php if (empty($dueSoon)): ?>
        <p class="text-dim mt-0">No renewals due in the next <?= RENEWAL_WINDOW_DAYS ?> days.</p>
    <?php else: ?>
        <?php foreach ($dueSoon as $sub): renewalRow($sub); endforeach; ?>
    <?php endif; ?>
</div>

<?php
/** Small local helper to render one renewal row -- kept in this page since it's not reused elsewhere. */
function renewalRow(Subscription $sub): void
{
    $days = $sub->daysUntilRenewal();
    $dueLabel = $days < 0 ? abs($days) . ' day(s) overdue' : ($days === 0 ? 'Due today' : "in {$days} day(s)");
    ?>
    <div class="plan-bar-row" style="align-items:center;">
        <div style="flex:1;">
            <strong><?= e($sub->customerName) ?></strong>
            <span class="text-dim"> — <?= e($sub->planName) ?> (<?= e(inr((float) $sub->planPrice)) ?>)</span>
            <div class="text-dim" style="font-size:12.5px;"><?= e(formatDate($sub->renewalDate)) ?> · <?= e($dueLabel) ?></div>
        </div>
        <div class="row-actions">
            <form method="post">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="renew">
                <input type="hidden" name="id" value="<?= $sub->id ?>">
                <button type="submit" class="btn btn-secondary btn-sm">Mark Renewed</button>
            </form>
            <form method="post" onsubmit="return confirm('Cancel this subscription?');">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="cancel">
                <input type="hidden" name="id" value="<?= $sub->id ?>">
                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
            </form>
        </div>
    </div>
    <?php
}

require __DIR__ . '/partials/app_footer.php';

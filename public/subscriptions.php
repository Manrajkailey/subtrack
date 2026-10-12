<?php
require_once __DIR__ . '/bootstrap.php';

$currentUser = $authService->requireLogin();
$userId = $currentUser->id;
$pageTitle = 'Subscriptions';
$activeNav = 'subscriptions';

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
    } elseif ($action === 'delete') {
        $subscriptionService->delete($userId, $id);
        flash('success', 'Subscription deleted.');
    }
    redirect('subscriptions.php');
}

$statusFilter = $_GET['status'] ?? 'all';
$subscriptions = $subscriptionRepository->allForUser($userId);

if ($statusFilter === 'active') {
    $subscriptions = array_values(array_filter($subscriptions, fn ($s) => $s->status === 'active'));
} elseif ($statusFilter === 'cancelled') {
    $subscriptions = array_values(array_filter($subscriptions, fn ($s) => $s->status === 'cancelled'));
}

require __DIR__ . '/partials/app_header.php';
?>

<div class="page-header">
    <div>
        <h1>Subscriptions</h1>
        <div class="sub"><?= count($subscriptions) ?> shown</div>
    </div>
    <a href="subscription_form.php" class="btn btn-primary" style="width:auto;">+ Add Subscription</a>
</div>

<div class="table-toolbar">
    <div>
        <a href="subscriptions.php?status=all" class="btn btn-sm <?= $statusFilter === 'all' ? 'btn-primary' : 'btn-secondary' ?>" style="width:auto;">All</a>
        <a href="subscriptions.php?status=active" class="btn btn-sm <?= $statusFilter === 'active' ? 'btn-primary' : 'btn-secondary' ?>" style="width:auto;">Active</a>
        <a href="subscriptions.php?status=cancelled" class="btn btn-sm <?= $statusFilter === 'cancelled' ? 'btn-primary' : 'btn-secondary' ?>" style="width:auto;">Cancelled</a>
    </div>
</div>

<div class="table-wrap">
    <?php if (empty($subscriptions)): ?>
        <div class="empty-state">No subscriptions here yet. <a href="subscription_form.php">Add one</a>.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Billing</th>
                    <th>Renewal Date</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscriptions as $sub): ?>
                    <tr>
                        <td>
                            <?= e($sub->customerName) ?>
                            <div class="text-dim" style="font-size:12px;"><?= e($sub->customerEmail) ?></div>
                        </td>
                        <td><?= e($sub->planName) ?></td>
                        <td><?= e(inr((float) $sub->planPrice)) ?></td>
                        <td><?= $sub->billingCycle === 'annual' ? 'Annual' : 'Monthly' ?></td>
                        <td>
                            <?= e(formatDate($sub->renewalDate)) ?>
                            <?php if ($sub->isActive() && $sub->daysUntilRenewal() < 0): ?>
                                <div><span class="badge badge-overdue">Overdue</span></div>
                            <?php elseif ($sub->isActive() && $sub->daysUntilRenewal() <= RENEWAL_WINDOW_DAYS): ?>
                                <div><span class="badge badge-due">Due soon</span></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?= $sub->status === 'active' ? 'active' : 'cancelled' ?>">
                                <?= $sub->status === 'active' ? 'Active' : 'Cancelled' ?>
                            </span>
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="subscription_form.php?id=<?= $sub->id ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <?php if ($sub->isActive()): ?>
                                    <form method="post">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="action" value="renew">
                                        <input type="hidden" name="id" value="<?= $sub->id ?>">
                                        <button type="submit" class="btn btn-secondary btn-sm">Renew</button>
                                    </form>
                                    <form method="post" onsubmit="return confirm('Cancel this subscription?');">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="action" value="cancel">
                                        <input type="hidden" name="id" value="<?= $sub->id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <form method="post" onsubmit="return confirm('Delete this subscription and its payment history?');">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $sub->id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/app_footer.php'; ?>

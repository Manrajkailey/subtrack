<?php
require_once __DIR__ . '/bootstrap.php';

$currentUser = $authService->requireLogin();
$userId = $currentUser->id;
$pageTitle = 'Subscription Plans';
$activeNav = 'plans';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    verifyCsrf();
    $id = (int) ($_POST['id'] ?? 0);
    try {
        $planRepository->delete($userId, $id);
        flash('success', 'Plan deleted.');
    } catch (PDOException $e) {
        flash('error', 'Cannot delete this plan while subscriptions are using it. Move or cancel those subscriptions first.');
    }
    redirect('plans.php');
}

$plans = $planRepository->allForUser($userId);
$subscriberCounts = $planRepository->subscriberCounts($userId);

require __DIR__ . '/partials/app_header.php';
?>

<div class="page-header">
    <div>
        <h1>Subscription Plans</h1>
        <div class="sub"><?= count($plans) ?> plans</div>
    </div>
    <a href="plan_form.php" class="btn btn-primary" style="width:auto;">+ Add Plan</a>
</div>

<div class="table-wrap">
    <?php if (empty($plans)): ?>
        <div class="empty-state">No plans yet. <a href="plan_form.php">Create your first plan</a>.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Price</th>
                    <th>Billing</th>
                    <th>Active Subscribers</th>
                    <th>Description</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan): ?>
                    <tr>
                        <td><strong><?= e($plan->planName) ?></strong></td>
                        <td><?= e(inr($plan->price)) ?></td>
                        <td><?= $plan->billingCycle === 'annual' ? 'Annual' : 'Monthly' ?></td>
                        <td><?= (int) ($subscriberCounts[$plan->id] ?? 0) ?></td>
                        <td class="text-dim"><?= e($plan->description ?? '—') ?></td>
                        <td>
                            <div class="row-actions">
                                <a href="plan_form.php?id=<?= $plan->id ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="post" onsubmit="return confirm('Delete this plan?');">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $plan->id ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/app_footer.php'; ?>

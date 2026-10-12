<?php
require_once __DIR__ . '/bootstrap.php';

$currentUser = $authService->requireLogin();
$userId = $currentUser->id;

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$editing = $id !== null;
$subscription = $editing ? $subscriptionRepository->find($userId, $id) : null;

if ($editing && $subscription === null) {
    flash('error', 'Subscription not found.');
    redirect('subscriptions.php');
}

$pageTitle = $editing ? 'Edit Subscription' : 'Add Subscription';
$activeNav = 'subscriptions';

$customers = $customerRepository->allForUser($userId);
$plans = $planRepository->allForUser($userId);

if (empty($customers) || empty($plans)) {
    require __DIR__ . '/partials/app_header.php';
    ?>
    <div class="page-header"><h1><?= e($pageTitle) ?></h1></div>
    <div class="card form-card">
        <p>You need at least one customer and one plan before creating a subscription.</p>
        <div class="form-actions">
            <?php if (empty($customers)): ?><a href="customer_form.php" class="btn btn-primary">Add a customer</a><?php endif; ?>
            <?php if (empty($plans)): ?><a href="plan_form.php" class="btn btn-primary">Add a plan</a><?php endif; ?>
        </div>
    </div>
    <?php
    require __DIR__ . '/partials/app_footer.php';
    exit;
}

$old = [
    'customer_id' => $subscription->customerId ?? ($customers[0]->id ?? ''),
    'plan_id' => $subscription->planId ?? ($plans[0]->id ?? ''),
    'start_date' => $subscription->startDate ?? date('Y-m-d'),
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $old['customer_id'] = (int) ($_POST['customer_id'] ?? 0);
    $old['plan_id'] = (int) ($_POST['plan_id'] ?? 0);
    $old['start_date'] = $_POST['start_date'] ?? '';

    if ($customerRepository->find($userId, $old['customer_id']) === null) {
        $errors[] = 'Please choose a valid customer.';
    }
    if ($planRepository->find($userId, $old['plan_id']) === null) {
        $errors[] = 'Please choose a valid plan.';
    }
    if ($old['start_date'] === '' || DateTime::createFromFormat('Y-m-d', $old['start_date']) === false) {
        $errors[] = 'Please choose a valid start date.';
    }

    if (empty($errors)) {
        if ($editing) {
            $subscriptionService->update($userId, $id, $old['customer_id'], $old['plan_id'], $old['start_date']);
            flash('success', 'Subscription updated.');
        } else {
            $subscriptionService->subscribe($userId, $old['customer_id'], $old['plan_id'], $old['start_date']);
            flash('success', 'Subscription created and first payment logged.');
        }
        redirect('subscriptions.php');
    }
}

require __DIR__ . '/partials/app_header.php';
?>

<div class="page-header">
    <div>
        <h1><?= e($pageTitle) ?></h1>
        <div class="sub"><a href="subscriptions.php">&larr; Back to subscriptions</a></div>
    </div>
</div>

<div class="card form-card">
    <?php foreach ($errors as $error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" novalidate>
        <?= csrfField() ?>
        <div class="field">
            <label for="customer_id">Customer</label>
            <select id="customer_id" name="customer_id" required>
                <?php foreach ($customers as $c): ?>
                    <option value="<?= $c->id ?>" <?= (int) $old['customer_id'] === $c->id ? 'selected' : '' ?>>
                        <?= e($c->name) ?> — <?= e($c->email) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="plan_id">Plan</label>
            <select id="plan_id" name="plan_id" required>
                <?php foreach ($plans as $p): ?>
                    <option value="<?= $p->id ?>" <?= (int) $old['plan_id'] === $p->id ? 'selected' : '' ?>>
                        <?= e($p->planName) ?> — <?= e(inr($p->price)) ?> / <?= $p->billingCycle === 'annual' ? 'year' : 'month' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="start_date">Start date</label>
            <input type="date" id="start_date" name="start_date" value="<?= e($old['start_date']) ?>" required>
            <small>The renewal date is calculated automatically from the plan's billing cycle.</small>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $editing ? 'Save changes' : 'Create subscription' ?></button>
            <a href="subscriptions.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/partials/app_footer.php'; ?>

<?php
require_once __DIR__ . '/bootstrap.php';

$currentUser = $authService->requireLogin();
$userId = $currentUser->id;

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$editing = $id !== null;
$plan = $editing ? $planRepository->find($userId, $id) : null;

if ($editing && $plan === null) {
    flash('error', 'Plan not found.');
    redirect('plans.php');
}

$pageTitle = $editing ? 'Edit Plan' : 'Add Plan';
$activeNav = 'plans';

$old = [
    'plan_name' => $plan->planName ?? '',
    'price' => $plan->price ?? '',
    'billing_cycle' => $plan->billingCycle ?? 'monthly',
    'description' => $plan->description ?? '',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $old['plan_name'] = trim($_POST['plan_name'] ?? '');
    $old['price'] = $_POST['price'] ?? '';
    $old['billing_cycle'] = ($_POST['billing_cycle'] ?? '') === 'annual' ? 'annual' : 'monthly';
    $old['description'] = trim($_POST['description'] ?? '') ?: null;

    if ($old['plan_name'] === '') {
        $errors[] = 'Plan name is required.';
    }
    if (!is_numeric($old['price']) || (float) $old['price'] <= 0) {
        $errors[] = 'Price must be a positive number.';
    }

    if (empty($errors)) {
        $price = (float) $old['price'];
        if ($editing) {
            $planRepository->update($userId, $id, $old['plan_name'], $price, $old['billing_cycle'], $old['description']);
            flash('success', 'Plan updated.');
        } else {
            $planRepository->create($userId, $old['plan_name'], $price, $old['billing_cycle'], $old['description']);
            flash('success', 'Plan created.');
        }
        redirect('plans.php');
    }
}

require __DIR__ . '/partials/app_header.php';
?>

<div class="page-header">
    <div>
        <h1><?= e($pageTitle) ?></h1>
        <div class="sub"><a href="plans.php">&larr; Back to plans</a></div>
    </div>
</div>

<div class="card form-card">
    <?php foreach ($errors as $error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" novalidate>
        <?= csrfField() ?>
        <div class="field">
            <label for="plan_name">Plan name</label>
            <input type="text" id="plan_name" name="plan_name" value="<?= e($old['plan_name']) ?>" placeholder="e.g. Premium" required>
        </div>
        <div class="two-col">
            <div class="field">
                <label for="price">Price (₹)</label>
                <input type="number" id="price" name="price" value="<?= e((string) $old['price']) ?>" min="0" step="0.01" required>
            </div>
            <div class="field">
                <label for="billing_cycle">Billing cycle</label>
                <select id="billing_cycle" name="billing_cycle">
                    <option value="monthly" <?= $old['billing_cycle'] === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                    <option value="annual" <?= $old['billing_cycle'] === 'annual' ? 'selected' : '' ?>>Annual</option>
                </select>
            </div>
        </div>
        <div class="field">
            <label for="description">Description (optional)</label>
            <textarea id="description" name="description" rows="3"><?= e($old['description'] ?? '') ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $editing ? 'Save changes' : 'Create plan' ?></button>
            <a href="plans.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/partials/app_footer.php'; ?>

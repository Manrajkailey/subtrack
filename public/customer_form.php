<?php
require_once __DIR__ . '/bootstrap.php';

$currentUser = $authService->requireLogin();
$userId = $currentUser->id;

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$editing = $id !== null;
$customer = $editing ? $customerRepository->find($userId, $id) : null;

if ($editing && $customer === null) {
    flash('error', 'Customer not found.');
    redirect('customers.php');
}

$pageTitle = $editing ? 'Edit Customer' : 'Add Customer';
$activeNav = 'customers';

$old = [
    'name' => $customer->name ?? '',
    'email' => $customer->email ?? '',
    'phone' => $customer->phone ?? '',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $old['name'] = trim($_POST['name'] ?? '');
    $old['email'] = trim($_POST['email'] ?? '');
    $old['phone'] = trim($_POST['phone'] ?? '') ?: null;

    if ($old['name'] === '') {
        $errors[] = 'Name is required.';
    } elseif (!preg_match('/^[\p{L}]+(?: [\p{L}]+)*$/u', $old['name'])) {
        $errors[] = 'Name can contain letters and spaces only.';
    }
    if ($old['phone'] !== null && !preg_match('/^[0-9]{1,10}$/', $old['phone'])) {
        $errors[] = 'Phone must contain digits only, up to 10 digits.';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }

    if (empty($errors)) {
        if ($editing) {
            $customerRepository->update($userId, $id, $old['name'], $old['email'], $old['phone']);
            flash('success', 'Customer updated.');
        } else {
            $customerRepository->create($userId, $old['name'], $old['email'], $old['phone']);
            flash('success', 'Customer added.');
        }
        redirect('customers.php');
    }
}

require __DIR__ . '/partials/app_header.php';
?>

<div class="page-header">
    <div>
        <h1><?= e($pageTitle) ?></h1>
        <div class="sub"><a href="customers.php">&larr; Back to customers</a></div>
    </div>
</div>

<div class="card form-card">
    <?php foreach ($errors as $error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" novalidate>
        <?= csrfField() ?>
        <div class="field">
            <label for="name">Customer name</label>
            <input type="text" id="name" name="name" value="<?= e($old['name']) ?>" required
                   pattern="[A-Za-zÀ-ɏ]+( [A-Za-zÀ-ɏ]+)*" title="Letters and spaces only"
                   oninput="this.value = this.value.replace(/[^\p{L} ]/gu, '')">
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= e($old['email']) ?>" required>
        </div>
        <div class="field">
            <label for="phone">Phone (optional)</label>
            <input type="text" id="phone" name="phone" value="<?= e($old['phone'] ?? '') ?>"
                   maxlength="10" inputmode="numeric" pattern="[0-9]{1,10}" title="Digits only, up to 10"
                   oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $editing ? 'Save changes' : 'Add customer' ?></button>
            <a href="customers.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/partials/app_footer.php'; ?>

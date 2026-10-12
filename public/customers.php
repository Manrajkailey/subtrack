<?php
require_once __DIR__ . '/bootstrap.php';

$currentUser = $authService->requireLogin();
$userId = $currentUser->id;
$pageTitle = 'Customers';
$activeNav = 'customers';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    verifyCsrf();
    $id = (int) ($_POST['id'] ?? 0);
    $customerRepository->delete($userId, $id);
    flash('success', 'Customer removed.');
    redirect('customers.php');
}

$customers = $customerRepository->allForUser($userId);

require __DIR__ . '/partials/app_header.php';
?>

<div class="page-header">
    <div>
        <h1>Customers</h1>
        <div class="sub"><?= count($customers) ?> total</div>
    </div>
    <a href="customer_form.php" class="btn btn-primary" style="width:auto;">+ Add Customer</a>
</div>

<div class="table-wrap">
    <?php if (empty($customers)): ?>
        <div class="empty-state">
            No customers yet. <a href="customer_form.php">Add your first customer</a>.
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Customer Since</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= e($customer->name) ?></td>
                        <td><?= e($customer->email) ?></td>
                        <td><?= e($customer->phone ?? '—') ?></td>
                        <td><?= e(formatDate($customer->createdAt)) ?></td>
                        <td>
                            <div class="row-actions">
                                <a href="customer_form.php?id=<?= $customer->id ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="post" onsubmit="return confirm('Delete this customer? Their subscriptions and payment history will be removed too.');">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $customer->id ?>">
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

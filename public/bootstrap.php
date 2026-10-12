<?php

/**
 * bootstrap.php: the app's tiny composition root.
 *
 * Every page includes this once. It wires concrete repositories into the
 * services that need them -- the one place in the app allowed to know
 * about both interfaces and concrete classes at the same time. Pages
 * and services themselves only ever see the interfaces / service
 * classes, never `new PdoXxxRepository()` directly.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Models/Customer.php';
require_once __DIR__ . '/../src/Models/Plan.php';
require_once __DIR__ . '/../src/Models/Subscription.php';
require_once __DIR__ . '/../src/Models/Payment.php';

require_once __DIR__ . '/../src/Repositories/UserRepositoryInterface.php';
require_once __DIR__ . '/../src/Repositories/PdoUserRepository.php';
require_once __DIR__ . '/../src/Repositories/CustomerRepositoryInterface.php';
require_once __DIR__ . '/../src/Repositories/PdoCustomerRepository.php';
require_once __DIR__ . '/../src/Repositories/PlanRepositoryInterface.php';
require_once __DIR__ . '/../src/Repositories/PdoPlanRepository.php';
require_once __DIR__ . '/../src/Repositories/SubscriptionRepositoryInterface.php';
require_once __DIR__ . '/../src/Repositories/PdoSubscriptionRepository.php';
require_once __DIR__ . '/../src/Repositories/PaymentRepositoryInterface.php';
require_once __DIR__ . '/../src/Repositories/PdoPaymentRepository.php';

require_once __DIR__ . '/../src/Services/AuthService.php';
require_once __DIR__ . '/../src/Services/SubscriptionService.php';
require_once __DIR__ . '/../src/Services/RevenueCalculatorService.php';

$pdo = Database::connection();

$userRepository = new PdoUserRepository($pdo);
$customerRepository = new PdoCustomerRepository($pdo);
$planRepository = new PdoPlanRepository($pdo);
$subscriptionRepository = new PdoSubscriptionRepository($pdo);
$paymentRepository = new PdoPaymentRepository($pdo);

$authService = new AuthService($userRepository);
$subscriptionService = new SubscriptionService($subscriptionRepository, $planRepository, $paymentRepository);
$revenueCalculator = new RevenueCalculatorService($subscriptionRepository, $planRepository, $paymentRepository);

// --- Small shared helpers -------------------------------------------------

/** Escapes a value for safe HTML output. */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Formats a number as Indian Rupees using the Indian digit-grouping
 * convention, e.g. 1499000.5 -> "₹14,99,000.50". Written by hand (no
 * intl extension) so it works on a stock XAMPP install.
 */
function inr(float $amount): string
{
    $negative = $amount < 0;
    $amount = abs($amount);
    $paise = number_format($amount, 2, '.', '');
    [$rupees, $decimals] = explode('.', $paise);

    if (strlen($rupees) > 3) {
        $lastThree = substr($rupees, -3);
        $rest = substr($rupees, 0, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
        $rupees = $rest . ',' . $lastThree;
    }

    return ($negative ? '-' : '') . '₹' . $rupees . '.' . $decimals;
}

function formatDate(?string $date): string
{
    if (!$date) {
        return '—';
    }
    return (new DateTimeImmutable($date))->format('d M Y');
}

function redirect(string $path)
{
    header('Location: ' . $path);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function getFlashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

/** CSRF token helpers -- every state-changing form includes this token. */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function verifyCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(400);
        die('Your session expired. Please go back and try again.');
    }
}

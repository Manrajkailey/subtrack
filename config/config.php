<?php
/**
 * Application configuration.
 *
 * For real deployment, copy this file's DB_* values to match your MySQL
 * setup (see README.md). Nothing else in the app should hard-code
 * connection details -- everything reads them from here (DIP: the rest
 * of the app depends on this single configuration source, not on
 * scattered literals).
 */

// --- Database ---------------------------------------------------------
require_once __DIR__ . '/env.php';

// Values come from .env (see .env.example); defaults match local XAMPP.
define('DB_DRIVER', env('DB_DRIVER', 'mysql'));   // mysql, pgsql (Supabase) or sqlite
define('DB_HOST', env('DB_HOST', '127.0.0.1'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_NAME', env('DB_NAME', 'subtrack'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));
define('DB_SSLMODE', env('DB_SSLMODE', 'require'));   // pgsql only

// Only used when DB_DRIVER is 'sqlite' (handy for quick local testing
// without a MySQL server installed).
define('DB_SQLITE_PATH', __DIR__ . '/../database/subtrack.sqlite');

// --- App ----------------------------------------------------------------
define('APP_NAME', 'SubTrack');
define('APP_TAGLINE', 'Business Subscription Revenue Tracker');

// Days ahead considered an "upcoming" renewal on the Renewals page.
define('RENEWAL_WINDOW_DAYS', 14);

// --- Error reporting (turn DISPLAY off in production) ------------------
error_reporting(E_ALL);
ini_set('display_errors', '1');

// --- Sessions ------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Kolkata');

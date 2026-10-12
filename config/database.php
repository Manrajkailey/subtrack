<?php
/**
 * Database: a thin singleton wrapper around PDO.
 *
 * Single Responsibility: this class does exactly one thing -- hand back
 * a configured PDO connection. Every repository depends on this (via the
 * PDO instance it returns), never on raw mysqli/connection details, which
 * is what lets repositories be unit-testable and swappable (DIP).
 */

require_once __DIR__ . '/config.php';

class Database
{
    private static ?PDO $instance = null;

    private function __construct()
    {
        // Not instantiable; use Database::connection().
    }

    public static function connection(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
        }

        return self::$instance;
    }

    private static function createConnection(): PDO
    {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        if (DB_DRIVER === 'sqlite') {
            $pdo = new PDO('sqlite:' . DB_SQLITE_PATH, null, null, $options);
            $pdo->exec('PRAGMA foreign_keys = ON');
            return $pdo;
        }

        if (DB_DRIVER === 'pgsql') {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
                DB_HOST,
                DB_PORT,
                DB_NAME,
                DB_SSLMODE
            );
            // Supabase's transaction pooler doesn't support server-side prepares.
            $options[PDO::ATTR_EMULATE_PREPARES] = true;
            return new PDO($dsn, DB_USER, DB_PASS, $options);
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_CHARSET
        );

        return new PDO($dsn, DB_USER, DB_PASS, $options);
    }
}

<?php

require_once __DIR__ . '/UserRepositoryInterface.php';
require_once __DIR__ . '/../Models/User.php';

/**
 * PdoUserRepository: the only class in the app that knows the `users`
 * table's SQL. Everything above it (services, controllers) talks to
 * UserRepositoryInterface instead (DIP), so this class could be swapped
 * for a different storage engine without touching the rest of the app.
 */
class PdoUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return $row ? User::fromRow($row) : null;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? User::fromRow($row) : null;
    }

    public function create(string $name, string $email, string $passwordHash, string $businessName): User
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (name, email, password_hash, business_name) VALUES (:name, :email, :password_hash, :business_name)'
        );
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'business_name' => $businessName,
        ]);

        return $this->findById((int) $this->pdo->lastInsertId('users_id_seq'));
    }
}

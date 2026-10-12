<?php

require_once __DIR__ . '/CustomerRepositoryInterface.php';
require_once __DIR__ . '/../Models/Customer.php';

class PdoCustomerRepository implements CustomerRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function allForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM customers WHERE user_id = :uid ORDER BY name ASC');
        $stmt->execute(['uid' => $userId]);

        return array_map([Customer::class, 'fromRow'], $stmt->fetchAll());
    }

    public function find(int $userId, int $id): ?Customer
    {
        $stmt = $this->pdo->prepare('SELECT * FROM customers WHERE user_id = :uid AND id = :id LIMIT 1');
        $stmt->execute(['uid' => $userId, 'id' => $id]);
        $row = $stmt->fetch();

        return $row ? Customer::fromRow($row) : null;
    }

    public function create(int $userId, string $name, string $email, ?string $phone): Customer
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO customers (user_id, name, email, phone) VALUES (:uid, :name, :email, :phone)'
        );
        $stmt->execute(['uid' => $userId, 'name' => $name, 'email' => $email, 'phone' => $phone]);

        return $this->find($userId, (int) $this->pdo->lastInsertId('customers_id_seq'));
    }

    public function update(int $userId, int $id, string $name, string $email, ?string $phone): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE customers SET name = :name, email = :email, phone = :phone WHERE user_id = :uid AND id = :id'
        );

        return $stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'uid' => $userId, 'id' => $id]);
    }

    public function delete(int $userId, int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM customers WHERE user_id = :uid AND id = :id');

        return $stmt->execute(['uid' => $userId, 'id' => $id]);
    }

    public function countForUser(int $userId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM customers WHERE user_id = :uid');
        $stmt->execute(['uid' => $userId]);

        return (int) $stmt->fetchColumn();
    }
}

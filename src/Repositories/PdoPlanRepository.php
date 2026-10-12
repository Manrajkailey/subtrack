<?php

require_once __DIR__ . '/PlanRepositoryInterface.php';
require_once __DIR__ . '/../Models/Plan.php';

class PdoPlanRepository implements PlanRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function allForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM plans WHERE user_id = :uid ORDER BY price ASC');
        $stmt->execute(['uid' => $userId]);

        return array_map([Plan::class, 'fromRow'], $stmt->fetchAll());
    }

    public function find(int $userId, int $id): ?Plan
    {
        $stmt = $this->pdo->prepare('SELECT * FROM plans WHERE user_id = :uid AND id = :id LIMIT 1');
        $stmt->execute(['uid' => $userId, 'id' => $id]);
        $row = $stmt->fetch();

        return $row ? Plan::fromRow($row) : null;
    }

    public function create(int $userId, string $planName, float $price, string $billingCycle, ?string $description): Plan
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO plans (user_id, plan_name, price, billing_cycle, description) VALUES (:uid, :name, :price, :cycle, :desc)'
        );
        $stmt->execute([
            'uid' => $userId,
            'name' => $planName,
            'price' => $price,
            'cycle' => $billingCycle,
            'desc' => $description,
        ]);

        return $this->find($userId, (int) $this->pdo->lastInsertId('plans_id_seq'));
    }

    public function update(int $userId, int $id, string $planName, float $price, string $billingCycle, ?string $description): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE plans SET plan_name = :name, price = :price, billing_cycle = :cycle, description = :desc
             WHERE user_id = :uid AND id = :id'
        );

        return $stmt->execute([
            'name' => $planName,
            'price' => $price,
            'cycle' => $billingCycle,
            'desc' => $description,
            'uid' => $userId,
            'id' => $id,
        ]);
    }

    public function delete(int $userId, int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM plans WHERE user_id = :uid AND id = :id');

        return $stmt->execute(['uid' => $userId, 'id' => $id]);
    }

    public function subscriberCounts(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT plan_id, COUNT(*) AS cnt FROM subscriptions
             WHERE user_id = :uid AND status = 'active' GROUP BY plan_id"
        );
        $stmt->execute(['uid' => $userId]);

        $counts = [];
        foreach ($stmt->fetchAll() as $row) {
            $counts[(int) $row['plan_id']] = (int) $row['cnt'];
        }

        return $counts;
    }
}

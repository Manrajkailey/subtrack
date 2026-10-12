<?php

require_once __DIR__ . '/SubscriptionRepositoryInterface.php';
require_once __DIR__ . '/../Models/Subscription.php';

class PdoSubscriptionRepository implements SubscriptionRepositoryInterface
{
    private const SELECT_WITH_JOINS = "
        SELECT s.*, c.name AS customer_name, c.email AS customer_email,
               p.plan_name, p.price AS plan_price, p.billing_cycle
        FROM subscriptions s
        JOIN customers c ON c.id = s.customer_id
        JOIN plans p ON p.id = s.plan_id
    ";

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function allForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(self::SELECT_WITH_JOINS . ' WHERE s.user_id = :uid ORDER BY s.renewal_date ASC');
        $stmt->execute(['uid' => $userId]);

        return array_map([Subscription::class, 'fromRow'], $stmt->fetchAll());
    }

    public function find(int $userId, int $id): ?Subscription
    {
        $stmt = $this->pdo->prepare(self::SELECT_WITH_JOINS . ' WHERE s.user_id = :uid AND s.id = :id LIMIT 1');
        $stmt->execute(['uid' => $userId, 'id' => $id]);
        $row = $stmt->fetch();

        return $row ? Subscription::fromRow($row) : null;
    }

    public function create(int $userId, int $customerId, int $planId, string $startDate, string $renewalDate): Subscription
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO subscriptions (user_id, customer_id, plan_id, start_date, renewal_date, status)
             VALUES (:uid, :cid, :pid, :start, :renewal, 'active')"
        );
        $stmt->execute([
            'uid' => $userId,
            'cid' => $customerId,
            'pid' => $planId,
            'start' => $startDate,
            'renewal' => $renewalDate,
        ]);

        return $this->find($userId, (int) $this->pdo->lastInsertId('subscriptions_id_seq'));
    }

    public function update(int $userId, int $id, int $customerId, int $planId, string $startDate, string $renewalDate): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE subscriptions SET customer_id = :cid, plan_id = :pid, start_date = :start, renewal_date = :renewal
             WHERE user_id = :uid AND id = :id'
        );

        return $stmt->execute([
            'cid' => $customerId,
            'pid' => $planId,
            'start' => $startDate,
            'renewal' => $renewalDate,
            'uid' => $userId,
            'id' => $id,
        ]);
    }

    public function markCancelled(int $userId, int $id, string $cancelledAt): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE subscriptions SET status = 'cancelled', cancelled_at = :cancelled_at WHERE user_id = :uid AND id = :id"
        );

        return $stmt->execute(['cancelled_at' => $cancelledAt, 'uid' => $userId, 'id' => $id]);
    }

    public function markRenewed(int $userId, int $id, string $newRenewalDate): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE subscriptions SET renewal_date = :renewal, status = 'active', cancelled_at = NULL
             WHERE user_id = :uid AND id = :id"
        );

        return $stmt->execute(['renewal' => $newRenewalDate, 'uid' => $userId, 'id' => $id]);
    }

    public function delete(int $userId, int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM subscriptions WHERE user_id = :uid AND id = :id');

        return $stmt->execute(['uid' => $userId, 'id' => $id]);
    }

    public function countActive(int $userId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM subscriptions WHERE user_id = :uid AND status = 'active'");
        $stmt->execute(['uid' => $userId]);

        return (int) $stmt->fetchColumn();
    }

    public function countCancelled(int $userId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM subscriptions WHERE user_id = :uid AND status = 'cancelled'");
        $stmt->execute(['uid' => $userId]);

        return (int) $stmt->fetchColumn();
    }

    public function countNewInRange(int $userId, string $from, string $to): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM subscriptions WHERE user_id = :uid AND start_date BETWEEN :from AND :to'
        );
        $stmt->execute(['uid' => $userId, 'from' => $from, 'to' => $to]);

        return (int) $stmt->fetchColumn();
    }

    public function countCancelledInRange(int $userId, string $from, string $to): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM subscriptions WHERE user_id = :uid AND cancelled_at BETWEEN :from AND :to'
        );
        $stmt->execute(['uid' => $userId, 'from' => $from, 'to' => $to]);

        return (int) $stmt->fetchColumn();
    }

    public function countActiveAsOf(int $userId, string $asOfDate): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM subscriptions
             WHERE user_id = :uid AND start_date <= :asof
               AND (status = 'active' OR cancelled_at IS NULL OR cancelled_at > :asof2)"
        );
        $stmt->execute(['uid' => $userId, 'asof' => $asOfDate, 'asof2' => $asOfDate]);

        return (int) $stmt->fetchColumn();
    }

    public function upcomingRenewals(int $userId, int $days): array
    {
        // Date bound computed in PHP rather than with a DB-specific date
        // function, so the same query text works against MySQL or SQLite.
        $cutoff = (new DateTimeImmutable('today'))->modify("+{$days} days")->format('Y-m-d');

        $stmt = $this->pdo->prepare(
            self::SELECT_WITH_JOINS . "
            WHERE s.user_id = :uid AND s.status = 'active'
              AND s.renewal_date <= :cutoff
            ORDER BY s.renewal_date ASC"
        );
        $stmt->execute(['uid' => $userId, 'cutoff' => $cutoff]);

        return array_map([Subscription::class, 'fromRow'], $stmt->fetchAll());
    }

    public function overdueRenewals(int $userId): array
    {
        $today = (new DateTimeImmutable('today'))->format('Y-m-d');

        $stmt = $this->pdo->prepare(
            self::SELECT_WITH_JOINS . "
            WHERE s.user_id = :uid AND s.status = 'active' AND s.renewal_date < :today
            ORDER BY s.renewal_date ASC"
        );
        $stmt->execute(['uid' => $userId, 'today' => $today]);

        return array_map([Subscription::class, 'fromRow'], $stmt->fetchAll());
    }
}

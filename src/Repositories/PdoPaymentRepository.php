<?php

require_once __DIR__ . '/PaymentRepositoryInterface.php';
require_once __DIR__ . '/../Models/Payment.php';

class PdoPaymentRepository implements PaymentRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $subscriptionId, float $amount, string $paymentDate, string $status = 'paid'): Payment
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO payments (subscription_id, amount, payment_date, payment_status)
             VALUES (:sid, :amount, :date, :status)'
        );
        $stmt->execute([
            'sid' => $subscriptionId,
            'amount' => $amount,
            'date' => $paymentDate,
            'status' => $status,
        ]);

        $id = (int) $this->pdo->lastInsertId('payments_id_seq');
        $stmt = $this->pdo->prepare('SELECT * FROM payments WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return Payment::fromRow($stmt->fetch());
    }

    public function totalForUser(int $userId): float
    {
        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(pay.amount), 0) FROM payments pay
             JOIN subscriptions s ON s.id = pay.subscription_id
             WHERE s.user_id = :uid AND pay.payment_status = 'paid'"
        );
        $stmt->execute(['uid' => $userId]);

        return (float) $stmt->fetchColumn();
    }

    public function totalInRange(int $userId, string $from, string $to): float
    {
        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(pay.amount), 0) FROM payments pay
             JOIN subscriptions s ON s.id = pay.subscription_id
             WHERE s.user_id = :uid AND pay.payment_status = 'paid'
               AND pay.payment_date BETWEEN :from AND :to"
        );
        $stmt->execute(['uid' => $userId, 'from' => $from, 'to' => $to]);

        return (float) $stmt->fetchColumn();
    }

    public function monthlyTotalsForUser(int $userId, int $months): array
    {
        $end = new DateTimeImmutable('first day of this month');
        $start = $end->modify('-' . ($months - 1) . ' months');

        // Pre-fill every month in the window with 0 so the chart has no gaps.
        $totals = [];
        $cursor = $start;
        for ($i = 0; $i < $months; $i++) {
            $totals[$cursor->format('Y-m')] = 0.0;
            $cursor = $cursor->modify('+1 month');
        }

        $stmt = $this->pdo->prepare(
            "SELECT pay.payment_date, pay.amount FROM payments pay
             JOIN subscriptions s ON s.id = pay.subscription_id
             WHERE s.user_id = :uid AND pay.payment_status = 'paid'
               AND pay.payment_date >= :from"
        );
        $stmt->execute(['uid' => $userId, 'from' => $start->format('Y-m-d')]);

        foreach ($stmt->fetchAll() as $row) {
            $key = substr($row['payment_date'], 0, 7); // 'Y-m'
            if (isset($totals[$key])) {
                $totals[$key] += (float) $row['amount'];
            }
        }

        return $totals;
    }
}

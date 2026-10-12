<?php

interface PaymentRepositoryInterface
{
    public function create(int $subscriptionId, float $amount, string $paymentDate, string $status = 'paid'): Payment;

    /** Sum of paid payments for a user across all time. */
    public function totalForUser(int $userId): float;

    /** Sum of paid payments for a user within [$from, $to] (inclusive). */
    public function totalInRange(int $userId, string $from, string $to): float;

    /**
     * Paid revenue grouped by calendar month for the last $months months
     * (oldest first). Keys are 'Y-m', values are float totals -- months
     * with no payments are included as 0 so charts don't show gaps.
     * @return array<string,float>
     */
    public function monthlyTotalsForUser(int $userId, int $months): array;
}

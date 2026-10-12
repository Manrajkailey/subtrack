<?php

interface SubscriptionRepositoryInterface
{
    /** @return Subscription[] joined with customer + plan info, newest first */
    public function allForUser(int $userId): array;

    public function find(int $userId, int $id): ?Subscription;

    public function create(int $userId, int $customerId, int $planId, string $startDate, string $renewalDate): Subscription;

    public function update(int $userId, int $id, int $customerId, int $planId, string $startDate, string $renewalDate): bool;

    public function markCancelled(int $userId, int $id, string $cancelledAt): bool;

    public function markRenewed(int $userId, int $id, string $newRenewalDate): bool;

    public function delete(int $userId, int $id): bool;

    public function countActive(int $userId): int;

    public function countCancelled(int $userId): int;

    /** Subscriptions whose start_date falls within [$from, $to] (inclusive). */
    public function countNewInRange(int $userId, string $from, string $to): int;

    /** Subscriptions whose cancelled_at falls within [$from, $to] (inclusive). */
    public function countCancelledInRange(int $userId, string $from, string $to): int;

    /** Subscriptions considered active as of a given date (for historical reporting). */
    public function countActiveAsOf(int $userId, string $asOfDate): int;

    /** @return Subscription[] active subscriptions renewing within the next $days days (including overdue). */
    public function upcomingRenewals(int $userId, int $days): array;

    /** @return Subscription[] active subscriptions whose renewal date has already passed. */
    public function overdueRenewals(int $userId): array;
}

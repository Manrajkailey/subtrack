<?php

interface PlanRepositoryInterface
{
    /** @return Plan[] */
    public function allForUser(int $userId): array;

    public function find(int $userId, int $id): ?Plan;

    public function create(int $userId, string $planName, float $price, string $billingCycle, ?string $description): Plan;

    public function update(int $userId, int $id, string $planName, float $price, string $billingCycle, ?string $description): bool;

    public function delete(int $userId, int $id): bool;

    /**
     * Number of subscriptions currently on each plan.
     * @return array<int,int> plan_id => count
     */
    public function subscriberCounts(int $userId): array;
}

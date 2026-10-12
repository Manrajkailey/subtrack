<?php

require_once __DIR__ . '/../Repositories/SubscriptionRepositoryInterface.php';
require_once __DIR__ . '/../Repositories/PlanRepositoryInterface.php';
require_once __DIR__ . '/../Repositories/PaymentRepositoryInterface.php';

/**
 * SubscriptionService: the business rules around creating, renewing and
 * cancelling a subscription -- e.g. "renewing pushes the renewal date
 * forward by one billing cycle and logs a payment" -- live here rather
 * than in a page script or in the repository. Repositories only know
 * how to read/write rows; this class knows what those writes *mean*
 * for the business (SRP). It depends only on repository interfaces,
 * so a future PostgreSql* implementation would need zero changes here (DIP).
 */
class SubscriptionService
{
    private SubscriptionRepositoryInterface $subscriptions;
    private PlanRepositoryInterface $plans;
    private PaymentRepositoryInterface $payments;

    public function __construct(
        SubscriptionRepositoryInterface $subscriptions,
        PlanRepositoryInterface $plans,
        PaymentRepositoryInterface $payments
    ) {
        $this->subscriptions = $subscriptions;
        $this->plans = $plans;
        $this->payments = $payments;
    }

    /**
     * Creates a new subscription starting today (or a given date), sets
     * its first renewal date based on the plan's billing cycle, and logs
     * the first payment.
     */
    public function subscribe(int $userId, int $customerId, int $planId, string $startDate): Subscription
    {
        $plan = $this->plans->find($userId, $planId);
        if ($plan === null) {
            throw new InvalidArgumentException('Selected plan was not found.');
        }

        $renewalDate = $this->nextRenewalDate($startDate, $plan->billingCycle);

        $subscription = $this->subscriptions->create($userId, $customerId, $planId, $startDate, $renewalDate);
        $this->payments->create($subscription->id, $plan->price, $startDate, 'paid');

        return $subscription;
    }

    /**
     * Marks the subscription renewed: extends the renewal date by one
     * more billing cycle from its current renewal date and logs a new
     * payment dated today.
     */
    public function renew(int $userId, int $subscriptionId): void
    {
        $subscription = $this->subscriptions->find($userId, $subscriptionId);
        if ($subscription === null) {
            throw new InvalidArgumentException('Subscription not found.');
        }

        $plan = $this->plans->find($userId, $subscription->planId);
        $newRenewalDate = $this->nextRenewalDate($subscription->renewalDate, $plan->billingCycle);

        $this->subscriptions->markRenewed($userId, $subscriptionId, $newRenewalDate);
        $this->payments->create($subscriptionId, $plan->price, date('Y-m-d'), 'paid');
    }

    public function cancel(int $userId, int $subscriptionId, ?string $cancelledAt = null): void
    {
        $this->subscriptions->markCancelled($userId, $subscriptionId, $cancelledAt ?? date('Y-m-d'));
    }

    public function update(int $userId, int $id, int $customerId, int $planId, string $startDate): void
    {
        $plan = $this->plans->find($userId, $planId);
        if ($plan === null) {
            throw new InvalidArgumentException('Selected plan was not found.');
        }

        $renewalDate = $this->nextRenewalDate($startDate, $plan->billingCycle);
        $this->subscriptions->update($userId, $id, $customerId, $planId, $startDate, $renewalDate);
    }

    public function delete(int $userId, int $id): void
    {
        $this->subscriptions->delete($userId, $id);
    }

    private function nextRenewalDate(string $fromDate, string $billingCycle): string
    {
        $interval = $billingCycle === 'annual' ? 'P1Y' : 'P1M';

        return (new DateTimeImmutable($fromDate))->add(new DateInterval($interval))->format('Y-m-d');
    }
}

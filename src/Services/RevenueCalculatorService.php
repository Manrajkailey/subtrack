<?php

require_once __DIR__ . '/../Repositories/SubscriptionRepositoryInterface.php';
require_once __DIR__ . '/../Repositories/PlanRepositoryInterface.php';
require_once __DIR__ . '/../Repositories/PaymentRepositoryInterface.php';

/**
 * RevenueCalculatorService: turns raw subscription/payment rows into the
 * business metrics this whole project is named after (MRR, ARR, churn,
 * ARPU...). Keeping this separate from the repositories means adding a
 * new metric (Open/Closed Principle) never requires touching SQL code,
 * and keeping it separate from the pages means the same numbers are
 * guaranteed to match on the dashboard, the revenue page and reports.
 */
class RevenueCalculatorService
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

    /** Monthly Recurring Revenue: every active subscription's price, normalized to a monthly figure. */
    public function mrr(int $userId): float
    {
        $plansById = $this->indexPlans($userId);
        $mrr = 0.0;

        foreach ($this->activeSubscriptionPlanIds($userId) as $planId) {
            if (isset($plansById[$planId])) {
                $mrr += $plansById[$planId]->monthlyPrice();
            }
        }

        return round($mrr, 2);
    }

    /** Annual Recurring Revenue: a simplified projection, MRR x 12. */
    public function arr(int $userId): float
    {
        return round($this->mrr($userId) * 12, 2);
    }

    /** Average Revenue Per (active) User. */
    public function arpu(int $userId): float
    {
        $active = $this->subscriptions->countActive($userId);

        return $active > 0 ? round($this->mrr($userId) / $active, 2) : 0.0;
    }

    /** Total revenue ever collected (all paid payments). */
    public function totalRevenue(int $userId): float
    {
        return round($this->payments->totalForUser($userId), 2);
    }

    public function totalRevenueInRange(int $userId, string $from, string $to): float
    {
        return round($this->payments->totalInRange($userId, $from, $to), 2);
    }

    /**
     * Churn rate over the trailing $days days: cancellations in the
     * window divided by (active now + cancellations in the window),
     * i.e. roughly "what share of the subscribers you had are gone".
     */
    public function churnRate(int $userId, int $days = 30): float
    {
        $to = date('Y-m-d');
        $from = (new DateTimeImmutable($to))->modify("-{$days} days")->format('Y-m-d');

        $cancelledInWindow = $this->subscriptions->countCancelledInRange($userId, $from, $to);
        $activeNow = $this->subscriptions->countActive($userId);
        $denominator = $activeNow + $cancelledInWindow;

        return $denominator > 0 ? round(($cancelledInWindow / $denominator) * 100, 1) : 0.0;
    }

    /** Renewal / retention rate -- the mirror image of churn. */
    public function renewalRate(int $userId, int $days = 30): float
    {
        return round(100 - $this->churnRate($userId, $days), 1);
    }

    /** @return array<string,float> 'Y-m' => revenue, oldest first, for the last $months months. */
    public function monthlyRevenueSeries(int $userId, int $months = 6): array
    {
        return $this->payments->monthlyTotalsForUser($userId, $months);
    }

    /**
     * How many active subscribers are on each plan right now.
     * @return array<int,array{plan: Plan, count: int}>
     */
    public function planPopularity(int $userId): array
    {
        $counts = $this->plans->subscriberCounts($userId);
        $result = [];

        foreach ($this->plans->allForUser($userId) as $plan) {
            $result[] = ['plan' => $plan, 'count' => $counts[$plan->id] ?? 0];
        }

        usort($result, fn ($a, $b) => $b['count'] <=> $a['count']);

        return $result;
    }

    /** @return int[] plan_id for every currently-active subscription. */
    private function activeSubscriptionPlanIds(int $userId): array
    {
        $ids = [];
        foreach ($this->subscriptions->allForUser($userId) as $subscription) {
            if ($subscription->isActive()) {
                $ids[] = $subscription->planId;
            }
        }

        return $ids;
    }

    /** @return array<int,Plan> plan_id => Plan */
    private function indexPlans(int $userId): array
    {
        $indexed = [];
        foreach ($this->plans->allForUser($userId) as $plan) {
            $indexed[$plan->id] = $plan;
        }

        return $indexed;
    }
}

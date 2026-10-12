<?php

class Subscription
{
    public int $id;
    public int $userId;
    public int $customerId;
    public int $planId;
    public string $startDate;
    public string $renewalDate;
    public string $status; // 'active' | 'cancelled'
    public ?string $cancelledAt;
    public string $createdAt;
    // Optional joined-in fields, populated by list queries for display.
    public ?string $customerName;
    public ?string $customerEmail;
    public ?string $planName;
    public ?float $planPrice;
    public ?string $billingCycle;

    public function __construct(
        int $id,
        int $userId,
        int $customerId,
        int $planId,
        string $startDate,
        string $renewalDate,
        string $status,
        ?string $cancelledAt,
        string $createdAt,
        ?string $customerName = null,
        ?string $customerEmail = null,
        ?string $planName = null,
        ?float $planPrice = null,
        ?string $billingCycle = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->customerId = $customerId;
        $this->planId = $planId;
        $this->startDate = $startDate;
        $this->renewalDate = $renewalDate;
        $this->status = $status;
        $this->cancelledAt = $cancelledAt;
        $this->createdAt = $createdAt;
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
        $this->planName = $planName;
        $this->planPrice = $planPrice;
        $this->billingCycle = $billingCycle;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (int) $row['user_id'],
            (int) $row['customer_id'],
            (int) $row['plan_id'],
            $row['start_date'],
            $row['renewal_date'],
            $row['status'],
            $row['cancelled_at'] ?? null,
            $row['created_at'],
            $row['customer_name'] ?? null,
            $row['customer_email'] ?? null,
            $row['plan_name'] ?? null,
            isset($row['plan_price']) ? (float) $row['plan_price'] : null,
            $row['billing_cycle'] ?? null
        );
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function daysUntilRenewal(): int
    {
        $today = new DateTimeImmutable('today');
        $renewal = new DateTimeImmutable($this->renewalDate);
        return (int) $today->diff($renewal)->format('%r%a');
    }
}

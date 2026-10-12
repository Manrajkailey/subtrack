<?php

class Plan
{
    public int $id;
    public int $userId;
    public string $planName;
    public float $price;
    public string $billingCycle; // 'monthly' | 'annual'
    public ?string $description;
    public string $createdAt;

    public function __construct(
        int $id,
        int $userId,
        string $planName,
        float $price,
        string $billingCycle,
        ?string $description,
        string $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->planName = $planName;
        $this->price = $price;
        $this->billingCycle = $billingCycle;
        $this->description = $description;
        $this->createdAt = $createdAt;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (int) $row['user_id'],
            $row['plan_name'],
            (float) $row['price'],
            $row['billing_cycle'],
            $row['description'] ?? null,
            $row['created_at']
        );
    }

    /** Normalizes this plan's price to a monthly figure, regardless of billing cycle. */
    public function monthlyPrice(): float
    {
        return $this->billingCycle === 'annual' ? $this->price / 12 : $this->price;
    }
}

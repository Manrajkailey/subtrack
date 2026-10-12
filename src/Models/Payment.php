<?php

class Payment
{
    public int $id;
    public int $subscriptionId;
    public float $amount;
    public string $paymentDate;
    public string $paymentStatus; // 'paid' | 'pending' | 'failed'

    public function __construct(
        int $id,
        int $subscriptionId,
        float $amount,
        string $paymentDate,
        string $paymentStatus
    ) {
        $this->id = $id;
        $this->subscriptionId = $subscriptionId;
        $this->amount = $amount;
        $this->paymentDate = $paymentDate;
        $this->paymentStatus = $paymentStatus;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (int) $row['subscription_id'],
            (float) $row['amount'],
            $row['payment_date'],
            $row['payment_status']
        );
    }
}

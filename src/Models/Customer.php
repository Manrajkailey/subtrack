<?php

class Customer
{
    public int $id;
    public int $userId;
    public string $name;
    public string $email;
    public ?string $phone;
    public string $createdAt;

    public function __construct(
        int $id,
        int $userId,
        string $name,
        string $email,
        ?string $phone,
        string $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->createdAt = $createdAt;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (int) $row['user_id'],
            $row['name'],
            $row['email'],
            $row['phone'] ?? null,
            $row['created_at']
        );
    }
}

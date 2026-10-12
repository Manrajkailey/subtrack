<?php

/**
 * User: a business owner / admin account.
 * A Model's only job is to represent a row as an object (SRP) -- it
 * knows nothing about SQL or HTTP.
 */
class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $passwordHash;
    public string $businessName;
    public string $createdAt;

    public function __construct(
        int $id,
        string $name,
        string $email,
        string $passwordHash,
        string $businessName,
        string $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->businessName = $businessName;
        $this->createdAt = $createdAt;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            $row['name'],
            $row['email'],
            $row['password_hash'],
            $row['business_name'],
            $row['created_at']
        );
    }
}

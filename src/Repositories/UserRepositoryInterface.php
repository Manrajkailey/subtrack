<?php

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;

    public function create(string $name, string $email, string $passwordHash, string $businessName): User;
}

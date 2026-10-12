<?php

interface CustomerRepositoryInterface
{
    /** @return Customer[] */
    public function allForUser(int $userId): array;

    public function find(int $userId, int $id): ?Customer;

    public function create(int $userId, string $name, string $email, ?string $phone): Customer;

    public function update(int $userId, int $id, string $name, string $email, ?string $phone): bool;

    public function delete(int $userId, int $id): bool;

    public function countForUser(int $userId): int;
}

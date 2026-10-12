<?php

require_once __DIR__ . '/../Repositories/UserRepositoryInterface.php';

/**
 * AuthService: everything about registering and authenticating a
 * business-owner account lives here, not scattered across login.php /
 * register.php. It depends on UserRepositoryInterface, not a concrete
 * repository class (DIP), so it can be tested with a fake repository.
 */
class AuthService
{
    private UserRepositoryInterface $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    /**
     * @throws InvalidArgumentException when validation fails or the email is taken.
     */
    public function register(string $name, string $email, string $password, string $businessName): User
    {
        $name = trim($name);
        $email = trim(strtolower($email));
        $businessName = trim($businessName);

        if ($name === '' || $email === '' || $businessName === '') {
            throw new InvalidArgumentException('Please fill in every field.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Please enter a valid email address.');
        }

        if (strlen($password) < 6) {
            throw new InvalidArgumentException('Password must be at least 6 characters.');
        }

        if ($this->users->findByEmail($email) !== null) {
            throw new InvalidArgumentException('An account with that email already exists.');
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        return $this->users->create($name, $email, $hash, $businessName);
    }

    public function attemptLogin(string $email, string $password): ?User
    {
        $user = $this->users->findByEmail(trim(strtolower($email)));

        if ($user === null || !password_verify($password, $user->passwordHash)) {
            return null;
        }

        return $user;
    }

    public function login(User $user): void
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['business_name'] = $user->businessName;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public function currentUser(): ?User
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }

        return $this->users->findById((int) $_SESSION['user_id']);
    }

    public function requireLogin(): User
    {
        $user = $this->currentUser();

        if ($user === null) {
            header('Location: login.php');
            exit;
        }

        return $user;
    }
}

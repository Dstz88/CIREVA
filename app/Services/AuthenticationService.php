<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\events\Registered;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthenticationService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user with a specified role.
     *
     * @param array $data
     * @param int $roleId
     * @return User
     * @throws Exception
     */
    public function registerUser(array $data, int $roleId): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $roleId,
        ];

        $user = $this->userRepository->create($userData);

        event(new Registered($user));

        return $user;
    }

    /**
     * Authenticate a user.
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Log out the authenticated user.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }
}

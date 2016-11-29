<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 28.11.2016 г.
 * Time: 19:02
 */

namespace FPopov\Services\Application;


use FPopov\Adapter\Database;
use FPopov\Adapter\DatabaseInterface;
use FPopov\Core\MVC\SessionInterface;
use FPopov\Models\DB\User\User;
use FPopov\Repositories\User\UserRepository;
use FPopov\Repositories\User\UserRepositoryInterface;

class AuthenticationService implements AuthenticationServiceInterface
{
    const AUTHENTICATION_ID = 'id';

    private $db;
    private $session;
    private $encryptionService;

    /** @var  UserRepository */
    private $userRepository;

    public function __construct(DatabaseInterface $db, SessionInterface $session, EncryptionServiceInterface $encryptionService, UserRepositoryInterface $userRepository)
    {
        $this->db = $db;
        $this->session = $session;
        $this->encryptionService = $encryptionService;
        $this->userRepository = $userRepository;
    }

    public function isAuthenticated() : bool
    {
        return $this->session->exists(self::AUTHENTICATION_ID);
    }

    public function logout()
    {
        $this->session->destroy();
    }

    public function login($username, $password) : bool
    {
        $userParams = [
            'username' => $username
        ];

        $user = $this->userRepository->findByCondition($userParams, User::class, null, 'asc', 1, 0);

        $user = $user->current();

        if (empty($user)) {
            return false;
        }

        /** @var User $user */
        $hash = $user->getPassword();

        if ($this->encryptionService->verify($password, $hash)) {
            $this->session->set('id', $user->getId());
            return true;
        }

        return false;
    }

    public function getUserId()
    {
        return $this->session->get(self::AUTHENTICATION_ID);
    }
}
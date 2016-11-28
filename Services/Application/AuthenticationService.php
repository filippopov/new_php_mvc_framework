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
use FPopov\Models\DB\User;

class AuthenticationService implements AuthenticationServiceInterface
{
    const AUTHENTICATION_ID = 'id';

    private $db;
    private $session;
    private $encryptionService;

    public function __construct(DatabaseInterface $db, SessionInterface $session, EncryptionServiceInterface $encryptionService)
    {
        $this->db = $db;
        $this->session = $session;
        $this->encryptionService = $encryptionService;
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
        $query = "
            SELECT
                u.id,
                u.username,
                u.password,
                u.full_name AS fullName,
                u.first_name AS firstName,
                u.last_name AS lastName,
                u.is_active AS isActive,
                u.email,
                u.birthday,
                u.role
            FROM
                users AS u
            WHERE 
                u.username = ?
            LIMIT 1     
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute(
            [
                $username
            ]
        );

        /** @var User $user */
        $user = $stmt->fetchObject(User::class);

        if (empty($user)) {
            return false;
        }

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
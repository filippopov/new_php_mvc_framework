<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 25.11.2016 г.
 * Time: 21:26
 */

namespace FPopov\Services;


use FPopov\Adapter\Database;
use FPopov\Adapter\DatabaseInterface;
use FPopov\Models\DB\User;

class UserService implements UserServiceInterface
{
    /** @var Database */
    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
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

        if (password_verify($password, $hash)) {
            $_SESSION['id'] = $user->getId();
            return true;
        }

        return false;
    }

    public function register($username, $password) : bool
    {
        $query = "
            INSERT INTO 
                users (username, password) 
            VALUES (?, ?);
        ";

        $stmt = $this->db->prepare($query);

        return $stmt->execute(
            [
                $username,
                password_hash($password, PASSWORD_BCRYPT)
            ]
        );
    }
}
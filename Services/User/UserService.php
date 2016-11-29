<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 25.11.2016 г.
 * Time: 21:26
 */

namespace FPopov\Services\User;


use FPopov\Adapter\Database;
use FPopov\Adapter\DatabaseInterface;
use FPopov\Core\MVC\SessionInterface;
use FPopov\Models\Binding\User\UserProfileEditBindingModel;
use FPopov\Models\DB\User\User;
use FPopov\Services\Application\EncryptionServiceInterface;

class UserService implements UserServiceInterface
{
    private $db;
    private $encryptionService;

    public function __construct(DatabaseInterface $db, EncryptionServiceInterface $encryptionService)
    {
        $this->db = $db;
        $this->encryptionService = $encryptionService;
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
                $this->encryptionService->hash($password)
            ]
        );
    }

    public function findOne($id) : User
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
                u.id = ?
            LIMIT 1     
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute(
            [
                $id
            ]
        );

        /** @var User $user */
        $user = $stmt->fetchObject(User::class);

        return $user;
    }

    public function edit(UserProfileEditBindingModel $bindingModel)
    {
        if ($bindingModel->getPassword() != $bindingModel->getConfirmPassword()) {
            return false;
        }

        $query = "
            UPDATE 
                users
            SET 
                username = ?, 
                password = ?, 
                email = ?, 
                birthday = ?
            WHERE 
                id = ?
        ";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $bindingModel->getUsername(),
            $this->encryptionService->hash($bindingModel->getPassword()),
            $bindingModel->getEmail(),
            $bindingModel->getBirthday(),
            $bindingModel->getId()
        ]);
    }
}
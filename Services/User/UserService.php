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
}
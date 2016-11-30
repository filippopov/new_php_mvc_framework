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
use FPopov\Repositories\User\UserRepository;
use FPopov\Repositories\User\UserRepositoryInterface;
use FPopov\Services\AbstractService;
use FPopov\Services\Application\EncryptionServiceInterface;

class UserService extends AbstractService  implements UserServiceInterface
{
    private $db;
    private $encryptionService;

    /** @var  UserRepository */
    private $userRepository;

    public function __construct(DatabaseInterface $db, EncryptionServiceInterface $encryptionService, UserRepositoryInterface $userRepository)
    {
        $this->db = $db;
        $this->encryptionService = $encryptionService;
        $this->userRepository = $userRepository;
    }

    public function register($username, $password) : bool
    {
        return $this->userRepository->create([
            'username' => $username,
            'password' => $this->encryptionService->hash($password)
        ]);
    }

    public function findOne($id) : User
    {
        /** @var User $user */
        $user = $this->userRepository->findOneRowById($id, User::class);

        return $user;
    }

    public function edit(UserProfileEditBindingModel $bindingModel)
    {
        if ($bindingModel->getPassword() != $bindingModel->getConfirmPassword()) {
            return false;
        }

        $params = [
            'username' => $bindingModel->getUsername(),
            'password' => $this->encryptionService->hash($bindingModel->getPassword()),
            'email' => $bindingModel->getEmail(),
            'birthday' => $bindingModel->getBirthday(),
        ];

        return $this->userRepository->update($bindingModel->getId(), $params);
    }
}
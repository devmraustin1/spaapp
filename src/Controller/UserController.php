<?php

namespace App\Controller;

use App\Db;
use App\Dto\LoginForm;
use App\Dto\RegisterForm;
use App\ErrorJsonResponse;
use App\SuccessJsonResponse;
use Exception;
use Monolog\Logger;

class UserController
{
    public function __construct(
        private readonly Logger $logger,
        private readonly Db     $db
    )
    {
    }

    /**
     * @throws Exception
     */
    public function register(): never
    {
        try {
            $registerFrom = RegisterForm::fromUserRequest();
        } catch (Exception $ex) {
            echo new ErrorJsonResponse($ex->getMessage());
            exit ();
        }

        $_SESSION['user_id'] = $this->db->createNewUser($registerFrom->login, $registerFrom->password);

        echo new SuccessJsonResponse("Registered", []);

        exit();
    }

    public function login(): never
    {
        try {
            $loginForm = LoginForm::fromUserRequest();
        } catch (Exception $ex) {
            echo new ErrorJsonResponse($ex->getMessage());
            exit ();
        }

        try {
            $userArray = $this->db->getUserByLoginAndPassword($loginForm->login, $loginForm->password);
            $_SESSION['user_id'] = $userArray['id'];
        } catch (Exception $e) {
        }
        echo new SuccessJsonResponse("Login OK", []);
        header("Location: /");
        exit;
    }
}


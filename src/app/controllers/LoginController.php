<?php
session_start();
use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        
    }
    public function loginAction()
    {
        $email=$this->request->getPost('email');
        $password=$this->request->getPost('password');
        $user=Users::findFirst(
            [
                'email = :email: AND password = :password:',
                'bind' => [
                    'email' => $email,
                    'password' => $password,
                ],
            ]
        );
        if ($user && $user->permission=='approved') {
            $_SESSION['currentUser']=$_SESSION['currentUser'] ?? array();
            $_SESSION['currentUser'] = array(
                'id'=>$user->id,
                'name'=>$user->name,
                'permission'=>$user->permission,
                'role'=>$user->role
            );
            echo 1;
        } else {
            echo 0;
        }
    }
    public function logoutAction()
    {
        session_unset();
        header('location: /');
    }
}

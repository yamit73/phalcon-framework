<?php
use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
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
        if ($user) {
            if ($user->permission=='approved') {
                $this->session->set('id', $user->id);
                $this->session->set('name', $user->name);
                $this->session->set('role', $user->role);
                $this->session->set('permission', $user->permission);
                $this->session->id=$user->id;
                if ($this->session->role==='admin') {
                    $this->response->redirect('http://localhost:8080/admin');
                } else {
                    $this->response->redirect('/');
                }
            } else {
                $this->view->message="You don't have permission to login!";
            }
        } else {
            $this->view->message="Invalid credentials";
        }
    }
    public function logoutAction()
    {
        $this->session->destroy();
        $this->response->redirect('/');
    }
}

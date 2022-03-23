<?php
use Phalcon\Mvc\Controller;

class SignupController extends Controller
{

    public function indexAction()
    {
        $user = new Users();

        $user->assign(
            $this->request->getPost(),
            [
                'name',
                'email',
                'password'
            ]
        );

        $success = $user->save();
        if ($success==1) {
            echo 1;
        } else {
            echo 0;
        }
    }
}

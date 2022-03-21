<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        session_start();
        $this->view->currentUser=$_SESSION['currentUser'];
        $this->view->posts=Posts::find();
        $this->view->category=Categories::find();
    }
    public function blogbycategoryAction()
    {
        session_start();
        $this->view->currentUser=$_SESSION['currentUser'];
        $id=$this->request->getQuery('catId');
        $this->view->posts=Posts::find([
            'conditions' => 'category_id='.$id.'',
        ]);
        $this->view->category=Categories::find();
    }
    public function singleBlogAction()
    {
        session_start();
        $this->view->currentUser=$_SESSION['currentUser'];
        $id=$this->request->getQuery('id');
        $this->view->post=Post::findFirst($id);
        $this->view->category=Categories::find();
    }
    
}

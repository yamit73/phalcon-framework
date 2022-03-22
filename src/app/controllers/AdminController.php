<?php
session_start();
use Phalcon\Mvc\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        $this->view->currentUser=$_SESSION['currentUser'];
        $currentSection=$this->request->getQuery('currentSection');
        if ($currentSection=='blogs') {
            if (!isset($_SESSION['currentUser']) || $_SESSION['currentUser']['role'] != 'admin') {
                header("Location: login");
            }
            $posts=Posts::find(['order' => 'review_date DESC']);
            $data=array();
            foreach ($posts as $val) {
                array_push($data, array(
                    'id'=>$val->post_id ,
                    'user_id'=>$val->user_id,
                    'category_id'=>$val->category_id,
                    'post_title'=>$val->post_title,
                    'post_topic'=>$val->post_topic,
                    'post_description'=>$val->post_description,
                    'review_date'=>$val->review_date,
                    'publish_date'=>$val->publish_date,
                    'status'=>$val->status
                ));
            }
            $this->view->dat=$data;
        } elseif ($currentSection=='users') {
            if (!isset($_SESSION['currentUser']) || $_SESSION['currentUser']['role'] != 'admin') {
                header("Location: login");
            }
            $users=Users::find();
            $data=array();
            foreach ($users as $val) {
                array_push($data, array(
                    'id'=>$val->id,
                    'name'=>$val->name,
                    'email'=>$val->email,
                    'role'=>$val->role,
                    'permission'=>$val->permission
                ));
            }
            $this->view->dat=$data;
        } elseif ($currentSection=='myprofile') {

            if (!isset($_SESSION['currentUser']['id'])) {
                header("Location: login");
            }
            $user=Users::findFirst($_SESSION['currentUser']['id']);
            $data=array(
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email
            );
            $this->view->dat=$data;
        } elseif ($currentSection=='myblogs') {

            if (!isset($_SESSION['currentUser']['id'])) {
                header("Location: login");
            }
            if ($_SESSION['currentUser']['role']!='writer') {
                die('<h2 class="text-warning">You are not a writer!</h2>');
            }
            $posts=Posts::find('user_id='.$_SESSION['currentUser']['id'].'');
            $data=array();
            foreach ($posts as $val) {
                array_push($data, array(
                    'id'=>$val->post_id ,
                    'user_id'=>$val->user_id,
                    'category_id'=>$val->category_id,
                    'post_title'=>$val->post_title,
                    'post_topic'=>$val->post_topic,
                    'post_description'=>$val->post_description,
                    'review_date'=>$val->review_date,
                    'publish_date'=>$val->publish_date,
                    'status'=>$val->status
                ));
            }
            $this->view->dat=$data;
        }
        $this->view->currentSection=$currentSection;
    }
    public function changeStatusAction()
    {
        $newStatus=$this->request->getQuery('newStatus');
        $date=date('Y-m-d');
        $post = Posts::findFirst($this->request->getQuery('blogId'));
        $post->status = $newStatus;
        if ($newStatus=='review') {
            // $post->publish_date = '';
            $post->review_date = date('Y-m-d');
        } else {
            $post->publish_date = date('Y-m-d');
        }
        $post->save();
        header("Location: http://localhost:8080/admin?currentSection=blogs");
    }
}

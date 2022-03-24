<?php
use Phalcon\Mvc\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        $currentSection=$this->request->getQuery('currentSection');
        if ($currentSection=='blogs') {
            if (!isset($this->session->id) || $this->session->role != 'admin') {
                $this->response->redirect("login");
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
            if (!isset($this->session->id) || $this->session->role != 'admin') {
                $this->response->redirect("login");
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

            if (!isset($this->session->id)) {
                $this->response->redirect("login");
            }
            $user=Users::findFirst($this->session->id);
            $data=array(
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email
            );
            $this->view->dat=$data;
        } elseif ($currentSection=='myblogs') {

            if (!isset($this->session->id)) {
                $this->response->redirect("login");
            }
            if ($this->session->role!='writer') {
                die('<h2 class="text-warning">You are not a writer!</h2>');
            }
            $posts=Posts::find('user_id='.$this->session->id.'');
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
        $this->response->redirect("http://localhost:8080/admin?currentSection=blogs");
    }
    public function changeRoleAction()
    {
        $newRole=$this->request->getQuery('newRole');
        $user = Users::findFirst($this->request->getQuery('userId'));
        $user->role = $newRole;
        $user->save();
        $this->response->redirect("http://localhost:8080/admin?currentSection=users");
    }
    public function changePermissionAction()
    {
        $newPer=$this->request->getQuery('newPer');
        $user = Users::findFirst($this->request->getQuery('userId'));
        $user->permission = $newPer;
        $user->save();
        $this->response->redirect("http://localhost:8080/admin?currentSection=users");
    }
    // public function deleteUserAction()
    // {
    //     $user = Users::findFirst($this->request->getQuery('id'));
    //     $user->delete();
    //     header("Location: http://localhost:8080/admin?currentSection=users");
    // }
}

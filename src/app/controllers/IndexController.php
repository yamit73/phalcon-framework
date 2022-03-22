<?php
session_start();
use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->currentUser=$_SESSION['currentUser'];
        $this->view->posts=Posts::find(['order'=>'publish_date DESC']);
        $this->view->category=Categories::find();
    }
    public function blogbycategoryAction()
    {
        $this->view->currentUser=$_SESSION['currentUser'];
        $id=$this->request->getQuery('catId');
        $this->view->posts=Posts::find([
            'conditions' => 'category_id='.$id.'',
        ]);
        $this->view->category=Categories::find();
    }
    public function singleBlogAction()
    {
        $this->view->currentUser=$_SESSION['currentUser'];
        $id=$this->request->getQuery('id');
        $this->view->category=Categories::find();
        $post=Posts::findFirst($id);
        $categoryName=Categories::findFirst($post->category_id);
        $userName=Users::findFirst($post->user_id);
        $com=Comments::find('post_id='.$id.'');
        $comment=array();
        $this->view->posts=array(
            'post_id'=>$post->post_id,
            'post_title'=>$post->post_title,
            'user_name'=>$userName->name,
            'category_name'=>$categoryName->category_name,
            'post_description'=>$post->post_description,
            'post_topic'=>$post->post_topic,
            'publish_date'=>$post->publish_date,
            'post_content'=>$post->post_content
        );
        foreach ($com as $val) {
            $user_name=Users::findFirst(['conditions' => 'id = '.$val->user_id.'']);
            array_push($comment, array(
                'user_name'=>$user_name->name,
                'comment'=>$val->comment
            ));
        }
        $this->view->comment=$comment;
    }
    public function commentAction()
    {
        if (!isset($_SESSION['currentUser'])) {
            header("location: http://localhost:8080/login");
        } else {
            $comment=new Comments();
            $comment->post_id=$this->request->getQuery('post_id');
            $comment->user_id=$_SESSION['currentUser']['id'];
            $comment->comment=$this->request->getPost('comment');
            $comment->comment_date=date('y-m-d');
            if ($comment->save()) {
                header("Location: http://localhost:8080/index/singleBlog?id=".$this->request->getQuery('post_id')."");
            }
        }
    }
    public function writeAction()
    {
        $this->view->currentUser=$_SESSION['currentUser'];
        if (!isset($_SESSION['currentUser'])) {
            header("location: http://localhost:8080/login");
        }
        if ($_SESSION['currentUser']['role']!='writer') {
            header("location: http://localhost:8080");
        }
        $this->view->category=Categories::find();
        $post = new Posts();

        $post->assign(
            $this->request->getPost(),
            [
                'category_id',
                'post_topic',
                'post_title',
                'post_description',
                'post_content'
            ]
        );
        $post->user_id=$_SESSION['currentUser']['id'];
        $post->review_date=date('Y-m-d');
        $success=$post->save();
        $this->view->success=$success;
        if ($success) {
            $this->view->message = "Blog posted successfully, Wait for approval";
        } else {
            $this->view->message = "Blog not posted: <br>".implode("<br>", $post->getMessages());
        }
    }
}

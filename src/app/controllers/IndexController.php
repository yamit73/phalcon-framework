<?php
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->posts=Posts::find(['order'=>'publish_date DESC']);
        $this->view->category=Categories::find();
    }
    public function blogbycategoryAction()
    {
        $id=$this->request->getQuery('catId');
        $this->view->posts=Posts::find([
            'conditions' => 'category_id='.$id.'',
        ]);
        $this->view->category=Categories::find();
    }
    public function singleBlogAction()
    {
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
        if (!isset($this->session->id)) {
            $this->response->redirect("login");
        } else {
            $comment=new Comments();
            $comment->post_id=$this->request->getQuery('post_id');
            $comment->user_id=$this->session->id;
            $comment->comment=$this->request->getPost('comment');
            $comment->comment_date=date('y-m-d');
            if ($comment->save()) {
                $this->response->redirect("http://localhost:8080/index/singleBlog?id=".$this->request->getQuery('post_id')."");
            }
        }
    }
    public function writeAction()
    {
        if (!isset($this->session->id)) {
            $this->response->redirect("http://localhost:8080/login");
        }
        if ($this->session->role!='writer') {
            $this->response->redirect("http://localhost:8080");
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
        $post->user_id=$this->session->id;
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

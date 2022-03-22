<?php

use Phalcon\Mvc\Model;

class Comments extends Model
{
    public $comment_id;
    public $user_id;
    public $post_id;
    public $comment;
    public $comment_date;
}

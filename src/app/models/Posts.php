<?php

use Phalcon\Mvc\Model;

class Posts extends Model
{
    public $post_id;
    public $user_id;
    public $category_id;
    public $post_title;
    public $post_topic;
    public $post_description;
    public $review_date;
    public $publish_date;
    public $status;
    public $post_content;
}

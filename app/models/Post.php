<?php

class Post
{
   private $db;

   public function __construct()
   {
      $this->db = new Database;
   }

   public function getPosts()
   {
      $this->db->query("SELECT *, posts.id as postID, users.id as userID, posts.created_at as postCreated, users.created_at as userCreated
      FROM posts INNER JOIN users on posts.user_id = users.id ORDER BY posts.created_at DESC");
      $res = $this->db->resultSet();
      return $res;
   }
}

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

   public function addPost($data)
   {
      $this->db->query("INSERT INTO posts (title, body, user_id) VALUES (:title, :body, :user_id)");
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':body', $data['body']);
      $this->db->bind(':user_id', $data['user_id']);

      if ($this->db->execute()) {
         return true;
      }
      return false;
   }

   public function getPostById($id)
   {
      $this->db->query("SELECT * FROM posts WHERE id = :id");
      $this->db->bind(':id', $id);
      $row = $this->db->single();
      return $row;
   }

   public function updatePost($data)
   {
      $this->db->query("UPDATE posts SET title = :title , body = :body WHERE id = :user_id");
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':body', $data['body']);
      $this->db->bind(':user_id', $data['id']);

      if ($this->db->execute()) {
         return true;
      }
      return false;
   }
   public function deletePost($id)
   {
      $this->db->query("DELETE FROM posts WHERE id =:id");

      $this->db->bind(':id', $id);

      if ($this->db->execute()) {
         return true;
      }
      return false;
   }
}

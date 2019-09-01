<?php

class Pages extends Controller
{

   public function __construct()
   { }
   public function index()
   {
      if (isLoggedIn()) {
         redirect('/posts');
      }
      $data = [
         'title' => 'Home',
         'description' => ' Simple socila media'
      ];
      $this->view("pages/index", $data);
   }
   public function about()
   {
      $data = [
         'title' => 'About',
         'description' => ' Share post with other users'
      ];
      $this->view("pages/about", $data);
   }
}

<?php

class Users extends Controller
{
   public function __construct()
   {
      $this->userModel = $this->model('User');
   }

   public function register()
   {
      // Check for post
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

         // Sanitize POST data
         $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


         //Init data
         $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'password' => trim($_POST['password']),
            'confirm_password' => trim($_POST['confirm_password']),
            'name_err' => '',
            'email_err' => '',
            'password_err' => '',
            'confirm_password_err' => ''
         ];

         // Validate Email
         if (empty($data['email'])) {
            $data['email_err'] = "Please enter email";
         } else {
            // Check if email exist
            if ($this->userModel->findUserByEmail($data['email'])) {
               $data['email_err'] = "Please already exists";
            }
         }
         // Validate Name
         if (empty($data['name'])) {
            $data['name_err'] = "Please enter name";
         }
         // Validate Password
         if (empty($data['password'])) {
            $data['password_err'] = "Please enter password";
         } else if (strlen($data['password']) < 6) {
            $data['password_err'] = "Password must be at least 6 characters";
         }
         // Validate Confrim Password
         if (empty($data['confirm_password'])) {
            $data['confirm_password_err'] = "Please confrim password";
         } else {
            if ($data['password'] != $data['confirm_password']) {
               $data['confirm_password_err'] = "Passwords do not match";
            }
         }
         if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {

            // Hash password
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // Register User

            if ($this->userModel->register($data)) {
               flash('register_success', 'You are registered and can log in');
               redirect("users/login");
            } else {
               die("Something went wrong");
            }
         } else {
            // Load view with errors
            $this->view('users/register', $data);
         }
      } else {

         //Init data
         $data = [
            'name' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'name_err' => '',
            'email_err' => '',
            'password_err' => '',
            'confirm_password_err' => ''
         ];
         // Load view

         $this->view('users/register', $data);
      }
   }

   public function login()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

         // Sanitize POST data
         $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


         //Init data
         $data = [
            'email' => trim($_POST['email']),
            'password' => trim($_POST['password']),
            'email_err' => '',
            'password_err' => '',
         ];

         // Validate Email
         if (empty($data['email'])) {
            $data['email_err'] = "Please enter email";
         }

         // Validate Password
         if (empty($data['password'])) {
            $data['password_err'] = "Please enter password";
         }

         if ($this->userModel->findUserByEmail($data['email'])) { } else {
            $data['email_err'] = "No user found";
         }
         if (empty($data['email_err']) && empty($data['password_err'])) {
            // Check and set User
            $loggedinUser = $this->userModel->login($data['email'], $data['password']);
            if ($loggedinUser) {
               // Create session
               $this->createUserSession($loggedinUser);
            } else {
               $data['password_err'] = 'Password incorrect';
               $this->view('users/login', $data);
            }
         } else {
            // Load view with errors
            $this->view('users/login', $data);
         }
      } else {

         //Init data
         $data = [

            'email' => '',
            'password' => '',
            'email_err' => '',
            'password_err' => '',

         ];
         // Load view

         $this->view('users/login', $data);
      }
   }

   public function createUserSession($user)
   {
      $_SESSION['user_id'] = $user->id;
      $_SESSION['email'] = $user->email;
      $_SESSION['name'] = $user->name;

      redirect('posts/index');
   }

   // Log out function
   public function logout()
   {
      unset($_SESSION['user_id']);
      unset($_SESSION['email']);
      unset($_SESSION['name']);
      session_destroy();
      redirect('users/login');
   }
}

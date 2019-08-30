<?php

/*
 * App Core Class
 * Creates URL and loads core controller
 * URL FORMAT - /controller/method/params
 */


class Core
{
   protected $currentController = "Pages";
   protected $currentMethod = "index";
   protected $params = [];

   public function __construct()
   {
      $url = $this->getUrl();

      // Chekc if exist controller
      if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {

         // If exist, set as controller
         $this->currentController = ucwords($url[0]);
         // Unset 0 Index
         unset($url[0]);
      }

      // Require controller
      require_once '../app/controllers/' . $this->currentController . '.php';

      // Instantiate controller class
      $this->currentController = new $this->currentController;

      // Check second segment of url is passed
      if (isset($url[1])) {
         //Check if method exist in controller
         if (method_exists($this->currentController, $url[1])) {
            $this->currentMethod = $url[1];
            unset($url[1]);
         }
      }

      // Get params fix params with array_values to start again from 0
      $this->params = $url ? array_values($url) : [];

      // call_user_func_array ($foo->bar(), params)
      // calls the method [*$this->currentMethod*] in the controller [*$this->currentController*] withe a list of paramentes $this->params
      call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
   }

   public function getUrl()
   {
      if (isset($_GET['url'])) {
         $url = rtrim($_GET['url'], '/');
         $url = filter_var($url, FILTER_SANITIZE_URL);
         $url = explode('/', $url);

         return $url;
      }
   }
}

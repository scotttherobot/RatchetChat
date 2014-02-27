<?php

/**
 * A page object.
 */

class Page {

   public static $app = false;

   public $user = false;
   public $title = '';
   public $style = [];
   public $scripts = [];
   public $theme = 'admin';
   public $templates = [];

   public function __construct($app, $user = false) {
      static::$app = $app;

      // If a user was passed in, let's use that for the context of the page.
      // Otherwise, get the singleton user.
      $this->user = $user ?: User::auth();
   }

   public function addTemplate($template) {
   }

   public function addData($data) {
   }

   public function setTitle($title) {
   }

   public function setTheme($theme) {
   }

   public function addStyle($stylesheet) {
   }

   public function addScript($script) {
   }

   public function render($return = false) {
   }

   public function error($message) {
   }

   public function addAuthenticator($callable, $redirect = false) {
      if (is_callable($callable)) {
         if ($callable()) {
            return true;
         }
      }
      // Probably should die here.
      static::$app->redirect('/403/');
      return false;
   }

}

<?php

class URI {

   private static $tags = [
      'ADMIN' => [
         'LOGIN' => '/login/',
         'LOGOUT' => '/logout/',
         'STUDENTS' => '/students/',
         'TESTS' => '/tests/',
         'SIGNUP' => '/signup/',
         'TEACHER_SIGNUP' => '/signup?teacher=1',
         'ASSIGNMENTS' => '/assignments/',
         'API' => '/0.1/',
      ],
      'API-01' => [
         'LOGIN' => '/login/',
         'REGISTER' => '/login/',
         'LOGOUT' => '/logout/',
         '403' => '403',
      ],
      'TEACHER-01' => [
         'STUDENTS' => '/students/',
         'SECTIONS' => '/sections/',
         'QUESTIONS' => '/questions/',
         'TESTS' => '/tests/',
         'TEACHER' => '/',
      ],
      'STUDENT-01' => [
         'STUDENT' => '/',
         'ASSIGNMENTS' => '/assignments/',
         'ATTEMPTS' => '/attempts/',
      ],
   ];

   public static function tag($tag) {
      return idx(self::$tags['ADMIN'], $tag, NULL);
   }

   public static function api($tag) {
      return idx(self::$tags['API-01'], $tag, NULL);
   }

   public static function teacher($tag) {
      return idx(self::$tags['TEACHER-01'], $tag, NULL);
   }

   public static function student($tag) {
      return idx(self::$tags['STUDENT-01'], $tag, NULL);
   }

   public static function isActive($tag, $bool = false) {
      $return = $bool ?: "active";
      $route = idx(self::$tags['ADMIN'], $tag, NULL);
      $request = idx($_SERVER, 'REQUEST_URI', NULL);
      $path = explode("/", trim($request));
      $path = "/$path[1]/";
      return $path == $route ? $return : false;
   }
   
   public static function content($id, $name = "") {
      $name = preg_replace('/\s+/', '+', $name);
      return "/p/$id/$name";
   }

   public static function leader($imgid) {
      return "/img/none.jpg";
   }

}

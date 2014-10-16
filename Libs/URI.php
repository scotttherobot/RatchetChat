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
   ];

   public static function tag($tag) {
      return idx(self::$tags['ADMIN'], $tag, NULL);
   }

   public static function api($tag) {
      return idx(self::$tags['API-01'], $tag, NULL);
   }

   public static function isActive($tag, $bool = false) {
      $return = $bool ?: "active";
      $route = idx(self::$tags['ADMIN'], $tag, NULL);
      $request = idx($_SERVER, 'REQUEST_URI', NULL);
      $path = explode("/", trim($request));
      $path = "/$path[1]/";
      return $path == $route ? $return : false;
   }
}

<?php

function loggedIn() {
   $result = false;
   // global $userid, $uid;

   if (isset($_SERVER['REMOTE_USER'])){
      $uid = $_SERVER['REMOTE_USER'];
      if (!uidExists($uid)){
         // addUser($uid);
         // #TODO
         echo 'ERROR: add user function has to be implemented';
      }

      $userid = getIdFromUid($uid);
      $result = true;
   }
   return $result;
}

function uidExists($uid) {
   $result = false;

   $query = "select count(*) from doorperson where uid = '" . $uid . "';";
   $count = queryValue($query);
   if ( $count == 1 ) {
      $result = true;
   }
   
   return $result;
}

function getIdFromUid($uid) {
   $result = 0;

   $query = "select id from doorperson where uid = '" . $uid . "';";
   $result = queryValue($query);

   return $result;
}

?>

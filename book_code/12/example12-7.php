<?php
function destroy_session_and_data()
{
   session_start();
   $_SESSION = array();

// if (session_id() != "" || isset($_COOKIE[session_name()]))
//           The above line appears in the book but is not
//           actually required and should be ignored

   setcookie(session_name(), '', time() - 2592000, '/');
   session_destroy();
}
?>
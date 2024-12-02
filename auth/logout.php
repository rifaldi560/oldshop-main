<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
if (session_id() != '' || isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time() - 3600, '/');
}
session_unset();
session_destroy();

// Remove cookies related to authentication
if (isset($_COOKIE['id'])) {
  setcookie('id', '', time() - 3600, '/');
}

if (isset($_COOKIE['key'])) {
  setcookie('key', '', time() - 3600, '/');
}

// Redirect to the login page
header("Location: /");
exit;
?>
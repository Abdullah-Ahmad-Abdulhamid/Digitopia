<?php
require_once 'config/config.php';

// Logout user
if (isLoggedIn()) {
    $user = new User();
    $user->logout();
}

// Redirect to login page
redirectTo('login.php');
?>

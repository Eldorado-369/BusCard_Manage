<?php
session_start();
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session
header('Location: http://localhost/Project/loginPage/loginPage.php'); // Redirect to login page
exit;

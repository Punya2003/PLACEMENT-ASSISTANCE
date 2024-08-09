<?php
// Start the session
session_start();

// Destroy the session
session_destroy();

// Redirect to the main page (adjust the path as needed)
header("Location: main_page.php");
exit();
?>

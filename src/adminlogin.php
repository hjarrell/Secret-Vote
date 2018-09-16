<?php
session_start();

// Holds the hardcoded admin password
$superSecretPassword = "watermelonadminpass";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["password"]) && $_POST["password"] === $superSecretPassword) {
        $_SESSION["isadmin"] = true; // Store that the user is an admin
    }
}

// Redirect back to the main admin page
Header("Location: admin.php");
?>
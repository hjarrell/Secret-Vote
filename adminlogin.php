<?php
session_start();

$superSecretPassword = "watermelonadminpass";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["password"]) && $_POST["password"] === $superSecretPassword) {
        $_SESSION["isadmin"] = true;
    }
}

Header("Location: admin.php");

?>
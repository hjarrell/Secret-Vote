<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get database login information.
    include "databasecreds.php";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Checks if there is even a current poll
    $checkForCurrentPollSql = "SELECT * from current_poll;";
    $result = $conn->query($checkForCurrentPollSql);
    if ($result->num_rows > 0) {
        $result = $result->fetch_assoc();

        // Check to see if the password in the DB matches the password 
        if ($result["pword"] == $_POST["pass"]) {
            // Make the user session show that they are authenticated
            $_SESSION[md5($result["poll_id"])] = "authenticated";
        }
        
    }
    $conn->close();
}
// Redirects back to main page
Header("Location: index.php");
?>
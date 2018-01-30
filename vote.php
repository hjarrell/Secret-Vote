<?php
session_start();

$vote = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "127.0.0.1";
    $username = "secretVote";
    $password = "test";
    $dbname = "voting";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Checks if there is even a current poll
    $checkForCurrentPollSql = "SELECT * from current_poll;";
    $result = $conn->query($checkForCurrentPollSql);
    if ($result->num_rows > 0) {
        // Gets the name of the person we are voting on
        $name = $result->fetch_assoc()["name"];

        // Checks to see if there is even a vote
        if (!empty($_POST["vote"])){
            $vote = $_POST["vote"];
            $insertSql = "";
            // Manually sets the vote instead of appending $vote to protect against SQL Injection
            switch ($vote) {
                case "YES":
                    $insertSql = "INSERT INTO votes VALUES ('".$name."','YES');";
                    break;
                case "NO":
                    $insertSql = "INSERT INTO votes VALUES ('".$name."','NO');";
                    break;
                case "ABSTAIN":
                    $insertSql = "INSERT INTO votes VALUES ('" . $name . "','ABSTAIN');";
                    break;
                default:
                    break;
            }
            // If we have a valid vote then we should insert it.
            if (!empty($insertSql)) {
                $conn->query($insertSql);
                $_SESSION[$name] = true; // This keeps double voting from happening
            }
        }
    }
    $conn->close();
}
// Redirects back to main page
Header("Location: index.php");
?>
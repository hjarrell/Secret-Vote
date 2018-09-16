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
        // Gets the id of the poll we are voting on
        $pollId = $conn->real_escape_string($result->fetch_assoc()["poll_id"]);
        $pollVoteType = $result->fetch_assoc()["voting_type"];

        // Make sure that if it password protected the user authenticated
        if ($pollVoteType === "password" && $_SESSION[md5($pollId)] !== "authenticated") {
            Header("Location: index.php");
        } else {
            $stmt = $conn->prepare("INSERT INTO votes (poll_id, option_id) VALUES (?, ?);");
            $stmt->bind_param("ii", $pollId, $optionId);

            // Checks to see if there is even a vote
            if (!empty($_POST["vote"])){
                $vote = $_POST["vote"];
                
                $optionId = $conn->real_escape_string($vote);

                // If we have a valid vote then we should insert it.
                if (!empty($optionId)) {
                    $stmt->execute();
                    $_SESSION[md5($pollId)] = "voted"; // This keeps double voting from happening
                }
            }
        }
    }
    $conn->close();
}
// Redirects back to main page
Header("Location: index.php");
?>
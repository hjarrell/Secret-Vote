<?php
session_start(); // Have to start session before html

if ($_SESSION["isadmin"] == true) { // Make sure we have admin permissions
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Make sure the request is a POST
        // Get database login information
        include "databasecreds.php";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: ");
        } else {
            // Make sure the form sent a title for the poll
            if (!empty($_POST["title"])) {
                // Create the actual poll data
                $createPollQuery = "INSERT INTO polls (title) VALUES ('" . $conn->real_escape_string($_POST["title"]) . "');";
                $pollCreated = $conn->query($createPollQuery);
                // If the poll was actually created add options
                if ($pollCreated) {
                    // Get the id of the poll that was just created
                    $pollId = $conn->insert_id;
                    // For every POST data make sure that it starts with option and then insert as a poll option
                    foreach ($_POST as $formName => $formData) {
                        if (substr($formName, 0, 6) === "option") {
                            $insertOptionQuery = "INSERT INTO poll_options (poll_id, option_text) VALUES (".$conn->real_escape_string($pollId) . ", '" . $conn->real_escape_string($formData) . "');";
                            $conn->query($insertOptionQuery);
                        }
                    }
                    // Make this poll the current poll being voted on
                    $makeCurrentPollQuery = "INSERT INTO current_poll VALUES (1," . $pollId . ");";
                    $conn->query($makeCurrentPollQuery);
                } else {
                    echo "Creating post failed.";
                }
            } else {
                echo "Creating post failed.";
            }
        }
        $conn->close();
    }
}

Header("Location: admin.php");
?>
<?php
session_start();

if ($_SESSION["isadmin"] == true) { // Make sure we have admin permissions
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Make sure the request is a POST
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "voting";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: ");
        } else {
            if (!empty($_POST["command"])) { // Check to see if the command field is even there
                $command = $_POST["command"];
                $sql = "";
                switch ($command) {
                    case "stopvote":
                        $sql = "DELETE FROM current_poll WHERE 1=1;"; // Delete the current poll to stop voting
                        break;
                    case "startvote":
                        if (!empty($_POST["name"])) {
                            $name = $_POST["name"];
                            $sql = "INSERT INTO current_poll VALUES (1,'".mysqli_real_escape_string($conn, $name)."');"; // Start a poll by inserting into current_poll
                        }
                        break;
                    default:
                        break;
                }

                // Run the command if the switch found an actual command
                if (!empty($sql)) {
                    $conn->query($sql);
                }

            }
        }
    }
}

// Redirect back to main admin page
Header("Location: admin.php");
?>
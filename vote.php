<?php
session_start();

$vote = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "voting";

    /*
    CREATE TABLE current_poll (
    id enum('1') NOT NULL,
    name VARCHAR(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    */

    $conn = new mysqli($servername, $username, $password, $dbname);

    $checkForCurrentPollSql = "SELECT * from current_poll;";
    $result = $conn->query($checkForCurrentPollSql);
    if ($result->num_rows > 0) {
        $name = $result->fetch_assoc()["name"];

        if (!empty($_POST["vote"])){
            $vote = htmlspecialchars($_POST["vote"]);
            $insertSql = "";
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
                die ("oh no" . $vote);
                    break;
            }
            print($insertSql);
            if (!empty($insertSql)) {
                $conn->query($insertSql);
                $_SESSION[$name] = true;
            }
        }
    }
    $conn->close();
}
    HEADER("Location: index.php");
?>
<?php
session_start();

if ($_SESSION["isadmin"] == true) {

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

        if ($conn->connect_error) {
            die("Connection failed: ");
        } else {
            if (!empty($_POST["command"])) {
                $command = $_POST["command"];
                $sql = "";
                switch ($command) {
                    case "stopvote":
                        $sql = "DELETE FROM current_poll WHERE 1=1;";
                        break;
                    case "startvote":
                        if (!empty($_POST["name"])) {
                            $name = $_POST["name"];
                            $sql = "INSERT INTO current_poll VALUES (1,'".mysqli_real_escape_string($conn, $name)."');";
                        }
                        break;
                    default:
                        break;
                }

                if (!empty($sql)) {
                    $conn->query($sql);
                }

            }
        }
    }
}
Header("Location: admin.php");

?>
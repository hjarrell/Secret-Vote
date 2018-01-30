<?php
session_start();
?>

<html>
 <head>
  <title>Secret Voting - SHHHHH</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
  table {
      border-collapse: collapse;
      width: 100%;
  }
    th,td {
        border: 1px solid #ddd;
        padding: 15px;
    }
    tr-nth-child(even) {
        background-color: #f2f2f2;
    }
    th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: black;
        color: white;
    }
  </style>
 </head>
 <body>

<h1>Secret Votings - SHHHHH ITS SECRET</h1>

<?php if ($_SESSION["isadmin"] == true){ 

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
        $checkForCurrentPollSql = "SELECT * from current_poll;";
        $result = $conn->query($checkForCurrentPollSql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row["name"];

            

            echo "<h3>We are currently voting on: <strong>" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</strong></h3>";
            echo "<br>";
            echo '
                <form action="admincommand.php" method="post">
                    <input type="hidden" name="command" value="stopvote">
                    <input type="submit" value="STOP POLL">
                </form>
            ';
        } else {
            echo '
                Start a poll (If you are revoting on someone add a 1(2,3,... and so on for every time) after the name or people wont be able to vote.):
                <form action="admincommand.php" method="post">
                    <input type="hidden" name="command" value="startvote"> 
                    <input type="text" name="name">
                    <input type="submit">
                </form>
            ';
        }

        $nameSql = "SELECT DISTINCT name FROM votes ORDER BY name ASC;";
        $nameResult = $conn->query($nameSql);
        if ($nameResult->num_rows > 0) {
            echo '
                <table>
                    <tr>
                        <th>Name</th>
                        <th>YES</th>
                        <th>NO</th>
                        <th>ABSTAIN</th>
                    </tr>
            ';

            while ($row = $nameResult->fetch_assoc()) {
                $yesVotesResult = $conn->query("SELECT COUNT(*) AS total FROM votes WHERE name='". $row["name"] . "' AND vote='YES';") or die($conn->error);
                $yesVotes = $yesVotesResult->fetch_assoc()["total"];
                $noVotesResult = $conn->query("SELECT COUNT(*) AS total FROM votes WHERE name='". $row["name"] . "' AND vote='NO';");
                $noVotes = $noVotesResult->fetch_assoc()["total"];
                $abstainVotesResult = $conn->query("SELECT COUNT(*) AS total FROM votes WHERE name='". $row["name"] . "' AND vote='ABSTAIN';");
                $abstainVotes = $abstainVotesResult->fetch_assoc()["total"];
                echo '<tr>';
                echo '<td>'.$row["name"].'</td>';
                echo '<td>'.$yesVotes.'</td>';
                echo '<td>'.$noVotes.'</td>';
                echo '<td>'.$abstainVotes.'</td>';
                echo '</tr>';
            }


            echo '</table>';
        } else {
            echo "You currently have no votes :(";
        }
    }

} else { ?>

    <form action="adminlogin.php" method="post">
    Password: <input type="text" name="password">
    <input type="submit">

</form>

<?php } ?>

</body>

</html>
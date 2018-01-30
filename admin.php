<?php
session_start(); // Have to start session before html
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

        // Gets all the names of the people who have votes
        $nameSql = "SELECT DISTINCT name FROM votes ORDER BY name ASC;";
        $nameResult = $conn->query($nameSql);
        if ($nameResult->num_rows > 0) {
            // Sets up the table header
            echo '
                <table>
                    <tr>
                        <th>Name</th>
                        <th>YES</th>
                        <th>NO</th>
                        <th>ABSTAIN</th>
                        <th>Total</th>
                    </tr>
            ';

            // Loops over each row of names
            while ($row = $nameResult->fetch_assoc()) {
                // These all count the results of the votes by each type and then the grand total and then fetching the result
                $yesVotesResult = $conn->query("SELECT COUNT(*) AS total FROM votes WHERE name='". $row["name"] . "' AND vote='YES';") or die($conn->error);
                $yesVotes = $yesVotesResult->fetch_assoc()["total"];
                $noVotesResult = $conn->query("SELECT COUNT(*) AS total FROM votes WHERE name='". $row["name"] . "' AND vote='NO';");
                $noVotes = $noVotesResult->fetch_assoc()["total"];
                $abstainVotesResult = $conn->query("SELECT COUNT(*) AS total FROM votes WHERE name='". $row["name"] . "' AND vote='ABSTAIN';");
                $abstainVotes = $abstainVotesResult->fetch_assoc()["total"];
                $totalVotesResult = $conn->query("SELECT COUNT(*) AS total FROM votes WHERE name='". $row["name"] . "';");
                $totalVotes = $totalVotesResult->fetch_assoc()["total"];
                
                // Sets up the table row.
                echo '<tr>';
                echo '<td>'.$row["name"].'</td>';
                echo '<td>'.$yesVotes.'</td>';
                echo '<td>'.$noVotes.'</td>';
                echo '<td>'.$abstainVotes.'</td>';
                echo '<td>'.$totalVotes.'</td>';
                echo '</tr>';
            }
            // Closes the table started above
            echo '</table>';
        } else {
            echo "You currently have no votes :(";
        }
    }

} else { ?>

    <form action="adminlogin.php" method="post">
        Password: <input type="password" name="password">
        <input type="submit">
    </form>

<?php } ?>

</body>

</html>

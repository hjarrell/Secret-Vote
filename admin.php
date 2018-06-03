<?php
session_start(); // Have to start session before html
?>

<html>
 <head>
  <title>Secret Voting - SHHHHH</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/favicon.ico?" type="image/x-icon">
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

<?php if ($_SESSION["isadmin"] == true) {

    $servername = "127.0.0.1";
    $username = "secretVote";
    $password = "test";
    $dbname = "voting";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: ");
    } else {
        $checkForCurrentPollSql = "SELECT p.title, p.id from current_poll cp INNER JOIN polls p ON cp.poll_id = p.id;";
        $currentPollResult = $conn->query($checkForCurrentPollSql);
        if ($currentPollResult->num_rows > 0) {
            $currentPoll = $currentPollResult->fetch_assoc();
            $name = $currentPoll["title"];
            $pollId = $currentPoll["id"];

            echo "<h3>We are currently voting on: <strong>" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</strong></h3>";
            echo "<br>";
            echo '
                <form action="admincommand.php" method="post">
                    <input type="hidden" name="command" value="stopvote">
                    <input type="submit" value="STOP POLL">
                </form>
            ';
        } else {
            echo '<a href="newpoll.php">Create a new poll</a><br/>';
        }

        // Gets every poll in the database
        $getAllPollsSql = "SELECT id, title FROM polls;";
        $getAllPollsResult = $conn->query($getAllPollsSql);
        if ($getAllPollsResult->num_rows > 0) { // Checks to make sure we have at least 1 poll
            // Clear votes button
            echo '
                <form action="admincommand.php" method="post">
                    <input type="hidden" name="command" value="clearvotes">
                    <input type="submit" value="CLEAR ALL VOTES!">
                </form>
            ';
            
            // Loop through each poll
            while ($pollRow = $getAllPollsResult->fetch_assoc()) {
                echo "<hr/>";
                echo "Poll: " . htmlspecialchars($pollRow["title"]);
                echo "<br/>";
                // Gets all the poll options and vote counts for the current poll
                $votesSql = "SELECT DISTINCT COUNT(po.option_text), po.option_text FROM votes v INNER JOIN poll_options po ON v.option_id = po.id WHERE v.poll_id = " . $conn->real_escape_string($pollRow["id"]) . " GROUP BY po.option_text ORDER BY COUNT(po.option_text) DESC;";
                $votesResult = $conn->query($votesSql);
                if ($votesResult->num_rows > 0) {
                    echo "
                    <table>
                        <tr>
                            <th>
                            Poll Option
                            </th>
                            <th>
                            Votes
                            </th>
                        </tr>";
                    $totalSum = 0;
                    while ($row = $votesResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>";
                        echo htmlspecialchars($row["option_text"]);
                        echo "</td>";
                        echo "<td>";
                        echo htmlspecialchars($row["COUNT(po.option_text)"]);
                        echo "</td>";
                        echo "</tr>";
                        $totalSum += $row["COUNT(po.option_text)"];
                    }
                    echo "<tr>
                <td><strong>Total</strong></td>
                <td>";
                    echo htmlspecialchars($totalSum);
                    echo "</td></tr>";

                    echo "
            </table>
            ";

                } else {
                    echo "There are currently have no votes :(";
                }
            }
        }
    }

} else {?>

    <form action="adminlogin.php" method="post">
        Password: <input type="password" name="password">
        <input type="submit">
    </form>

<?php }?>

</body>

</html>

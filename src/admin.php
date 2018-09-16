<?php
session_start(); // Have to start session before html
include "util.php";
?>

<html>
 <head>
  <?php echo get_header(); ?>
 </head>
 <body>

 <?php
    echo get_admin_nav_bar();
 ?>

<?php if ($_SESSION["isadmin"] == true) {
    // Get database login information
    include "databasecreds.php";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: ");
    } else {
        echo '<div class="container">';
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
                    <button type="submit" class="btn waves-effect waves-light red darken-4"> STOP THE POLL! </button>
                </form>

                    <form action="admincommand.php" method="post">
                        <input type="hidden" name="command" value="clearvotes">
                        <button type="submit" class="btn waves-effect waves-light red darken-4"> CLEAR ALL VOTES! </button>
                    </form>
            ';
        } else {
            echo "<h3>There is currently no active poll</h3>";
            echo '<a href="newpoll.php" class="btn waves-effect waves-light amber accent-4">Create a new poll</a><br/>';
        }

        // Gets every poll in the database
        $getAllPollsSql = "SELECT id, title FROM polls ORDER BY id DESC;";
        $getAllPollsResult = $conn->query($getAllPollsSql);
        if ($getAllPollsResult->num_rows > 0) { // Checks to make sure we have at least 1 poll
            
            // Loop through each poll
            while ($pollRow = $getAllPollsResult->fetch_assoc()) {
                echo '<div class="card">
                        <div class="card-content">
                ';
                echo '<span class="card-title">' . htmlspecialchars($pollRow["title"]) . '</span>'; 
                echo "<br/>";
                // Gets all the poll options and vote counts for the current poll
                $votesSql = "SELECT DISTINCT COUNT(po.option_text), po.option_text FROM votes v INNER JOIN poll_options po ON v.option_id = po.id WHERE v.poll_id = " . $conn->real_escape_string($pollRow["id"]) . " GROUP BY po.option_text ORDER BY COUNT(po.option_text) DESC;";
                $totalVotesSql = "SELECT COUNT(po.option_text) as tot FROM votes v INNER JOIN poll_options po ON v.option_id = po.id WHERE v.poll_id = " . $conn->real_escape_string($pollRow["id"]);
                $votesResult = $conn->query($votesSql);
                $totalVotes = $conn->query($totalVotesSql)->fetch_assoc()["tot"];
                if ($votesResult->num_rows > 0) {
                    echo '
                    <table class="responsive-table">
                        <tr>
                            <th>
                            Poll Option
                            </th>
                            <th>
                            Votes
                            </th>
                            <th>
                            % Total
                            </th>
                        </tr>';
                    $totalSum = 0;
                    while ($row = $votesResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>";
                        echo htmlspecialchars($row["option_text"]);
                        echo "</td>";
                        echo "<td>";
                        echo htmlspecialchars($row["COUNT(po.option_text)"]);
                        echo "</td>";
                        echo "<td>";
                        echo htmlspecialchars(sprintf("%01.2f", doubleval($row["COUNT(po.option_text)"])/doubleval($totalVotes) * 100.0)) . "%";
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "<tr>
                <td><strong>Total</strong></td>
                <td>";
                    echo htmlspecialchars($totalVotes);
                    echo "</td><td>100%</td></tr>";

                    echo "
            </table>
            ";

                } else {
                    echo "There are currently have no votes :(";
                }
                echo "</div></div>";
            }
        }
        echo "</div>";
    }

} else {?>

    <div class="container">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Please login.</span>
                <form action="adminlogin.php" method="post">
                    <div class="input-field">
                        <input type="password" name="password">
                        <label for="password">Password</label>
                    </div>
                    <button type="submit" class="btn waves-effect waves-light amber accent-4">Login</button>
                </form>
            </div>
        </div>
    </div>

<?php }?>
</body>

</html>

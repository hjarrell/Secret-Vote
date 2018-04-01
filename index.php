<?php
// Start the session so that the user can be uniquely identified for the purpose of not voting twice.
// They are not able to be actually identified by name however.
session_start();
?>

<html>
 <head>
  <title>Secret Voting - SHHHHH</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/favicon.ico?" type="image/x-icon">
 </head>
 <body>

<h1>Secret Voting - SHHHHH IT IS SECRET</h1>

 <?php
    // Database information
    $servername = "127.0.0.1";
    $username = "secretVote";
    $password = "test";
    $dbname = "voting";

    // Creates a connection to the database with the info above.
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: ");
    } else {
        // Checks if there is a current poll up.
        $checkForCurrentPollSql = "SELECT * from current_poll;";
        $result = $conn->query($checkForCurrentPollSql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); // The current_poll table can only ever have 1 row
            $name = $row["name"];

            // Print out who we are voting on and escape the string just in case
            echo "<h3>We are currently voting on: <strong>" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</strong></h3>";
            echo "<br>";
            // Session of the name means we voted on this person already
            if (isset($_SESSION[md5($name)])) {
                echo "You already voted for this person.";
            } else {
                echo '
                    Cast your vote!
                    <form action="vote.php" method="post">
                        <input type="radio" name="vote" value="YES">YES</input>
                        <br>
                        <input type="radio" name="vote" value="NO">NO</input>
                        <br>
                        <input type="radio" name="vote" value="ABSTAIN">ABSTAIN</input>
                        <br>
                        <input type="submit">
                    </form>
                ';
            }
        } else {
            echo "There is not a poll currently going on...Stay tuned!";
        }
    }
 ?> 
</body>
</html>
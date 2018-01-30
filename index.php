<?php
session_start();
?>

<html>
 <head>
  <title>Secret Voting - SHHHHH</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
 </head>
 <body>

<h1>Secret Votings - SHHHHH ITS SECRET</h1>

 <?php
 
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
        if (isset($_SESSION[$name])) {
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
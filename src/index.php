<?php
// Start the session so that the user can be uniquely identified for the purpose of not voting twice.
// They are not able to be actually identified by name however.
session_start();
include "util.php";
?>

<html>
 <head>
  <?php echo get_header(); ?>
 </head>
 <body>

<?php echo get_nav_bar(); ?>

 <?php
    // Database information
    include "databasecreds.php";

    // Creates a connection to the database with the info above.
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: ");
    } else {
        // Checks if there is a current poll up.
        $checkForCurrentPollSql = "SELECT p.id, title, voting_type from current_poll cp INNER JOIN polls p ON cp.poll_id = p.id;";
        $result = $conn->query($checkForCurrentPollSql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); // The current_poll table can only ever have 1 row
            $title = $row["title"];
            $pollId = $row["id"];
            $pollType = $row["voting_type"];
            $pollOptionsQuery = "SELECT id, option_text FROM poll_options WHERE poll_id = " . $pollId . ";";
            $pollOptionsResult = $conn->query($pollOptionsQuery);

            // Print out who we are voting on and escape the string just in case
            echo '<div class="container">';
            echo "<h3>We are currently voting on: <strong>" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "</strong></h3>";
            echo "<br>";
            // Session of the name means we voted on this person already
            if ($_SESSION[md5($pollId)] === "voted") {
                echo "You already voted for this.";
            } else {
                switch ($pollType) {
                    // Once and many vote the same way.
                    case "once":
                    case "many":
                        echo '
                        <div class="card">
                            <div class="card-content">
                            <span class="card-title">Cast your vote!</span>
                        <form action="vote.php" method="post">
                        ';
                        while ($pollOption = $pollOptionsResult->fetch_assoc()) {
                            echo make_radio_button("vote", htmlspecialchars($pollOption["id"]), htmlspecialchars($pollOption["option_text"]));
                            echo '<br/>';
                        }
                        echo '    <button type="submit" class="btn waves-effect waves-light amber accent-4">Submit</button>
                        </form>
                        </div>
                        </div>
                        ';
                        break;
                    case "password":
                        // Check to see if the user used the right password
                        if ($_SESSION[md5($pollId)] === "authenticated") {
                            echo '
                            <div class="card">
                            <div class="card-content">
                            <span class="card-title">Cast your vote!</span>
                            <form action="vote.php" method="post">';
                            while ($pollOption = $pollOptionsResult->fetch_assoc()) {
                                echo make_radio_button("vote", htmlspecialchars($pollOption["id"]), htmlspecialchars($pollOption["option_text"]));
                                echo '<br/>';
                            }
                            echo '    <button type="submit" class="btn waves-effect waves-light amber accent-4">Submit</button>
                            </form>
                            </div>
                            </div>
                            ';
                        // Prompt for the password
                        } else {
                            echo '
                            <div class="card">
                            <div class="card-content">
                            <span class="card-title">Please enter the password.</span>
                            <form action="voteauth.php" method="post">
                                <div class="input-field">
                                <input type="text" name="pass">
                                <label for="pass">Password</label>
                                </div>
                                <br/>
                                <button type="submit" class="btn waves-effect waves-light amber accent-4">Submit</button>
                            </form>
                            </div>
                            </div>
                            ';
                        }
                        break;
                    case "otp":
                        break;
                }
            }
            echo "</div>";
        } else {
            echo "There is not a poll currently going on...Stay tuned!";
        }
    }
 ?> 
</body>
</html>
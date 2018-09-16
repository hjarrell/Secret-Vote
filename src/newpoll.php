<?php
session_start(); // Have to start session before html

if ($_SESSION["isadmin"] != true) {
    Header("Location: admin.php");
}

include "util.php";
?>

<html>
    <head>
        <?php echo get_header(); ?>
        </head>
    <body>
        <?php echo get_nav_bar(); ?>
        <div class="container row ">
            <div class="row">
            </div>
            <div class="col s12">
                <form action="poll.php" method="post" id="poll-form" class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Create a New Poll</span>
                            <div class="row">
                                <div class="input-field">
                                    <input type="text" name="title">
                                    <label for="title">Title</label>
                                </div>

                                <br/>
                                <strong>Enter your poll options below</strong>
                                <br/>
                                <div id="poll-options">
                                    <div id="option1" class="row s12">
                                        <div class="input-field col s11">
                                            <input type="text" name="option1" class="">
                                            <label for="option1">Poll Option</label>
                                        </div>
                                        <div class="input-field col s1">
                                            <button class="btn waves-effect waves-light" type="button" onclick="removeOption(1);">X</button>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn waves-effect waves-light" type="button" onclick="newPollOption();">Add Option</button>
                                <button class="btn waves-effect waves-light" type="button" onclick="resetYN();">Reset to Y/N</button>
                                <button class="btn waves-effect waves-light" type="button" onclick="resetYNA();">Reset to Y/N/A</button>
                                <button class="btn waves-effect waves-light" type="button" onclick="resetYNANC();">Reset to Y/N/A/NC</button>
                                <div id="vote-type-div" class="input-field">
                                    <strong>Voting type: </strong>
                                    <div id="vote-type-descr">
                                        This tries to keep the user from voting more than once but is not that secure.
                                        The user can get around this by using multiple browsers or incognito mode.
                                    </div>
                                    <select id="vote-type-select" name="voteType" onchange="voteTypeChanged()">
                                        <option value="once">Vote only once</option>
                                        <option value="many">Vote as many times as you want</option>
                                        <option value="password">Need a password to vote</option>
                                        <!-- Not supported yet -->
                                        <!-- <option value="otp">Need a unique code for each person to vote</option> -->
                                    </select>
                            </div>
                            <br/>
                            <br/>
                            <br/>
                            <button class="btn waves-effect waves-light amber accent-4" type="submit">Create Poll</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script src="js/poll.js"></script>
    </body>
</html>

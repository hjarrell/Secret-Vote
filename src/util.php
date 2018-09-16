<?php

    function get_nav_bar() {
        return '<nav>
            <div class="nav-wrapper">
                <a href="index.php" class="brand-logo truncate">Secret Voting - SHHHHH ITS SECRET</a>
            </div>
        </nav>';
    }

    function get_admin_nav_bar() {
        return '<nav>
            <div class="nav-wrapper">
                <a href="index.php" class="brand-logo truncate">Secret Voting - SHHHHH ITS SECRET</a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="admin.php">Admin</a></li>
                </ul>
            </div>
        </nav>';
    }

    function get_header() {
        return '
        <title>Secret Voting - SHHHHH</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/favicon.ico?" type="image/x-icon">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        ';
    }


?>
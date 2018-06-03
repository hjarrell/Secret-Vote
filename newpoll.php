<?php
session_start(); // Have to start session before html

if ($_SESSION["isadmin"] != true) {
    Header("Location: admin.php");
}
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
  <script>
      "use strict";

      // Creates a new input box for a new poll option
      function newPollOption() {
            var pollOptions = document.getElementById("poll-options");
            var newOptionNumber = parseInt(pollOptions.lastElementChild.id.split("option")[1]) + 1;

            // Make sure someone hasn't changed the poll option numbers
            if (newOptionNumber != null && document.getElementById("option" + newOptionNumber) == null) {
                // Create the div that wraps an input element
                var newOptionDiv = document.createElement("div");
                newOptionDiv.id = "option" + newOptionNumber;

                // Creates the actual input textbox
                var newOptionInput = document.createElement("input");
                newOptionInput.type = "text";
                newOptionInput.name = "option" + newOptionNumber;
                newOptionDiv.appendChild(newOptionInput);

                // Creates the remove option button
                var newOptionDelBtn = document.createElement("button");
                newOptionDelBtn.type = "button";
                // Wraps the function call in another function in order to pass the option number.
                newOptionDelBtn.onclick = function() { removeOption(newOptionNumber); };
                newOptionDelBtn.textContent = "X";
                newOptionDiv.appendChild(newOptionDelBtn);

                pollOptions.appendChild(newOptionDiv);
            } else {
                console.log("Error: Something went wrong parsing option");
            }
      }

      // Delete a poll option
      function removeOption(optionNumber) {
          // Make sure that at least 1 poll option exists
          if (document.getElementById("poll-options").childElementCount > 1) {
            var optionDiv = document.getElementById("option" + optionNumber);
            // Check to make sure that that poll option is even real
            if (optionDiv != null) {
                optionDiv.parentNode.removeChild(optionDiv);
            } else {
                console.log("Error removing option");
            }
          } else {
            alert("You need at least 1 option.");
          }
      }
  </script>
 </head>
 <body>
    <form action="poll.php" method="post" id="poll-form">
        Title: <input type="text" name="title">
        <br/>
        Enter your poll options below
        <br/>
        <div id="poll-options">
            <div id="option1">
                <input type="text" name="option1">
                <button type="button" onclick="removeOption(1);">X</button>
            </div>
        </div>
        <br/>
        <button type="button" onclick="newPollOption();">Add Option</button>
        <br/>
        <br/>
        <input type="submit">
    </form>
</body>
</html>

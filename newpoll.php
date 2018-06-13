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

      // Removes the password input if changing vote types.
      // Added this function because I didn't want to repeat this code 3 times.
      function removePassowordField() {
          if (document.getElementById("password-input-div") !== null) {
              document.getElementById("password-input-div").remove();
          }
      }

      // Called when the vote type was changed so the message and relevant fileds can be populated.
      function voteTypeChanged() {
          // Get what the option was changed to.
          var selectedVoteType = document.getElementById("vote-type-select").value;
          var voteTypeDescr = document.getElementById("vote-type-descr");

          // Actually change the description and add/remove fields as needed.
          switch (selectedVoteType) {
                case "once":
                    removePassowordField();
                    voteTypeDescr.textContent = "This tries to keep the user from voting more than once but is not that secure.\
                        The user can get around this by using multiple browsers or incognito mode.";
                    break;
                case "many":
                    removePassowordField();
                    voteTypeDescr.textContent = "This puts no restrictions on voting at all.";
                    break;
                case "password":
                    voteTypeDescr.textContent = "This puts a password wall but then treats the poll as a voting only once poll.";
                    var passwordInputDiv = document.createElement("div");
                    passwordInputDiv.id = "password-input-div";
                    
                    var passwordDescr = document.createElement("strong");
                    passwordDescr.textContent = "Enter poll password: ";
                    passwordInputDiv.appendChild(passwordDescr);

                    var passwordInput = document.createElement("input");
                    passwordInput.name = "pollPassword";
                    passwordInputDiv.appendChild(passwordInput);

                    document.getElementById("vote-type-div").appendChild(passwordInputDiv);
                    break;
                case "otp":
                    removePassowordField();
                    voteTypeDescr.textContent = "This is the most secure way of voting. Users have a unique key that they have to put in before voting.\
                        You can print these keys and manage who is out of the room and such from the admin page.";
                    break;
          }
      }
  </script>
 </head>
 <body>
    <form action="poll.php" method="post" id="poll-form">
        <strong>Title:</strong>
        <input type="text" name="title">
        <br/>
        <strong>Enter your poll options below</strong>
        <br/>
        <div id="poll-options">
            <div id="option1">
                <input type="text" name="option1">
                <button type="button" onclick="removeOption(1);">X</button>
            </div>
        </div>
        <div id="vote-type-div">
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
        <button type="button" onclick="newPollOption();">Add Option</button>
        <br/>
        <br/>
        <input type="submit">
    </form>
</body>
</html>

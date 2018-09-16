/* jshint browser: true */
/* globals $:false, jQuery:false */
'use strict';

// Setup the options field
$(document).ready(function(){
    $('select').formSelect();
});

// Creates a new input box for a new poll option
function newPollOption() {
    var pollOptions = document.getElementById("poll-options");
    var newOptionNumber = parseInt(pollOptions.lastElementChild.id.split("option")[1]) + 1;

    // Make sure someone hasn't changed the poll option numbers
    if (newOptionNumber != null && document.getElementById("option" + newOptionNumber) == null) {
        // Create the div that wraps an input element
        var newOptionDiv = document.createElement("div");
        newOptionDiv.id = "option" + newOptionNumber;
        newOptionDiv.className = "row";

        var inputDiv = document.createElement("div");
        inputDiv.className = "input-field col s11";
        // Creates the actual input textbox
        var newOptionInput = document.createElement("input");
        newOptionInput.type = "text";
        newOptionInput.name = "option" + newOptionNumber;
        inputDiv.appendChild(newOptionInput);

        var newOptionLabel = document.createElement("label");
        newOptionLabel.setAttribute('for', newOptionDiv.id);
        newOptionLabel.textContent = 'Poll Option';
        inputDiv.appendChild(newOptionLabel);

        newOptionDiv.appendChild(inputDiv);


        var buttonDiv = document.createElement("div");
        buttonDiv.className = "input-field col s1";
        // Creates the remove option button
        var newOptionDelBtn = document.createElement("button");
        newOptionDelBtn.type = "button";
        newOptionDelBtn.className = "btn waves-effect waves-light";
        // Wraps the function call in another function in order to pass the option number.
        newOptionDelBtn.onclick = function() { removeOption(newOptionNumber); };
        newOptionDelBtn.textContent = "X";
        buttonDiv.appendChild(newOptionDelBtn);

        newOptionDiv.appendChild(buttonDiv);

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

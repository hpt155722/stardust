<?php
    include 'utilities/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>stardust - Log in</title>

    <!-- Include stylesheets -->
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="login.css">
    

    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Include Javascript -->
    <script src="login.js"></script>

</head>
<body>
    <!-- LOADING SCREEN -->
    <div class = 'loadingContainer' style = 'display: none'>
        <img class = 'loadingLogo' src = 'resources/images/pinkStar.png'>
    </div>


    <div class = 'contentBox'>
        <img class = 'logo' src = 'resources/images/stardustLogo.png'>
        <!-- LOGIN CONTAINER -->
        <div class = 'loginBox'>
            <input class = 'usernameBox' maxlength = "20" placeholder = 'username'></input>
            <input class = 'passwordBox' type = 'password' maxlength = "30" placeholder = 'password'></input>
            <button onclick = 'login()'> log in </button>
            <p class = 'errorMessage' style = 'display: none'> Error message goes here </p>
            <p class = 'successMessage' style = 'display: none'> Success message goes here </p>
            <div class = 'options'>
                <p class='notAUserLink' onclick='switchToSignup()'> Not a User? <span class=' linkBold'> Create an account. </span> </p>
                <p class = 'link forgotPasswordLink'> Forgot password? </p>
            </div>
        </div>
        <!-- SIGNUP CONTAINER -->
        <div class = 'signupBox' style = 'display: none'>
            <input class = 'emailBox' placeholder = 'enter your email'></input>
            <input class = 'createdUsernameBox' maxlength = "20" placeholder = 'create your username'></input>
            <input class = 'createdPasswordBox' type = 'password' maxlength = "30" placeholder = 'create a password'></input>
            <input class = 'confirmPasswordBox' type = 'password' maxlength = "30" placeholder = 'confirm your password'></input>
            <button onclick = 'signup()'> create your account </button>
            <p class = 'errorMessage' style = 'display: none'> Error message goes here </p>
            <div class = 'options'>
                <p class='alreadyAUser' onclick='switchToLogin()'> Already a User? <span class=' linkBold'> Log in. </span> </p>
            </div>
        </div>
    </div>

    

    <!-- STARS -->

    <img class = 'star1 rotate-center' src = 'resources/images/pinkStar.png'>
    <img class = 'star2 rotate-center2' src = 'resources/images/pinkStar.png'>
    <img class = 'star3 rotate-reverse' src = 'resources/images/pinkStar.png'>

    <img class = 'starOnString1 slide-bottom1' src = 'resources/images/starOnString1.png'>
    <img class = 'starOnString2 slide-up' src = 'resources/images/starOnString2.png'>
    <img class = 'starOnString3 slide-bottom2' src = 'resources/images/starOnString3.png'>

</body>
</html>
//Page startup
$(document).ready(function() {
    $('.loadingContainer').hide();
});
sessionStorage.removeItem('newUser');
if (sessionStorage.getItem('loggedInUsername') !== null) {
    window.location.href = 'pages/home/home.php';
}

function onload() {
    $('.usernameBox').val('');
    $('.passwordBox').val('');

    var startup = sessionStorage.getItem('startup');
    if (sessionStorage.getItem('startup') == null) {
        $('.startUpLogo').show().addClass('puff-in-center');
        setTimeout(() => {
            $('.slogalBox').css('opacity', 100).addClass('typewriter');
        },  1200);
        setTimeout(() => {
            $('.pageContent').show();
            $('.startUpContainer').addClass('fade-out');
        },  4000);
        setTimeout(() => {
            $('.startUpContainer').hide();
        },  4700);
        sessionStorage.setItem('startup', 'false');
    } else {
        $('.pageContent').show();
        $('.startUpContainer').hide();
    }
}

//When user clicks the login button
function login() {
    $(".loadingContainer").show();
    $(".errorMessage").hide();

    var userGivenUsername = $(".usernameBox").val();
    var userGivenPassword = $(".passwordBox").val();

    //Validate login
    if (userGivenUsername == "" || userGivenPassword == "") {
        $(".errorMessage").text("Credentials cannot be empty.");
        $(".errorMessage").show();
    } else {
        $.post("utilities/loginFunction.php", { username: userGivenUsername, password: userGivenPassword }, function (data) {
            if (data.trim() === "Logged in successfully.") {
                sessionStorage.setItem('loggedInUsername', userGivenUsername);
                window.location.href = "pages/home/home.php";
            } else {
                $(".errorMessage").text(data);
                $(".errorMessage").show();
            }
        });
    }
    $(".loadingContainer").hide();
}

function isValidEmail(email) {
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    return emailPattern.test(email);
}

//When user clicks the signup button
function signup() {
    $(".loadingContainer").show();
    $(".errorMessage").hide();

    var userGivenEmail = $(".emailBox").val();
    var createdUsername = $(".createdUsernameBox").val();
    var createdPassword = $(".createdPasswordBox").val();
    var confirmPassword = $(".confirmPasswordBox").val();

    //Validate if input is not blank
    if (userGivenEmail == "" || createdUsername == "" || createdPassword == "" || confirmPassword == "") {
        $(".errorMessage").text("All credentials are required. Please try again.");
        $(".errorMessage").show();
        $(".loadingContainer").hide();
    } else if (!isValidEmail(userGivenEmail)) {
        //Validate if email is in correct format
        $(".errorMessage").text("Not a valid email. Please try again.");
        $(".errorMessage").show();
        $(".loadingContainer").hide();
    } else if (createdPassword != confirmPassword) {
        //Validate if password is confirmed
        $(".errorMessage").text("Passwords do not match. Please try again.");
        $(".errorMessage").show();
        $(".loadingContainer").hide();
    } else if (createdPassword.length < 8) {
        //Validate if password is long enough
        $(".errorMessage").text("Password must be at least 8 characters. Please try again.");
        $(".errorMessage").show();
        $(".loadingContainer").hide();
    } else {
        $.post("utilities/signupFunction.php", { email: userGivenEmail, username: createdUsername, password: createdPassword }, function (data) {
            //If account created successfully
            if (data.trim() === "Account successfully created. Please log in.") {
                $('.emailBox').val('');
                $('.createdUsernameBox').val('');
                $('.createdPasswordBox').val('');
                $('.confirmPasswordBox').val('');

                $(".successMessage").text(data);
                $(".successMessage").show();

                sessionStorage.setItem('loggedInUsername', createdUsername);
                sessionStorage.setItem('newUser', 'true');
                window.location.href = "pages/profileSetup/profileSetup.php";
            } else {
                $(".errorMessage").text(data);
                $(".errorMessage").show();
                $(".loadingContainer").hide();
            }
        });
    }
}

//Hide error when user changes input
$(function () {
    $("input").on("input", function () {
        $(".errorMessage").hide();
        $(".successMessage").hide();
    });
});

//When user switches to login page
function switchToLogin() {
    $(".signupBox").addClass("fade-out");
    setTimeout(() => {
        $(".signupBox").hide();
        $(".errorMessage").hide();
        $('.contentBox').css('top', '20vh');
        $('.contentBox').css('padding: 4vh 4vw 6vh 4vw');
        $(".loginBox").show().addClass("fade-in");
    }, 800);
    setTimeout(() => {
        $(".signupBox").removeClass("fade-out");
        $(".loginBox").removeClass("fade-in");

        $(".usernameBox").val("");
        $(".passwordBox").val("");
    }, 1600);
}

//When user switches to signup page
function switchToSignup() {
    $(".loginBox").addClass("fade-out");
    setTimeout(() => {
        $(".loginBox").hide();
        $(".errorMessage").hide();
        $('.contentBox').css('top', '15vh');
        $('.contentBox').css('padding', '4vh 4vw');
        $(".signupBox").show().addClass("fade-in");
    }, 800);
    setTimeout(() => {
        $(".loginBox").removeClass("fade-out");
        $(".signupBox").removeClass("fade-in");

        $(".emailBox").val("");
        $(".createdUsernameBox").val("");
        $(".createdPasswordBox").val("");
        $(".confirmPasswordBox").val("");
        
    }, 1600);
}

function goToHome() {
    window.location.href = '../home/home.php';
}  
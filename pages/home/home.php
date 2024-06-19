<?php
    session_start();

    if (empty($_SESSION['loggedInUsername'])) {
        echo "<script> window.location.href = '../../login.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Shopping List</title>

	<!-- Include stylesheets -->
	<link rel="stylesheet" href="../../main.css">
	<link rel="stylesheet" href="home.css">

	<!-- Include jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

	<!-- Include Javascript -->
	<script src="home.js"></script>
</head>

<body onload='onload()'>
	<!-- LOADING SCREEN -->
	<div class='loadingContainer'>
		<img class='loadingLogo rotate-center' src='../../resources/images/pinkStar.png'>
	</div>

	<div class='pageContent' style='display: none'>
		<!-- HEADER -->
		<div class='header'>
			<img class='headerLogo' src='../../resources/images/stardustLogoInvert.png'>
		</div>
		<!-- FEED -->
		<!-- SEARCH -->
		<!-- NOTIFICATIONS PAGE -->
		<!-- ACCOUNT PAGE -->
		<div class='accountsPage contentPage'>
			<!-- ACCOUNT SETTINGS PAGE -->
			<div class = 'accountSettings' style = 'display: none'>
				<div class = 'accountSettingsMenu'>
					<button class = 'logoutButton' onclick = 'logout()'> tester 1</button>
					<button class = 'logoutButton' onclick = 'logout()'> tester 2</button>
					<button class = 'logoutButton' onclick = 'logout()'> tester 3</button>
					<button class = 'logoutButton' onclick = 'logout()'> log out</button>
				</div>
				<div class = 'accountSettingsBackground' onclick = 'closeSettings()'></div>
			</div>
			<div class='currentUserInfo'>
				<img class='currentUserProfilePic' src='../../resources/images/defaultProfilePic.png'>
				<div class='currentUserTextInfo'>
					<p class='currentUserUsername'>
						username default
					</p>
					<p class='currentUserBio'>
						no biography yet
					</p>
				</div>
			</div>
			<hr>
			<div class='currentUserPostContainer'>
				<p class='noPostsYet'> no posts yet</p>
			</div>
			<img class='settingsIcon' onclick = 'openSettings()' src='../../resources/images/settingsIcon.png'>
		</div>
		<!-- FOOTER -->
		<div class='footer'>
			<img class='footerIcon feed' onclick='reselectPage(this)' src='../../resources/images/feedIcon.png'>
			<img class='footerIcon search' onclick="reselectPage(this)" src='../../resources/images/searchIcon.png'>
			<img class='footerIcon notifications' onclick="reselectPage(this)" src='../../resources/images/notificationsIcon.png'>
			<img class='footerIcon profile selectedPage' onclick="reselectPage(this)" src='../../resources/images/profileIcon.png'>
		</div>
	</div>


</body>

</html>
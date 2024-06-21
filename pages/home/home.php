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

	<!-- Include Cropper.js CSS and JS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>


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
		<div class='feedPage contentPage' style = 'display: none'>
			<p> feed page here implement later </p>
		</div>
		<!-- SEARCH -->
		<div class='searchPage contentPage' style = 'display: none'>
			<p> search page here implement later</p>
		</div>
		<!-- NOTIFICATIONS PAGE -->
		<div class='notificationsPage contentPage' style = 'display: none'>
			<p> notifications page here implement later</p>
		</div>
		<!-- ACCOUNT PAGE -->
		<div class='accountsPage contentPage'>
			<!-- EDIT BIO PAGE -->
			<div class = 'editBioContainer' style = 'display: none'>
				<div class = 'editBioMenu'>
					<p class = 'editBioLabel'> edit bio </p>
					<textarea class = 'editBioTextArea'> </textarea>
					<button class = 'saveEdittedBio' onclick = 'saveBio()' style = 'display: none'> save bio </button>
				</div>
				<div class = 'editBioBackground' onclick = 'closeEditBio()'></div>
			</div>
			<!-- ACCOUNT SETTINGS PAGE -->
			<div class = 'accountSettingsContainer' style = 'display: none'>
				<div class = 'accountSettingsMenu'>
					<button class = 'editBioButton' onclick = 'closeSettings(); openEditBio()'> edit bio</button>
					<button class = 'menuButton' onclick = 'logout()'> tester 2</button>
					<button class = 'menuButton' onclick = 'logout()'> tester 3</button>
					<button class = 'logoutButton' onclick = 'logout()'> log out</button>
				</div>
				<div class = 'accountSettingsBackground' onclick = 'closeSettings()'></div>
			</div>
			<!-- MAIN ACCOUNT PAGE -->	
			<div class='currentUserInfo'>
				<input type="file" id="fileInput" style="display: none;">
				<img class='currentUserProfilePic' onclick="handleProfilePicUpload()" src='../../resources/images/defaultProfilePic.png'>

				<div class='currentUserTextInfo'>
					<p class='currentUserUsername'>
						username default
					</p>
					<p class='currentUserBio'>
						no biography yet
					</p>
				</div>
			</div>
			<button class='saveProfilePicButton' style='display: none'> save profile picture </button>

			<hr>
			<div class='currentUserPostContainer'>
				<p class='noPostsYet'> no posts yet</p>
			</div>
			<img class='settingsIcon' onclick = 'openSettings()' src='../../resources/images/settingsIcon.png'>
		</div>
		<!-- FOOTER -->
		<div class='footer'>
			<img class='footerIcon feed' onclick="changePage('feed'); reselectPage(this)" src='../../resources/images/feedIcon.png'>
			<img class='footerIcon search' onclick="changePage('search'); reselectPage(this)" src='../../resources/images/searchIcon.png'>
			<img class='footerIcon notifications' onclick="changePage('notifications'); reselectPage(this)" src='../../resources/images/notificationsIcon.png'>
			<img class='footerIcon profile selectedPage' onclick="changePage('accounts'); eselectPage(this)" src='../../resources/images/profileIcon.png'>
		</div>
	</div>


	<!-- Include Javascript -->
	<script src="home.js"></script>

</body>

</html>
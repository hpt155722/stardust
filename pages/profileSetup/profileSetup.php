<?php
    session_start();

    if (empty($_SESSION['loggedInUser'])) {
        echo "<script> window.location.href = '../../login.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>stardust - profile setup</title>

	<link rel="icon" href="../../stardust.ico" type="image/x-icon">


	<!-- Include stylesheets -->
	<link rel="stylesheet" href="../../main.css">
	<link rel="stylesheet" href="profileSetup.css">


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

	<!-- PAGE CONTENT -->
	<div class='pageContent' style='display: none;'>
		<!-- PROFILE PICTURE UPLOAD -->
		<div class='uploadProfileContainer'>
			<p class='greeting' style='opacity: 0;'>hello null!</p>
			<p class='uploadQuestion' style='opacity: 0;'>would you like to upload <br> a profile picture?</p>

			<input type="file" id="fileInput" style="display: none;">
			<img class="uploadProfilePicButton" style='opacity: 0;' onclick="handleProfilePicUpload()" src="../../resources/images/uploadAProfilePicture.png" style="cursor: pointer;">
			<button class='saveProfilePicButton' style='display: none'> save profile picture </button>
			<p class='skipUpload' onclick='goToBio()' style='opacity: 0;'> no, skip this step </p>
		</div>

		<!-- CREATE A BIO -->
		<div class='createBioContainer' style='display: none;'>
			<p class='bioQuestion' style='opacity: 0;'>would you like to <br> create a bio?</p>
			<textarea class="bioBox" spellcheck="false" maxlength="150" placeholder="create a bio..." style='opacity: 0;'></textarea>
			<button class='saveBio' onclick='saveBio()' style='display: none;'> save bio </button>
			<p class='skipBio' onclick='goToHome()' style='opacity: 0;'> no, skip this step </p>
		</div>

	</div>

	<!-- Include Javascript -->
	<script src="profileSetup.js"></script>
	<script src="../../main.js"></script>

</body>

</html>
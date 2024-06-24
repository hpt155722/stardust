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
	<title>stardust - home</title>

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


		<!-- POST VIEW PAGE -->
		<div class='postViewContainer' style='display: none'>
			<div class='postViewMainContainer'>
				<!-- POST VIEW -->
				<div class='postsContainer'>
					<div class='postContainer'>
						<div class='posterInfo'>
							<img class='posterProfilePic' src='../../mater.jpg'>
							<p class='postUsername'>usernameNull</p>
							<img class='postMenu' src='../../resources/images/ellipsis.png' onclick='openEditPost()'>
						</div>
						<img class='postImage' src='../../mater.jpg'>
						<div class='postFooter'>
							<div class='postText'>
								<p class='postCaption'>tester caption b;lah blah</p>
								<p class='postDate'></p>
							</div>
							<div class='postIcons'>
								<p class='postLikeCount'></p>
								<img class='postHeartIcon' data-postID='' src='../../resources/images/unlikedHeart.png' onclick='toggleLike(this);'>
							</div>
						</div>
					</div>
				</div>
				<hr style='width: 90%; margin: 10px 0;'>
				<!-- POST VIEW COMMENTS -->
				<div class = 'commentingContainer'>
					<input class = 'commentingInputBox' maxlength="150" spellcheck = "false" placeholder = 'write a comment...'/>
					<button class = 'postCommentButton' onclick = 'postComment()'> post </button>
				</div>
				<div class='allCommentsContainer'>
					<div class='commentContainer'>
						<img class='commentorProfilePic' src='../../mater.jpg'>
						<div class='commentText'>
							<p class='commentorUsername'> sillygirl24</p>
							<p class='commentorComment'> so cute! i love it</p>
						</div>
						<p class='commentDate'> 06.23.24</p>
					</div>
				</div>

			</div>

			<div class='postViewBackground' onclick='closePostView()'></div>
		</div>
		<!-- FEED -->
		<!-- POST DELETE PAGE -->
		<div class='postEditContainer' style='display: none'>
			<div class='postEditMenu'>
				<button class='deletePost' onclick='deletePost()'> delete this post</button>
			</div>
			<div class='postEditBackground' onclick='closeEditPost()'></div>
		</div>
		<!-- MAIN FEED PAGE -->
		<div class='feedPage contentPage' style='display: none'>
			<div class='feedPagePosts'>
				<div class='postContainer'>
					<div class='posterInfo'>
						<img class='posterProfilePic' src='../../mater.jpg'>
						<p class='postUsername'> testuser </p>
						<img class='postMenu' src='../../resources/images/ellipsis.png'>
					</div>
					<img class='postImage' src='../../mater.jpg'>
					<div class='postFooter'>
						<div class='postText'>
							<p class='postCaption'>tester caption 123 i love pancakes lololol beep boop </p>
							<p class='postDate'>4:35AM • 06.21.24</p>
						</div>
						<div class='postIcons'>
							<p class='postCommentCount'> 7 </p>
							<img class='postCommentIcon' src='../../resources/images/comment.png'>
							<p class='postLikeCount'> 22 </p>
							<img class='postHeartIcon' src='../../resources/images/unlikedHeart.png'>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- SEARCH -->
		<div class='searchPage contentPage' style='display: none'>
			<p> search page here implement later</p>
		</div>
		<!-- NOTIFICATIONS PAGE -->
		<div class='notificationsPage contentPage' style='display: none'>
			<p> notifications page here implement later</p>
		</div>
		<!-- ACCOUNT PAGE -->
		<div class='accountsPage contentPage'>
			<!-- EDIT BIO PAGE -->
			<div class='editBioContainer' style='display: none'>
				<div class='editBioMenu'>
					<p class='editBioLabel'> edit bio </p>
					<textarea class='editBioTextArea'> </textarea>
					<button class='saveEdittedBio' onclick='saveBio()' style='display: none'> save bio </button>
				</div>
				<div class='editBioBackground' onclick='closeEditBio()'></div>
			</div>
			<!-- CHANGE PASSWORD PAGE -->
			<div class='changePasswordContainer' style='display: none'>
				<div class='changePasswordMenu'>
					<p class='changePasswordLabel'> change password</p>
					<input class='currentPasswordBox' type='password' maxlength="30" placeholder='current password'></input>
					<input class='createdPasswordBox' type='password' maxlength="30" placeholder='create a new password'></input>
					<input class='confirmPasswordBox' type='password' maxlength="30" placeholder='confirm your new password'></input>
					<button class='changePasswordConfirmButton' onclick='updatePassword()' style='display: none'> update password </button>
					<p class='updatePasswordErrorMessage' style='display: none'> Error message goes here</p>
					<p class='updatePasswordSuccessMessage' style='display: none'> Success message goes here</p>
				</div>
				<div class='editBioBackground' onclick='closeChangePassword()'></div>
			</div>
			<!-- ACCOUNT SETTINGS PAGE -->
			<div class='accountSettingsContainer' style='display: none'>
				<div class='accountSettingsMenu'>
					<button class='editBioButton' onclick='closeSettings(); openEditBio()'> edit bio</button>
					<button class='changePasswordButton' onclick='openChangePassword()'> change password</button>
					<button class='menuButton' onclick='logout()'> tester 3</button>
					<button class='logoutButton' onclick='logout()'> log out</button>
				</div>
				<div class='accountSettingsBackground' onclick='closeSettings()'></div>
			</div>
			<!-- MAIN ACCOUNT PAGE -->
			<div class='currentUserInfo'>
				<input type="file" id="fileInput" style="display: none;">
				<img class='currentUserProfilePic' onclick="handleProfilePicUpload()" src='../../resources/profilePics/defaultProfilePic.png'>

				<div class='currentUserTextInfo'>
					<div class='usernameAndFollowersCount'>
						<p class='currentUserUsername'>
							username default
						</p>
						<p class='currentUserFollowersInfo'>
							0 followers
						</p>
					</div>

					<p class='currentUserBio'>
						no biography yet
					</p>
				</div>


			</div>
			<button class='saveProfilePicButton' style='display: none'> save profile picture </button>

			<hr>
			<p class='noPostsYet' style='display: none'> no posts yet</p>
			<div class='currentUserPostContainer' style='display: none'>
				<div class='currentUserPostPreviewContainer'>
					<img class='currentUserPostPreview' src='../../mater.jpg'>
					<img class='currentUserPostPreview' src='../../mater.jpg'>
					<img class='currentUserPostPreview' src='../../mater.jpg'>

				</div>
			</div>
			<img class='settingsIcon' onclick='openSettings()' src='../../resources/images/settingsIcon.png'>
		</div>
		<!-- FOOTER -->
		<div class='footer'>
			<img class='footerIcon feed selectedPage' onclick="changePage('feed'); reselectPage(this)" src='../../resources/images/feedIcon.png'>
			<img class='footerIcon search' onclick="changePage('search'); reselectPage(this)" src='../../resources/images/searchIcon.png'>
			<img class='footerIcon notifications' onclick="changePage('notifications'); reselectPage(this)" src='../../resources/images/notificationsIcon.png'>
			<img class='footerIcon profile' onclick="changePage('accounts'); reselectPage(this)" src='../../resources/images/profileIcon.png'>
		</div>
	</div>


	<!-- Include Javascript -->
	<script src="home.js"></script>
	<script src="../../main.js"></script>

</body>

</html>
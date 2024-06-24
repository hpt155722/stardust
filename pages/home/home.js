// Variables
let currentPageOpened;

// Functions

// Change pages
function changePage(pageToOpen) {
	currentPageOpened = pageToOpen;
	$('.loadingContainer').show();

	if (currentPageOpened == "feed") {
		// Load feed page
		loadFeed();

		// Show feed page
		$('.feedPage').show();

		// Hide other pages
		$('.searchPage').hide();
		$('.notificationsPage').hide();
		$('.accountsPage').hide();
	} else if (currentPageOpened == "search") {
		// Show search page
		$('.searchPage').show();

		// Hide other pages
		$('.feedPage').hide();
		$('.notificationsPage').hide();
		$('.accountsPage').hide();

		$('.pageContent').show();
		$('.loadingContainer').hide();
	} else if (currentPageOpened == "notifications") {
		// Show notifications page
		$('.notificationsPage').show();

		// Hide other pages
		$('.feedPage').hide();
		$('.searchPage').hide();
		$('.accountsPage').hide();

		$('.pageContent').show();
		$('.loadingContainer').hide();
	} else if (currentPageOpened == "accounts") {
		// Load accounts page
		loadCurrentUserProfile();

		// Show accounts page
		$('.accountsPage').show();

		// Hide other pages
		$('.feedPage').hide();
		$('.searchPage').hide();
		$('.notificationsPage').hide();
	} else {
		console.log("Unknown currentPageOpened value: " + currentPageOpened);
	}
}

// Page Initialization
function onload() {
	changePage('feed');
}

// Footer Page Selection
function reselectPage(element) {
	$('.footerIcon').removeClass('selectedPage');
	$(element).addClass('selectedPage');
}

// User Profile Operations

// Load current user's profile
function loadCurrentUserProfile() {
	$('.loadingContainer').show();
	$.get('../../utilities/loadCurrentUserProfile.php', function(userData, status) {
		if (userData) {
			var profilePicUrl = "../../resources/profilePics/" + userData.profilePic;
			var img = new Image();

			img.onload = function() {
				$('.currentUserProfilePic').attr('src', profilePicUrl);
				$('.pageContent').show();
				$('.loadingContainer').hide();
			};

			img.onerror = function() {
				console.error('Error loading profile picture:', profilePicUrl);
				$('.pageContent').show();
				$('.loadingContainer').hide();
			};

			img.src = profilePicUrl;

			$('.currentUserUsername').text(userData.username);
			$('.currentUserBio').text(userData.biography);
			$('.currentUserFollowersInfo').text(userData.relationshipCount + ' followers');

		} else {
			sessionStorage.removeItem('loggedInUsername');
			window.location.href = '../../login.php';
		}
	}, 'json')
	.fail(function(xhr, status, error) {
		console.error('Error loading user profile:', status, error);
		$('.loadingContainer').hide();
	});

	$.get('../../utilities/loadCurrentUsersPosts.php', function(data) {
		if (data == "No posts found.") {
			$('.noPostsYet').show();
			$('.currentUserPostContainer').hide();
		} else {
			$('.currentUserPostPreviewContainer').html(data);
			$('.currentUserPostContainer').show();
		}
	});
}

// Settings Operations

// Open settings page
function openSettings() {
	$('.accountSettingsContainer').addClass('fade-in').show();
	setTimeout(() => {
		$('.accountSettingsContainer').removeClass('fade-in');
	}, 400);
}

// Close settings page
function closeSettings() {
	$('.accountSettingsContainer').addClass('fade-out');
	setTimeout(() => {
		$('.accountSettingsContainer').removeClass('fade-out');
		$('.accountSettingsContainer').hide();
	}, 400);
}

// Edit Bio Operations

// Open edit bio page
function openEditBio() {
	$('.saveEdittedBio').hide();
	var bioText = $('.currentUserBio').text();
	$('.editBioTextArea').val(bioText);

	$('.editBioContainer').addClass('fade-in').show();
	setTimeout(() => {
		$('.editBioContainer').removeClass('fade-in');
	}, 400);
}

// Close edit bio page
function closeEditBio() {
	$('.editBioContainer').addClass('fade-out');
	setTimeout(() => {
		$('.editBioContainer').removeClass('fade-out');
		$('.editBioContainer').hide();
		$('.editBioTextArea').val('');
	}, 400);
}

// Show save bio button if user names edits to bio
$(function() {
	$('.editBioTextArea').on('input', function() {
		$('.saveEdittedBio').show();
	});
});

// Save Bio
function saveBio() {
	$('.loadingContainer').show();
	var bioContent = $('.editBioTextArea').val();

	$.post("../../utilities/saveBio.php", {
		bioContent: bioContent
	}, function(data) {
		if (data.trim() === "Bio saved successfully.") {
			$('.currentUserBio').text(bioContent);
			$('.loadingContainer').hide();
			closeEditBio();
		} else {
			console.log(data);
			// REDIRECT TO ERROR PAGE
		}
	});
}

// Change Password Operations

// Open change password page
function openChangePassword() {
	$('.updatePasswordErrorMessage').hide();
	$('.updatePasswordSuccessMessage').hide();
	$('.currentPasswordBox').val('');
	$('.createdPasswordBox').val('');
	$('.confirmPasswordBox').val('');
	$('.changePasswordContainer').addClass('fade-in').show();
	$('.accountSettingsContainer').addClass('fade-out');
	setTimeout(() => {
		$('.changePasswordContainer').removeClass('fade-in');
		$('.accountSettingsContainer').removeClass('fade-out').hide();
	}, 400);
}

// Close change password page
function closeChangePassword() {
	$('.changePasswordContainer').addClass('fade-out');
	setTimeout(() => {
		$('.changePasswordContainer').removeClass('fade-out').hide();
	}, 400);
}

// Function to check if all password fields are filled
function checkPasswordsFilled() {
	var currentPassword = $('.currentPasswordBox').val();
	var newPassword = $('.createdPasswordBox').val();
	var confirmPassword = $('.confirmPasswordBox').val();

	return currentPassword !== '' && newPassword !== '' && confirmPassword !== '';
}

// Function to toggle the visibility of the update password button
function toggleUpdateButton() {
	if (checkPasswordsFilled()) {
		$('.changePasswordConfirmButton').show();
	} else {
		$('.changePasswordConfirmButton').hide();
	}
}

// Bind keyup event to all password input fields
$('.currentPasswordBox, .createdPasswordBox, .confirmPasswordBox').keyup(function() {
	toggleUpdateButton();
	$('.updatePasswordErrorMessage').hide();
	$('.updatePasswordSuccessMessage').hide();
});

// Update Password
function updatePassword() {
	var currentPassword = $('.currentPasswordBox').val();
	var createdPassword = $('.createdPasswordBox').val();
	var errorMessageElement = $('.updatePasswordErrorMessage');
	var successMessageElement = $('.updatePasswordSuccessMessage');

	$.post('../../utilities/updatePassword.php', {
		currentPassword: currentPassword,
		createdPassword: createdPassword
	})
	.done(function(response) {
		if (response === "password updated successfully") {
			$('.currentPasswordBox').val('');
			$('.createdPasswordBox').val('');
			$('.confirmPasswordBox').val('');

			successMessageElement.text(response);
			successMessageElement.show();

		} else {
			errorMessageElement.text(response);
			errorMessageElement.show();
		}
	})
	.fail(function(xhr, status, error) {
		console.error('Error updating password:', error);
		errorMessageElement.text("Error updating password: " + error);
		errorMessageElement.show();
	});
}

// Profile Picture Operations

// Handle profile picture upload
function handleProfilePicUpload() {
	$('#fileInput').click();
}

$('#fileInput').on('change', function() {
	console.log('File input changed');
	var file = this.files[0];
	var reader = new FileReader();

	reader.onload = function(e) {
		var img = new Image();
		img.onload = function() {
			var canvas = document.createElement('canvas');
			var ctx = canvas.getContext('2d');
			var squareSize = Math.min(this.width, this.height);

			canvas.width = squareSize;
			canvas.height = squareSize;

			// Crop image to square
			ctx.drawImage(img, (this.width - squareSize) / 2, (this.height - squareSize) / 2, squareSize, squareSize, 0, 0, squareSize, squareSize);

			// Create a new canvas for resizing
			var resizedCanvas = document.createElement('canvas');
			var resizedCtx = resizedCanvas.getContext('2d');
			resizedCanvas.width = 500;
			resizedCanvas.height = 500;

			// Resize cropped image to 500x500
			resizedCtx.drawImage(canvas, 0, 0, squareSize, squareSize, 0, 0, 500, 500);

			// Convert canvas to data URL and set as src for preview
			var resizedImg = resizedCanvas.toDataURL('image/png');
			$('.currentUserProfilePic').attr('src', resizedImg);
			$('.saveProfilePicButton').show();

			// Store resized image data URL for later use
			$('.saveProfilePicButton').data('resizedImg', resizedImg);
		};

		img.src = e.target.result;
	};

	reader.readAsDataURL(file);
});

$('.saveProfilePicButton').on('click', function() {
	$('.loadingContainer').show();
	$('.saveProfilePicButton').hide();
	var resizedImg = $(this).data('resizedImg');

	if (resizedImg) {
		$.post('../../utilities/uploadProfilePic.php', {
			image: resizedImg
		})
		.done(function(response) {
			console.log('Image uploaded successfully');
			$('.loadingContainer').hide();
		})
		.fail(function(xhr, status, error) {
			console.error('Error uploading image');
		});
	} else {
		console.error('No cropped image available to save.');
	}
});

// Feed Operations

// Load feed
function loadFeed() {
	$('.loadingContainer').show();

	$.get('../../utilities/loadFeed.php', function(response) {
		var tempElement = $('<div>').html(response);

		var hiddenContainer = $('<div>').css('display', 'none').appendTo('body');
		hiddenContainer.append(tempElement);

		function checkResourcesLoaded() {
			var images = hiddenContainer.find('img');
			var totalImages = images.length;
			var loadedImages = 0;

			images.on('load', function() {
				loadedImages++;
				if (loadedImages === totalImages) {
					$('.feedPagePosts').html(tempElement.html());
					hiddenContainer.remove();
					$('.pageContent').show();
					$('.loadingContainer').hide();
				}
			}).each(function() {
				if (this.complete) {
					$(this).trigger('load');
				}
			});

			if (totalImages === 0) {
				$('.feedPagePosts').html(tempElement.html());
				hiddenContainer.remove();
				$('.pageContent').show();
				$('.loadingContainer').hide();
			}
		}

		checkResourcesLoaded();

	}).fail(function(xhr, status, error) {
		console.error('Error fetching data:', error);
	});
}

// Post Operations

// Edit post
let postCurrentlyEditting;

function openEditPost(postID) {
	postCurrentlyEditting = postID;
	$('.postEditContainer').addClass('fade-in').show();
	setTimeout(() => {
		$('.postEditContainer').removeClass('fade-in');
	}, 400);
}

function closeEditPost() {
	$('.postEditContainer').addClass('fade-out');
	setTimeout(() => {
		$('.postEditContainer').removeClass('fade-out');
		$('.postEditContainer').hide();
	}, 400);
}

// Delete post
function deletePost() {
	$.get("../../utilities/deletePost.php", {
		postID: postCurrentlyEditting
	}, function(data, status) {
		console.log("Data: " + data + "\nStatus: " + status);
		loadFeed();
		closeEditPost();
		closePostView();
	});
}

// Like Operations

// Like or unlike a post
function toggleLike(img) {
	var postID = img.getAttribute('data-postID');
	var src = img.getAttribute('src');
	var likedHeart = '../../resources/images/likedHeart.png';
	var unlikedHeart = '../../resources/images/unlikedHeart.png';

	var likesCountElement = img.previousElementSibling;

	if (!likesCountElement) {
		console.log('Likes count element not found!');
		return;
	}

	var likesCount = parseInt(likesCountElement.textContent);

	if (src === unlikedHeart) {
		$.post('../../utilities/addLike.php', {
			postID: postID
		}, function(response) {
			if (response === 'Liked successfully!') {
				img.setAttribute('src', likedHeart);
				likesCount++;
				likesCountElement.textContent = likesCount;
			} else {
				console.log('Error adding like: ' + response);
			}
		});
	} else if (src === likedHeart) {
		$.post('../../utilities/removeLike.php', {
			postID: postID
		}, function(response) {
			if (response === 'Like removed successfully!') {
				img.setAttribute('src', unlikedHeart);
				likesCount--;
				likesCountElement.textContent = likesCount;
			} else {
				console.log('Error removing like');
			}
		});
	}
}

// Post View Operations

// Open post view page
var currentScroll;
function openPostView() {
    // Apply 'fixed' positioning to the feed and set its top position
	currentScroll = $('.pageContent').offset().top - $(window).scrollTop() + 'px';
    $('.feedPage').css({
        'position': 'fixed',
        'top': currentScroll,
    });

    $('.postViewContainer').show();
    $('.postViewMainContainer').addClass('slide-in-right').show();
    $('.postViewBackground').addClass('fade-in').show();
    setTimeout(() => {
        $('.postViewMainContainer').removeClass('slide-in-right');
        $('.postViewBackground').removeClass('fade-in');
    }, 400);
}

// Close post view page
function closePostView() {
    scrollBackToCurrentPost(function() {
        // This code will execute after scrollBackToCurrentPost has finished
        $('.postViewBackground').addClass('fade-out');
        $('.postViewMainContainer').addClass('slide-out-right');
        setTimeout(() => {
            $('.postViewBackground').removeClass('fade-out');
            $('.postViewMainContainer').removeClass('slide-out-right');
            $('.postViewContainer').hide();
        }, 400);
    });
}

function scrollBackToCurrentPost(callback) {
    $('.feedPage').css({
        'position': 'relative',
        'top': '0px',
    });

    var scrollValue = parseInt(currentScroll, 10); 
    scrollValue = Math.abs(scrollValue); 

    var currentScrollTop = $(window).scrollTop();
    $('html, body').scrollTop(currentScrollTop + scrollValue);
    
    // Call the callback immediately after scrolling
    callback();
}

// Load post view
let currentlyOpenPost;
function loadPostView(postID) {
	currentlyOpenPost = postID;
	$.get("../../utilities/loadPostView.php", {
		postID: postID
	}, function(response) {
		if (response.error) {
			console.error("Error fetching post:", response.error);
		} else {
			$(".postsContainer").html(response.postData);
			$(".allCommentsContainer").html(response.commentsData);
			openPostView();
		}
	}, 'json');
}

//Post comment function
function postComment() {
    if ($('.commentingInputBox').val() != '') {
        $.post('../../utilities/postComment.php', {
            commentText: $('.commentingInputBox').val(),
            postID: currentlyOpenPost
        }, function(response) {
            // Successful response
            $(".allCommentsContainer").html(response);
        }).fail(function(xhr, textStatus, errorThrown) {
            // Error occurred
            console.error("Error: " + errorThrown);
            alert("Error posting comment. Please try again later.");
        }).always(function() {
            // Clear the input box after sending the comment
            $('.commentingInputBox').val('');
        });
    }
}


// Logout
function logout() {
	$.post('../../utilities/logout.php', function(response) {
		sessionStorage.removeItem('newUser');
		sessionStorage.removeItem('loggedInUsername');
		window.location.href = '../../login.php';
	})
	.fail(function(xhr, status, error) {
		console.error('Error logging out:', error);
	});
}

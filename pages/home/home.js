let currentPageOpened;

//Change pages
function changePage(pageToOpen) {
	currentPageOpened = pageToOpen
	$('.loadingContainer').show();

	if (currentPageOpened == "feed") {
		//Load feed page
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
		//Load accounts page
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

function onload() {
	changePage('feed');
}

//Functions for footer page select
function reselectPage(element) {
	$('.footerIcon').removeClass('selectedPage');
	$(element).addClass('selectedPage');
}

// Load current user's profile
function loadCurrentUserProfile() {
	$('.loadingContainer').show();
    $.get('../../utilities/loadCurrentUserProfile.php', function(userData, status) {
        // Load current user profile
        if (userData) {
            var profilePicUrl = "../../resources/profilePics/" + userData.profilePic;
            var img = new Image();

            img.onload = function() {
                // Image has loaded, set the source and hide loading container
                $('.currentUserProfilePic').attr('src', profilePicUrl);
				//Display page
				$('.pageContent').show();
				$('.loadingContainer').hide();	            };

            img.onerror = function() {
                // In case of image loading error
                console.error('Error loading profile picture:', profilePicUrl);
				//Display page
				$('.pageContent').show();
				$('.loadingContainer').hide();	            };

            img.src = profilePicUrl;

            $('.currentUserUsername').text(userData.username);
            $('.currentUserBio').text(userData.biography);
            
            // Display relationship count
            $('.currentUserFollowersInfo').text(userData.relationshipCount + ' followers');
			
		} else {
            // Handle case where user data is not available
            sessionStorage.removeItem('loggedInUsername');
            window.location.href = '../../login.php';
        }
    }, 'json')
    .fail(function(xhr, status, error) {
        // Handle AJAX error
        console.error('Error loading user profile:', status, error);
        // You can add additional error handling as needed
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


//Open up settings page
function openSettings() {
	$('.accountSettingsContainer').addClass('fade-in').show();
	setTimeout(() => {
		$('.accountSettingsContainer').removeClass('fade-in');
	}, 400);
}

//Close up settings page
function closeSettings() {
	$('.accountSettingsContainer').addClass('fade-out');
	setTimeout(() => {
		$('.accountSettingsContainer').removeClass('fade-out');
		$('.accountSettingsContainer').hide();
	}, 400);
}

//Open up edit bio page
function openEditBio() {
	$('.saveEdittedBio').hide();
	var bioText = $('.currentUserBio').text();
	$('.editBioTextArea').val(bioText);

	$('.editBioContainer').addClass('fade-in').show();
	setTimeout(() => {
		$('.editBioContainer').removeClass('fade-in');
	}, 400);
}

//Close up edit bio page
function closeEditBio() {
	$('.editBioContainer').addClass('fade-out');
	setTimeout(() => {
		$('.editBioContainer').removeClass('fade-out');
		$('.editBioContainer').hide();
		$('.editBioTextArea').val('');
	}, 400);
}

//Show save bio button if user names edits to bio
$(function() {
	$('.editBioTextArea').on('input', function() {
		$('.saveEdittedBio').show();
	});
});

//When user updates bio
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
			//REDIRECT TO ERROR PAGE
		}
	});
}

//Open up change password page
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

//Close up change password page
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

//When user clicks update password 
function updatePassword() {
    var currentPassword = $('.currentPasswordBox').val();
    var createdPassword = $('.createdPasswordBox').val();
    var errorMessageElement = $('.updatePasswordErrorMessage');
    var successMessageElement = $('.updatePasswordSuccessMessage');

    // Send data using $.post()
    $.post('../../utilities/updatePassword.php', {
        currentPassword: currentPassword,
        createdPassword: createdPassword
    })
    .done(function(response) {
        // Handle success response
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
        // Handle error response
        console.error('Error updating password:', error);
        errorMessageElement.text("Error updating password: " + error);
        errorMessageElement.show();
    });
}




/* UPLOAD PROFILE PICTURE */

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

//Load feed 
function loadFeed() {
    $('.loadingContainer').show();

    $.get('../../utilities/loadFeed.php', function(response) {
        var tempElement = $('<div>').html(response);

        // Append the temporary element to a hidden container
        var hiddenContainer = $('<div>').css('display', 'none').appendTo('body');
        hiddenContainer.append(tempElement);

        // Function to check if all resources are loaded
        function checkResourcesLoaded() {
            var images = hiddenContainer.find('img');
            var totalImages = images.length;
            var loadedImages = 0;

            images.on('load', function() {
                loadedImages++;
                if (loadedImages === totalImages) {
                    // All images are loaded
                    $('.feedPage.contentPage').html(tempElement.html());
                    hiddenContainer.remove();
                    $('.pageContent').show();
                    $('.loadingContainer').hide();
                }
            }).each(function() {
                // Check if images are already loaded (for cached images)
                if (this.complete) {
                    $(this).trigger('load');
                }
            });

            // If there are no images to load, proceed
            if (totalImages === 0) {
                $('.feedPage.contentPage').html(tempElement.html());
                hiddenContainer.remove();
                $('.pageContent').show();
                $('.loadingContainer').hide();
            }
        }

        // Check if all resources (images) are loaded
        checkResourcesLoaded();

    }).fail(function(xhr, status, error) {
        console.error('Error fetching data:', error);
    });
}



//Open edit post
let postCurrentlyEditting;
function openEditPost(postID) {
    postCurrentlyEditting = postID;
    $('.postEditContainer').addClass('fade-in').show();
	setTimeout(() => {
		$('.postEditContainer').removeClass('fade-in');
	}, 400);
}

//Close up settings page
function closeEditPost() {
	$('.postEditContainer').addClass('fade-out');
	setTimeout(() => {
		$('.postEditContainer').removeClass('fade-out');
		$('.postEditContainer').hide();
	}, 400);
}

function deletePost() {
    $.get("../../utilities/deletePost.php", { postID: postCurrentlyEditting }, function(data, status) {
        console.log("Data: " + data + "\nStatus: " + status);
        loadFeed();
        closeEditPost();
		closePostView();
    });

}

//Like or unlike a post
function toggleLike(img) {
    var postID = img.getAttribute('data-postID');
    var src = img.getAttribute('src');
    var likedHeart = '../../resources/images/likedHeart.png';
    var unlikedHeart = '../../resources/images/unlikedHeart.png';

    // Find the element displaying the likes count (assuming it's directly above the img)
    var likesCountElement = img.previousElementSibling;
    
    if (!likesCountElement) {
        console.log('Likes count element not found!');
        return;
    }

    // Get current likes count as integer
    var likesCount = parseInt(likesCountElement.textContent);

    // Determine action based on the current state (liked or unliked)
    if (src === unlikedHeart) {
        // User wants to like the post
        $.post('../../utilities/addLike.php', { postID: postID }, function(response) {
            if (response === 'Liked successfully!') {
                img.setAttribute('src', likedHeart);
                likesCount++; // Increment likes count
                likesCountElement.textContent = likesCount; // Update displayed likes count
            } else {
                // Handle error if necessary
                console.log('Error adding like: ' + response);
            }
        });
    } else if (src === likedHeart) {
        // User wants to unlike the post
        $.post('../../utilities/removeLike.php', { postID: postID }, function(response) {
            if (response === 'Like removed successfully!') {
                img.setAttribute('src', unlikedHeart);
                likesCount--; // Decrement likes count
                likesCountElement.textContent = likesCount; // Update displayed likes count
            } else {
                // Handle error if necessary
                console.log('Error removing like');
            }
        });
    }
}

//Open up post view page
function openPostView() {
	$('.postViewContainer').show();
	$('.postViewMainContainer').addClass('slide-in-right').show();
	$('.postViewBackground').addClass('fade-in').show();
	setTimeout(() => {
		$('.postViewMainContainer').removeClass('slide-in-right');
		$('.postViewBackground').removeClass('fade-in');
	}, 400);
}

//Close up post view page
function closePostView() {
	$('.postViewBackground').addClass('fade-out');
	$('.postViewMainContainer').addClass('slide-out-right');
	setTimeout(() => {
		$('.postViewBackground').removeClass('fade-out');
		$('.postViewMainContainer').removeClass('slide-out-right');	
		$('.postViewContainer').hide();
	}, 400);
}

function loadPostView(postID) {
	$.get("../../utilities/loadPostView.php", { postID: postID }, function(response) {
		if (response.error) {
			console.error("Error fetching post:", response.error);
		} else {
			$(".postViewMainContainer").html(response.postData); // Set postData to postViewMainContainer

			var commentsContainer = $("<hr style='width: 90%; margin: 10px 0;'>").append("<div class='allCommentsContainer'>" + response.commentsData + "</div>");
			$(".postViewMainContainer").append(commentsContainer);
			openPostView();
		}
	}, 'json');
	
}

//Logout
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


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

		//Change footer
		reselectPage($('.feed'));

		// Show feed page
		$('.feedPage').show();

		// Hide other pages
		$('.searchPage').hide();
		$('.notificationsPage').hide();
		$('.accountsPage').hide();
	} else if (currentPageOpened == "search") {
		// Show search page
		loadNewUser();
		$('.searchPage').show();

		//Change footer
		reselectPage($('.search'));
				
		// Hide other pages
		$('.feedPage').hide();
		$('.notificationsPage').hide();
		$('.accountsPage').hide();

		$('.pageContent').show();
		$('.loadingContainer').hide();
	} else if (currentPageOpened == "notifications") {
		// Show notifications page
		loadNotificationsPage();
		$('.notificationsPage').show();

		//Change footer
		reselectPage($('.notifications'))

		// Hide other pages
		$('.feedPage').hide();
		$('.searchPage').hide();
		$('.accountsPage').hide();

		$('.pageContent').show();
		$('.loadingContainer').hide();
	} else if (currentPageOpened == "accounts") {
		// Load accounts page
		loadCurrentUserProfile();

		//Change footer
		reselectPage($('.profile'))

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
			$('.accountsPage .noPostsYet').show();
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
					$('.feedPage').html(tempElement.html());
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
				$('.feedPage').html(tempElement.html());
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

function openEditPost(postID, pageOpenedEditPostOn) {
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
		if (currentPageOpened == 'feed') {
			loadFeed();
			closeEditPost();
		}
		if (currentPageOpened == 'accounts') {
			closePostView();
			closeEditPost();
			changePage('accounts');
		}
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
function openPostView() {
	if (profileOpened != null) {
		$('.backToFeedButton').text('back to profile');
		closeProfileView('fromSearchUser');

	}
	if (currentPageOpened == 'feed') {
		$('.backToFeedButton').text('back to feed');
	}
	if (currentPageOpened == 'account') {
		$('.backToFeedButton').text('back to account');
	}
	$('.footer').addClass('slide-out-bottom');
    $('.'+currentPageOpened+'Page').addClass('slide-out-left');
    $('.postViewPage').addClass('slide-in-right').show().css('position', 'fixed');

    setTimeout(() => {
		$('.'+currentPageOpened+'Page').removeClass('slide-out-left').hide();
        $('.postViewPage').removeClass('slide-in-right').css('position', 'absolute');;
		$('.footer').removeClass('slide-out-bottom').hide();
    }, 400);
}



// Close post view page
var closedFromSearch = false;
function closePostView() {
	console.log("closePostView: " + openedFromSearch );
	if (openedFromSearch) { //If post view is from search
			closedFromSearch = true;
			loadProfileView(profileOpened);
			$('.backToFeedButton').text('back to search');
			$('.postViewPage').addClass('slide-out-right');
			$('.profileViewPage').addClass('slide-in-left').show();
	
			setTimeout(() => {
				$('profileViewPage').removeClass('slide-in-left');
				$('.postViewPage').removeClass('slide-out-right').hide();
				closedFromSearch = false;
			}, 400);
		} else {
			if (currentPageOpened == 'feed') {
				loadFeed();
				$('.postViewPage').addClass('slide-out-right');
				$('.'+currentPageOpened+'Page').addClass('slide-in-left').show();
				$('.footer').addClass('slide-in-bottom').show();
				setTimeout(() => {
					$('.footer').removeClass('slide-in-bottom');
					$('.'+currentPageOpened+'Page').removeClass('slide-in-left');
					$('.postViewPage').removeClass('slide-out-right').hide();
					scrollToCurrentPost();
				}, 400);
			} else {
				$('.postViewPage').addClass('slide-out-right');
				$('.'+currentPageOpened+'Page').addClass('slide-in-left').show();
				$('.footer').addClass('slide-in-bottom').show();
		
				setTimeout(() => {
					$('.footer').removeClass('slide-in-bottom');
					$('.'+currentPageOpened+'Page').removeClass('slide-in-left');
					$('.postViewPage').removeClass('slide-out-right').hide();
				}, 400);
			}
		}
}

// Scroll to current post and invoke callback when done
function scrollToCurrentPost() {
	console.log(currentlyOpenPost);
	loadFeed(); 
    let elementId = 'post' + currentlyOpenPost;

    let $element = $('#' + elementId);
    
    if ($element.length) {
        let targetScrollTop = $element.offset().top - 80;
        $('html, body').scrollTop(targetScrollTop);
    } else {
        console.error('Element with ID ' + elementId + ' not found.');
    }
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

// Create a Post Operations

// Open create a post page
function openCreateAPostPage() {
	$('.createACaption').hide();
	$('.createPostButton').hide();
	$('#createPostFileInput').val(''); 
	$('#croppedImage').hide();
	$('.uploadImageButton').show();
	$('.createACaption').val('');


	$('.footer').addClass('slide-out-bottom');
    $('.accountsPage').addClass('slide-out-left');
    $('.createAPostPage').addClass('slide-in-right').show();

    setTimeout(() => {
		$('.accountsPage').removeClass('slide-out-left').hide();
        $('.createAPostPage').removeClass('slide-in-right');
		$('.footer').removeClass('slide-out-bottom').hide();
    }, 400);
}

// Close create a post page
function closeCreateAPostPage() {
	$('#croppedImage').croppie('destroy');
	$('.createAPostPage').addClass('slide-out-right');
	$('.footer').addClass('slide-in-bottom').show();
	$('.accountsPage').addClass('slide-in-left').show();

	setTimeout(() => {
		$('.footer').removeClass('slide-in-bottom');
		$('.accountsPage').removeClass('slide-in-left');
		$('.createAPostPage').removeClass('slide-out-right').hide();
	}, 400);
}
// Function to handle image upload
function handleImageUpload() {
    $('#createPostFileInput').click();
}

// Function to process selected image using Croppie
$('#createPostFileInput').change(function() {
    $('.loadingContainer').show();
    $('.uploadImageButton').hide();
    $('#croppedImage').show();

    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var image = new Image();
            image.src = e.target.result;
			$('#croppedImage').croppie('destroy');

            $(image).on('load', function() {
                // Initialize Croppie
                var $croppedImage = $('#croppedImage');
                $croppedImage.croppie({
                    viewport: {
                        width: 250,
                        height: 250,
                    },
                    boundary: {
                        width: 250,
                        height: 250
                    }
                });
                $croppedImage.croppie('bind', {
                    url: e.target.result
                });

                // Set cropContainer size to a square
                var size = Math.min(image.width, image.height);
                var $cropContainer = $('#cropContainer');
                $cropContainer.width(size);
                $cropContainer.height(size);

                // Set up Croppie events
                $croppedImage.on('update.croppie', function(ev, data) {
                    // Update the display of the cropped image
                    var $croppedResult = $('#croppedResult');
                    $croppedResult.empty();
                    $croppedResult.append($('<img>', { src: data.image }));

                    // Hide loading container when everything is loaded
                    $('.loadingContainer').hide();
					$('body').css('zoom', '100%');

                });

                // Show additional UI elements
                $('.createACaption').show();
                $('.createPostButton').show();
            });
        };
        reader.readAsDataURL(file);
    }
	 else {
		$('.loadingContainer').hide();

	 }
});

// Function to crop image using Croppie
function uploadPost() {
    $('.createPostButton').hide();
    $('.loadingContainer').show();

    $('#croppedImage').croppie('result', {
        type: 'base64',
        size: { width: 800, height: 800 }, 
        format: 'jpeg'
    }).then(function(croppedData) {
        createAPost(croppedData);
        // Destroy Croppie instance after uploading
        $('#croppedImage').croppie('destroy');
		$('.loadingContainer').hide();

    });
}

function createAPost(croppedData){
    var caption = $('.createACaption').val();
    console.log(croppedData);

    if (croppedData) {
        $.post('../../utilities/createAPost.php', { image: croppedData, caption:caption })
        .done(function(response) {
            console.log(response);
            $.get('../../utilities/loadCurrentUsersPosts.php', function(data) {
                if (data == "No posts found.") {
                    $('.accountsPage .noPostsYet').show();
                    $('.currentUserPostContainer').hide();
                } else {
                    $('.currentUserPostPreviewContainer').html(data);
                    $('.currentUserPostContainer').show();
                }
            });

            closeCreateAPostPage();
        })
        .fail(function(xhr, status, error) {
            console.error('Error uploading image');
        });
    } else {
        console.error('No cropped image available to save.');
    }
}

//Delete comment operations

var currentlyOpenComment;
// Open delete comment page
function openDeleteComment(commentID) {
	currentlyOpenComment = commentID;
	$('.deleteCommentContainer').addClass('fade-in').show();
	setTimeout(() => {
		$('.deleteCommentContainer').removeClass('fade-in');
	}, 400);
}

// Close  delete comment page
function closeDeleteComment() {
	$('.deleteCommentContainer').addClass('fade-out');
	setTimeout(() => {
		$('.deleteCommentContainer').removeClass('fade-out').hide();
	}, 400);
}

function deleteComment() {
    $.get("../../utilities/deleteComment.php", {
        commentID: currentlyOpenComment,
		postID: currentlyOpenPost
    })
    .done(function(response) {
		$(".allCommentsContainer").html(response);
		closeDeleteComment(); // Close the delete comment UI or perform other actions

    })
    .fail(function(xhr, textStatus, errorThrown) {
        console.error("Error deleting comment:", errorThrown);
    });
}

//Notifications Page Operations

//Load Notifications Page 
function loadNotificationsPage() {
    $.get("../../utilities/loadNotificationsPage.php", {})
    .done(function(response) {
        $('.notificationsPage').html(response);
    })
    .fail(function(xhr, textStatus, errorThrown) {
        console.error("Error loading notifications:", errorThrown);
    });
}

//Profile View Operations

// Open profileView page
var profileOpened = null;
function openProfileView() {
	if (currentPageOpened == 'feed') {
		$('.backToFeedButton').text('back to feed');
	}
	if (currentPageOpened == 'search') {
		$('.backToFeedButton').text('back to search');
	}
	$('.footer').addClass('slide-out-bottom');
	$('.'+currentPageOpened + 'Page').addClass('slide-out-left');
    $('.profileViewPage').addClass('slide-in-right').show().css('position', 'fixed');

    setTimeout(() => {
        $('.profileViewPage').removeClass('slide-in-right').css('position', 'absolute');
		$('.footer').removeClass('slide-out-bottom').hide();
		$('.'+currentPageOpened + 'Page').removeClass('slide-out-left').hide();
    }, 400);
}

// Close profile view page
function closeProfileView(fromWhere) {
	if (fromWhere == null) {
		profileOpened = null;
		$('.footer').addClass('slide-in-bottom').show();
		$('.profileViewPage').addClass('slide-out-right');
		$('.'+currentPageOpened + 'Page').addClass('slide-in-left').show();

		setTimeout(() => {
			$('.footer').removeClass('slide-in-bottom');
			$('.profileViewPage').removeClass('slide-out-right').hide();
			$('.'+currentPageOpened + 'Page').removeClass('slide-in-left');
		}, 400);
	}
	if (fromWhere == 'fromSearchUser') {
		$('.footer').addClass('slide-in-bottom').show();
		$('.profileViewPage').addClass('slide-out-left');

		setTimeout(() => {
			$('.footer').removeClass('slide-in-bottom');
			$('.profileViewPage').removeClass('slide-out-left').hide();
		}, 400);
	}
}

var openedFromSearch = false;
function loadProfileView(userID, fromSearch) {
	openedFromSearch = fromSearch;
	profileOpened = userID;

	$('.profileViewPage .noPostsYet').hide();
    // Load user profile information
    $.get("../../utilities/loadUser.php", { userID: userID })
        .done(function(response) {
            $('.userInfo').html(response);
        })
        .fail(function(xhr, textStatus, errorThrown) {
            console.error("Error loading user:", errorThrown);
        });

    // Load user's posts
    $.get("../../utilities/loadUserPosts.php", { userID: userID, fromSearch: fromSearch })
        .done(function(response) {
			if (response === 'No posts found.') {
				$('.profileViewPage .noPostsYet').show();
				$('.userPostContainer').hide();
			} else {
				$('.userPostPreviewContainer').html(response);
				$('.userPostContainer').show();
				$('.profileViewPage .noPostsYet').hide();
			}
			console.log(closedFromSearch === false);

			if (closedFromSearch === false) {
            	openProfileView(); 
			}
        })
        .fail(function(xhr, textStatus, errorThrown) {
            console.error("Error loading user's posts:", errorThrown);
        });
}

// Follow or unfollow a user
function toggleFollow(userID) {
    var follow = '../../resources/images/follow.png';
    var following = '../../resources/images/following.png';
    var $img = $('.followButton');

    var $followersInfo = $('.userFollowersInfo'); 

    var followersInfoText = $followersInfo.text().trim();
    var parts = followersInfoText.split(' ');
    var followersCount = parseInt(parts[0]);

    if ($img.attr('src') === follow) {
        $.post('../../utilities/addFollower.php', {
            userID: userID
        }, function(response) {
            if (response === 'Follower added successfully!') {
                $img.attr('src', following);
                followersCount++;
                $followersInfo.text(followersCount + ' followers');
				$('.followButton').addClass('following');
            } else {
                console.log(response);
            }
        });
    } else if ($img.attr('src') === following) {
        $.post('../../utilities/removeFollower.php', {
            userID: userID
        }, function(response) {
            if (response === 'Follower removed successfully!') {
                $img.attr('src', follow);
                followersCount--;
                $followersInfo.text(followersCount + ' followers');
				$('.followButton').removeClass('following');
            } else {
                console.log(response);
            }
        });
    }
}

//Search Operations

$('.searchInput').on('input', function() {
	// Check if the input field is not empty
	if ($(this).val().trim() !== '') {
		searchUser();
	} else {
		loadNewUser();	
	}	
});

function searchUser() {
	$('.newUserLabel').hide();
	$('.newUsers').hide();


	$.get("../../utilities/searchUser.php", { searchquery: $('.searchInput').val()})
	.done(function(response) {
		$('.searchResults').html(response);
		$('.searchResults').show();
	})
	.fail(function(xhr, textStatus, errorThrown) {
		console.error("Error loading user:", errorThrown);
	});
}

function loadNewUser() {
	$('.searchResults').hide();
	$('.newUserLabel').show();

	$.get("../../utilities/loadNewUsers.php", { })
	.done(function(response) {
		$('.newUsers').html(response);
		$('.newUsers').show();
	})
	.fail(function(xhr, textStatus, errorThrown) {
		console.error("Error loading user:", errorThrown);
	});
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


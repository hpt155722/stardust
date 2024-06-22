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

		$('.loadingContainer').hide();
	} else if (currentPageOpened == "search") {
		// Show search page
		$('.searchPage').show();

		// Hide other pages
		$('.feedPage').hide();
		$('.notificationsPage').hide();
		$('.accountsPage').hide();

		$('.loadingContainer').hide();
	} else if (currentPageOpened == "notifications") {
		// Show notifications page
		$('.notificationsPage').show();

		// Hide other pages
		$('.feedPage').hide();
		$('.searchPage').hide();
		$('.accountsPage').hide();

		$('.loadingContainer').hide();
	} else if (currentPageOpened == "accounts") {
		// Show accounts page
		$('.accountsPage').show();

		// Hide other pages
		$('.feedPage').hide();
		$('.searchPage').hide();
		$('.notificationsPage').hide();

		$('.loadingContainer').hide();
	} else {
		console.log("Unknown currentPageOpened value: " + currentPageOpened);
	}

}

function onload() {
	loadCurrentUserProfile();
	$('.pageContent').show();
	//Testing
	$('.currentUserBio').text("saklklklklkldjalksdjaklsjdkalsjdkalsjdkalsjdkasld");
	changePage('feed');
}

//Functions for footer page select
function reselectPage(element) {
	$('.footerIcon').removeClass('selectedPage');
	$(element).addClass('selectedPage');
}

// Load current user's profile
function loadCurrentUserProfile() {
	$.get('../../utilities/loadCurrentUserProfile.php', function(userData, status) {
			// Load current user profile
			if (userData) {
				var profilePicUrl = "../../resources/profilePics/" + userData.profilePic;
				var img = new Image();

				img.onload = function() {
					// Image has loaded, set the source and hide loading container
					$('.currentUserProfilePic').attr('src', profilePicUrl);
					$('.loadingContainer').hide();
				};

				img.onerror = function() {
					// In case of image loading error
					console.error('Error loading profile picture:', profilePicUrl);
					$('.loadingContainer').hide();
				};

				img.src = profilePicUrl;

				$('.currentUserUsername').text(userData.username);
				$('.currentUserBio').text(userData.biography);
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
	$.get('../../utilities/loadFeed.php', function(response) {
			$('.feedPage.contentPage').html(response);
			$('.feedPage.contentPage').show();
		})
		.fail(function(xhr, status, error) {
			//SHOW ERROR PAGE
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


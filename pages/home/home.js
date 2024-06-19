function onload() {
    loadCurrentUserProfile();
    $('.pageContent').show();
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
    $('.accountSettings').addClass('fade-in').show();
    setTimeout(() => {
        $('.accountSettings').removeClass('fade-in');
    }, 400);
}

//Close up settings page
function closeSettings() {
    $('.accountSettings').addClass('fade-out');
    setTimeout(() => {
        $('.accountSettings').removeClass('fade-out');
        $('.accountSettings').hide();
    }, 400);
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
function onload () {
    /*if (sessionStorage.getItem('newUser') == null) {
        window.location.href = '../home/home.php';
        
    }*/
    sessionStorage.removeItem('newUser');
    $('.loadingContainer').hide();
    $('.pageContent').show();

    let loggedInUsername = sessionStorage.getItem('loggedInUsername');
    $('.greeting').html('hello ' + loggedInUsername + ', <br> welcome to stardust!');

    setTimeout(() => {
        $('.greeting').css('opacity','100').addClass('fade-in');
    }, 500);
    setTimeout(() => {
        $('.uploadQuestion').css('opacity','100').addClass('fade-in');
        $('.greeting').css('opacity', '').removeClass('fade-in');
    }, 1500);
    setTimeout(() => {
        $('.uploadProfilePicButton').css('opacity','100').addClass('fade-in');
        $('.uploadQuestion').css('opacity', '').removeClass('fade-in');
    }, 2200);
    setTimeout(() => {
        $('.skipUpload').css('opacity','100').addClass('fade-in');
        $('.uploadProfilePicButton').css('opacity', '').removeClass('fade-in');
    }, 3200);
    setTimeout(() => {
        $('.skipUpload').css('opacity', '').removeClass('fade-in');
    }, 3900);
}

function goToBio () {
    $('.uploadProfileContainer').addClass('fade-out');

    setTimeout(() => {
        $('.uploadProfileContainer').hide();
        $('.createBioContainer').show();
        $('.bioQuestion').show().addClass('fade-in');
    }, 1000);
    setTimeout(() => {
        $('.bioBox').css('opacity','100').addClass('fade-in');
        $('.bioQuestion').css('opacity', '').removeClass('fade-in');
    }, 1700);
    setTimeout(() => {
        $('.bioBox').css('opacity', '').removeClass('fade-in');
        $('.skipBio').css('opacity','100').addClass('fade-in');
    }, 2400);
    setTimeout(() => {
        $('.skipBio').css('opacity', '').removeClass('fade-in');
    }, 3200);
}

$(function () {
    $('.bioBox').on('input', function() {
        if ($(this).val().trim() !== '') {
          $('.saveBio').show();
        } else {
          $('.saveBio').hide();
        }
    });
});

function saveBio() {
    var bioContent = $('.bioBox').val();

    $.post("../../utilities/saveBio.php", { bioContent: bioContent }, function (data) {
        if (data.trim() === "Bio saved successfully.") {
            window.location.href = "../home/home.php";
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
            $('.uploadProfilePicButton').css('border-radius', '999vw');
            $('.uploadProfilePicButton').css('border', '10px solid white');        
            $('.uploadProfilePicButton').attr('src', resizedImg);
            $('.saveProfilePicButton').show();
            
            // Store resized image data URL for later use
            $('.saveProfilePicButton').data('resizedImg', resizedImg);
        };

        img.src = e.target.result;
    };

    reader.readAsDataURL(file);
});

$('.saveProfilePicButton').on('click', function() {
	$('.saveProfilePicButton').hide();
    var resizedImg = $(this).data('resizedImg');

    if (resizedImg) {
        $.post('../../utilities/uploadProfilePic.php', { image: resizedImg })
        .done(function(response) {
            console.log('Image uploaded successfully');
            goToBio();
        })
        .fail(function(xhr, status, error) {
            console.error('Error uploading image');
        });
    } else {
        console.error('No cropped image available to save.');
    }
});

function goToHome() {
	window.location.href = '../home/home.php';
}
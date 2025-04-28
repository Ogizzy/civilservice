
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('photo-preview').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Make the photo container clickable to trigger the file input
document.querySelector('.profile-photo-container').addEventListener('click', function() {
    document.getElementById('passport').click();
});

// Hover effect for the photo container
const photoContainer = document.querySelector('.profile-photo-container');
const overlay = document.querySelector('.overlay');

photoContainer.addEventListener('mouseenter', function() {
    overlay.style.opacity = '1';
});

photoContainer.addEventListener('mouseleave', function() {
    overlay.style.opacity = '0';
});


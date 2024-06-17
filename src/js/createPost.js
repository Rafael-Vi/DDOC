document.addEventListener('DOMContentLoaded', function() {
    function setFileInputAccept() {
        var postType = document.getElementById('post-type');
        var fileInput = document.getElementById('file-input'); // Corrected id
        if (postType && fileInput) {
            // Clear the file input when the post type changes
            fileInput.value = ''; // Reset the file input

            switch (postType.value) {
                case 'audio':
                    fileInput.setAttribute('accept', 'audio/mp3');
                    break;
                case 'image':
                    fileInput.setAttribute('accept', 'image/jpeg,image/jpg,image/png,image/gif');
                    break;
                case 'video':
                    fileInput.setAttribute('accept', 'video/mp4');
                    break;
                default:
                    fileInput.removeAttribute('accept');
            }
        }
    }

    // Call the function on page load
    setFileInputAccept();

    // Call the function on change of the 'post-type' element
    var postType = document.getElementById('post-type');
    if (postType) {
        postType.addEventListener('change', setFileInputAccept);
    }
});
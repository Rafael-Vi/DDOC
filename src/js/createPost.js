document.getElementById('post-type').addEventListener('change', function() {
    var fileInput = document.getElementById('file-upload');
    switch (this.value) {
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
});
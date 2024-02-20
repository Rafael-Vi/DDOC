var postContainers = document.getElementsByClassName('post-container');

for (var i = 0; i < postContainers.length; i++) {
    postContainers[i].addEventListener('mouseover', function() {
        var editButton = this.querySelector('.edit-post');
        if (editButton) {
            editButton.style.visibility = 'visible';
        }
    });
    postContainers[i].addEventListener('mouseout', function() {
        var editButton = this.querySelector('.edit-post');
        if (editButton) {
            editButton.style.visibility = 'hidden';
        }
    });
}
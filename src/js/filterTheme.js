var themeSelect = document.getElementById('themeSelect');
if (themeSelect) {
    themeSelect.addEventListener('change', function() {
        var url = new URL(window.location.href);
        url.searchParams.set('theme', this.value);
        window.location.href = url.toString();
        console.log("changed");
    });
}

var typeSelect = document.getElementById('typeSelect');
if (typeSelect) {
    typeSelect.addEventListener('change', function() {
        var url = new URL(window.location.href);
        url.searchParams.set('type', this.value);
        window.location.href = url.toString();
        console.log("changed");
    });
}
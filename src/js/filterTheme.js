document.getElementById('themeSelect').addEventListener('change', function() {
    var url = new URL(window.location.href);
    url.searchParams.set('theme', this.value);
    window.location.href = url.toString();
    console.log("changed");
});
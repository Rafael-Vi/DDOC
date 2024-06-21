var themeSelect = document.getElementById('themeSelect');
var typeSelect = document.getElementById('typeSelect');
var selectedTheme = '';
var selectedType = '';

function updateUrl() {
    console.log("Attempting to update URL with:", selectedTheme, selectedType); // Debugging
    var baseUrl = window.location.origin + '/ranking-contas/';
    var newUrl = baseUrl;
    if (selectedTheme) newUrl += selectedTheme + '/';
    if (selectedType) newUrl += selectedType;
    console.log("URL Updated to:", newUrl); // Debugging
    window.location.href = newUrl;
}

if (themeSelect) {
    themeSelect.addEventListener('change', function() {
        selectedTheme = this.value;
        console.log("Theme selected:", selectedTheme); // Debugging
        updateUrl();
    });
}

if (typeSelect) {
    typeSelect.addEventListener('change', function() {
        selectedType = this.value;
        console.log("Type selected:", selectedType); // Debugging
        updateUrl();
    });
}
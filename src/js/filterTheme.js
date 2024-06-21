var themeSelect = document.getElementById('themeSelect');
var typeSelect = document.getElementById('typeSelect');
var selectedTheme = '';
var selectedType = '';

function updateUrl() {
    console.log("Attempting to update URL with:", selectedTheme, selectedType); // Debugging
    if (selectedTheme && selectedType) {
        var baseUrl = window.location.origin + '/ranking-contas/';
        var newUrl = baseUrl + selectedTheme + '/' + selectedType;
        console.log("URL Updated to:", newUrl); // Debugging
        window.location.href = newUrl;
    }
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
var themeSelect = document.getElementById('themeSelect');
var typeSelect = document.getElementById('typeSelect');
var selectedTheme = '';
var selectedType = '';

function updateUrl() {
    if (selectedTheme && selectedType) { // Ensure both selections are made
        var baseUrl = window.location.origin + '/ranking-contas/';
        window.location.href = baseUrl + selectedTheme + '/' + selectedType;
        console.log("URL Updated");
    }
}

if (themeSelect) {
    themeSelect.addEventListener('change', function() {
        selectedTheme = this.value;
        updateUrl(); // Update URL when theme changes
    });
}

if (typeSelect) {
    typeSelect.addEventListener('change', function() {
        selectedType = this.value;
        updateUrl(); // Update URL when type changes
    });
}
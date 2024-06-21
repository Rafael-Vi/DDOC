var themeSelect = document.getElementById('themeSelect');
var typeSelect = document.getElementById('typeSelect');
var selectedTheme = 'none';
var selectedType = 'none';

function updateUrl() {
    console.log("Attempting to update URL with:", selectedTheme, selectedType); // Debugging
    // Determine the base URL based on the current page context
    var currentPage = window.location.pathname.includes('ranking-posts') ? 'ranking-posts' : 'ranking-contas';
    var baseUrl = window.location.origin + '/' + currentPage + '/';
    var newUrl = baseUrl;
    
    // For 'ranking-posts', append theme and type to the URL
    if (currentPage === 'ranking-posts') {
        if (selectedTheme) newUrl += selectedTheme + '/';
        if (selectedType) newUrl += selectedType;
    } else if (currentPage === 'ranking-contas') {
        // For 'ranking-contas', only type is relevant
        if (selectedType) newUrl += selectedType;
    }
    
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
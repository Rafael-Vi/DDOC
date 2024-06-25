// Step 1: Extract Filters from URL
function extractFiltersFromUrl() {
    const pathSegments = window.location.pathname.split('/').filter(Boolean); // Split and remove any empty strings
    const currentPage = pathSegments.includes('ranking-posts') ? 'ranking-posts' : 'ranking-contas';
    let theme = 'none';
    let type = 'none';

    if (currentPage === 'ranking-posts' && pathSegments.length >= 3) {
        // Assuming the URL structure is /ranking-posts/theme/type or /ranking-posts/theme/
        theme = pathSegments[1] || 'none';
        type = pathSegments[2] || 'none';
    } else if (currentPage === 'ranking-contas' && pathSegments.length >= 2) {
        // Assuming the URL structure is /ranking-contas/type
        type = pathSegments[1] || 'none';
    }

    return { theme, type };
}

// Step 2: Update Variables Based on URL
const { theme, type } = extractFiltersFromUrl();
selectedTheme = theme;
selectedType = type;

// Step 3: Set Dropdown Values to Reflect URL Filters
window.addEventListener('DOMContentLoaded', (event) => {
    if (themeSelect && selectedTheme !== 'none') {
        themeSelect.value = selectedTheme;
    }
    if (typeSelect && selectedType !== 'none') {
        typeSelect.value = selectedType;
    }
});

// Existing code for handling changes in the dropdowns remains the same

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
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

// Step 1: Extract Filters from URL remains the same

// Step 2: Update Variables Based on URL remains the same

// Step 3: Set Dropdown Values to Reflect URL Filters
window.addEventListener('DOMContentLoaded', (event) => {
    const currentPage = window.location.pathname.includes('ranking-posts') ? 'ranking-posts' : 'ranking-contas';
    if (currentPage === 'ranking-posts' && themeSelect && selectedTheme !== 'none') {
        themeSelect.value = selectedTheme;
    }
    if (typeSelect && selectedType !== 'none') {
        typeSelect.value = selectedType;
    }
});

// Modified updateUrl function
function updateUrl() {
    console.log("Attempting to update URL with:", selectedTheme, selectedType); // Debugging
    var currentPage = window.location.pathname.includes('ranking-posts') ? 'ranking-posts' : 'ranking-contas';
    var baseUrl = window.location.origin + '/' + currentPage + '/';
    var newUrl = baseUrl;
    
    if (currentPage === 'ranking-posts') {
        if (selectedTheme) newUrl += selectedTheme + '/';
        if (selectedType) newUrl += selectedType;
    } else if (currentPage === 'ranking-contas') {
        if (selectedType) newUrl += selectedType;
    }
    
    console.log("URL Updated to:", newUrl); // Debugging
    window.location.href = newUrl;
}

// Conditionally add event listener to themeSelect
if (window.location.pathname.includes('ranking-posts') && themeSelect) {
    themeSelect.addEventListener('change', function() {
        selectedTheme = this.value;
        console.log("Theme selected:", selectedTheme); // Debugging
        updateUrl();
    });
}

// Always add event listener to typeSelect but handle based on page
if (typeSelect) {
    typeSelect.addEventListener('change', function() {
        selectedType = this.value;
        console.log("Type selected:", selectedType); // Debugging
        updateUrl();
    });
}
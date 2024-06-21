var themeSelect = document.getElementById('themeSelect');
if (themeSelect) {
    themeSelect.addEventListener('change', function() {
        var baseUrl = window.location.origin + '/ranking-contas/theme/';
        window.location.href = baseUrl + this.value;
        console.log("Theme changed");
    });
}

var typeSelect = document.getElementById('typeSelect');
if (typeSelect) {
    typeSelect.addEventListener('change', function() {
        var baseUrl = window.location.origin + '/ranking-contas/type/';
        window.location.href = baseUrl + this.value;
        console.log("Type changed");
    });
}
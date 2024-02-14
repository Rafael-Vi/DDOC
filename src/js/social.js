
function openSearch(event) {
  event.preventDefault();
  var searchDiv = document.getElementById('search-div');
  
  if (searchDiv.classList.contains('hidden')) {
    searchDiv.classList.remove('hidden');
  } else {
    searchDiv.classList.add('hidden');
  }
}

function logout() {
  if (confirm('Are you sure you want to log out?')) {
      window.location.href = '../src/include/functions/logout.inc.php';
  }
}

window.onload = function() {
  setTimeout(function() {
    document.getElementById('loadingScreen').style.display = 'none';
}, 600);
};

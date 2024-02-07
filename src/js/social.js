
function openSearch(event) {
  event.preventDefault();
  var searchDiv = document.getElementById('search-div');
  
  if (searchDiv.classList.contains('hidden')) {
    searchDiv.classList.remove('hidden');
  } else {
    searchDiv.classList.add('hidden');
  }
}

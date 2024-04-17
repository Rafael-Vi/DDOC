
function openSearch(event) {
  event.preventDefault();
  var searchDiv = document.getElementById('search-div');
  
  if (searchDiv.classList.contains('hidden')) {
    searchDiv.classList.remove('hidden');
  } else {
    searchDiv.classList.add('hidden');
  }
}
function searchStuff() {
  const value = document.getElementById('search-input').value;
  if (value.length != 0) {
    fetch('../src/include/functions/SQLfunctions.inc.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'function=getSearchStuff&value=' + encodeURIComponent(value),
  })

  .then(response => response.text())
  .then(data => {
      // Handle the data from the PHP file
      const searchPeople = document.getElementById('search-people');
      let searchDestroyable = document.getElementById('search-destroyable');
      if (searchDestroyable) {
          searchDestroyable.remove();
      }
      searchDestroyable = document.createElement('div');
      searchDestroyable.id = 'search-destroyable';
      searchDestroyable.innerHTML = data;
      searchPeople.appendChild(searchDestroyable);
  })
  
  .catch(error => {
      // Handle the error
      console.error('Error:', error);
  });
  }
  else {
    let searchDestroyable = document.getElementById('search-destroyable');
    if (searchDestroyable) {
        searchDestroyable.remove();
    }
  }

}
function logout(senderName) {
  if (confirm(senderName + ' asks: Are you sure you want to log out?')) {
      window.location.href = '../src/include/functions/logout.inc.php';
  }
}
window.onload = function() {
  setTimeout(function() {
    document.getElementById('loadingScreen').style.display = 'none';
}, 600);
};
document.getElementById('search-input').addEventListener('input', searchStuff);



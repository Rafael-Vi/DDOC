
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
      searchDestroyable.classList.add('flex', 'flex-col', 'items-center');
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
function logout() {
  if (confirm('DDOC asks: Are you sure you want to log out?')) {
      window.location.href = '../src/include/functions/logout.inc.php';
  }
}
window.onload = function() {
  setTimeout(function() {
    document.getElementById('loadingScreen').style.display = 'none';
}, 600);
};
document.getElementById('search-input').addEventListener('input', searchStuff);

function loadNotificationsNavBar() {
  fetch('../src/include/functions/SQLfunctions.inc.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'function=loadNotificationsNavBar',
  })
  .then(response => response.text())
  .then(data => {
      // Only update if the data has changed
      if (data !== lastDataNotif) {
          const notifNumber = parseInt(data); // get the number of unread notifications
          const notifNumberCleanerElement = document.getElementById('notif-number-cleaner');

          // Remove all child elements from notifNumberCleanerElement
          while (notifNumberCleanerElement.firstChild) {
              notifNumberCleanerElement.removeChild(notifNumberCleanerElement.firstChild);
          }

          if (notifNumber > 0) {
              // If there are unread notifications, create and show the notification number
              const notifNumberElement = document.createElement('span');
              notifNumberElement.id = 'notif-number';
              notifNumberElement.className = 'bg-orange-500 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm ubuntu-bold';
              notifNumberElement.textContent = notifNumber > 99 ? '99+' : notifNumber;
              notifNumberCleanerElement.appendChild(notifNumberElement);
              notifNumberCleanerElement.style.display = 'flex';
          } else {
              // If there are no unread notifications, hide the notification number
              notifNumberCleanerElement.style.display = 'none';
          }
          lastDataNotif = data;
      }
  })
  .catch(error => {
      console.error('Error:', error);
  });
}
let lastDataNotif = null;

setInterval(loadNotificationsNavBar, 500);

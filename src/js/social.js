$(document).ready(function() {
  $('#search-link, #search-button').on('click', function(event) {
      event.preventDefault();
      $('#search-div').toggleClass('hidden');
  });


  function searchStuff() {
      const value = $('#search-input').val();
      if (value.length != 0) {
          $.post('/src/include/functions/SQLfunctions.inc.php', {
              function: 'getSearchStuff',
              value: value
          }, function(data) {
              let $searchDestroyable = $('#search-destroyable');
              if ($searchDestroyable.length) {
                  $searchDestroyable.remove();
              }
              $searchDestroyable = $('<div>', {
                  id: 'search-destroyable',
                  class: 'flex flex-col items-center',
                  html: data
              });
              $('#search-people').append($searchDestroyable);
          }).fail(function(error) {
              console.error('Error:', error);
          });
      } else {
          $('#search-destroyable').remove();
      }
  }

  $(document).ready(function() {
    $('#logout-link').click(function(e) {
        e.preventDefault(); // Prevent the default anchor behavior
        if (confirm('Are you sure you want to log out?')) {
            window.location.href = '../src/include/functions/logout.inc.php';
        }
    });
});

$(document).ready(function() {
    $('#logout-button').click(function(e) {
        e.preventDefault(); // Prevent the default anchor behavior
        if (confirm('Are you sure you want to log out?')) {
            window.location.href = '../src/include/functions/logout.inc.php';
        }
    });
});

  setTimeout(function() {
      $('#loadingScreen').hide();
  }, 200);

  $('#search-input').on('input', searchStuff);

  function loadNotificationsNavBar() {
      $.post('/src/include/functions/SQLfunctions.inc.php', {
          function: 'loadNotificationsNavBar'
      }, function(data) {
          if (data !== lastDataNotif) {
              const notifNumber = parseInt(data);
              const $notifNumberCleanerElement = $('#notif-number-cleaner');
              $notifNumberCleanerElement.empty();

              if (notifNumber > 0) {
                  const notifNumberElement = $('<span>', {
                      id: 'notif-number',
                      class: 'bg-orange-500 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm ubuntu-bold',
                      text: notifNumber > 99 ? '99+' : notifNumber
                  });
                  $notifNumberCleanerElement.append(notifNumberElement).show();
              } else {
                  $notifNumberCleanerElement.hide();
              }
              lastDataNotif = data;
          }
      }).fail(function(error) {
          console.error('Error:', error);
      });
  }
  let lastDataNotif = null;

  setInterval(loadNotificationsNavBar, 500);
});
$('#close-error').on('click', function() {
    $('.error-container').remove();
});
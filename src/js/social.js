

// Add event listener for when the DOM content is loaded, calling the init function
document.addEventListener('DOMContentLoaded', init, false);

// Function to initialize the page
function init(){
 
  
// Métodos e Coisas para fazer a edição de perfil--------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------


    function openDialog() {
      document.getElementById('dialog').classList.remove('hidden');
    }

    function closeDialog() {
      const textInputs = document.querySelectorAll('#dialog input[type="text"], #dialog textarea');
      let hasUnsavedAlterations = false;

      textInputs.forEach(input => {
        if (input.value !== '') {
          hasUnsavedAlterations = true;
        }
      });

      if (hasUnsavedAlterations) {
        const confirmResult = confirm('There are unsaved alterations. Do you want to continue closing?');
        if (!confirmResult) {
          return; // Stop the function execution if the user clicks "Cancel"
        }
      }

      textInputs.forEach(input => {
        input.value = ''; // Clear the input values
      });

      document.getElementById('dialog').classList.add('hidden');
    }

// Métodos e Coisas para fazer a Pesquisa ---------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------

    function searchAjax(inputValue) {
      // Create a new XMLHttpRequest object
      const xhr = new XMLHttpRequest();

      // Define the PHP file URL and the request method
      const url = '../include/functions/searchAccounts.inc.php';
      const method = 'POST'; // Or 'POST' based on your PHP file

      // Set up the AJAX request
      xhr.open(method, `${url}?input=${inputValue}`, true);

      // Define the callback function when the AJAX request is completed
      xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 400) {
          // Request was successful, handle the response here
          console.log(xhr.responseText);
        } else {
          // Request failed
          console.error('Error: ' + xhr.statusText);
        }
      };

      // Send the AJAX request
      xhr.send();
    }

    const searchInput = document.getElementById('search-input');
    const searchPeopleDiv = document.getElementById('search-people');
    
    // Add an event listener to call the searchAjax function on keyup inside the search input
    searchInput.addEventListener('keyup', function(event) {
      // Get the current value of the search input
      const inputValue = event.target.value;
    
      // Remove all div elements inside the "search-people" div
      while (searchPeopleDiv.firstChild) {
        searchPeopleDiv.removeChild(searchPeopleDiv.firstChild);
      }
    
      // Call the function to make the AJAX call with the input value
      searchAjax(inputValue);
    });
    
}





// Define the functions in the global scope
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

function openSearch(event) {
  event.preventDefault(); // Prevent the default action
  var searchDiv = document.getElementById('search-div');
  
  if (searchDiv.classList.contains('hidden')) {
    searchDiv.classList.remove('hidden'); // Show the div if it's hidden
  } else {
    searchDiv.classList.add('hidden'); // Hide the div if it's visible
  }
}
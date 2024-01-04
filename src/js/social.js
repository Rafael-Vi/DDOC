
// Add event listener for when the DOM content is loaded, calling the init function
document.addEventListener('DOMContentLoaded', init, false);

// Function to initialize the page
function init(){
 
const liElements = document.querySelectorAll('li');

for (const liElement of liElements) {
const aElements = liElement.querySelectorAll('a'); // Get all a elements inside this li
    
for (const aElement of aElements) {
    const linkName = aElement.id; // Get the link's ID
    console.log(linkName);
    aElement.addEventListener('click', () => {
    // Call the openDiv function with the link's ID
    console.log(linkName)
    openDiv(linkName);
    });
}
}

    function openDiv(linkName) {
        const divs = document.querySelectorAll('div');
      
        for (const divElement of divs) {
          if (divElement.id === linkName.replace('-link', '-div')) {
            // Show the corresponding div
            divElement.classList.remove('hidden'); // Remove hidden class
            divElement.classList.add('block'); // Add block class
      
            // Hide all other divs
            for (const otherDiv of divs) {
              if (otherDiv.classList.contains('block') && otherDiv.id !== divElement.id) {
                otherDiv.classList.remove('block');
                otherDiv.classList.add('hidden');
              }
            }
          } else {
            divElement.classList.remove('active');
          }
        }
     }

   
}

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
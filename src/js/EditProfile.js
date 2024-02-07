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
        return;
      }
      textInputs.forEach(input => {
        input.value = ''; // Clear the input values
      });
    
      document.getElementById('dialog').classList.add('hidden');
    }
    else
    {
      document.getElementById('dialog').classList.add('hidden');
    }
}

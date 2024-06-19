function openDialog() {
    console.log('Opening profile dialog'); // Debugging: Log action

    var dialog = document.getElementById('profile-dialog');

    // Check if the dialog supports the showModal method
    if (dialog.showModal) {
        dialog.showModal();
    } else {
        // Fallback for browsers that do not support showModal
        dialog.style.display = "block";
    }

    console.log('Dialog displayed'); // Debugging: Confirm dialog is displayed
}
function closeDialog() {
  const textInputs = document.querySelectorAll('#profile-dialog input[type="text"], #profile-dialog textarea');
  let hasUnsavedAlterations = false;
  textInputs.forEach(input => {
      if (input.value !== '') {
          hasUnsavedAlterations = true;
      }

  });

  if (hasUnsavedAlterations) {
      const confirmResult = confirm('Existem alterações que não foram salvas. Deseja continuar?');
      if (!confirmResult) {
          return;
      }
      textInputs.forEach(input => {
          input.value = ''; // Clear the input values
      });
  }

  var dialog = document.getElementById('profile-dialog');
  dialog.close();
}
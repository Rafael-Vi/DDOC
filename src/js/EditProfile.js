function openDialog() {
  var dialog = document.getElementById('profile-dialog');
  if (dialog.showModal) {
      dialog.showModal();
  } else {
      dialog.style.display = "block";
  }
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
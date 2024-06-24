$(document).ready(function() {

    function generateRandomString(length) {
        var result = "";
        var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    function customConfirm() {
        var randomString = generateRandomString(8); // Generates a random 8 characters string
        var userInput = prompt("Por favor, escreva este texto para confirmar a exclusão: " + randomString);
        if (userInput === randomString) {
            // Perform AJAX request instead of redirecting
            $.ajax({
                url: "/src/include/functions/SQLfunctions.inc.php",
                type: "POST",
                data: {
                    function: 'deleteUser',
                },
                success: function(data) {
                    // Handle success
                    window.location.href = '/src/include/functions/logout.inc.php';
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error("Error during deletion:", error);
                }
            });
        }
    }

    // Example usage: Attach customConfirm to a button
    $('#apagar-conta').on('click', function() {
        customConfirm();
    });

    // Your existing code...
});
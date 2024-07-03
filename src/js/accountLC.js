// Add the flip class on the flipper element when the login button is clicked
var loginButton = document.getElementById("loginButton");
loginButton.onclick = function() {
  document.querySelector("#flipper").classList.add("flip");
};

// Assuming the ID of the "Login na minha conta →" link has been changed to backToLoginButton
var backToLoginButton = document.getElementById("backToLoginButton");
backToLoginButton.onclick = function() {
  document.querySelector("#flipper").classList.remove("flip");
};
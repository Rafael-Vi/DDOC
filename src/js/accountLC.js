// Add the flip class on the flipper element when the login button is clicked
var loginButton = document.getElementById("loginButton");
loginButton.onclick = function() {
  document.querySelector("#flipper").classList.add("flip");
}

// Remove the flip class from the flipper element when the register button is clicked
var registerButton = document.getElementById("registerButton");
registerButton.onclick = function() {
  document.querySelector("#flipper").classList.remove("flip");
}
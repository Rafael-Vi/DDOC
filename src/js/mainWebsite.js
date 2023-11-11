import { initializeApp } from "firebase/app";
import {getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword, onAuthStateChanged} from "firebase/auth"

// Your web app's Firebase configuration
var firebaseConfig = {
    apiKey: "AIzaSyDTLrGYDyW18hnO0JNRh7erqJN_jfmrhbc",
    authDomain: "ddoc-111d2.firebaseapp.com",
    databaseURL: "https://ddoc-111d2-default-rtdb.europe-west1.firebasedatabase.app",
    projectId: "ddoc-111d2",
    storageBucket: "ddoc-111d2.appspot.com",
    messagingSenderId: "1050682981941",
    appId: "1:1050682981941:web:407ee032ca3af908d5bb7c",
    measurementId: "G-7N1DXSK9M6"  
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

// Get a reference to the authentication service
const auth = getAuth(app);
  

  // Function to handle the registration form submission
function register() {
  var username = document.getElementById('usernameR').value;
  var email = document.getElementById('emailR').value;
  var password = document.getElementById('passwordR').value;

  // Create a new user in the Firebase Authentication system
  createUserWithEmailAndPassword(auth, email, password)
      .then((userCredential) => {
          // User successfully created
          const user = userCredential.user;
          alert('User successfully created');
          EnterMAin();
      })
      .catch((error) => {
          const errorMessage = error.message;
          alert(errorMessage);
      });
}

// Listen for the registration form submission event
document.getElementById('registerForm').addEventListener('submit', (e) => {
  e.preventDefault(); // Prevent the form from submitting
  register();
});

// Listen for the login form submission event
document.getElementById('loginForm').addEventListener('submit', (e) => {
  e.preventDefault(); // Prevent the form from submitting

  var email = document.getElementById('emailL').value;
  var password = document.getElementById('passwordL').value;

  signInWithEmailAndPassword(auth, email, password)
      .then((userCredential) => {
          // User successfully logged in
          const user = userCredential.user;
          alert('User successfully logged in');
          EnterMAin();
      })
      .catch((error) => {
          // An error occurred during the login process
          const errorMessage = error.message;
          alert(errorMessage);
      });
});

// Toggle the flip class on the flipper element when the login button is clicked
var loginButton = document.getElementById("loginButton");
loginButton.onclick = function() {
  document.querySelector("#flipper").classList.toggle("flip");
}

// Toggle the flip class on the flipper element when the register button is clicked
var registerButton = document.getElementById("registerButton");
registerButton.onclick = function() {
  document.querySelector("#flipper").classList.toggle("flip");
}

// Redirect to the social.html page
function EnterMAin() {
  location.replace("/src/social.html");
}

// Listen for changes in the authentication state
onAuthStateChanged(auth, (user) => {
  if (user) {
      const uid = user.uid;
      alert(uid);
  } else {
      // User is signed out
  }
});



const profileLink = document.getElementById("profile-link");
const notificationsLink = document.getElementById("notifications-link");
const rankingsLink = document.getElementById("rankings-link");
const messagesLink = document.getElementById("messages-link");
const createPostLink = document.getElementById("create-post-link");

const divs = {
    "profile-div": document.getElementById("profile-div"),
    "notifications-div": document.getElementById("notifications-div"),
    "rankings-div": document.getElementById("rankings-div"),
    "messages-div": document.getElementById("messages-div"),
    "create-post-div": document.getElementById("create-post-div")
};

profileLink.addEventListener("focus", toggleDivs);
notificationsLink.addEventListener("focus", toggleDivs);
rankingsLink.addEventListener("focus", toggleDivs);
messagesLink.addEventListener("focus", toggleDivs);
createPostLink.addEventListener("focus", toggleDivs);

profileLink.addEventListener("blur", hideDiv);
notificationsLink.addEventListener("blur", hideDiv);
rankingsLink.addEventListener("blur", hideDiv);
messagesLink.addEventListener("blur", hideDiv);
createPostLink.addEventListener("blur", hideDiv);

function toggleDivs(e) {
    e.preventDefault();
    
    for (let key in divs) {
        if (key === `${e.target.id.split('-')[0]}-div`) {
            divs[key].style.display = "block";
        } else {
            divs[key].style.display = "none";
        }
    }
}

function hideDiv(e) {
    for (let key in divs) {
        if (key === `${e.target.id.split('-')[0]}-div`) {
            divs[key].style.display = "none";
        }
    }
}

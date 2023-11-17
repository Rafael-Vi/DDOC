document.addEventListener('DOMContentLoaded', init, false);
function init(){
    const profileLink = document.getElementById("profile-link");
    const notificationsLink = document.getElementById("notifications-link");
    const rankingsLink = document.getElementById("rankings-link");
    const messagesLink = document.getElementById("messages-link");
    const createPostLink = document.getElementById("createPost-link");
    const moreLink = document.getElementById("more-link");

    const divs = {
        // Define div elements by their IDs
        "profile-div": document.getElementById("profile-div"),
        "notifications-div": document.getElementById("notifications-div"),
        "rankings-div": document.getElementById("rankings-div"),
        "messages-div": document.getElementById("messages-div"),
        "createPost-div": document.getElementById("createPost-div"),
        "dropdown-div": document.getElementById("dropdown-div"),
       "mainContent-div": document.getElementById("mainContent-div"),
       "rightButtons-div": document.getElementById("rightButtons-div"),
    };
    
    // Add event listeners for focus event on specific elements, calling the toggleDivs function
    profileLink.addEventListener("focus", toggleDivs);
    notificationsLink.addEventListener("focus", toggleDivs);
    rankingsLink.addEventListener("focus", toggleDivs);
    messagesLink.addEventListener("focus", toggleDivs);
    createPostLink.addEventListener("focus", toggleDivs);
    moreLink.addEventListener("focus", toggleDivs);
    
    // Add event listeners for blur event on specific elements, calling the hideDiv function
    profileLink.addEventListener("blur", hideDiv);
    notificationsLink.addEventListener("blur", hideDiv);
    rankingsLink.addEventListener("blur", hideDiv);
    messagesLink.addEventListener("blur", hideDiv);
    createPostLink.addEventListener("blur", hideDiv);
    moreLink.addEventListener("focus", hideDiv);
    
    // Function to toggle the display of div elements based on the target element's ID  
    function toggleDivs(e) {
        e.preventDefault();

        // Loop through each key in the divs object
        for (let key in divs) {
            // Check if the key matches the target element's ID
            if (key === `${e.target.id.split('-')[0]}-div`) {
                // Display the matching div element
                divs[key].style.display = "block";
                
            } else {
                // Hide the non-matching div elements
                divs[key].style.display = "none";
            }
        }

        // Hide mainContent-div and rightButtons-div
        divs["mainContent-div"].style.display = "none";
        divs["rightButtons-div"].style.visibility = "hidden";

    }
    // Function to hide the div element based on the target element's ID
    function hideDiv(e) {
        // Loop through each key in the divs object
        for (let key in divs) {
            // Check if the key matches the target element's ID
            if (key === `${e.target.id.split('-')[0]}-div`) {
                // Hide the matching div element
                divs[key].style.display = "none";
            }
        }

        checkScreenSize();
        // Show mainContent-div
        divs["mainContent-div"].style.display = "block";
    }

   function checkScreenSize() {
    // Check screen size
    const mediaQuery = window.matchMedia('(max-width: 768px)');

    // Check if any divs (except mainContent-div) are displayed
    let isAnyDivDisplayed = false;
    for (let key in divs) {
        if (key !== "mainContent-div" && divs[key].style.display !== "none") {
            isAnyDivDisplayed = true;
            break;
        }
    }

    // Hide rightButtons-div if screen width is below 768px, or if any other divs (except mainContent-div) are displayed
    if (mediaQuery.matches || isAnyDivDisplayed) {
        divs["rightButtons-div"].style.visibility = "hidden";
    } else {
        divs["rightButtons-div"].style.display = "block";
    }
}

}
document.addEventListener("DOMContentLoaded", function() {
    // Hide the loading overlay after the content is fully loaded
    document.getElementById("loading-overlay").style.display = "none";
});

// Show the loading overlay when the page is being reloaded or navigated
window.addEventListener("beforeunload", function() {
    document.getElementById("loading-overlay").style.display = "flex";
});
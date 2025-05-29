import './bootstrap';

// Add this at the end of your app.js file
console.log('App.js loaded');

// Debug navigation
window.addEventListener('load', function() {
    console.log('Current URL:', window.location.href);
    
    // Check for any redirects
    let originalPushState = history.pushState;
    history.pushState = function() {
        console.log('Navigation changed:', arguments);
        return originalPushState.apply(this, arguments);
    };
    
    let originalReplaceState = history.replaceState;
    history.replaceState = function() {
        console.log('Navigation replaced:', arguments);
        return originalReplaceState.apply(this, arguments);
    };
});


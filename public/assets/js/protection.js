// Disable right click
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
});

// Disable F12, Ctrl+Shift+I, Ctrl+U
document.addEventListener('keydown', function(e) {
    if (e.key === 'F12' || 
        (e.ctrlKey && e.shiftKey && e.key === 'I') ||
        (e.ctrlKey && e.key === 'u') ||
        (e.ctrlKey && e.shiftKey && e.key === 'C') ||
        (e.ctrlKey && e.shiftKey && e.key === 'J')) {
        e.preventDefault();
    }
});

// Disable text selection
document.onselectstart = function() {
    return false;
};

// Disable drag
document.ondragstart = function() {
    return false;
};

// Console warning
console.log('%cSTOP!', 'color: red; font-size: 50px; font-weight: bold;');
console.log('%cThis is a browser feature intended for developers. Unauthorized access is prohibited.', 'color: red; font-size: 16px;');

// Clear console periodically
setInterval(function() {
    console.clear();
}, 1000);

// Detect DevTools
let devtools = {open: false, orientation: null};
setInterval(function() {
    if (window.outerHeight - window.innerHeight > 200 || window.outerWidth - window.innerWidth > 200) {
        if (!devtools.open) {
            devtools.open = true;
            document.body.innerHTML = '<div style="text-align:center;margin-top:200px;"><h1>Access Denied</h1><p>Developer tools detected!</p></div>';
        }
    }
}, 500);
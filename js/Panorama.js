function panoramaView(path){
    pannellum.viewer('panorama', {
        "type": "equirectangular",
        "panorama": path,
        "autoLoad": true,
        "autoRotate":-3,
        "autoRotateInactivityDelay": 5000
    });
}


function handleResize() {
    // Get the height of the div element
    let headerHeight = document.getElementById('nav-header-container').offsetHeight;
    let screenHeight =  window.innerHeight;
    document.getElementById('panorama').style.height = (screenHeight - headerHeight) + "px";
}

// Add an event listener for the resize event
window.addEventListener('resize', handleResize);

// Call the handleResize function initially to get the height on page load
handleResize();
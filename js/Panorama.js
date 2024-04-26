function panoramaView(path){
    pannellum.viewer('panorama', {
        "type": "equirectangular",
        "panorama": path,
        "autoLoad": true,
        "autoRotate":-3,
        "autoRotateInactivityDelay": 5000
    });
}


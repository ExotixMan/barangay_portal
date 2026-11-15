(function () {
    'use strict';
    let requested = false;
    // Detection is optional. Load it when a camera is requested, not during signup.
    window.loadOpenCv = function () {
        if (requested) return;
        requested = true;
        const script = document.createElement('script');
        script.src = 'https://docs.opencv.org/4.x/opencv.js';
        script.async = true;
        script.addEventListener('load', function () { window.onOpenCvReady?.(); });
        script.addEventListener('error', function () { window.cvReady = false; });
        document.head.appendChild(script);
    };
})();

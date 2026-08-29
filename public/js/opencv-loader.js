(function () {
    'use strict';

    const script = document.createElement('script');
    script.src = 'https://docs.opencv.org/4.x/opencv.js';
    script.async = true;
    script.addEventListener('load', function () {
        window.onOpenCvReady?.();
    });
    script.addEventListener('error', function () {
        window.cvReady = false;
    });
    document.head.appendChild(script);
})();

window.cvReady = false;
        window.onOpenCvReady = function () {
            window.cvReady = true;
            document.dispatchEvent(new Event('opencv-ready'));
        };

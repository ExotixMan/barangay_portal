document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("resubmitIdForm");

    const frontInput = document.getElementById("valid_id");
    const backInput = document.getElementById("valid_id_back");

    const frontCard = document.getElementById("frontSideCard");
    const backCard = document.getElementById("backSideCard");

    const frontStatus = document.getElementById("frontStatus");
    const backStatus = document.getElementById("backStatus");

    const frontFileName = document.getElementById("frontFileName");
    const backFileName = document.getElementById("backFileName");

    const frontPreviewImage = document.getElementById("frontPreviewImage");
    const backPreviewImage = document.getElementById("backPreviewImage");

    const frontEmptyState = document.getElementById("frontEmptyState");
    const backEmptyState = document.getElementById("backEmptyState");

    const submitButton = document.getElementById("submitIdButton");

    const readyStatus = document.getElementById("readyStatus");
    const readyStatusTitle = document.getElementById("readyStatusTitle");
    const readyStatusText = document.getElementById("readyStatusText");

    const startScannerButton = document.getElementById("startIdScanner");

    const modalElement = document.getElementById("idScannerModal");
    const scannerModal = new bootstrap.Modal(modalElement);

    const video = document.getElementById("idScannerVideo");
    const canvas = document.getElementById("idCaptureCanvas");

    const captureButton = document.getElementById("captureIdButton");
    const restartButton = document.getElementById("restartScannerButton");

    const scannerStep = document.getElementById("scannerStep");
    const scannerSideTitle = document.getElementById("scannerSideTitle");
    const scannerSideDescription = document.getElementById(
        "scannerSideDescription"
    );

    const cameraStatusText = document.getElementById("cameraStatusText");

    const flipOverlay = document.getElementById("flipOverlay");

    const scanFrontCheck = document.getElementById("scanFrontCheck");
    const scanBackCheck = document.getElementById("scanBackCheck");

    const frontScannerStatus = document.getElementById(
        "frontScannerStatus"
    );

    const backScannerStatus = document.getElementById(
        "backScannerStatus"
    );

    const progressBar = document.getElementById("scanProgressBar");
    const progressLabel = document.getElementById("scanProgressLabel");


    let mediaStream = null;

    let currentSide = "front";

    let frontCaptured = false;
    let backCaptured = false;

    let frontPreviewUrl = null;
    let backPreviewUrl = null;


    /* =====================================================
       FILE VALIDATION
    ===================================================== */

    function validateFile(file) {

        if (!file) {
            return false;
        }

        const allowedTypes = [
            "image/jpeg",
            "image/png",
            "application/pdf"
        ];

        if (!allowedTypes.includes(file.type)) {
            alert("Please select a JPG, JPEG, PNG or PDF file.");
            return false;
        }

        const maxSize = 5 * 1024 * 1024;

        if (file.size > maxSize) {
            alert("The selected file must not exceed 5MB.");
            return false;
        }

        return true;
    }


    /* =====================================================
       PREVIEW
    ===================================================== */

    function displayPreview(side, file) {

        const isFront = side === "front";

        const image = isFront
            ? frontPreviewImage
            : backPreviewImage;

        const empty = isFront
            ? frontEmptyState
            : backEmptyState;

        const status = isFront
            ? frontStatus
            : backStatus;

        const card = isFront
            ? frontCard
            : backCard;

        const nameElement = isFront
            ? frontFileName
            : backFileName;


        nameElement.textContent = file.name;


        if (file.type === "application/pdf") {

            image.classList.add("d-none");
            empty.classList.remove("d-none");

            empty.innerHTML = `
                <i class="fas fa-file-pdf"></i>
                <strong>PDF selected</strong>
                <span>${escapeHtml(file.name)}</span>
            `;

        } else {

            if (isFront && frontPreviewUrl) {
                URL.revokeObjectURL(frontPreviewUrl);
            }

            if (!isFront && backPreviewUrl) {
                URL.revokeObjectURL(backPreviewUrl);
            }

            const previewUrl = URL.createObjectURL(file);

            if (isFront) {
                frontPreviewUrl = previewUrl;
            } else {
                backPreviewUrl = previewUrl;
            }

            image.src = previewUrl;

            empty.classList.add("d-none");
            image.classList.remove("d-none");
        }


        status.textContent = "Ready";

        status.classList.remove("waiting");
        status.classList.add("complete");

        card.classList.add("complete");

        updateSubmissionState();
    }


    function escapeHtml(value) {

        const div = document.createElement("div");

        div.textContent = value;

        return div.innerHTML;
    }


    /* =====================================================
       FILE INPUT EVENTS
    ===================================================== */

    frontInput.addEventListener("change", () => {

        const file = frontInput.files[0];

        if (!file) {
            return;
        }

        if (!validateFile(file)) {

            frontInput.value = "";

            return;
        }

        displayPreview("front", file);
    });


    backInput.addEventListener("change", () => {

        const file = backInput.files[0];

        if (!file) {
            return;
        }

        if (!validateFile(file)) {

            backInput.value = "";

            return;
        }

        displayPreview("back", file);
    });


    /* =====================================================
       SUBMISSION STATE
    ===================================================== */

    function updateSubmissionState() {

        const hasFront =
            frontInput.files &&
            frontInput.files.length > 0;

        const hasBack =
            backInput.files &&
            backInput.files.length > 0;


        if (hasFront && hasBack) {

            submitButton.disabled = false;

            readyStatus.classList.add("ready");

            readyStatus.querySelector(
                ".ready-status-icon"
            ).innerHTML = `
                <i class="fas fa-circle-check"></i>
            `;

            readyStatusTitle.textContent =
                "Your ID is ready for submission";

            readyStatusText.textContent =
                "Both sides have been added. You may now submit them for barangay review.";

        } else {

            submitButton.disabled = true;

            readyStatus.classList.remove("ready");

            readyStatus.querySelector(
                ".ready-status-icon"
            ).innerHTML = `
                <i class="fas fa-hourglass-half"></i>
            `;

            readyStatusTitle.textContent =
                "Complete your ID submission";


            if (hasFront && !hasBack) {

                readyStatusText.textContent =
                    "Front side received. Please add the back side.";

            } else if (!hasFront && hasBack) {

                readyStatusText.textContent =
                    "Back side received. Please add the front side.";

            } else {

                readyStatusText.textContent =
                    "Front and back copies are required before submission.";
            }
        }
    }


    /* =====================================================
       CAMERA
    ===================================================== */

    async function startCamera() {

        stopCamera();

        cameraStatusText.textContent =
            "Requesting camera access...";

        captureButton.disabled = true;


        try {

            mediaStream =
                await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: {
                            ideal: "environment"
                        },

                        width: {
                            ideal: 1920
                        },

                        height: {
                            ideal: 1080
                        }
                    },

                    audio: false
                });


            video.srcObject = mediaStream;

            await video.play();


            cameraStatusText.textContent =
                "Camera ready — position your ID inside the guide.";

            captureButton.disabled = false;

        } catch (error) {

            console.error(error);

            cameraStatusText.textContent =
                "Camera permission unavailable.";

            captureButton.disabled = true;

            alert(
                "We could not access your camera. Please allow camera permission or upload your ID manually."
            );
        }
    }


    function stopCamera() {

        if (!mediaStream) {
            return;
        }

        mediaStream
            .getTracks()
            .forEach(track => track.stop());

        mediaStream = null;

        video.srcObject = null;
    }


    /* =====================================================
       START SCANNER
    ===================================================== */

    startScannerButton.addEventListener("click", async () => {

        resetScanner();

        scannerModal.show();

        setTimeout(() => {
            startCamera();
        }, 300);
    });


    /* =====================================================
       RESET
    ===================================================== */

    function resetScanner() {

        currentSide = "front";

        frontCaptured = false;
        backCaptured = false;

        scannerStep.textContent = "1 / 2";

        scannerSideTitle.textContent =
            "FRONT SIDE";

        scannerSideDescription.textContent =
            "Position the front side inside the frame.";

        captureButton.innerHTML = `
            <i class="fas fa-camera"></i>
            Capture front side
        `;

        scanFrontCheck.classList.remove("complete");
        scanFrontCheck.classList.add("active");

        scanBackCheck.classList.remove(
            "complete",
            "active"
        );

        scanFrontCheck.querySelector(
            ".check-circle"
        ).textContent = "1";

        scanBackCheck.querySelector(
            ".check-circle"
        ).textContent = "2";

        frontScannerStatus.textContent =
            "Waiting for capture";

        backScannerStatus.textContent =
            "Not started";

        setProgress(0);

        flipOverlay.classList.add("d-none");
    }


    restartButton.addEventListener("click", async () => {

        resetScanner();

        await startCamera();
    });


    /* =====================================================
       CAPTURE
    ===================================================== */

    captureButton.addEventListener("click", () => {

        if (!video.videoWidth || !video.videoHeight) {

            alert("Camera is not ready yet.");

            return;
        }

        captureCurrentSide();
    });


    function captureCurrentSide() {

        captureButton.disabled = true;

        cameraStatusText.textContent =
            "Capturing image...";


        /*
         * Standard ID / credit-card aspect ratio:
         * approximately 1.586 : 1
         */
        const targetAspect = 1.586;

        const videoWidth = video.videoWidth;
        const videoHeight = video.videoHeight;

        let cropWidth;
        let cropHeight;


        if ((videoWidth / videoHeight) > targetAspect) {

            cropHeight = videoHeight * 0.68;

            cropWidth =
                cropHeight * targetAspect;

        } else {

            cropWidth = videoWidth * 0.84;

            cropHeight =
                cropWidth / targetAspect;
        }


        cropWidth = Math.min(
            cropWidth,
            videoWidth
        );

        cropHeight = Math.min(
            cropHeight,
            videoHeight
        );


        const sourceX =
            (videoWidth - cropWidth) / 2;

        const sourceY =
            (videoHeight - cropHeight) / 2;


        /*
         * Save at a useful verification resolution.
         */
        const outputWidth = 1400;
        const outputHeight =
            Math.round(outputWidth / targetAspect);


        canvas.width = outputWidth;
        canvas.height = outputHeight;


        const context =
            canvas.getContext("2d");


        context.drawImage(
            video,

            sourceX,
            sourceY,
            cropWidth,
            cropHeight,

            0,
            0,
            outputWidth,
            outputHeight
        );


        canvas.toBlob(

            blob => {

                if (!blob) {

                    alert(
                        "Unable to capture the ID. Please try again."
                    );

                    captureButton.disabled = false;

                    return;
                }


                const fileName =
                    currentSide === "front"
                        ? `valid-id-front-${Date.now()}.jpg`
                        : `valid-id-back-${Date.now()}.jpg`;


                const file =
                    new File(
                        [blob],
                        fileName,
                        {
                            type: "image/jpeg"
                        }
                    );


                assignCapturedFile(
                    currentSide,
                    file
                );

            },

            "image/jpeg",
            0.94
        );
    }


    /* =====================================================
       ATTACH CAPTURE TO REAL INPUT
    ===================================================== */

    function assignCapturedFile(side, file) {

        const transfer =
            new DataTransfer();

        transfer.items.add(file);


        if (side === "front") {

            frontInput.files =
                transfer.files;

            displayPreview(
                "front",
                file
            );

            frontCaptured = true;

            completeFrontScan();

        } else {

            backInput.files =
                transfer.files;

            displayPreview(
                "back",
                file
            );

            backCaptured = true;

            completeBackScan();
        }
    }


    /* =====================================================
       FRONT COMPLETE
    ===================================================== */

    function completeFrontScan() {

        scanFrontCheck.classList.remove("active");
        scanFrontCheck.classList.add("complete");

        scanFrontCheck.querySelector(
            ".check-circle"
        ).innerHTML =
            `<i class="fas fa-check"></i>`;

        frontScannerStatus.textContent =
            "Captured successfully";

        setProgress(50);


        flipOverlay.classList.remove("d-none");


        cameraStatusText.textContent =
            "Front captured successfully.";


        setTimeout(() => {

            flipOverlay.classList.add("d-none");

            currentSide = "back";

            scannerStep.textContent =
                "2 / 2";

            scannerSideTitle.textContent =
                "BACK SIDE";

            scannerSideDescription.textContent =
                "Flip the ID and position the back side inside the frame.";

            scanBackCheck.classList.add("active");

            backScannerStatus.textContent =
                "Ready for capture";


            captureButton.innerHTML = `
                <i class="fas fa-camera"></i>
                Capture back side
            `;

            captureButton.disabled = false;

            cameraStatusText.textContent =
                "Place the BACK side inside the guide.";

        }, 1700);
    }


    /* =====================================================
       BACK COMPLETE
    ===================================================== */

    function completeBackScan() {

        scanBackCheck.classList.remove("active");
        scanBackCheck.classList.add("complete");

        scanBackCheck.querySelector(
            ".check-circle"
        ).innerHTML =
            `<i class="fas fa-check"></i>`;

        backScannerStatus.textContent =
            "Captured successfully";


        setProgress(100);


        captureButton.innerHTML = `
            <i class="fas fa-circle-check"></i>
            Scan complete
        `;

        captureButton.disabled = true;


        cameraStatusText.textContent =
            "Front and back ID successfully captured.";


        setTimeout(() => {

            stopCamera();

            scannerModal.hide();

        }, 900);
    }


    /* =====================================================
       PROGRESS
    ===================================================== */

    function setProgress(value) {

        progressBar.style.width =
            `${value}%`;

        progressLabel.textContent =
            `${value}%`;
    }


    /* =====================================================
       MODAL CLEANUP
    ===================================================== */

    modalElement.addEventListener(
        "hidden.bs.modal",
        () => {

            stopCamera();
        }
    );


    /* =====================================================
       FORM SUBMISSION
    ===================================================== */

    form.addEventListener("submit", event => {

        if (
            !frontInput.files.length ||
            !backInput.files.length
        ) {

            event.preventDefault();

            alert(
                "Please provide both the front and back sides of your valid ID."
            );

            return;
        }


        const frontFile =
            frontInput.files[0];

        const backFile =
            backInput.files[0];


        if (
            !validateFile(frontFile) ||
            !validateFile(backFile)
        ) {

            event.preventDefault();

            return;
        }


        submitButton.disabled = true;


        submitButton
            .querySelector(".submit-text")
            .classList.add("d-none");


        submitButton
            .querySelector(".submit-loading")
            .classList.remove("d-none");
    });


    updateSubmissionState();
});
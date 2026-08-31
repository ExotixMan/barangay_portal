const __cfg_barangay_system_profile_1 = JSON.parse(document.getElementById('js-config-barangay-system-profile-1')?.dataset.jsConfig || '{}');

document.addEventListener('DOMContentLoaded', function () {
    const scannerModalElement = document.getElementById('idScannerModal');
    const scannerModal = scannerModalElement ? new bootstrap.Modal(scannerModalElement) : null;
    const scannerVideo = document.getElementById('idScannerVideo');
    const scannerOverlay = document.getElementById('idScannerOverlay');
    const scannerWorkCanvas = document.getElementById('idScannerWorkCanvas');
    const scannerResultCanvas = document.getElementById('idScannerResultCanvas');
    const scannerStatus = document.getElementById('idScannerStatus');
    const scannerStatusText = document.getElementById('idScannerStatusText');
    const scannerTargetText = document.getElementById('idScannerTargetText');
    const scannerRetakeBtn = document.getElementById('idScannerRetakeBtn');
    const scannerManualCaptureBtn = document.getElementById('idScannerManualCaptureBtn');
    const scanBothSidesBtn = document.getElementById('scanBothSidesBtn');
    const idUploadForm = document.getElementById('idUploadForm');

    const scannerSides = [
        { inputId: 'valid_id', label: 'front side', display: __cfg_barangay_system_profile_1.v1 },
        { inputId: 'valid_id_back', label: 'back side', display: __cfg_barangay_system_profile_1.v2 },
    ];

    const scannerState = {
        stream: null,
        rafId: null,
        sideIndex: 0,
        detectedPoints: null,
        lastPoints: null,
        stableFrames: 0,
        isCapturing: false,
        waitingForFlip: false,
        signatures: {},
        quality: {},
        completed: false,
        readyToCapture: false,
        frameTick: 0,
    };

    function setScannerStatus(message, state = 'waiting') {
        if (scannerStatusText) scannerStatusText.textContent = message;
        if (!scannerStatus) return;

        scannerStatus.classList.toggle('is-ready', state === 'ready');
        scannerStatus.classList.toggle('is-error', state === 'error');
    }

    function setCaptureReady(ready) {
        scannerState.readyToCapture = !!ready;

        if (scannerManualCaptureBtn) {
            scannerManualCaptureBtn.disabled = !scannerState.readyToCapture;
            scannerManualCaptureBtn.setAttribute('aria-disabled', scannerState.readyToCapture ? 'false' : 'true');
        }
    }

    function updateScanStatus() {
        const front = document.getElementById('valid_id');
        const back = document.getElementById('valid_id_back');
        const status = document.getElementById('idAutoScanStatus');
        const title = document.getElementById('idAutoScanStatusTitle');
        const text = document.getElementById('idAutoScanStatusText');
        const hasFront = !!front?.files?.length;
        const hasBack = !!back?.files?.length;

        if (status) status.classList.toggle('is-complete', hasFront && hasBack);

        if (!title || !text) return;

        if (hasFront && hasBack) {
            title.textContent = __cfg_barangay_system_profile_1.v3;
            text.textContent = __cfg_barangay_system_profile_1.v4;
        } else if (hasFront) {
            title.textContent = __cfg_barangay_system_profile_1.v5;
            text.textContent = __cfg_barangay_system_profile_1.v6;
        } else if (hasBack) {
            title.textContent = __cfg_barangay_system_profile_1.v7;
            text.textContent = __cfg_barangay_system_profile_1.v8;
        } else {
            title.textContent = __cfg_barangay_system_profile_1.v9;
            text.textContent = __cfg_barangay_system_profile_1.v10;
        }

        if (scanBothSidesBtn) {
            scanBothSidesBtn.innerHTML = hasFront && hasBack
                ? `<i class="fas fa-rotate-right"></i> ${__cfg_barangay_system_profile_1.v11}`
                : `<i class="fas fa-camera-retro"></i> ${__cfg_barangay_system_profile_1.v12}`;
        }
    }

    function updateSelectedFilePreview(input, file) {
        const textId = input.getAttribute('data-file-text');
        const textEl = textId ? document.getElementById(textId) : null;

        if (textEl) {
            textEl.textContent = file ? file.name : (input.id === 'valid_id_back' ? __cfg_barangay_system_profile_1.v13 : __cfg_barangay_system_profile_1.v14);
        }

        const previewId = input.getAttribute('data-preview');
        const shellId = input.getAttribute('data-preview-shell');
        const preview = previewId ? document.getElementById(previewId) : null;
        const shell = shellId ? document.getElementById(shellId) : null;

        if (!preview || !shell) return;

        if (!file || !file.type.startsWith('image/')) {
            preview.removeAttribute('src');
            preview.classList.add('d-none');
            shell.querySelector('.bhd-id-empty')?.classList.remove('d-none');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            preview.src = event.target.result;
            preview.classList.remove('d-none');
            shell.querySelector('.bhd-id-empty')?.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }


    async function fileVisualFingerprint(file) {
        if (!file || !file.type?.startsWith('image/')) return null;

        const bitmap = await createImageBitmap(file);
        const canvas = document.createElement('canvas');
        canvas.width = 16;
        canvas.height = 16;
        const ctx = canvas.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(bitmap, 0, 0, 16, 16);
        bitmap.close?.();

        const data = ctx.getImageData(0, 0, 16, 16).data;
        const values = [];

        for (let i = 0; i < data.length; i += 4) {
            values.push((data[i] * .299) + (data[i + 1] * .587) + (data[i + 2] * .114));
        }

        const average = values.reduce((sum, value) => sum + value, 0) / values.length;
        return values.map(value => value >= average ? 1 : 0);
    }

    function fingerprintDistance(a, b) {
        if (!a || !b || a.length !== b.length) return 1;

        let different = 0;
        for (let i = 0; i < a.length; i++) {
            if (a[i] !== b[i]) different++;
        }

        return different / a.length;
    }

    async function uploadedFilesLookTheSame(frontFile, backFile) {
        if (!frontFile || !backFile) return false;

        // Fast exact-file clue. Server-side SHA/perceptual validation is still authoritative.
        if (
            frontFile.name === backFile.name &&
            frontFile.size === backFile.size &&
            frontFile.type === backFile.type
        ) {
            return true;
        }

        if (!frontFile.type.startsWith('image/') || !backFile.type.startsWith('image/')) {
            return false;
        }

        try {
            const [frontHash, backHash] = await Promise.all([
                fileVisualFingerprint(frontFile),
                fileVisualFingerprint(backFile),
            ]);

            return fingerprintDistance(frontHash, backHash) <= 0.05;
        } catch (error) {
            console.warn('Could not compare selected ID images in the browser:', error);
            return false;
        }
    }

    async function rejectDuplicatePair(frontInput, backInput, message) {
        const frontFile = frontInput?.files?.[0] || null;
        const backFile = backInput?.files?.[0] || null;

        if (!frontFile || !backFile) return false;
        if (!await uploadedFilesLookTheSame(frontFile, backFile)) return false;

        backInput.value = '';

        if (backInput.id === 'valid_id_back') {
            updateSelectedFilePreview(backInput, null);
            document.querySelector(`[data-remove-file="${backInput.id}"]`)?.classList.remove('is-visible');
            updateScanStatus();
        }

        alert(message);
        return true;
    }

    document.querySelectorAll('#idUploadForm input[type="file"]').forEach(function (input) {
        input.addEventListener('change', async function () {
            const file = input.files?.[0] || null;

            if (file && file.size > 5 * 1024 * 1024) {
                alert(__cfg_barangay_system_profile_1.v15);
                input.value = '';
                updateSelectedFilePreview(input, null);
                document.querySelector(`[data-remove-file="${input.id}"]`)?.classList.remove('is-visible');
                updateScanStatus();
                return;
            }

            if (file && !file.type.startsWith('image/')) {
                alert(__cfg_barangay_system_profile_1.v16);
                input.value = '';
                updateSelectedFilePreview(input, null);
                document.querySelector(`[data-remove-file="${input.id}"]`)?.classList.remove('is-visible');
                updateScanStatus();
                return;
            }

            updateSelectedFilePreview(input, file);
            const removeBtn = document.querySelector(`[data-remove-file="${input.id}"]`);
            removeBtn?.classList.toggle('is-visible', !!file);
            updateScanStatus();

            const frontInput = document.getElementById('valid_id');
            const backInput = document.getElementById('valid_id_back');
            await rejectDuplicatePair(
                frontInput,
                backInput,
                'The back of the valid ID appears to be the same picture as the front. Please provide the actual back side.'
            );
        });
    });

    document.querySelectorAll('[data-remove-file]').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const input = document.getElementById(button.getAttribute('data-remove-file'));
            if (!input) return;

            input.value = '';
            updateSelectedFilePreview(input, null);
            button.classList.remove('is-visible');
            updateScanStatus();
        });
    });

    if (idUploadForm) {
        idUploadForm.addEventListener('submit', function (event) {
            const front = document.getElementById('valid_id');
            const back = document.getElementById('valid_id_back');

            if (!front?.files?.length || !back?.files?.length) {
                event.preventDefault();
                alert(__cfg_barangay_system_profile_1.v17);
                return;
            }

            const frontFile = front.files[0];
            const backFile = back.files[0];
            if (
                frontFile.name === backFile.name &&
                frontFile.size === backFile.size &&
                frontFile.type === backFile.type
            ) {
                event.preventDefault();
                alert('The valid ID front and back cannot be the same picture.');
            }
        });
    }


    const profilePwdForm = document.getElementById('profilePwdClassificationForm');
    const profilePwdFront = document.getElementById('profile_pwd_id_front');
    const profilePwdBack = document.getElementById('profile_pwd_id_back');

    [profilePwdFront, profilePwdBack].forEach(function (input) {
        input?.addEventListener('change', async function () {
            const file = input.files?.[0] || null;
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert('PWD ID images must not exceed 5 MB each.');
                input.value = '';
                return;
            }

            const allowed = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!allowed.includes(file.type)) {
                alert('PWD ID proof must be JPG, JPEG, PNG, or PDF.');
                input.value = '';
                return;
            }

            await rejectDuplicatePair(
                profilePwdFront,
                profilePwdBack,
                'The PWD ID back appears to be the same picture as the front. Please provide the actual back side.'
            );
        });
    });

    if (profilePwdForm) {
        profilePwdForm.addEventListener('submit', function (event) {
            const frontFile = profilePwdFront?.files?.[0] || null;
            const backFile = profilePwdBack?.files?.[0] || null;

            if (!frontFile || !backFile) {
                event.preventDefault();
                alert('Please attach both the front and back of your PWD ID card.');
                return;
            }

            if (
                frontFile.name === backFile.name &&
                frontFile.size === backFile.size &&
                frontFile.type === backFile.type
            ) {
                event.preventDefault();
                alert('The PWD ID front and back cannot be the same picture.');
            }
        });
    }

    function resetScannerDetection() {
        scannerState.detectedPoints = null;
        scannerState.lastPoints = null;
        scannerState.stableFrames = 0;
        scannerState.frameTick = 0;
        setCaptureReady(false);

        if (scannerOverlay) {
            const ctx = scannerOverlay.getContext('2d');
            ctx.clearRect(0, 0, scannerOverlay.width, scannerOverlay.height);
        }
    }

    function orderPoints(points) {
        const sorted = points.slice().sort((a, b) => a.x - b.x);
        const left = sorted.slice(0, 2).sort((a, b) => a.y - b.y);
        const right = sorted.slice(2).sort((a, b) => a.y - b.y);

        return [left[0], right[0], right[1], left[1]];
    }

    function distance(a, b) {
        return Math.hypot(a.x - b.x, a.y - b.y);
    }

    function arePointsStable(current, previous) {
        if (!current || !previous) return false;

        const a = orderPoints(current);
        const b = orderPoints(previous);
        const movement = a.reduce((sum, point, index) => sum + distance(point, b[index]), 0) / 4;

        return movement < 42;
    }

    function syncScannerCanvases() {
        if (!scannerVideo || !scannerOverlay || !scannerWorkCanvas) return;

        const width = scannerVideo.videoWidth || 1280;
        const height = scannerVideo.videoHeight || 720;

        [scannerOverlay, scannerWorkCanvas].forEach(function (canvas) {
            if (canvas.width !== width || canvas.height !== height) {
                canvas.width = width;
                canvas.height = height;
            }
        });
    }

    function drawOverlay(points) {
        if (!scannerOverlay || !scannerVideo) return;

        syncScannerCanvases();
        const ctx = scannerOverlay.getContext('2d');
        ctx.clearRect(0, 0, scannerOverlay.width, scannerOverlay.height);

        if (!points || points.length !== 4) return;

        const ordered = orderPoints(points);
        ctx.lineWidth = Math.max(4, scannerOverlay.width * 0.004);
        ctx.strokeStyle = '#22c55e';
        ctx.fillStyle = 'rgba(34, 197, 94, 0.12)';
        ctx.beginPath();
        ctx.moveTo(ordered[0].x, ordered[0].y);
        ordered.slice(1).forEach(point => ctx.lineTo(point.x, point.y));
        ctx.closePath();
        ctx.fill();
        ctx.stroke();
    }

    function getCardPoints() {
        if (!window.cvReady || typeof cv === 'undefined' || !scannerVideo || !scannerWorkCanvas) return null;

        syncScannerCanvases();
        const ctx = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

        let src = null;
        let gray = null;
        let blur = null;
        let edges = null;
        let contours = null;
        let hierarchy = null;

        try {
            src = cv.imread(scannerWorkCanvas);
            gray = new cv.Mat();
            blur = new cv.Mat();
            edges = new cv.Mat();
            contours = new cv.MatVector();
            hierarchy = new cv.Mat();

            cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY);
            cv.GaussianBlur(gray, blur, new cv.Size(5, 5), 0);
            cv.Canny(blur, edges, 70, 180);
            cv.dilate(edges, edges, cv.Mat.ones(3, 3, cv.CV_8U));
            cv.findContours(edges, contours, hierarchy, cv.RETR_EXTERNAL, cv.CHAIN_APPROX_SIMPLE);

            let bestPoints = null;
            let bestArea = 0;
            const frameArea = src.rows * src.cols;
            const minimumArea = frameArea * 0.055;

            for (let i = 0; i < contours.size(); i++) {
                const contour = contours.get(i);
                const area = cv.contourArea(contour);

                if (area < minimumArea) {
                    contour.delete();
                    continue;
                }

                const perimeter = cv.arcLength(contour, true);
                const approx = new cv.Mat();
                cv.approxPolyDP(contour, approx, 0.025 * perimeter, true);

                if (approx.rows === 4 && cv.isContourConvex(approx)) {
                    const points = [];

                    for (let row = 0; row < 4; row++) {
                        points.push({
                            x: approx.data32S[row * 2],
                            y: approx.data32S[row * 2 + 1],
                        });
                    }

                    const ordered = orderPoints(points);
                    const width = Math.max(distance(ordered[0], ordered[1]), distance(ordered[3], ordered[2]));
                    const height = Math.max(distance(ordered[0], ordered[3]), distance(ordered[1], ordered[2]));
                    const ratio = width / Math.max(height, 1);

                    if (ratio >= 1.2 && ratio <= 2.35 && area > bestArea) {
                        bestArea = area;
                        bestPoints = points;
                    }
                }

                approx.delete();
                contour.delete();
            }

            return bestPoints;
        } catch (error) {
            console.warn('ID scanner detection failed:', error);
            return null;
        } finally {
            if (src) src.delete();
            if (gray) gray.delete();
            if (blur) blur.delete();
            if (edges) edges.delete();
            if (contours) contours.delete();
            if (hierarchy) hierarchy.delete();
        }
    }

    function cropGuideFallback() {
        if (!scannerVideo || !scannerWorkCanvas || !scannerResultCanvas) return null;

        syncScannerCanvases();
        const ctx = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

        const w = scannerWorkCanvas.width;
        const h = scannerWorkCanvas.height;
        const x = Math.round(w * 0.12);
        const y = Math.round(h * 0.23);
        const cropW = Math.round(w * 0.76);
        const cropH = Math.round(h * 0.54);

        scannerResultCanvas.width = cropW;
        scannerResultCanvas.height = cropH;
        scannerResultCanvas.getContext('2d').drawImage(scannerWorkCanvas, x, y, cropW, cropH, 0, 0, cropW, cropH);

        return scannerResultCanvas;
    }

    function cropDetectedCard(points) {
        if (!points || !window.cvReady || typeof cv === 'undefined' || !scannerVideo || !scannerWorkCanvas || !scannerResultCanvas) {
            return cropGuideFallback();
        }

        syncScannerCanvases();
        const ctx = scannerWorkCanvas.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(scannerVideo, 0, 0, scannerWorkCanvas.width, scannerWorkCanvas.height);

        const ordered = orderPoints(points);
        const outputWidth = Math.round(Math.max(distance(ordered[0], ordered[1]), distance(ordered[3], ordered[2])));
        const outputHeight = Math.round(Math.max(distance(ordered[0], ordered[3]), distance(ordered[1], ordered[2])));

        let src = null;
        let dst = null;
        let transform = null;
        let sourcePoints = null;
        let destinationPoints = null;

        try {
            src = cv.imread(scannerWorkCanvas);
            dst = new cv.Mat();
            sourcePoints = cv.matFromArray(4, 1, cv.CV_32FC2, [
                ordered[0].x, ordered[0].y,
                ordered[1].x, ordered[1].y,
                ordered[2].x, ordered[2].y,
                ordered[3].x, ordered[3].y,
            ]);
            destinationPoints = cv.matFromArray(4, 1, cv.CV_32FC2, [
                0, 0,
                outputWidth - 1, 0,
                outputWidth - 1, outputHeight - 1,
                0, outputHeight - 1,
            ]);

            transform = cv.getPerspectiveTransform(sourcePoints, destinationPoints);
            cv.warpPerspective(src, dst, transform, new cv.Size(outputWidth, outputHeight));

            scannerResultCanvas.width = outputWidth;
            scannerResultCanvas.height = outputHeight;
            cv.imshow(scannerResultCanvas, dst);

            return scannerResultCanvas;
        } catch (error) {
            console.warn('ID scanner crop failed:', error);
            return cropGuideFallback();
        } finally {
            if (src) src.delete();
            if (dst) dst.delete();
            if (transform) transform.delete();
            if (sourcePoints) sourcePoints.delete();
            if (destinationPoints) destinationPoints.delete();
        }
    }

    function analyzeCanvas(canvas) {
        const width = Math.min(640, canvas.width || 1);
        const height = Math.max(1, Math.round(width * ((canvas.height || 1) / (canvas.width || 1))));
        const sample = document.createElement('canvas');
        sample.width = width;
        sample.height = height;

        const ctx = sample.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(canvas, 0, 0, width, height);

        const data = ctx.getImageData(0, 0, width, height).data;
        const gray = new Float32Array(width * height);
        let total = 0;
        let dark = 0;
        let bright = 0;

        for (let i = 0, p = 0; i < data.length; i += 4, p++) {
            const value = data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114;
            gray[p] = value;
            total += value;
            if (value < 35) dark++;
            if (value > 245) bright++;
        }

        const mean = total / gray.length;
        let variance = 0;
        let edgeTotal = 0;
        let edgeCount = 0;

        for (let y = 1; y < height - 1; y++) {
            for (let x = 1; x < width - 1; x++) {
                const idx = y * width + x;
                variance += Math.pow(gray[idx] - mean, 2);

                const gx = Math.abs(gray[idx - 1] - gray[idx + 1]);
                const gy = Math.abs(gray[idx - width] - gray[idx + width]);
                const edge = gx + gy;
                edgeTotal += edge;
                if (edge > 28) edgeCount++;
            }
        }

        const usablePixels = Math.max(1, (width - 2) * (height - 2));

        return {
            brightness: mean,
            contrast: Math.sqrt(variance / usablePixels),
            sharpness: edgeTotal / usablePixels,
            edgeDensity: edgeCount / usablePixels,
            darkRatio: dark / gray.length,
            brightRatio: bright / gray.length,
        };
    }

    function canvasSignature(canvas) {
        const size = 32;
        const sig = document.createElement('canvas');
        sig.width = size;
        sig.height = size;

        const ctx = sig.getContext('2d', { willReadFrequently: true });
        ctx.drawImage(canvas, 0, 0, size, size);

        const data = ctx.getImageData(0, 0, size, size).data;
        const pixels = [];

        for (let i = 0; i < data.length; i += 4) {
            pixels.push((data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114) / 255);
        }

        return pixels;
    }

    function signatureDifference(a, b) {
        if (!a || !b || a.length !== b.length) return 1;

        let diff = 0;
        for (let i = 0; i < a.length; i++) {
            diff += Math.abs(a[i] - b[i]);
        }

        return diff / a.length;
    }

    function validateCapture(canvas, points, inputId) {
        if (!canvas) {
            return { passed: false, message: __cfg_barangay_system_profile_1.v18 };
        }

        if (points) {
            const ordered = orderPoints(points);
            const width = Math.max(distance(ordered[0], ordered[1]), distance(ordered[3], ordered[2]));
            const height = Math.max(distance(ordered[0], ordered[3]), distance(ordered[1], ordered[2]));
            const ratio = Math.max(width, height) / Math.max(1, Math.min(width, height));

            if (ratio < 1.2 || ratio > 2.35) {
                return { passed: false, message: __cfg_barangay_system_profile_1.v19 };
            }
        }

        const analysis = analyzeCanvas(canvas);

        if (analysis.brightness < 42) return { passed: false, message: __cfg_barangay_system_profile_1.v20 };
        if (analysis.brightness > 238) return { passed: false, message: __cfg_barangay_system_profile_1.v21 };
        if (analysis.contrast < 16) return { passed: false, message: __cfg_barangay_system_profile_1.v22 };
        if (analysis.edgeDensity < 0.010 || analysis.sharpness < 7) return { passed: false, message: __cfg_barangay_system_profile_1.v23 };
        if (analysis.darkRatio > 0.42) return { passed: false, message: __cfg_barangay_system_profile_1.v24 };
        if (analysis.brightRatio > 0.34) return { passed: false, message: __cfg_barangay_system_profile_1.v25 };

        const signature = canvasSignature(canvas);

        if (inputId === 'valid_id_back' && scannerState.signatures.valid_id) {
            const diff = signatureDifference(scannerState.signatures.valid_id, signature);

            if (diff < 0.075) {
                return { passed: false, message: __cfg_barangay_system_profile_1.v26 };
            }
        }

        return { passed: true, message: __cfg_barangay_system_profile_1.v27, signature };
    }

    function attachCanvasToInput(canvas, inputId, quality) {
        const input = document.getElementById(inputId);
        if (!input || !canvas) return;

        canvas.toBlob(function (blob) {
            if (!blob) {
                setScannerStatus(__cfg_barangay_system_profile_1.v18, 'error');
                scannerState.isCapturing = false;
                return;
            }

            const file = new File([blob], inputId === 'valid_id_back'
                ? `valid_id_back_scan_${Date.now()}.jpg`
                : `valid_id_front_scan_${Date.now()}.jpg`, { type: 'image/jpeg' });

            const transfer = new DataTransfer();
            transfer.items.add(file);
            input.files = transfer.files;
            scannerState.quality[inputId] = quality;
            scannerState.signatures[inputId] = quality.signature;
            input.dispatchEvent(new Event('change', { bubbles: true }));

            if (inputId === 'valid_id') {
                scannerState.waitingForFlip = true;
                setCaptureReady(false);
                setScannerStatus('Front side captured. Flip the ID to the back side.', 'ready');

                window.setTimeout(function () {
                    scannerState.sideIndex = 1;
                    scannerState.waitingForFlip = false;
                    scannerState.isCapturing = false;
                    resetScannerDetection();

                    if (scannerTargetText) scannerTargetText.textContent = __cfg_barangay_system_profile_1.v2;
                    setScannerStatus('Align the back side inside the guide. Capture will unlock when the image is ready.', 'waiting');
                    scannerLoop();
                }, 1600);

                return;
            }

            scannerState.completed = true;
            setScannerStatus(__cfg_barangay_system_profile_1.v30, 'ready');
            updateScanStatus();

            window.setTimeout(function () {
                if (scannerModal) scannerModal.hide();
            }, 800);

            scannerState.isCapturing = false;
        }, 'image/jpeg', 0.94);
    }

    function captureScannerFrame() {
        if (scannerState.isCapturing || scannerState.waitingForFlip) return;

        const side = scannerSides[scannerState.sideIndex] || scannerSides[0];

        if (!scannerState.readyToCapture) {
            setScannerStatus(`Not ready yet. Keep the ${side.display.toLowerCase()} steady inside the guide.`, 'error');
            return;
        }

        scannerState.isCapturing = true;
        setCaptureReady(false);

        const canvas = cropDetectedCard(scannerState.detectedPoints);
        const quality = validateCapture(canvas, scannerState.detectedPoints, side.inputId);

        if (!quality.passed) {
            setScannerStatus(__cfg_barangay_system_profile_1.v31.replace(':side', side.display).replace(':message', quality.message), 'error');
            scannerState.isCapturing = false;

            window.setTimeout(function () {
                setScannerStatus(__cfg_barangay_system_profile_1.v32.replace(':side', side.display), 'waiting');
            }, 1600);

            return;
        }

        setScannerStatus(__cfg_barangay_system_profile_1.v33.replace(':side', side.display), 'ready');
        attachCanvasToInput(canvas, side.inputId, quality);
    }

    function scannerLoop() {
        if (!scannerState.stream || !scannerVideo || scannerState.isCapturing || scannerState.completed) return;

        scannerState.rafId = requestAnimationFrame(scannerLoop);

        if (scannerVideo.readyState < 2) return;

        scannerState.frameTick++;
        const side = scannerSides[scannerState.sideIndex] || scannerSides[0];
        const points = getCardPoints();

        drawOverlay(points);

        if (points) {
            const stable = arePointsStable(points, scannerState.lastPoints);
            scannerState.stableFrames = stable ? scannerState.stableFrames + 1 : 1;
            scannerState.lastPoints = points;
            scannerState.detectedPoints = points;

            if (scannerState.stableFrames < 4) {
                setCaptureReady(false);
                setScannerStatus(`Hold the ${side.display.toLowerCase()} steady inside the guide…`, 'waiting');
                return;
            }

            // Quality is checked before enabling the button. Nothing is captured here.
            if (scannerState.frameTick % 3 === 0) {
                const candidate = cropDetectedCard(points);
                const quality = validateCapture(candidate, points, side.inputId);

                if (quality.passed) {
                    setCaptureReady(true);
                    setScannerStatus(`Ready to capture ${side.display.toLowerCase()}. Hold steady and press Capture.`, 'ready');
                } else {
                    setCaptureReady(false);
                    setScannerStatus(quality.message, 'waiting');
                }
            }

            return;
        }

        scannerState.stableFrames = 0;
        scannerState.lastPoints = null;
        scannerState.detectedPoints = null;

        // Detector misses can happen on some IDs/cameras. Use the fixed guide area as a
        // conservative quality fallback, but still require the user to press Capture.
        if (scannerState.frameTick % 6 === 0) {
            const candidate = cropGuideFallback();
            const quality = validateCapture(candidate, null, side.inputId);

            if (quality.passed) {
                setCaptureReady(true);
                setScannerStatus(`Ready to capture ${side.display.toLowerCase()}. Keep the ID inside the guide and press Capture.`, 'ready');
            } else {
                setCaptureReady(false);

                if (!window.cvReady || typeof cv === 'undefined') {
                    setScannerStatus(`Align the ${side.display.toLowerCase()} inside the guide. ${quality.message}`, 'waiting');
                } else {
                    setScannerStatus(`Keep all four corners of the ${side.display.toLowerCase()} inside the guide. ${quality.message}`, 'waiting');
                }
            }
        }
    }

    function stopScannerCamera() {
        if (scannerState.rafId) {
            cancelAnimationFrame(scannerState.rafId);
            scannerState.rafId = null;
        }

        if (scannerState.stream) {
            scannerState.stream.getTracks().forEach(track => track.stop());
            scannerState.stream = null;
        }

        if (scannerVideo) scannerVideo.srcObject = null;

        resetScannerDetection();
        scannerState.isCapturing = false;
        scannerState.waitingForFlip = false;
        setCaptureReady(false);
    }

    async function startScannerCamera() {
        if (!scannerVideo || !navigator.mediaDevices?.getUserMedia) {
            setScannerStatus(__cfg_barangay_system_profile_1.v37, 'error');
            return;
        }

        stopScannerCamera();
        scannerState.completed = false;
        scannerState.sideIndex = 0;
        scannerState.quality = {};
        scannerState.signatures = {};
        scannerState.readyToCapture = false;
        scannerState.frameTick = 0;
        setCaptureReady(false);

        if (scannerTargetText) scannerTargetText.textContent = __cfg_barangay_system_profile_1.v1;

        setScannerStatus(__cfg_barangay_system_profile_1.v38, 'waiting');

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                audio: false,
                video: {
                    facingMode: { ideal: 'environment' },
                    width: { ideal: 1920 },
                    height: { ideal: 1080 },
                },
            });

            scannerState.stream = stream;
            scannerVideo.srcObject = stream;
            await scannerVideo.play();
            syncScannerCanvases();
            setScannerStatus('Align the front side inside the guide. Capture will unlock when the image is ready.', 'waiting');
            scannerLoop();
        } catch (error) {
            console.warn('Camera access failed:', error);
            setScannerStatus(__cfg_barangay_system_profile_1.v40, 'error');
        }
    }

    if (scanBothSidesBtn) {
        scanBothSidesBtn.addEventListener('click', function () {
            if (!scannerModal) return;

            const front = document.getElementById('valid_id');
            const back = document.getElementById('valid_id_back');

            if (front) front.value = '';
            if (back) back.value = '';
            updateSelectedFilePreview(front, null);
            updateSelectedFilePreview(back, null);
            updateScanStatus();

            scannerModal.show();
        });
    }

    if (scannerModalElement) {
        scannerModalElement.addEventListener('shown.bs.modal', startScannerCamera);
        scannerModalElement.addEventListener('hidden.bs.modal', stopScannerCamera);
    }

    if (scannerRetakeBtn) {
        scannerRetakeBtn.addEventListener('click', startScannerCamera);
    }

    if (scannerManualCaptureBtn) {
        setCaptureReady(false);
        scannerManualCaptureBtn.addEventListener('click', function () {
            captureScannerFrame();
        });
    }

    document.addEventListener('opencv-ready', function () {
        if (scannerState.stream && !scannerState.isCapturing) {
            setCaptureReady(false);
            setScannerStatus('Scanner engine ready. Align the ID inside the guide and hold it steady.', 'waiting');
        }
    });

    updateScanStatus();
});

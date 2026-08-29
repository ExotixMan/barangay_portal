<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Resubmit Valid ID - Barangay Hulong Duhat</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/valid-id-resubmit.css') }}">
</head>

<body>

<main class="verification-page">

    <div class="verification-container">

        {{-- TOP NAV --}}
        <div class="top-navigation">
            <a href="{{ route('login') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                <span>Back to login</span>
            </a>

            <div class="secure-label">
                <i class="fas fa-lock"></i>
                Secure verification
            </div>
        </div>


        <section class="verification-card">

            {{-- HEADER --}}
            <header class="verification-header">

                <div class="brand-logo">
                    <img src="{{ asset('Images/logo.png') }}"
                         alt="Barangay Hulong Duhat Logo">
                </div>

                <div class="header-content">
                    <div class="verification-badge">
                        <i class="fas fa-shield-halved"></i>
                        Identity Verification
                    </div>

                    <h1>Resubmit your valid ID</h1>

                    <p>
                        Your previous ID submission needs to be replaced.
                        Scan or upload a clear copy of the
                        <strong>front and back</strong> of your valid ID.
                    </p>
                </div>

            </header>


            {{-- ALERTS --}}
            @if(session('warning'))
                <div class="system-alert alert-warning-custom">
                    <div class="alert-icon">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>

                    <div>
                        <strong>Attention required</strong>
                        <p>{{ session('warning') }}</p>
                    </div>
                </div>
            @endif


            @if(session('fail'))
                <div class="system-alert alert-danger-custom">
                    <div class="alert-icon">
                        <i class="fas fa-circle-xmark"></i>
                    </div>

                    <div>
                        <strong>Submission failed</strong>
                        <p>{{ session('fail') }}</p>
                    </div>
                </div>
            @endif


            @if($errors->any())
                <div class="system-alert alert-danger-custom">
                    <div class="alert-icon">
                        <i class="fas fa-circle-xmark"></i>
                    </div>

                    <div>
                        <strong>Please check your submission</strong>

                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif



            {{-- REJECTION REASON --}}
            @if(!empty($resident->valid_id_rejection_reason))
                <div class="rejection-card">

                    <div class="rejection-icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>

                    <div class="rejection-content">
                        <span>Barangay verification remarks</span>

                        <h3>Why your previous ID was rejected</h3>

                        <p>
                            {{ $resident->valid_id_rejection_reason }}
                        </p>
                    </div>

                </div>
            @endif



            {{-- PROCESS --}}
            <div class="verification-steps">

                <div class="process-step completed">
                    <div class="step-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>Previous ID reviewed</span>
                </div>

                <div class="step-line"></div>

                <div class="process-step active">
                    <div class="step-icon">
                        2
                    </div>
                    <span>Replace ID</span>
                </div>

                <div class="step-line"></div>

                <div class="process-step">
                    <div class="step-icon">
                        3
                    </div>
                    <span>Barangay review</span>
                </div>

            </div>



            <form
                method="POST"
                action="{{ route('valid-id.resubmit') }}"
                enctype="multipart/form-data"
                id="resubmitIdForm"
            >
                @csrf


                {{-- SCANNER SECTION --}}
                <section class="scanner-section">

                    <div class="section-heading">

                        <div>
                            <span class="section-eyebrow">
                                <i class="fas fa-id-card-clip"></i>
                                VALID ID SCANNER
                            </span>

                            <h2>Scan both sides of your ID</h2>

                            <p>
                                Use your device camera for the clearest and
                                easiest submission.
                            </p>
                        </div>

                        <span class="recommended-badge">
                            <i class="fas fa-star"></i>
                            Recommended
                        </span>

                    </div>


                    {{-- MAIN SCANNER CTA --}}
                    <div class="scanner-action-card">

                        <div class="scanner-action-icon">
                            <i class="fas fa-camera"></i>
                        </div>

                        <div class="scanner-action-info">
                            <strong>Automatic ID capture</strong>

                            <span>
                                Scan the front first, then flip the ID when prompted.
                            </span>
                        </div>

                        <button
                            type="button"
                            class="start-scan-button"
                            id="startIdScanner"
                        >
                            <i class="fas fa-camera-retro"></i>
                            <span>Start ID Scan</span>
                        </button>

                    </div>



                    {{-- HIDDEN ACTUAL INPUTS --}}
                    <input
                        type="file"
                        id="valid_id"
                        name="valid_id"
                        accept="image/jpeg,image/png,application/pdf"
                        class="visually-hidden"
                        required
                    >

                    <input
                        type="file"
                        id="valid_id_back"
                        name="valid_id_back"
                        accept="image/jpeg,image/png,application/pdf"
                        class="visually-hidden"
                        required
                    >



                    {{-- FRONT + BACK STATUS --}}
                    <div class="id-side-grid">

                        {{-- FRONT --}}
                        <div class="id-side-card" id="frontSideCard">

                            <div class="side-card-header">
                                <span class="side-number">1</span>

                                <div>
                                    <strong>Front side</strong>
                                    <span>Photo and information side</span>
                                </div>

                                <span class="side-status waiting"
                                      id="frontStatus">
                                    Required
                                </span>
                            </div>


                            <div class="id-preview" id="frontPreview">

                                <div class="empty-preview"
                                     id="frontEmptyState">

                                    <i class="far fa-id-card"></i>

                                    <strong>No front image yet</strong>

                                    <span>
                                        Scan your ID or choose a file below.
                                    </span>

                                </div>

                                <img
                                    src=""
                                    alt="Front ID preview"
                                    id="frontPreviewImage"
                                    class="d-none"
                                >

                            </div>


                            <div class="side-actions">

                                <label
                                    for="valid_id"
                                    class="upload-file-button"
                                >
                                    <i class="fas fa-upload"></i>
                                    Choose front file
                                </label>

                                <span class="file-name"
                                      id="frontFileName">
                                    No file selected
                                </span>

                            </div>

                        </div>



                        {{-- BACK --}}
                        <div class="id-side-card" id="backSideCard">

                            <div class="side-card-header">

                                <span class="side-number">2</span>

                                <div>
                                    <strong>Back side</strong>
                                    <span>Back information side</span>
                                </div>

                                <span
                                    class="side-status waiting"
                                    id="backStatus"
                                >
                                    Required
                                </span>

                            </div>


                            <div class="id-preview" id="backPreview">

                                <div class="empty-preview"
                                     id="backEmptyState">

                                    <i class="far fa-id-card"></i>

                                    <strong>No back image yet</strong>

                                    <span>
                                        Make sure this is the actual back side.
                                    </span>

                                </div>

                                <img
                                    src=""
                                    alt="Back ID preview"
                                    id="backPreviewImage"
                                    class="d-none"
                                >

                            </div>


                            <div class="side-actions">

                                <label
                                    for="valid_id_back"
                                    class="upload-file-button"
                                >
                                    <i class="fas fa-upload"></i>
                                    Choose back file
                                </label>

                                <span class="file-name"
                                      id="backFileName">
                                    No file selected
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- REQUIREMENTS --}}
                    <div class="requirements-box">

                        <div class="requirement-title">
                            <i class="fas fa-circle-info"></i>

                            <div>
                                <strong>For faster verification</strong>
                                <span>Make sure your ID meets these requirements.</span>
                            </div>
                        </div>

                        <div class="requirements-grid">

                            <div>
                                <i class="fas fa-check-circle"></i>
                                Entire ID is visible
                            </div>

                            <div>
                                <i class="fas fa-check-circle"></i>
                                Text is readable
                            </div>

                            <div>
                                <i class="fas fa-check-circle"></i>
                                Avoid glare and shadows
                            </div>

                            <div>
                                <i class="fas fa-check-circle"></i>
                                Front and back are different
                            </div>

                            <div>
                                <i class="fas fa-check-circle"></i>
                                JPG, PNG or PDF
                            </div>

                            <div>
                                <i class="fas fa-check-circle"></i>
                                Maximum 5MB per file
                            </div>

                        </div>

                    </div>

                </section>



                {{-- SUBMISSION STATUS --}}
                <div class="ready-status" id="readyStatus">

                    <div class="ready-status-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>

                    <div>
                        <strong id="readyStatusTitle">
                            Complete your ID submission
                        </strong>

                        <span id="readyStatusText">
                            Front and back copies are required before submission.
                        </span>
                    </div>

                </div>



                {{-- SUBMIT --}}
                <button
                    type="submit"
                    class="submit-button"
                    id="submitIdButton"
                    disabled
                >

                    <span class="submit-text">
                        <i class="fas fa-paper-plane"></i>
                        Submit ID for review
                    </span>

                    <span class="submit-loading d-none">
                        <i class="fas fa-circle-notch fa-spin"></i>
                        Submitting...
                    </span>

                </button>


                <div class="privacy-note">
                    <i class="fas fa-lock"></i>

                    <span>
                        Your ID is used only for identity and residency
                        verification by authorized barangay personnel.
                    </span>
                </div>

            </form>

        </section>

    </div>

</main>



{{-- =========================================================
     ID SCANNER MODAL
========================================================= --}}
<div
    class="modal fade scanner-modal"
    id="idScannerModal"
    tabindex="-1"
    aria-labelledby="idScannerModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <div>
                    <span class="modal-eyebrow">
                        CAMERA SCANNER
                    </span>

                    <h5 class="modal-title"
                        id="idScannerModalLabel">
                        Scan your valid ID
                    </h5>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                <div class="scanner-instruction">
                    <i class="fas fa-lightbulb"></i>

                    <span>
                        Place the entire ID inside the guide.
                        Keep your hands steady and avoid light reflections.
                    </span>
                </div>


                <div class="camera-layout">

                    <div class="scanner-stage">

                        <video
                            id="idScannerVideo"
                            autoplay
                            playsinline
                            muted
                        ></video>

                        <canvas id="idCaptureCanvas"></canvas>

                        <div class="scanner-guide">
                            <span class="guide-corner top-left"></span>
                            <span class="guide-corner top-right"></span>
                            <span class="guide-corner bottom-left"></span>
                            <span class="guide-corner bottom-right"></span>

                            <div class="guide-center">
                                <i class="far fa-id-card"></i>
                            </div>
                        </div>


                        <div class="scanner-side-banner">

                            <span
                                class="scanner-step-pill"
                                id="scannerStep"
                            >
                                1 / 2
                            </span>

                            <div>
                                <strong id="scannerSideTitle">
                                    FRONT SIDE
                                </strong>

                                <span id="scannerSideDescription">
                                    Position the front side inside the frame.
                                </span>
                            </div>

                        </div>


                        <div class="camera-status">

                            <span class="camera-status-dot"></span>

                            <span id="cameraStatusText">
                                Starting camera...
                            </span>

                        </div>



                        {{-- TRANSITION --}}
                        <div
                            class="flip-overlay d-none"
                            id="flipOverlay"
                        >

                            <div class="flip-card">

                                <div class="flip-icon">
                                    <i class="fas fa-rotate"></i>
                                </div>

                                <strong>
                                    Front side captured
                                </strong>

                                <span>
                                    Flip your ID. We will capture the back side next.
                                </span>

                            </div>

                        </div>

                    </div>


                    <aside class="scanner-sidebar">

                        <div class="sidebar-title">
                            <span>Scan progress</span>
                            <strong id="scanProgressLabel">0%</strong>
                        </div>


                        <div class="scan-progress-bar">
                            <span id="scanProgressBar"></span>
                        </div>


                        <div
                            class="scan-check-item active"
                            id="scanFrontCheck"
                        >
                            <span class="check-circle">
                                1
                            </span>

                            <div>
                                <strong>Front side</strong>
                                <small id="frontScannerStatus">
                                    Waiting for capture
                                </small>
                            </div>
                        </div>


                        <div
                            class="scan-check-item"
                            id="scanBackCheck"
                        >
                            <span class="check-circle">
                                2
                            </span>

                            <div>
                                <strong>Back side</strong>
                                <small id="backScannerStatus">
                                    Not started
                                </small>
                            </div>
                        </div>


                        <div class="scanner-tip">
                            <i class="fas fa-sun"></i>

                            <p>
                                Use a bright area, but avoid direct light
                                reflecting from laminated IDs.
                            </p>
                        </div>

                    </aside>

                </div>


                <div class="scanner-controls">

                    <button
                        type="button"
                        class="scanner-button secondary"
                        id="restartScannerButton"
                    >
                        <i class="fas fa-rotate-right"></i>
                        Restart
                    </button>

                    <button
                        type="button"
                        class="scanner-button primary"
                        id="captureIdButton"
                        disabled
                    >
                        <i class="fas fa-camera"></i>
                        Capture front side
                    </button>

                    <button
                        type="button"
                        class="scanner-button cancel"
                        data-bs-dismiss="modal"
                    >
                        <i class="fas fa-xmark"></i>
                        Cancel
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('js/valid-id-resubmit.js') }}"></script>

</body>
</html>
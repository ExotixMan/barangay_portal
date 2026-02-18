<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Clearance Application Form</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
</head>

<body>
    <!-- Back Button -->
    <div class="back-button-container container-fluid px-3 px-md-4">
        <a href="{{ route('clearance') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Clearance Info
        </a>
    </div>

    <!-- Page Header -->
    <div class="form-page-header container-fluid px-3 px-md-4">
        <h1 class="mb-3"><i class="fas fa-file-signature"></i> Barangay Clearance Application</h1>
        <p class="mb-0">Fill out the form below to apply for your Barangay Clearance</p>
    </div>

    <!-- Application Form -->
    <section class="application-form-section container-fluid px-3 px-md-4" id="apply-form">
        <div class="container form-container px-0">
            <form id="requirementForm" class="requirement-form">
                <div class="form-progress">
                    <div class="progress-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <span>Personal Info</span>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <span>Requirements</span>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-circle">3</div>
                            <span>Review & Submit</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                </div>

                <!-- Step 1: Personal Information -->
                <div class="form-step active" id="step1">
                    <h3><i class="fas fa-user"></i> Personal Information</h3>

                    <div class="form-grid row g-3">
                        <div class="form-group col-md-12">
                            <label for="firstName">
                                <i class="fas fa-user"></i> First Name *
                            </label>
                            <input type="text" id="firstName" name="firstName" required
                                placeholder="Enter your first name" class="form-control">
                            <div class="form-hint">As it appears on your ID</div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="middleName">
                                <i class="fas fa-user"></i> Middle Name
                            </label>
                            <input type="text" id="middleName" name="middleName"
                                placeholder="Enter your middle name" class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="lastName">
                                <i class="fas fa-user"></i> Last Name *
                            </label>
                            <input type="text" id="lastName" name="lastName" required
                                placeholder="Enter your last name" class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="suffix">
                                <i class="fas fa-user-tag"></i> Suffix
                            </label>
                            <select id="suffix" name="suffix" class="form-control">
                                <option value="">Select suffix</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid row g-3">
                        <div class="form-group col-md-12">
                            <label for="birthdate">
                                <i class="fas fa-birthday-cake"></i> Date of Birth *
                            </label>
                            <input type="date" id="birthdate" name="birthdate" required class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="gender">
                                <i class="fas fa-venus-mars"></i> Gender *
                            </label>
                            <select id="gender" name="gender" required class="form-control">
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-group full-width">
                        <label for="address">
                            <i class="fas fa-home"></i> Complete Address *
                        </label>
                        <textarea id="address" name="address" required rows="3"
                            placeholder="House No., Street, Subdivision/Village, Barangay Hulo, Malabon City" class="form-control"></textarea>
                        <div class="form-hint">Include specific landmarks for accurate verification</div>
                    </div>

                    <div class="form-grid row g-3">
                        <div class="form-group col-md-12">
                            <label for="contactNumber">
                                <i class="fas fa-phone"></i> Contact Number *
                            </label>
                            <input type="tel" id="contactNumber" name="contactNumber" required
                                placeholder="09XX XXX XXXX" class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email Address *
                            </label>
                            <input type="email" id="email" name="email" required
                                placeholder="your.email@example.com" class="form-control">
                        </div>
                    </div>

                    <div class="form-actions row g-2 btns">
                        <div class="col-12 col-md-auto">
                            <a href="{{ route('clearance') }}" class="btn-prev" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                        <div class="col-12 col-md-auto ms-md-auto btns">
                            <button type="button" class="btn-next" data-next="step2">Next Step <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Requirements -->
                <div class="form-step" id="step2">
                    <h3><i class="fas fa-file-upload"></i> Upload Requirements</h3>

                    <div class="upload-section row g-4">
                        <div class="upload-group col-12">
                            <label for="validId">
                                <i class="fas fa-id-card"></i> Valid ID *
                                <span class="upload-size">Max: 5MB (JPG, PNG, PDF)</span>
                            </label>
                            <div class="upload-area" id="validIdUpload">
                                <i class="fas fa-cloud-upload-alt mb-3"></i>
                                <p class="mb-0">Drag & drop your valid ID or <span>browse</span></p>
                                <input type="file" id="validId" name="validId" accept=".jpg,.jpeg,.png,.pdf"
                                    hidden>
                            </div>
                            <div class="upload-preview mt-2" id="validIdPreview"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="purpose">
                            <i class="fas fa-bullseye"></i> Purpose of Clearance *
                        </label>
                        <select id="purpose" name="purpose" required class="form-control">
                            <option value="">Select purpose</option>
                            <option value="employment">Employment</option>
                            <option value="business">Business Permit</option>
                            <option value="scholarship">Scholarship</option>
                            <option value="travel">Travel/Abroad</option>
                            <option value="bank">Bank Transaction</option>
                            <option value="government">Government Transaction</option>
                            <option value="school">School Requirement</option>
                            <option value="other">Other</option>
                        </select>
                        <textarea id="purposeOther" name="purposeOther"
                            placeholder="Please specify other purpose..." rows="2"
                            style="display: none; margin-top: 10px;" class="form-control"></textarea>
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step1"><i
                                    class="fas fa-arrow-left"></i> Previous</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="button" class="btn-next" data-next="step3">Next Step <i
                                    class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Review & Payment -->
                <div class="form-step" id="step3">
                    <h3><i class="fas fa-check-circle"></i> Review & Submit</h3>

                    <div class="review-section">
                        <div class="review-card">
                            <h4><i class="fas fa-user-check"></i> Personal Information</h4>
                            <div class="review-content" id="reviewPersonal"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-file-check"></i> Uploaded Documents</h4>
                            <div class="review-content" id="reviewDocuments"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-receipt"></i> Payment & Pickup</h4>
                            <div class="review-content">
                                <div class="payment-summary">
                                    <div class="payment-item">
                                        <span>Barangay Clearance Fee</span>
                                        <span>₱100.00</span>
                                    </div>
                                    <div class="payment-item">
                                        <span>Processing Fee</span>
                                        <span>₱0.00</span>
                                    </div>
                                    <div class="payment-item total">
                                        <span>Total Amount to Pay</span>
                                        <span>₱100.00</span>
                                    </div>
                                </div>

                                <div class="pickup-instructions">
                                    <div class="pickup-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div>
                                            <h5><i class="fas fa-building"></i> Payment & Pickup Location
                                            </h5>
                                            <p>Barangay Hulong Duhat Hall<br>
                                                1 M. Blas St, Malabon, Metro Manila</p>
                                        </div>
                                    </div>

                                    <div class="pickup-info">
                                        <i class="fas fa-clock"></i>
                                        <div>
                                            <h5><i class="fas fa-calendar-alt"></i> Office Hours</h5>
                                            <p>Monday - Friday: 8:00 AM - 5:00 PM<br>
                                                Saturday: 8:00 AM - 12:00 PM</p>
                                        </div>
                                    </div>

                                    <div class="pickup-info">
                                        <i class="fas fa-user-check"></i>
                                        <div>
                                            <h5><i class="fas fa-id-card"></i> What to Bring</h5>
                                            <ul>
                                                <li>Valid ID (same as uploaded)</li>
                                                <li>Printed Reference Number</li>
                                                <li>Exact amount of ₱100.00</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="benefits-section">
                        <h4><i class="fas fa-star"></i> Benefits of Online Request</h4>
                        <div class="benefits-grid row g-12">
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-clock mb-3"></i>
                                <h5>No Long Lines</h5>
                                <p class="mb-0">Skip the queue when you arrive</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-check-circle mb-3"></i>
                                <h5>Pre-verified</h5>
                                <p class="mb-0">Your documents are pre-checked online</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-bolt mb-3"></i>
                                <h5>Fast Processing</h5>
                                <p class="mb-0">Your clearance is ready for pickup</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section">
                        <label class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required>
                            <span class="checkmark"></span>
                            I hereby certify that all information provided is true and correct. I understand
                            that any false statement may result in denial of my application.
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="privacy" name="privacy" required>
                            <span class="checkmark"></span>
                            <p style='margin-bottom: 0;'>I agree to the <a href="#">Privacy Policy</a> and <a href="#">Terms of
                                Service</a> of Barangay Hulo Online Services.</p>
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="pickup" name="pickup" required>
                            <span class="checkmark"></span>
                            I understand that I need to pay ₱100.00 at the Barangay Hall to receive my
                            clearance. I will bring my valid ID and reference number for verification.
                        </label>
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step2"><i
                                    class="fas fa-arrow-left"></i> Previous</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="submit" class="btn-submit" id="submitApplication">
                                <span class="btn-text">Submit Application</span>
                                <div class="spinner">
                                    <div class="spinner-dot"></div>
                                    <div class="spinner-dot"></div>
                                    <div class="spinner-dot"></div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Application Status -->
            <div class="application-status" id="statusMessage" style="display: none;">
                <div class="status-content">
                    <i class="fas fa-check-circle"></i>
                    <h3>Application Submitted Successfully!</h3>
                    <p>Your application has been received. Your reference number is: <strong
                            id="referenceNumber">BC-2025-001234</strong></p>
                    <div class="status-actions row g-2 justify-content-center">
                        <div class="col-12 col-md-auto">
                            <button class="btn-download w-100">
                                <i class="fas fa-download"></i> Download Receipt
                            </button>
                        </div>
                        <div class="col-12 col-md-auto">
                            <button class="btn-track w-100">
                                <i class="fas fa-search"></i> Track Application
                            </button>
                        </div>
                        <div class="col-12 col-md-auto">
                            <a href="{{ route('clearance') }}" class="btn-back-info w-100" style="text-decoration: none;">
                                <i class="fas fa-arrow-left"></i> Back to Info Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Add QR Code library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('requirementForm');
            const steps = document.querySelectorAll('.form-step');
            const progressFill = document.getElementById('progressFill');
            const progressSteps = document.querySelectorAll('.step');
            const nextButtons = document.querySelectorAll('.btn-next');
            const prevButtons = document.querySelectorAll('.btn-prev');
            const purposeSelect = document.getElementById('purpose');
            const purposeOther = document.getElementById('purposeOther');
            const termsCheckbox = document.getElementById('terms');
            const privacyCheckbox = document.getElementById('privacy');
            const pickupCheckbox = document.getElementById('pickup');
            const submitButton = document.getElementById('submitApplication');
            const statusMessage = document.getElementById('statusMessage');
            const referenceNumber = document.getElementById('referenceNumber');

            let currentStep = 1;
            const totalSteps = 3;

            // Show purpose other textarea when "Other" is selected
            purposeSelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    purposeOther.style.display = 'block';
                    purposeOther.required = true;
                } else {
                    purposeOther.style.display = 'none';
                    purposeOther.required = false;
                    purposeOther.value = '';
                }
            });

            // Next button functionality
            nextButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const nextStep = this.getAttribute('data-next');
                    if (validateStep(currentStep)) {
                        goToStep(nextStep);
                    }
                });
            });

            // Previous button functionality
            prevButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const prevStep = this.getAttribute('data-prev');
                    goToStep(prevStep);
                });
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate step 3 specific fields
                if (validateStep(3)) {
                    if (termsCheckbox.checked && privacyCheckbox.checked && pickupCheckbox.checked) {
                        submitApplication();
                    } else {
                        alert('Please accept all terms and conditions to proceed.');
                    }
                }
            });

            // File upload functionality
            setupFileUpload('validIdUpload', 'validId', 'validIdPreview');

            // Functions
            function goToStep(stepId) {
                // Hide all steps
                steps.forEach(step => {
                    step.classList.remove('active');
                });
                
                // Show current step
                document.getElementById(stepId).classList.add('active');
                
                // Update current step
                currentStep = parseInt(stepId.replace('step', ''));
                updateProgress();
                
                // Update review section if on step 3
                if (currentStep === 3) {
                    updateReview();
                }
            }

            function updateProgress() {
                // Calculate progress percentage
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                progressFill.style.width = `${progressPercentage}%`;
                
                // Update step indicators
                progressSteps.forEach(step => {
                    const stepNumber = parseInt(step.getAttribute('data-step'));
                    step.classList.remove('active');
                    if (stepNumber === currentStep) {
                        step.classList.add('active');
                    }
                });
            }

            function validateStep(stepNumber) {
                let isValid = true;
                const currentStepElement = document.getElementById(`step${stepNumber}`);
                
                // Clear previous error messages
                const existingErrors = currentStepElement.querySelectorAll('.error-message');
                existingErrors.forEach(error => error.remove());
                
                // Reset field styles
                const allFields = currentStepElement.querySelectorAll('input, select, textarea');
                allFields.forEach(field => {
                    field.style.borderColor = '#eee';
                    field.style.boxShadow = 'none';
                });
                
                // Validate required fields
                const requiredFields = currentStepElement.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    // Skip hidden fields
                    if (field.style.display === 'none') {
                        return;
                    }
                    
                    let fieldValue = field.value.trim();
                    
                    // For file inputs, check if files are selected
                    if (field.type === 'file') {
                        if (!field.files || field.files.length === 0) {
                            isValid = false;
                            showFieldError(field, 'This file is required');
                            return;
                        }
                        
                        // Validate file size
                        const file = field.files[0];
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        if (file.size > maxSize) {
                            isValid = false;
                            showFieldError(field, `File size must be less than ${maxSize / (1024 * 1024)}MB`);
                            return;
                        }
                        
                        // Validate file type
                        const validTypes = ['.jpg', '.jpeg', '.png', '.pdf'];
                        const fileExtension = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));
                        if (!validTypes.includes(fileExtension)) {
                            isValid = false;
                            showFieldError(field, 'Please upload JPG, PNG, or PDF files only');
                            return;
                        }
                    }
                    
                    // For text inputs, select, and textarea
                    else if (!fieldValue) {
                        isValid = false;
                        showFieldError(field, 'This field is required');
                        return;
                    }
                    
                    // Validate email format
                    if (field.type === 'email') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, 'Please enter a valid email address');
                        }
                    }
                    
                    // Validate phone number
                    if (field.id === 'contactNumber') {
                        const phoneRegex = /^09\d{9}$/;
                        const cleanedNumber = fieldValue.replace(/\s/g, '');
                        if (!phoneRegex.test(cleanedNumber)) {
                            isValid = false;
                            showFieldError(field, 'Please enter a valid Philippine mobile number (09XXXXXXXXX)');
                        }
                    }
                });
                
                // Validate file uploads for step 2
                if (stepNumber === 2) {
                    const fileInput = document.getElementById('validId');
                    if (!fileInput.files || fileInput.files.length === 0) {
                        isValid = false;
                        const uploadArea = document.getElementById('validIdUpload');
                        uploadArea.style.borderColor = '#ff4444';
                        uploadArea.style.backgroundColor = 'rgba(255, 68, 68, 0.05)';
                    }
                }
                
                return isValid;
            }

            function showFieldError(field, message) {
                field.style.borderColor = '#ff4444';
                field.style.boxShadow = '0 0 0 3px rgba(255, 68, 68, 0.1)';
                
                // Add error message
                const errorMessage = document.createElement('div');
                errorMessage.className = 'error-message';
                errorMessage.style.color = '#ff4444';
                errorMessage.style.fontSize = '0.85rem';
                errorMessage.style.marginTop = '5px';
                errorMessage.textContent = message;
                field.parentNode.appendChild(errorMessage);
            }

            function setupFileUpload(uploadAreaId, fileInputId, previewId) {
                const uploadArea = document.getElementById(uploadAreaId);
                const fileInput = document.getElementById(fileInputId);
                const previewArea = document.getElementById(previewId);
                
                // Click to upload
                uploadArea.addEventListener('click', () => fileInput.click());
                
                // Drag and drop
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, preventDefaults, false);
                });
                
                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                
                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, highlight, false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, unhighlight, false);
                });
                
                function highlight() {
                    uploadArea.style.borderColor = '#C62828';
                    uploadArea.style.backgroundColor = 'rgba(198, 40, 40, 0.05)';
                }
                
                function unhighlight() {
                    uploadArea.style.borderColor = '#ddd';
                    uploadArea.style.backgroundColor = '#fafafa';
                }
                
                // Handle file drop
                uploadArea.addEventListener('drop', handleDrop, false);
                
                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        handleFile(files[0]);
                    }
                }
                
                // Handle file selection
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        handleFile(this.files[0]);
                    }
                });
                
                function handleFile(file) {
                    // Validate file size
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    if (file.size > maxSize) {
                        alert(`File size must be less than ${maxSize / (1024 * 1024)}MB`);
                        return;
                    }
                    
                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    if (!validTypes.includes(file.type.toLowerCase())) {
                        // Also check by extension for older browsers
                        const fileExtension = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));
                        const validExtensions = ['.jpg', '.jpeg', '.png', '.pdf'];
                        if (!validExtensions.includes(fileExtension)) {
                            alert('Invalid file type. Please upload JPG, PNG, or PDF files only.');
                            return;
                        }
                    }
                    
                    // Show preview
                    const fileName = file.name;
                    const fileSize = formatFileSize(file.size);
                    
                    previewArea.innerHTML = `
                        <div class="file-preview">
                            <i class="fas fa-${file.type.includes('image') ? 'file-image' : 'file-pdf'}"></i>
                            <div class="file-info">
                                <div class="file-name">${fileName}</div>
                                <div class="file-size">${fileSize}</div>
                            </div>
                            <button type="button" class="remove-file" data-file-input="${fileInputId}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    previewArea.classList.add('active');
                    
                    // Add event listener to remove button
                    const removeButton = previewArea.querySelector('.remove-file');
                    removeButton.addEventListener('click', function() {
                        removeFile(fileInputId);
                    });
                    
                    // Reset upload area style
                    uploadArea.style.borderColor = '#ddd';
                    uploadArea.style.backgroundColor = '#fafafa';
                }
            }

            function removeFile(fileInputId) {
                const fileInput = document.getElementById(fileInputId);
                const previewId = `${fileInputId}Preview`;
                const previewArea = document.getElementById(previewId);
                const uploadArea = document.getElementById(`${fileInputId}Upload`);
                
                fileInput.value = '';
                previewArea.innerHTML = '';
                previewArea.classList.remove('active');
                uploadArea.style.borderColor = '#ddd';
                uploadArea.style.backgroundColor = '#fafafa';
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function updateReview() {
                // Personal Information
                const personalInfo = document.getElementById('reviewPersonal');
                const suffix = document.getElementById('suffix').value;
                const suffixDisplay = suffix ? ` ${suffix}` : '';
                
                personalInfo.innerHTML = `
                    <div class="review-item">
                        <div class="review-label">Full Name</div>
                        <div class="review-value">${document.getElementById('firstName').value} ${document.getElementById('middleName').value} ${document.getElementById('lastName').value}${suffixDisplay}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Date of Birth</div>
                        <div class="review-value">${formatDate(document.getElementById('birthdate').value)}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Gender</div>
                        <div class="review-value">${capitalizeFirstLetter(document.getElementById('gender').value)}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Address</div>
                        <div class="review-value">${document.getElementById('address').value}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Contact</div>
                        <div class="review-value">${document.getElementById('contactNumber').value}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Email</div>
                        <div class="review-value">${document.getElementById('email').value}</div>
                    </div>
                `;
                
                // Document Information
                const documentInfo = document.getElementById('reviewDocuments');
                const purposeValue = document.getElementById('purpose').value;
                let purposeText = '';
                if (purposeValue === 'other') {
                    purposeText = document.getElementById('purposeOther').value || 'Other';
                } else {
                    const purposeSelect = document.getElementById('purpose');
                    purposeText = purposeSelect.options[purposeSelect.selectedIndex].text;
                }
                
                const fileInput = document.getElementById('validId');
                const hasFile = fileInput.files && fileInput.files.length > 0;
                
                documentInfo.innerHTML = `
                    <div class="review-item">
                        <div class="review-label">Purpose</div>
                        <div class="review-value">${purposeText}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Documents Uploaded</div>
                        <div class="review-value">${hasFile ? '1 file uploaded' : 'No files uploaded'}</div>
                    </div>
                `;
            }

            function formatDate(dateString) {
                if (!dateString) return 'Not specified';
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return 'Invalid date';
                
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            function capitalizeFirstLetter(string) {
                if (!string) return '';
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            function submitApplication() {
                submitButton.classList.add('loading');
                submitButton.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    // Generate reference number
                    const refNum = 'BC-' + new Date().getFullYear() + '-' + Math.floor(100000 + Math.random() * 900000);
                    referenceNumber.textContent = refNum;
                    
                    // Show success message
                    form.style.display = 'none';
                    statusMessage.style.display = 'block';
                    
                    // Scroll to status message
                    statusMessage.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    
                    // Reset button state
                    submitButton.classList.remove('loading');
                    submitButton.disabled = false;
                }, 2000);
            }

            // Download receipt button
            const downloadButton = document.querySelector('.btn-download');
            if (downloadButton) {
                downloadButton.addEventListener('click', function() {
                    const applicantName = `${document.getElementById('firstName').value} ${document.getElementById('lastName').value}`;
                    const referenceNum = document.getElementById('referenceNumber').textContent;
                    const currentDate = new Date().toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    });
                    
                    // Create QR code data URL using Promise
                    const qrData = `Reference: ${referenceNum}\nName: ${applicantName}\nDate: ${currentDate}\nStatus: Processing`;
                    
                    QRCode.toDataURL(qrData, { width: 150 })
                        .then(qrDataUrl => {
                            const printContent = `<!DOCTYPE html>
                            <html>
                            <head>
                                <title>Barangay Clearance Application Receipt</title>
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                                <style>
                                    body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
                                    .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #C62828; padding-bottom: 20px; }
                                    .header h1 { color: #C62828; margin-bottom: 10px; font-size: 24px; }
                                    .header .subtitle { color: #666; font-size: 14px; }
                                    .details { margin: 30px 0; }
                                    .detail-row { display: flex; margin: 10px 0; padding: 5px 0; border-bottom: 1px solid #eee; }
                                    .detail-label { font-weight: bold; color: #333; width: 180px; }
                                    .detail-value { color: #666; flex: 1; }
                                    .instructions { margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px; }
                                    .instructions h3 { color: #C62828; margin-bottom: 15px; }
                                    .step { margin: 10px 0; padding-left: 20px; }
                                    .important-note { background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800; margin: 20px 0; font-size: 14px; }
                                    .footer { text-align: center; margin-top: 40px; color: #666; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
                                    .qr-code { text-align: center; margin: 20px 0; padding: 20px; background: white; border-radius: 10px; }
                                    .qr-code img { width: 150px; height: 150px; margin: 10px 0; border: 1px solid #eee; padding: 10px; background: white; }
                                    .qr-code p { color: #C62828; font-weight: 600; margin-bottom: 10px; }
                                    @media print { 
                                        body { margin: 20px; font-size: 12px; }
                                        .no-print { display: none !important; }
                                        .header h1 { font-size: 20px; }
                                        .qr-code img { border: 1px solid #ccc; }
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="header">
                                    <h1><i class="fas fa-file-signature"></i> Barangay Clearance Application Receipt</h1>
                                    <p class="subtitle">Barangay Hulong Duhat, Malabon City</p>
                                </div>
                                
                                <div class="details">
                                    <h3>Application Details</h3>
                                    <div class="detail-row">
                                        <div class="detail-label">Reference Number:</div>
                                        <div class="detail-value"><strong>${referenceNum}</strong></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Applicant Name:</div>
                                        <div class="detail-value">${applicantName}</div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Date Submitted:</div>
                                        <div class="detail-value">${currentDate}</div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Status:</div>
                                        <div class="detail-value"><span style="color: #C62828; font-weight: 600;">Submitted for Processing</span></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Amount to Pay:</div>
                                        <div class="detail-value"><strong>₱100.00</strong></div>
                                    </div>
                                </div>
                                
                                <div class="qr-code">
                                    <p><i class="fas fa-qrcode"></i> Scan to Verify Application Status</p>
                                    <img src="${qrDataUrl}" alt="QR Code for ${referenceNum}">
                                    <p style="font-size: 12px; color: #666; margin-top: 10px;">Reference: ${referenceNum}</p>
                                </div>
                                
                                <div class="instructions">
                                    <h3><i class="fas fa-clipboard-list"></i> Next Steps</h3>
                                    <div class="step"><strong>1.</strong> Wait for processing confirmation (1-3 business days)</div>
                                    <div class="step"><strong>2.</strong> Visit Barangay Hall during office hours</div>
                                    <div class="step"><strong>3.</strong> Present this receipt and valid ID</div>
                                    <div class="step"><strong>4.</strong> Pay ₱100.00 at the cashier</div>
                                    <div class="step"><strong>5.</strong> Receive your barangay clearance</div>
                                    
                                    <div class="important-note">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Important Note:</strong> Bring this receipt and the same valid ID you used during application when claiming your clearance. Payment must be made in cash at the barangay hall.
                                    </div>
                                    
                                    <p><strong><i class="fas fa-map-marker-alt"></i> Barangay Hall Location:</strong><br>
                                    1 M. Blas St, Malabon, Metro Manila<br>
                                    <strong><i class="fas fa-clock"></i> Office Hours:</strong><br>
                                    Monday - Friday: 8:00 AM - 5:00 PM<br>
                                    Saturday: 8:00 AM - 12:00 PM<br>
                                    <strong><i class="fas fa-phone"></i> Contact:</strong> (02) 123-4567</p>
                                </div>
                                
                                <div class="footer">
                                    <p>This is an electronically generated receipt. No signature is required.</p>
                                    <p>Barangay Hulong Duhat Online Services © ${new Date().getFullYear()}</p>
                                </div>
                                
                                <div class="no-print" style="text-align: center; margin-top: 30px;">
                                    <button onclick="window.print()" style="padding: 12px 25px; background: #C62828; color: white; border: none; border-radius: 8px; cursor: pointer; margin: 5px; font-size: 14px; font-weight: 600;">
                                        <i class="fas fa-print"></i> Print Receipt
                                    </button>
                                    <button onclick="window.close()" style="padding: 12px 25px; background: #666; color: white; border: none; border-radius: 8px; cursor: pointer; margin: 5px; font-size: 14px; font-weight: 600;">
                                        <i class="fas fa-times"></i> Close Window
                                    </button>
                                </div>
                            </body>
                            </html>
                            `;
                            
                            const printWindow = window.open('', '_blank', 'width=800,height=600');
                            if (printWindow) {
                                printWindow.document.write(printContent);
                                printWindow.document.close();
                            } else {
                                alert('Please allow pop-ups to print the receipt');
                            }
                        })
                        .catch(error => {
                            console.error('Error generating QR code:', error);
                            alert('Error generating QR code. Please try again.');
                        });
                });
            }
            
            // Track status button
            const trackButton = document.querySelector('.btn-track');
            if (trackButton) {
                trackButton.addEventListener('click', function() {
                    alert('Status tracking feature coming soon! Your application is currently being processed.');
                });
            }

            // Initialize first step
            updateProgress();
        });
    </script>
</body>

</html>
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
            <form method="POST" action="{{ route('clearance.store') }}"  enctype="multipart/form-data" id="requirementForm" class="requirement-form">
                @csrf
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
                            <input type="text" id="firstName" name="first_name" required
                                placeholder="Enter your first name" class="form-control">
                            <div class="form-hint">As it appears on your ID</div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="middleName">
                                <i class="fas fa-user"></i> Middle Name
                            </label>
                            <input type="text" id="middleName" name="middle_name"
                                placeholder="Enter your middle name" class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="lastName">
                                <i class="fas fa-user"></i> Last Name *
                            </label>
                            <input type="text" id="lastName" name="last_name" required
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
                            <input type="tel" id="contactNumber" name="contact_number" required
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
                                <input type="file" id="validId" name="valid_id_path" accept=".jpg,.jpeg,.png,.pdf"
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
                        <textarea id="purposeOther" name="purpose_other"
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
                            <button type="submit" class="btn-submit">
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

                if (!validateStep(3)) {
                    e.preventDefault();
                    return;
                }

                if (!termsCheckbox.checked || !privacyCheckbox.checked || !pickupCheckbox.checked) {
                    e.preventDefault();
                    alert('Please accept all terms and conditions.');
                    return;
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

            // Initialize first step
            updateProgress();
        });
    </script>
</body>

</html>
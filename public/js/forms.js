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
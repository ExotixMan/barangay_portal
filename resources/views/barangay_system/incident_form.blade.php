<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Incident Report Application Form</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    
    <style>
        .report-type-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
            width: 100%;
        }
        
        .report-type-option {
            cursor: pointer;
            display: block;
            width: 100%;
        }
        
        .report-type-option input[type="radio"] {
            display: none;
        }
        
        .type-card {
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            background: #f8f9fa;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .report-type-option input[type="radio"]:checked + .type-card {
            border-color: #C62828;
            background: rgba(198, 40, 40, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.1);
        }
        
        .type-card i {
            font-size: 2rem;
            color: #C62828;
            margin-bottom: 15px;
        }
        
        .type-card h4 {
            font-size: 1rem;
            color: #333;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .type-card p {
            color: #666;
            font-size: 0.85rem;
            line-height: 1.4;
            margin: 0;
        }
        
        .party-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #eee;
        }
        
        .party-section h4 {
            color: #C62828;
            font-size: 1.1rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .add-party-btn {
            background: #C62828;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin-bottom: 15px;
            border: 2px solid transparent;
        }
        
        .add-party-btn:hover {
            background: #d32f2f;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.2);
        }
        
        .witness-item {
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
        }
        
        .witness-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .witness-number {
            font-weight: 600;
            color: #C62828;
        }
        
        .remove-witness {
            background: none;
            border: none;
            color: #ff4444;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 5px;
            transition: all 0.3s ease;
        }
        
        .remove-witness:hover {
            color: #cc0000;
            transform: scale(1.1);
        }
        
        .confidentiality-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
            width: 100%;
        }
        
        .confidentiality-option {
            cursor: pointer;
            display: block;
            width: 100%;
        }
        
        .confidentiality-option input[type="radio"] {
            display: none;
        }
        
        .confidentiality-option .option-content {
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .confidentiality-option input[type="radio"]:checked + .option-content {
            border-color: #C62828;
            background: rgba(198, 40, 40, 0.05);
            box-shadow: 0 5px 15px rgba(198, 40, 40, 0.1);
        }
        
        .option-content h4 {
            color: #333;
            font-size: 1rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .option-content h4 i {
            color: #C62828;
        }
        
        .option-content p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
            line-height: 1.5;
        }
        
        .error-message {
            color: #ff4444;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        input.error, select.error, textarea.error {
            border-color: #ff4444 !important;
            box-shadow: 0 0 0 3px rgba(255, 68, 68, 0.1) !important;
        }
        
        .upload-area.highlight {
            border-color: #C62828;
            background-color: rgba(198, 40, 40, 0.05);
        }

        .upload-area.error {
            border-color: #ff4444 !important;
            background-color: rgba(255, 68, 68, 0.05) !important;
        }
        
        @media (max-width: 768px) {
            .report-type-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Back Button - Matching residency format exactly -->
    <div class="back-button-container container-fluid px-3 px-md-4">
        <a href="{{ route('incident') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Blotter Info
        </a>
    </div>

    <!-- Page Header - Matching residency format -->
    <div class="form-page-header container-fluid px-3 px-md-4">
        <h1 class="mb-3"><i class="fas fa-clipboard-list"></i> Barangay Blotter Report Application</h1>
        <p class="mb-0">Fill out the form below to file an official blotter report with Barangay Hulo</p>
    </div>

    <!-- Application Form -->
    <section class="application-form-section container-fluid px-3 px-md-4" id="apply-form">
        <div class="container form-container px-0">
            <form id="blotterForm" method="POST" action="{{ route('incident.store') }}" enctype="multipart/form-data" class="requirement-form">
                @csrf
                <!-- Progress Indicator - Exactly matching residency -->
                <div class="form-progress">
                    <div class="progress-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <span>Incident Info</span>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <span>Parties Involved</span>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-circle">3</div>
                            <span>Evidence</span>
                        </div>
                        <div class="step" data-step="4">
                            <div class="step-circle">4</div>
                            <span>Review & Submit</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                </div>

                <!-- Step 1: Incident Information -->
                <div class="form-step active" id="step1">
                    <h3><i class="fas fa-info-circle"></i> Incident Information</h3>

                    <!-- Report Type -->
                    <div class="form-group full-width">
                        <label><i class="fas fa-tag"></i> Type of Report *</label>
                        <div class="report-type-grid">
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="dispute" required>
                                <div class="type-card">
                                    <i class="fas fa-users"></i>
                                    <h4>Community Dispute</h4>
                                    <p>Neighbor conflicts, noise complaints, property issues</p>
                                </div>
                            </label>
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="security">
                                <div class="type-card">
                                    <i class="fas fa-shield-alt"></i>
                                    <h4>Security Concern</h4>
                                    <p>Suspicious activities, theft, vandalism</p>
                                </div>
                            </label>
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="public">
                                <div class="type-card">
                                    <i class="fas fa-road"></i>
                                    <h4>Public Safety</h4>
                                    <p>Road hazards, street lights, drainage issues</p>
                                </div>
                            </label>
                            <label class="report-type-option">
                                <input type="radio" name="reportType" value="other">
                                <div class="type-card">
                                    <i class="fas fa-question-circle"></i>
                                    <h4>Other Concerns</h4>
                                    <p>Other barangay-related issues requiring attention</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-grid row g-3">
                        <div class="form-group col-md-12">
                            <label for="incidentDate"><i class="fas fa-calendar-day"></i> Incident Date *</label>
                            <input type="date" id="incidentDate" name="incidentDate" required class="form-control">
                            <div class="form-hint">Date when incident occurred</div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="incidentTime"><i class="fas fa-clock"></i> Approximate Time *</label>
                            <input type="time" id="incidentTime" name="incidentTime" required class="form-control">
                            <div class="form-hint">Best estimate of time</div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="incidentLocation"><i class="fas fa-map-marker-alt"></i> Location of Incident *</label>
                        <input type="text" id="incidentLocation" name="incidentLocation" required 
                               placeholder="Specific location within Barangay Hulo" class="form-control">
                        <div class="form-hint">Include landmarks, street names, or house numbers for accurate location</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="incidentDescription"><i class="fas fa-align-left"></i> Description of Incident *</label>
                        <textarea id="incidentDescription" name="incidentDescription" required rows="4" 
                                  placeholder="Provide a clear and detailed description of what happened" class="form-control"></textarea>
                        <div class="form-hint">Include sequence of events, people involved, and any witnesses</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="immediateAction"><i class="fas fa-first-aid"></i> Immediate Actions Taken</label>
                        <textarea id="immediateAction" name="immediateAction" rows="3" 
                                  placeholder="What steps were taken immediately after the incident?" class="form-control"></textarea>
                        <div class="form-hint">E.g., contacted authorities, secured the area, etc.</div>
                    </div>

                    <div class="form-actions row g-2 btns" style="margin-top: 3px;">
                        <div class="col-12 col-md-auto">
                            <a href="{{ route('incident') }}" class="btn-prev" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                        <div class="col-12 col-md-auto ms-md-auto btns">
                            <button type="button" class="btn-next" data-next="step2">Next Step <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Parties Involved -->
                <div class="form-step" id="step2">
                    <h3><i class="fas fa-user-friends"></i> Parties Involved</h3>

                    <!-- Complainant (Your Information) -->
                    <div class="party-section">
                        <h4><i class="fas fa-user-check"></i> Complainant (Your Information)</h4>
                        <div class="form-grid row g-3">
                            <div class="form-group col-md-12">
                                <label for="complainantName"><i class="fas fa-user"></i> Full Name *</label>
                                <input type="text" id="complainantName" name="complainantName" required 
                                       placeholder="Enter your full name" class="form-control">
                                <div class="form-hint">As it appears on your ID</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="complainantContact"><i class="fas fa-phone"></i> Contact Number *</label>
                                <input type="tel" id="complainantContact" name="complainantContact" required 
                                       placeholder="09XX XXX XXXX" class="form-control">
                                <div class="form-hint">Active mobile number for updates</div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="complainantAddress"><i class="fas fa-home"></i> Complete Address *</label>
                                <input type="text" id="complainantAddress" name="complainantAddress" required 
                                       placeholder="House No., Street, Barangay Hulo, Malabon City" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="complainantEmail"><i class="fas fa-envelope"></i> Email Address</label>
                                <input type="email" id="complainantEmail" name="complainantEmail" 
                                       placeholder="your.email@example.com" class="form-control">
                                <div class="form-hint">Optional but recommended for updates</div>
                            </div>
                        </div>
                    </div>

                    <!-- Respondent -->
                    <div class="party-section">
                        <h4><i class="fas fa-user-times"></i> Respondent (Other Party)</h4>
                        <div class="form-grid row g-3">
                            <div class="form-group col-md-12">
                                <label for="respondentName"><i class="fas fa-user"></i> Full Name (if known)</label>
                                <input type="text" id="respondentName" name="respondentName" 
                                       placeholder="Enter full name of respondent" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="respondentContact"><i class="fas fa-phone"></i> Contact Number (if known)</label>
                                <input type="tel" id="respondentContact" name="respondentContact" 
                                       placeholder="09XX XXX XXXX" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="respondentAddress"><i class="fas fa-home"></i> Address (if known)</label>
                                <input type="text" id="respondentAddress" name="respondentAddress" 
                                       placeholder="Complete address if known" class="form-control">
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="respondentDescription"><i class="fas fa-id-card"></i> Description (if name unknown)</label>
                            <textarea id="respondentDescription" name="respondentDescription" rows="2" 
                                      placeholder="Physical description, clothing, vehicle, or any identifying features" class="form-control"></textarea>
                            <div class="form-hint">Provide as much detail as possible for identification</div>
                        </div>
                    </div>

                    <!-- Witnesses -->
                    <div class="party-section">
                        <h4><i class="fas fa-eye"></i> Witnesses</h4>
                        <button type="button" class="add-party-btn" id="addWitnessBtn">
                            <i class="fas fa-user-plus"></i> Add Witness
                        </button>
                        <div class="witnesses-list" id="witnessesList"></div>
                        <div class="form-hint">Add any persons who witnessed the incident</div>
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step1"><i class="fas fa-arrow-left"></i> Previous</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="button" class="btn-next" data-next="step3">Next Step <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Evidence & Confidentiality -->
                <div class="form-step" id="step3">
                    <h3><i class="fas fa-file-upload"></i> Evidence & Preferences</h3>

                    <div class="upload-section row g-4">
                        <!-- Photos -->
                        <div class="upload-group col-12">
                            <label for="photos">
                                <i class="fas fa-image"></i> Photos/Evidence Images
                                <span class="upload-size">Max: 10MB each (JPG, PNG)</span>
                            </label>
                            <div class="upload-area" id="photoUpload">
                                <i class="fas fa-cloud-upload-alt mb-3"></i>
                                <p class="mb-0">Drag & drop photos or <span>browse</span></p>
                                <p class="small text-muted mt-2">Take photos of the scene, damages, or any visual evidence</p>
                                <input type="file" id="photos" name="photos" accept=".jpg,.jpeg,.png" multiple hidden>
                            </div>
                            <div class="upload-preview mt-2" id="photoPreview"></div>
                        </div>

                        <!-- Videos -->
                        <div class="upload-group col-12">
                            <label for="videos">
                                <i class="fas fa-file-video"></i> Video Evidence
                                <span class="upload-size">Max: 50MB each (MP4, AVI, MOV)</span>
                            </label>
                            <div class="upload-area" id="videoUpload">
                                <i class="fas fa-cloud-upload-alt mb-3"></i>
                                <p class="mb-0">Drag & drop videos or <span>browse</span></p>
                                <p class="small text-muted mt-2">CCTV footage, phone videos, or any video recordings</p>
                                <input type="file" id="videos" name="videos" accept=".mp4,.avi,.mov" multiple hidden>
                            </div>
                            <div class="upload-preview mt-2" id="videoPreview"></div>
                        </div>

                        <!-- Documents -->
                        <div class="upload-group col-12">
                            <label for="documents">
                                <i class="fas fa-file-alt"></i> Supporting Documents
                                <span class="upload-size">Max: 5MB each (PDF, DOC, DOCX)</span>
                            </label>
                            <div class="upload-area" id="docUpload">
                                <i class="fas fa-cloud-upload-alt mb-3"></i>
                                <p class="mb-0">Drag & drop documents or <span>browse</span></p>
                                <p class="small text-muted mt-2">Medical reports, written statements, receipts, etc.</p>
                                <input type="file" id="documents" name="documents" accept=".pdf,.doc,.docx" multiple hidden>
                            </div>
                            <div class="upload-preview mt-2" id="docPreview"></div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="additionalInfo"><i class="fas fa-sticky-note"></i> Additional Information</label>
                        <textarea id="additionalInfo" name="additionalInfo" rows="3" 
                                  placeholder="Any other relevant information or context not covered above" class="form-control"></textarea>
                        <div class="form-hint">Include any details that may help in the investigation</div>
                    </div>

                    <!-- Confidentiality Options -->
                    <div class="form-group full-width">
                        <label><i class="fas fa-user-shield"></i> Confidentiality Preference *</label>
                        <div class="confidentiality-options">
                            <label class="confidentiality-option">
                                <input type="radio" name="confidentiality" value="public" required>
                                <div class="option-content">
                                    <h4><i class="fas fa-eye"></i> Public Report</h4>
                                    <p>Report can be viewed by barangay officials and may be discussed in hearings. Standard processing applies.</p>
                                </div>
                            </label>
                            <label class="confidentiality-option">
                                <input type="radio" name="confidentiality" value="confidential">
                                <div class="option-content">
                                    <h4><i class="fas fa-user-secret"></i> Confidential Report</h4>
                                    <p>Limited access to specific barangay officials only. Your identity will be protected.</p>
                                </div>
                            </label>
                            <label class="confidentiality-option">
                                <input type="radio" name="confidentiality" value="anonymous">
                                <div class="option-content">
                                    <h4><i class="fas fa-mask"></i> Anonymous Report</h4>
                                    <p>Report submitted without personal identification. Note: May limit investigation capabilities.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step2"><i class="fas fa-arrow-left"></i> Previous</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="button" class="btn-next" data-next="step4">Next Step <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Review & Submit -->
                <div class="form-step" id="step4">
                    <h3><i class="fas fa-check-circle"></i> Review & Submit</h3>

                    <div class="review-section">
                        <div class="review-card">
                            <h4><i class="fas fa-info-circle"></i> Incident Information</h4>
                            <div class="review-content" id="reviewIncident"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-user-friends"></i> Parties Involved</h4>
                            <div class="review-content" id="reviewParties"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-file-upload"></i> Evidence Submitted</h4>
                            <div class="review-content" id="reviewEvidence"></div>
                        </div>

                        <div class="review-card">
                            <h4><i class="fas fa-receipt"></i> Processing Information</h4>
                            <div class="review-content">
                                <div class="payment-summary">
                                    <div class="payment-item">
                                        <span>Blotter Report Filing Fee</span>
                                        <span>₱0.00</span>
                                    </div>
                                    <div class="payment-item">
                                        <span>Processing Fee</span>
                                        <span>₱0.00</span>
                                    </div>
                                    <div class="payment-item total">
                                        <span>Total Amount to Pay</span>
                                        <span>₱0.00</span>
                                    </div>
                                </div>

                                <div class="pickup-instructions">
                                    <div class="pickup-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div>
                                            <h5><i class="fas fa-building"></i> Report Processing Location</h5>
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
                                        <i class="fas fa-id-card"></i>
                                        <div>
                                            <h5><i class="fas fa-clipboard-check"></i> What to Bring for Follow-up</h5>
                                            <ul>
                                                <li>Valid ID (for verification)</li>
                                                <li>Reference Number</li>
                                                <li>Additional evidence (if any)</li>
                                                <li>List of witnesses (if applicable)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="benefits-section">
                        <h4><i class="fas fa-star"></i> Benefits of Online Blotter Report</h4>
                        <div class="benefits-grid row g-12">
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-clock mb-3"></i>
                                <h5>24/7 Filing</h5>
                                <p class="mb-0">File reports anytime, anywhere</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-check-circle mb-3"></i>
                                <h5>Official Record</h5>
                                <p class="mb-0">Get an official reference number</p>
                            </div>
                            <div class="benefit-item col-md-4 col-sm-6">
                                <i class="fas fa-bolt mb-3"></i>
                                <h5>Quick Response</h5>
                                <p class="mb-0">Faster barangay action and follow-up</p>
                            </div>
                        </div>
                    </div>

                    <div class="terms-section">
                        <label class="terms-checkbox">
                            <input type="checkbox" id="declareTruth" name="declareTruth" required>
                            <span class="checkmark"></span>
                            I declare that all information provided is true and correct to the best of my knowledge. I understand that false reporting may incur penalties under barangay ordinances.
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="agreePrivacy" name="agreePrivacy" required>
                            <span class="checkmark"></span>
                            <p style='margin-bottom: 0;'>I agree to the <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a> of Barangay Hulo Online Services.</p>
                        </label>
                        <label class="terms-checkbox">
                            <input type="checkbox" id="consentProcessing" name="consentProcessing" required>
                            <span class="checkmark"></span>
                            I consent to the processing of this report by authorized barangay officials for investigation and resolution purposes.
                        </label>
                    </div>

                    <div class="form-actions row g-2">
                        <div class="col-6 col-md-auto">
                            <button type="button" class="btn-prev" data-prev="step3"><i class="fas fa-arrow-left"></i> Previous</button>
                        </div>
                        <div class="col-6 col-md-auto ms-md-auto">
                            <button type="submit" class="btn-submit" id="submitBlotter">
                                <span class="btn-text">Submit Report</span>
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
    <!-- QR Code library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('blotterForm');
            const steps = document.querySelectorAll('.form-step');
            const progressFill = document.getElementById('progressFill');
            const progressSteps = document.querySelectorAll('.step');
            const nextButtons = document.querySelectorAll('.btn-next');
            const prevButtons = document.querySelectorAll('.btn-prev');
            const submitButton = document.getElementById('submitBlotter');
            const declareTruth = document.getElementById('declareTruth');
            const agreePrivacy = document.getElementById('agreePrivacy');
            const consentProcessing = document.getElementById('consentProcessing');

            let currentStep = 1;
            const totalSteps = 4;
            let witnessCount = 0;

            // Set max date for incident date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('incidentDate').max = today;
            
            // Set default values
            document.getElementById('incidentDate').value = today;
            
            // Set current time as default
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('incidentTime').value = `${hours}:${minutes}`;

            // Add witness functionality
            const addWitnessBtn = document.getElementById('addWitnessBtn');
            const witnessesList = document.getElementById('witnessesList');

            if (addWitnessBtn) {
                addWitnessBtn.addEventListener('click', function() {
                    witnessCount++;

                    const witnessId = `witness-${Date.now()}`;

                    const witnessHtml = `
                        <div class="witness-item" id="${witnessId}">
                            <div class="witness-header">
                                <span class="witness-number">Witness ${witnessCount}</span>
                                <button type="button" class="remove-witness" onclick="removeWitness('${witnessId}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Full Name</label>
                                    <input name="witnesses[][name]" type="text" class="form-control witness-name" placeholder="Full name of witness">
                                    <div class="error-message"></div>
                                </div>

                                <div class="col-md-6">
                                    <label>Contact Number</label>
                                    <input name="witnesses[][contact]" type="text" class="form-control witness-contact" placeholder="09XX XXX XXXX">
                                    <div class="error-message"></div>
                                </div>

                                <div class="col-12">
                                    <label>Witness Statement</label>
                                    <textarea name="witnesses[][statement]" class="form-control witness-statement" placeholder="What did the witness see or hear?"></textarea>
                                    <div class="error-message"></div>
                                </div>
                            </div>
                        </div>
                    `;

                    witnessesList.insertAdjacentHTML('beforeend', witnessHtml);
                });
            }

            // Make removeWitness function global
            window.removeWitness = function(id) {
                const element = document.getElementById(id);
                if (element) {
                    element.remove();
                    witnessCount--;
                    // Renumber remaining witnesses
                    document.querySelectorAll('.witness-item').forEach((el, index) => {
                        el.querySelector('.witness-number').textContent = `Witness ${index + 1}`;
                    });
                }
            };

            // File upload functionality - matching clearance form approach
            setupFileUpload('photoUpload', 'photos', 'photoPreview', ['image/jpeg', 'image/jpg', 'image/png'], 10);
            setupFileUpload('videoUpload', 'videos', 'videoPreview', ['video/mp4', 'video/avi', 'video/quicktime'], 50);
            setupFileUpload('docUpload', 'documents', 'docPreview', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'], 5);

            function setupFileUpload(uploadAreaId, fileInputId, previewId, allowedTypes, maxSizeMB) {
                const uploadArea = document.getElementById(uploadAreaId);
                const fileInput = document.getElementById(fileInputId);
                const previewArea = document.getElementById(previewId);
                
                if (!uploadArea || !fileInput || !previewArea) return;
                
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
                    uploadArea.classList.add('highlight');
                }
                
                function unhighlight() {
                    uploadArea.classList.remove('highlight');
                }
                
                // Handle file drop
                uploadArea.addEventListener('drop', handleDrop, false);
                
                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    if (files.length > 0) {
                        handleFiles(files);
                    }
                }
                
                // Handle file selection
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) {
                        handleFiles(this.files);
                    }
                });
                
                function handleFiles(files) {
                    // Reset upload area style
                    uploadArea.classList.remove('error');
                    
                    // Validate each file
                    let validFiles = [];
                    let hasError = false;
                    
                    Array.from(files).forEach(file => {
                        // Check file type
                        const fileExtension = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));
                        const validExtensions = {
                            'image/jpeg': ['.jpg', '.jpeg'],
                            'image/jpg': ['.jpg', '.jpeg'],
                            'image/png': ['.png'],
                            'video/mp4': ['.mp4'],
                            'video/avi': ['.avi'],
                            'video/quicktime': ['.mov'],
                            'application/pdf': ['.pdf'],
                            'application/msword': ['.doc'],
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document': ['.docx']
                        };
                        
                        let isValidType = false;
                        for (let [mimeType, extensions] of Object.entries(validExtensions)) {
                            if (allowedTypes.includes(mimeType) && extensions.includes(fileExtension)) {
                                isValidType = true;
                                break;
                            }
                        }
                        
                        if (!isValidType) {
                            alert(`Invalid file type for ${file.name}. Please upload valid file types.`);
                            hasError = true;
                            return;
                        }
                        
                        // Check file size
                        const maxSize = maxSizeMB * 1024 * 1024;
                        if (file.size > maxSize) {
                            alert(`${file.name} exceeds the maximum file size of ${maxSizeMB}MB.`);
                            hasError = true;
                            return;
                        }
                        
                        validFiles.push(file);
                    });
                    
                    if (hasError) {
                        uploadArea.classList.add('error');
                        return;
                    }
                    
                    // Update preview
                    previewArea.innerHTML = '';
                    previewArea.classList.add('active');
                    
                    validFiles.forEach((file, index) => {
                        const filePreview = document.createElement('div');
                        filePreview.className = 'file-preview';
                        
                        // Determine icon based on file type
                        let icon = 'fa-file';
                        if (file.type.includes('image')) icon = 'fa-file-image';
                        else if (file.type.includes('video')) icon = 'fa-file-video';
                        else if (file.type.includes('pdf')) icon = 'fa-file-pdf';
                        else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) icon = 'fa-file-word';
                        
                        filePreview.innerHTML = `
                            <i class="fas ${icon}"></i>
                            <div class="file-info">
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${formatFileSize(file.size)}</div>
                            </div>
                            <button type="button" class="remove-file" data-file-input="${fileInputId}" data-file-index="${index}">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        
                        previewArea.appendChild(filePreview);
                    });
                    
                    // Add event listeners to remove buttons
                    previewArea.querySelectorAll('.remove-file').forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.stopPropagation();
                            removeFile(fileInputId, previewId, uploadAreaId);
                        });
                    });
                }
            }

            function removeFile(fileInputId, previewId, uploadAreaId) {
                const fileInput = document.getElementById(fileInputId);
                const previewArea = document.getElementById(previewId);
                const uploadArea = document.getElementById(uploadAreaId);
                
                fileInput.value = '';
                previewArea.innerHTML = '';
                previewArea.classList.remove('active');
                uploadArea.classList.remove('error', 'highlight');
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Navigation
            nextButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const nextStep = this.getAttribute('data-next');
                    if (validateStep(currentStep)) {
                        goToStep(nextStep);
                    }
                });
            });

            prevButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const prevStep = this.getAttribute('data-prev');
                    goToStep(prevStep);
                });
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                if (!validateStep(4)) {
                    e.preventDefault();
                    return;
                }

                if (!declareTruth.checked || !agreePrivacy.checked || !consentProcessing.checked) {
                    e.preventDefault();
                    alert('Please accept all terms and conditions.');
                    return;
                }
            });

            function goToStep(stepId) {
                steps.forEach(step => {
                    step.classList.remove('active');
                });
                
                document.getElementById(stepId).classList.add('active');
                currentStep = parseInt(stepId.replace('step', ''));
                updateProgress();
                
                if (currentStep === 4) {
                    updateReview();
                }
            }

            function updateProgress() {
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                progressFill.style.width = `${progressPercentage}%`;
                
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
                    field.classList.remove('error');
                    field.style.borderColor = '';
                    field.style.boxShadow = '';
                });
                
                // Reset upload area styles
                const uploadAreas = currentStepElement.querySelectorAll('.upload-area');
                uploadAreas.forEach(area => {
                    area.classList.remove('error');
                });
                
                // Validate required fields
                const requiredFields = currentStepElement.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    // Skip hidden fields
                    if (field.style.display === 'none' || field.type === 'file') {
                        return;
                    }
                    
                    let fieldValue = field.value.trim();
                    
                    if (field.type === 'radio') {
                        // For radio buttons, check if any in the group is checked
                        const radioName = field.name;
                        const radioChecked = document.querySelector(`input[name="${radioName}"]:checked`);
                        if (!radioChecked) {
                            isValid = false;
                            const radioGroup = field.closest('.form-group');
                            if (radioGroup) {
                                showFieldError(radioGroup.querySelector('label'), 'Please select an option');
                            }
                        }
                        return;
                    }
                    
                    if (!fieldValue) {
                        isValid = false;
                        showFieldError(field, 'This field is required');
                        return;
                    }
                    
                    // Validate email format
                    if (field.type === 'email' && fieldValue) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(fieldValue)) {
                            isValid = false;
                            showFieldError(field, 'Please enter a valid email address');
                        }
                    }
                    
                    // Validate phone number
                    if (field.id === 'complainantContact') {
                        const phoneRegex = /^09\d{9}$/;
                        const cleanedNumber = fieldValue.replace(/\s/g, '');
                        if (!phoneRegex.test(cleanedNumber)) {
                            isValid = false;
                            showFieldError(field, 'Please enter a valid Philippine mobile number (09XXXXXXXXX)');
                        }
                    }
                    
                    // Validate date is not in future
                    if (field.id === 'incidentDate') {
                        const selectedDate = new Date(fieldValue);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        
                        if (selectedDate > today) {
                            isValid = false;
                            showFieldError(field, 'Incident date cannot be in the future');
                        }
                    }
                });
                
                // Validate witness fields
                if (stepNumber === 2) {
                    const witnessItems = document.querySelectorAll('.witness-item');
                    
                    witnessItems.forEach(item => {
                        const nameInput = item.querySelector('.witness-name');
                        const contactInput = item.querySelector('.witness-contact');
                        const statementInput = item.querySelector('.witness-statement');
                        
                        const name = nameInput ? nameInput.value.trim() : '';
                        const contact = contactInput ? contactInput.value.trim() : '';
                        const statement = statementInput ? statementInput.value.trim() : '';
                        
                        // If any witness field is filled, validate all required
                        if (name || contact || statement) {
                            if (!name) {
                                isValid = false;
                                showFieldError(nameInput, 'Witness name is required if adding witness details');
                            }
                            
                            if (contact) {
                                const phoneRegex = /^09\d{9}$/;
                                const cleanedNumber = contact.replace(/\s/g, '');
                                if (!phoneRegex.test(cleanedNumber)) {
                                    isValid = false;
                                    showFieldError(contactInput, 'Please enter a valid Philippine mobile number (09XXXXXXXXX)');
                                }
                            }
                            
                            if (!statement) {
                                isValid = false;
                                showFieldError(statementInput, 'Witness statement is required if adding witness details');
                            }
                        }
                    });
                }
                
                // Validate radio buttons in step 1
                if (stepNumber === 1) {
                    const reportTypeSelected = document.querySelector('input[name="reportType"]:checked');
                    if (!reportTypeSelected) {
                        isValid = false;
                        const reportTypeGrid = document.querySelector('.report-type-grid');
                        showFieldError(reportTypeGrid, 'Please select a report type');
                    }
                }
                
                // Validate confidentiality in step 3
                if (stepNumber === 3) {
                    const confidentialitySelected = document.querySelector('input[name="confidentiality"]:checked');
                    if (!confidentialitySelected) {
                        isValid = false;
                        const confidentialityOptions = document.querySelector('.confidentiality-options');
                        showFieldError(confidentialityOptions, 'Please select a confidentiality preference');
                    }
                }
                
                return isValid;
            }

            function showFieldError(field, message) {
                field.classList.add('error');
                field.style.borderColor = '#ff4444';
                field.style.boxShadow = '0 0 0 3px rgba(255, 68, 68, 0.1)';
                
                // Add error message
                const errorMessage = document.createElement('div');
                errorMessage.className = 'error-message';
                errorMessage.style.color = '#ff4444';
                errorMessage.style.fontSize = '0.85rem';
                errorMessage.style.marginTop = '5px';
                errorMessage.textContent = message;
                
                // Insert error message after the field or its container
                if (field.classList.contains('report-type-grid') || field.classList.contains('confidentiality-options')) {
                    field.parentNode.appendChild(errorMessage);
                } else if (field.parentNode) {
                    field.parentNode.appendChild(errorMessage);
                }
            }

            function updateReview() {
                // Incident Information
                const incidentReview = document.getElementById('reviewIncident');
                const reportType = document.querySelector('input[name="reportType"]:checked')?.closest('.report-type-option')?.querySelector('.type-card h4')?.textContent || 'Not selected';
                
                incidentReview.innerHTML = `
                    <div class="review-item">
                        <div class="review-label">Report Type</div>
                        <div class="review-value">${reportType}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Incident Date & Time</div>
                        <div class="review-value">${formatDate(document.getElementById('incidentDate').value)} at ${document.getElementById('incidentTime').value}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Location</div>
                        <div class="review-value">${document.getElementById('incidentLocation').value}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Description</div>
                        <div class="review-value">${document.getElementById('incidentDescription').value.substring(0, 100)}${document.getElementById('incidentDescription').value.length > 100 ? '...' : ''}</div>
                    </div>
                `;

                // Parties Involved
                const partiesReview = document.getElementById('reviewParties');
                const respondentName = document.getElementById('respondentName').value || 'Not specified';
                const witnessCount = document.querySelectorAll('.witness-item').length;
                
                partiesReview.innerHTML = `
                    <div class="review-item">
                        <div class="review-label">Complainant</div>
                        <div class="review-value">${document.getElementById('complainantName').value}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Contact Number</div>
                        <div class="review-value">${document.getElementById('complainantContact').value}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Respondent</div>
                        <div class="review-value">${respondentName}</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Witnesses</div>
                        <div class="review-value">${witnessCount} witness(es) added</div>
                    </div>
                `;

                // Evidence
                const evidenceReview = document.getElementById('reviewEvidence');
                const photoCount = document.getElementById('photos').files.length;
                const videoCount = document.getElementById('videos').files.length;
                const docCount = document.getElementById('documents').files.length;
                
                evidenceReview.innerHTML = `
                    <div class="review-item">
                        <div class="review-label">Photos</div>
                        <div class="review-value">${photoCount} file(s) uploaded</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Videos</div>
                        <div class="review-value">${videoCount} file(s) uploaded</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Documents</div>
                        <div class="review-value">${docCount} file(s) uploaded</div>
                    </div>
                    <div class="review-item">
                        <div class="review-label">Confidentiality</div>
                        <div class="review-value">${getConfidentialityText()}</div>
                    </div>
                `;
            }

            function getConfidentialityText() {
                const selected = document.querySelector('input[name="confidentiality"]:checked');
                if (!selected) return 'Not selected';
                
                const value = selected.value;
                if (value === 'public') return 'Public Report';
                if (value === 'confidential') return 'Confidential Report';
                if (value === 'anonymous') return 'Anonymous Report';
                return value;
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

            // Initialize first step
            updateProgress();
        });
    </script>
</body>
</html>
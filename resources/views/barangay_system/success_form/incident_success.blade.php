<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barangay Incident Report Receipt</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
</head>
<body>
    <div class="back-button-container container-fluid px-3 px-md-4">
        <a href="{{ route('incident') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Blotter Info
        </a>
    </div>

    <div class="form-page-header container-fluid px-3 px-md-4">
        <h1 class="mb-3"><i class="fas fa-clipboard-list"></i> Barangay Blotter Report Application</h1>
    </div>

    <!-- Application Form -->
    <section class="application-form-section container-fluid px-3 px-md-4" id="apply-form">
        <div class="container form-container px-0">
            <div class="application-status" id="statusMessage">
                <div class="status-content">
                    <i class="fas fa-check-circle"></i>
                    <h3>Blotter Report Submitted Successfully!</h3>
                    <p>Your report has been received. Your reference number is: <strong id="referenceNumber">{{ $reference_number }}</strong></p>
                    <div class="status-actions row g-2 justify-content-center">
                        <div class="col-12 col-md-auto">
                            <button class="btn-download w-100" id="printReport">
                                <i class="fas fa-print"></i> Print Report Summary
                            </button>
                        </div>
                        <div class="col-12 col-md-auto">
                            <button class="btn-track w-100">
                                <i class="fas fa-search"></i> Track Application
                            </button>
                        </div>
                        <div class="col-12 col-md-auto">
                            <a href="{{ route('incident') }}" class="btn-back-info w-100" style="text-decoration: none;">
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
    <!-- QR Code library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusMessage = document.getElementById('statusMessage');
            const referenceNumber = document.getElementById('referenceNumber');

            // Print report button - similar to residency receipt
            const printButton = document.getElementById('printReport');
            if (printButton) {
                printButton.addEventListener('click', function() {
                    const complainantName = '{{ $complaint_name }}';
                    const referenceNum = '{{ $reference_number }}';
                    const currentDate = '{{ $date_submitted }}';
                    
                    // Create QR code data
                    const qrData = `Reference: ${referenceNum}\nComplainant: ${complainantName}\nDate: ${currentDate}\nType: Blotter Report`;
                    
                    QRCode.toDataURL(qrData, { width: 150 })
                        .then(qrDataUrl => {
                            const printContent = `<!DOCTYPE html>
                            <html>
                            <head>
                                <title>Blotter Report Application Receipt</title>
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                                <style>
                                    body { 
                                        font-family: Arial, sans-serif; 
                                        margin: 30px; 
                                        line-height: 1.5; 
                                    }

                                    .header { 
                                        text-align: center; 
                                        margin-bottom: 20px; 
                                        border-bottom: 2px solid #C62828; 
                                        padding-bottom: 15px; 
                                    }

                                    .header h1 { 
                                        color: #C62828; 
                                        margin-bottom: 5px; 
                                        font-size: 22px; 
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        gap: 10px;
                                    }

                                    .header h1 i {
                                        font-size: 20px;
                                    }

                                    .header .subtitle { 
                                        color: #666; 
                                        font-size: 13px; 
                                    }

                                    .details { 
                                        margin: 20px 0; 
                                    }

                                    .detail-row { 
                                        display: flex; 
                                        margin: 6px 0; 
                                        padding: 4px 0; 
                                        border-bottom: 1px solid #eee; 
                                    }

                                    .detail-label { 
                                        font-weight: bold; 
                                        color: #333; 
                                        width: 170px; 
                                    }

                                    .detail-value { 
                                        color: #444; 
                                        flex: 1; 
                                    }

                                    .qr-code { 
                                        text-align: center; 
                                        margin: 15px 0; 
                                    }

                                    .qr-code img { 
                                        width: 130px; 
                                        height: 130px; 
                                        margin: 10px auto; 
                                        display: block;
                                        border: 1px solid #ddd; 
                                        padding: 8px; 
                                        background: white; 
                                    }

                                    .instructions { 
                                        margin-top: 15px; 
                                        padding: 15px; 
                                        background: #f8f9fa; 
                                        border-radius: 8px; 
                                        font-size: 13px;
                                    }

                                    .instructions h3 {
                                        display: flex;
                                        align-items: center;
                                        gap: 8px;
                                        color: #C62828;
                                    }

                                    .footer { 
                                        text-align: center; 
                                        margin-top: 20px; 
                                        color: #666; 
                                        font-size: 11px; 
                                        border-top: 1px solid #eee; 
                                        padding-top: 10px; 
                                    }
                                    @page {
                                        size: 8.5in 11in;
                                        margin: 0.5in;
                                    }

                                    @media print {
                                        body {
                                            margin: 0;
                                            font-size: 12px;
                                        }

                                        .no-print {
                                            display: none !important;
                                        }

                                        html, body {
                                            width: 8.5in;
                                            height: 11in;
                                        }

                                        * {
                                            page-break-inside: avoid !important;
                                        }
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="header">
                                    <h1><i class="fas fa-clipboard-list"></i> Barangay Blotter Report Receipt</h1>
                                    <p class="subtitle">Barangay Hulong Duhat, Malabon City</p>
                                </div>
                                
                                <div class="details">
                                    <h3>Report Details</h3>
                                    <div class="detail-row">
                                        <div class="detail-label">Reference Number:</div>
                                        <div class="detail-value"><strong>${referenceNum}</strong></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Complainant Name:</div>
                                        <div class="detail-value">${complainantName}</div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Date Submitted:</div>
                                        <div class="detail-value">${currentDate}</div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Status:</div>
                                        <div class="detail-value"><span style="color: #C62828; font-weight: 600;">Submitted for Investigation</span></div>
                                    </div>
                                </div>
                                
                                <div class="qr-code">
                                    <p><i class="fas fa-qrcode"></i> Scan to Verify Report Status</p>
                                    <img src="${qrDataUrl}" alt="QR Code for ${referenceNum}">
                                    <p style="font-size: 12px; color: #666; margin-top: 10px;">Reference: ${referenceNum}</p>
                                </div>
                                
                                <div class="instructions">
                                    <h3><i class="fas fa-clipboard-list"></i> Next Steps</h3>
                                    <div class="step"><strong>1.</strong> Barangay will review your report (1-2 business days)</div>
                                    <div class="step"><strong>2.</strong> You may be contacted for additional information</div>
                                    <div class="step"><strong>3.</strong> Check your email/phone for updates</div>
                                    <div class="step"><strong>4.</strong> Visit barangay hall if summoned for hearing</div>
                                    
                                    <div class="important-note">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Important Note:</strong> Keep this receipt for reference. Present it when following up on your report.
                                    </div>
                                    
                                    <p><strong><i class="fas fa-map-marker-alt"></i> Barangay Hall Location:</strong><br>
                                    1 M. Blas St, Malabon, Metro Manila<br>
                                    <strong><i class="fas fa-clock"></i> Office Hours:</strong><br>
                                    Monday - Friday: 8:00 AM - 5:00 PM<br>
                                    Saturday: 8:00 AM - 12:00 PM</p>
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
                            </html>`;
                            
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

            // Track button
            const trackButton = document.querySelector('.btn-track');
            if (trackButton) {
                trackButton.addEventListener('click', function() {
                    alert('Status tracking feature coming soon! Your report is currently being processed.');
                });
            }
        });
    </script>
</body>
</html>
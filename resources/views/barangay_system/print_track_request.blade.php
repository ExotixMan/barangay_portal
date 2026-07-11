<?php
// resources/views/barangay_system/print_track_request.blade.php
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.blade_print_request_status_reference', ['reference' => $trackedRequest['reference']]) }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            line-height: 1.4; 
            font-size: 12px;
        }

        .header { 
            text-align: center; 
            margin-bottom: 15px; 
            border-bottom: 2px solid #C62828; 
            padding-bottom: 10px; 
        }

        .header h1 { 
            color: #C62828; 
            margin-bottom: 3px; 
            font-size: 20px; 
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .header h1 i {
            font-size: 18px;
        }

        .header .subtitle { 
            color: #666; 
            font-size: 11px; 
        }

        .details { 
            margin: 15px 0; 
        }

        .details h3 {
            font-size: 14px;
            margin: 0 0 8px 0;
            color: #C62828;
        }

        .detail-row { 
            display: flex; 
            margin: 3px 0; 
            padding: 3px 0; 
            border-bottom: 1px solid #eee; 
        }

        .detail-label { 
            font-weight: bold; 
            color: #333; 
            width: 140px; 
            font-size: 12px;
        }

        .detail-value { 
            color: #444; 
            flex: 1; 
            font-size: 12px;
        }

        .qr-code { 
            text-align: center; 
            margin: 10px 0; 
        }

        .qr-code img { 
            width: 100px; 
            height: 100px; 
            margin: 5px auto; 
            display: block;
            border: 1px solid #ddd; 
            padding: 5px; 
            background: white; 
        }

        .qr-code p {
            margin: 3px 0;
            font-size: 11px;
        }

        .instructions { 
            margin-top: 10px; 
            padding: 10px; 
            background: #f8f9fa; 
            border-radius: 6px; 
            font-size: 11px;
        }

        .instructions h3 {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #C62828;
            font-size: 13px;
            margin: 0 0 8px 0;
        }

        .instructions h3 i {
            font-size: 12px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 5px;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #FF9800;
            border: 1px solid #FFE0B2;
        }
        
        .status-processing {
            background: #cce5ff;
            color: #2196F3;
            border: 1px solid #BBDEFB;
        }
        
        .status-approved {
            background: #d4edda;
            color: #4CAF50;
            border: 1px solid #C8E6C9;
        }
        
        .status-claimed {
            background: #d4edda;
            color: #4CAF50;
            border: 1px solid #C8E6C9;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #F44336;
            border: 1px solid #FFCDD2;
        }
        
        .step {
            margin-bottom: 4px;
            font-size: 11px;
        }
        
        .important-note {
            margin-top: 8px;
            padding: 6px 8px;
            background: #fff3cd;
            border-left: 3px solid #C62828;
            border-radius: 4px;
            font-size: 10px;
        }
        
        .footer { 
            text-align: center; 
            margin-top: 10px; 
            color: #666; 
            font-size: 9px; 
            border-top: 1px solid #eee; 
            padding-top: 6px; 
        }
        
        .footer p {
            margin: 2px 0;
        }
        
        .location-info {
            margin-top: 8px;
            font-size: 10px;
        }
        
        .location-info strong {
            color: #333;
        }
        
        @page {
            size: 8.5in 11in;
            margin: 0.3in;
        }

        @media print {
            body {
                margin: 0;
                font-size: 11px;
            }

            .no-print {
                display: none !important;
            }

            * {
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>
@include('chatbot.embed')
<body>
    <div class="header">
        <h1><i class="fas fa-file-signature"></i> {{ $trackedRequest['type'] }}</h1>
        <p class="subtitle">{{ __('messages.blade_print_track_request_blade_barangay_hulong_duhat_malabon_city') }}</p>
        
        @php
            $statusLower = strtolower($trackedRequest['status']);
            $statusClass = 'status-pending';
            if(str_contains($statusLower, 'process')) $statusClass = 'status-processing';
            elseif(str_contains($statusLower, 'approved')) $statusClass = 'status-approved';
            elseif(str_contains($statusLower, 'claim')) $statusClass = 'status-claimed';
            elseif(str_contains($statusLower, 'reject') || str_contains($statusLower, 'denied')) $statusClass = 'status-rejected';
        @endphp
        <div class="status-badge {{ $statusClass }}">
            <i class="fas {{ 
                str_contains($statusLower, 'pending') ? 'fa-clock' : 
                (str_contains($statusLower, 'process') ? 'fa-cog' : 
                (str_contains($statusLower, 'approved') ? 'fa-check-circle' : 
                (str_contains($statusLower, 'claim') ? 'fa-check-double' : 
                (str_contains($statusLower, 'reject') ? 'fa-times-circle' : 'fa-hourglass-half')))) 
            }}"></i> 
            {{ $trackedRequest['status'] }}
        </div>
    </div>
    
    <div class="details">
        <h3><i class="fas fa-file-alt" style="color: #C62828; font-size: 12px;"></i> {{ __('messages.print_application_details') }}</h3>
        <div class="detail-row">
            <div class="detail-label">{{ __('messages.print_ref_label') }}</div>
            <div class="detail-value"><strong>{{ $trackedRequest['reference'] }}</strong></div>
        </div>
        <div class="detail-row">
            <div class="detail-label">{{ __('messages.print_applicant_label') }}</div>
            <div class="detail-value">{{ $trackedRequest['name'] }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">{{ __('messages.print_date_label') }}</div>
            <div class="detail-value">{{ $trackedRequest['date'] }}</div>
        </div>
        @if(isset($trackedRequest['purpose']))
        <div class="detail-row">
            <div class="detail-label">{{ __('messages.print_purpose_label') }}</div>
            <div class="detail-value">{{ $trackedRequest['purpose'] }}</div>
        </div>
        @endif
        @if(isset($trackedRequest['expected_completion']))
        <div class="detail-row">
            <div class="detail-label">{{ __('messages.print_expected_label') }}</div>
            <div class="detail-value">{{ $trackedRequest['expected_completion'] }}</div>
        </div>
        @endif
        @if(isset($trackedRequest['amount']) && $trackedRequest['amount'] > 0)
        <div class="detail-row">
            <div class="detail-label">{{ __('messages.print_amount_label') }}</div>
            <div class="detail-value"><strong>₱{{ number_format($trackedRequest['amount'], 2) }}</strong></div>
        </div>
        @elseif(isset($trackedRequest['fee_label']))
        <div class="detail-row">
            <div class="detail-label">{{ __('messages.print_amount_label') }}</div>
            <div class="detail-value"><strong>{{ $trackedRequest['fee_label'] }}</strong></div>
        </div>
        @endif
        @if(isset($trackedRequest['remarks']))
        <div class="detail-row">
            <div class="detail-label">{{ __('messages.print_remarks_label') }}</div>
            <div class="detail-value">{{ $trackedRequest['remarks'] }}</div>
        </div>
        @endif
    </div>
    
    <div class="qr-code">
        <p><i class="fas fa-qrcode" style="color: #C62828;"></i> {{ __('messages.print_scan_label') }}</p>
        <div id="qrCode"></div>
        <p style="font-size: 10px; color: #666;">{{ __('messages.blade_print_ref_reference', ['reference' => $trackedRequest['reference']]) }}</p>
    </div>
    
    <div class="instructions">
        <h3><i class="fas fa-clipboard-list"></i> {{ __('messages.print_next_steps_title') }}</h3>
        
        @if(str_contains(strtolower($trackedRequest['type']), 'Incident Report') || str_contains(strtolower($trackedRequest['type']), 'incident'))
            <div class="step"><strong>1.</strong> {{ __('messages.blade_print_track_request_blade_wait_for_barangay_contact_for_investigation') }}</div>
            <div class="step"><strong>2.</strong> {{ __('messages.blade_print_track_request_blade_attend_scheduled_hearings') }}</div>
            <div class="step"><strong>3.</strong> {{ __('messages.blade_print_track_request_blade_receive_resolution') }}</div>
            
            <div class="important-note">
                <i class="fas fa-exclamation-triangle"></i> {{ __('messages.blade_print_track_request_blade_keep_reference_for_follow_ups') }}
            </div>
        @else
            <div class="step"><strong>1.</strong> {{ __('messages.blade_print_track_request_blade_wait_1_3_days_for_processing') }}</div>
            <div class="step"><strong>2.</strong> {{ __('messages.blade_print_track_request_blade_visit_barangay_hall_with_valid_id') }}</div>
            <div class="step"><strong>3.</strong> {{ __('messages.blade_print_track_request_blade_present_this_receipt') }}</div>
            @if(isset($trackedRequest['amount']) && $trackedRequest['amount'] > 0)
            <div class="step"><strong>4.</strong> {{ __('messages.blade_print_pay_amount_at_cashier', ['amount' => number_format($trackedRequest['amount'], 2)]) }}</div>
            <div class="step"><strong>5.</strong> {{ __('messages.blade_print_track_request_blade_receive_your_document') }}</div>
            @elseif(isset($trackedRequest['fee_label']) && $trackedRequest['fee_label'] === 'Depending on purpose')
            <div class="step"><strong>4.</strong> {{ __('messages.blade_print_track_request_blade_pay_depending_on_purpose_at_cashier') }}</div>
            <div class="step"><strong>5.</strong> {{ __('messages.blade_print_track_request_blade_receive_your_document') }}</div>
            @else
            <div class="step"><strong>4.</strong> {{ __('messages.blade_print_track_request_blade_receive_your_document') }}</div>
            @endif
            
            <div class="important-note">
                <i class="fas fa-exclamation-triangle"></i> {{ __('messages.blade_print_track_request_blade_bring_valid_id_used_in_application') }}
            </div>
        @endif
        
        <div class="location-info">
            <strong><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_print_track_request_blade_barangay_hall') }}</strong> {{ __('messages.blade_print_track_request_blade_1_m_blas_st_malabon') }}<br>
            <strong><i class="fas fa-clock"></i> {{ __('messages.blade_print_track_request_blade_hours') }}</strong> {{ __('messages.blade_print_track_request_blade_mon_fri_8am_5pm') }}<br>
            <strong><i class="fas fa-phone"></i> {{ __('messages.blade_print_track_request_blade_contact') }}</strong> 8-281-1373
        </div>
    </div>
    
    <div class="footer">
        <p>{{ __('messages.blade_print_track_request_blade_electronically_generated_no_signature_required') }}</p>
        <p>Barangay Hulong Duhat Online © {{ date('Y') }} • {{ now()->format('M d, Y h:i A') }}</p>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 15px;">
        <button onclick="window.print()" style="padding: 8px 20px; background: #C62828; color: white; border: none; border-radius: 6px; cursor: pointer; margin: 3px; font-size: 12px; font-weight: 600;">
            <i class="fas fa-print"></i> {{ __('messages.blade_print_track_request_blade_print') }}
        </button>
        <button onclick="window.close()" style="padding: 8px 20px; background: #666; color: white; border: none; border-radius: 6px; cursor: pointer; margin: 3px; font-size: 12px; font-weight: 600;">
            <i class="fas fa-times"></i> {{ __('messages.modal_close_btn') }}
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>

    <script>
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
        
        document.addEventListener('DOMContentLoaded', function() {
            const referenceNum = '{{ $trackedRequest['reference'] }}';
            const applicantName = '{{ $trackedRequest['name'] }}';
            const currentDate = '{{ $trackedRequest['date'] }}';
            
            const qrData = @json(__('messages.blade_print_qr_ref_name_date')).replace(':reference', referenceNum).replace(':name', applicantName).replace(':date', currentDate);
            
            QRCode.toDataURL(qrData, { width: 100 })
                .then(qrDataUrl => {
                    const qrImg = document.createElement('img');
                    qrImg.src = qrDataUrl;
                    qrImg.alt = @json(__('messages.blade_jsdyn_qr_code_for_reference')).replace(':reference', referenceNum);
                    
                    const qrContainer = document.getElementById('qrCode');
                    qrContainer.innerHTML = '';
                    qrContainer.appendChild(qrImg);
                })
                .catch(error => {
                    console.error(@json(__('messages.blade_js_error_generating_qr_code')), error);
                    document.getElementById('qrCode').innerHTML = '<p style="color: #999;">{{ __('messages.blade_js_qr_code_failed') }}</p>';
                });
        });
    </script>
</body>
</html>
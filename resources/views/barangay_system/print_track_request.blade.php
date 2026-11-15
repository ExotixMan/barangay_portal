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

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/barangay_system/print_track_request.css') }}">
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
            <div class="detail-value">{{ $trackedRequest['name'] ?? 'Resident' }}</div>
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
        <button data-browser-action="print" style="padding: 8px 20px; background: #C62828; color: white; border: none; border-radius: 6px; cursor: pointer; margin: 3px; font-size: 12px; font-weight: 600;">
            <i class="fas fa-print"></i> {{ __('messages.blade_print_track_request_blade_print') }}
        </button>
        <button data-browser-action="close" style="padding: 8px 20px; background: #666; color: white; border: none; border-radius: 6px; cursor: pointer; margin: 3px; font-size: 12px; font-weight: 600;">
            <i class="fas fa-times"></i> {{ __('messages.modal_close_btn') }}
        </button>
    </div>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script src="{{ asset('js/dark-mode.js') }}"></script>

    @include('shared.js-config', [
    'id' => 'js-config-barangay-system-print-track-request-1',
    'data' => [
        'v1' => e($trackedRequest['reference']),
        'v2' => e($trackedRequest['name'] ?? 'Resident'),
        'v3' => e($trackedRequest['date']),
        'v4' => __('messages.blade_print_qr_ref_name_date'),
        'v5' => __('messages.blade_jsdyn_qr_code_for_reference'),
        'v6' => __('messages.blade_js_error_generating_qr_code'),
        'v7' => e(__('messages.blade_js_qr_code_failed')),
    ],
])
<script src="{{ asset('js/pages/barangay_system/print_track_request-dynamic1.js') }}"></script>
<script src="{{ asset('js/action-bindings.js') }}"></script>
</body>
</html>

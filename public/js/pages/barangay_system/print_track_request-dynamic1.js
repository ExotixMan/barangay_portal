const __cfg_barangay_system_print_track_request_1 = JSON.parse(document.getElementById('js-config-barangay-system-print-track-request-1')?.dataset.jsConfig || '{}');

        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };

        document.addEventListener('DOMContentLoaded', function() {
            const referenceNum = '' + __cfg_barangay_system_print_track_request_1.v1 + '';
            const applicantName = '' + __cfg_barangay_system_print_track_request_1.v2 + '';
            const currentDate = '' + __cfg_barangay_system_print_track_request_1.v3 + '';

            const qrData = __cfg_barangay_system_print_track_request_1.v4.replace(':reference', referenceNum).replace(':name', applicantName).replace(':date', currentDate);

            QRCode.toDataURL(qrData, { width: 100 })
                .then(qrDataUrl => {
                    const qrImg = document.createElement('img');
                    qrImg.src = qrDataUrl;
                    qrImg.alt = __cfg_barangay_system_print_track_request_1.v5.replace(':reference', referenceNum);

                    const qrContainer = document.getElementById('qrCode');
                    qrContainer.innerHTML = '';
                    qrContainer.appendChild(qrImg);
                })
                .catch(error => {
                    console.error(__cfg_barangay_system_print_track_request_1.v6, error);
                    document.getElementById('qrCode').innerHTML = '<p style="color: #999;">' + __cfg_barangay_system_print_track_request_1.v7 + '</p>';
                });
        });

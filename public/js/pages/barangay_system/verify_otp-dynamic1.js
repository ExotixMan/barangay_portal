const __cfg_barangay_system_verify_otp_1 = JSON.parse(document.getElementById('js-config-barangay-system-verify-otp-1')?.dataset.jsConfig || '{}');

        let countdown = Number(__cfg_barangay_system_verify_otp_1.v1) || 0;
        const countdownSpan = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendBtn');
        const otpStatus = document.getElementById('otpStatus');

        function formatTime(totalSeconds) {
            const mins = Math.floor(totalSeconds / 60);
            const secs = totalSeconds % 60;
            return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }

        function showStatus(message, type) {
            otpStatus.textContent = message;
            otpStatus.className = 'otp-status show ' + type;
        }

        function updateResendUi() {
            if (countdown > 0) {
                resendBtn.disabled = true;
                countdownSpan.textContent = formatTime(countdown);
            } else {
                resendBtn.disabled = false;
                countdownSpan.textContent = '00:00';
            }
        }

        updateResendUi();

        let timer = setInterval(() => {
            if (countdown > 0) {
                countdown--;
                updateResendUi();
            }
        }, 1000);

        document.getElementById('otp').addEventListener('input', (event) => {
            event.target.value = event.target.value.replace(/\D+/g, '').slice(0, 6);
        });

        function resendOtp() {
            if (countdown > 0) {
                return;
            }

            resendBtn.disabled = true;
            showStatus(__cfg_barangay_system_verify_otp_1.v2, 'success');

            fetch("" + __cfg_barangay_system_verify_otp_1.v3 + "", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '' + __cfg_barangay_system_verify_otp_1.v4 + '',
                    'Accept': 'application/json',
                },
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    const error = new Error(data.message || __cfg_barangay_system_verify_otp_1.v5);
                    error.retryAfter = Number(data.retry_after || 0);
                    throw error;
                }
                return data;
            })
            .then(data => {
                clearInterval(timer);
                countdown = Number(data.retry_after || 300);
                updateResendUi();
                timer = setInterval(() => {
                    if (countdown > 0) {
                        countdown--;
                        updateResendUi();
                    }
                }, 1000);
                showStatus(data.success || __cfg_barangay_system_verify_otp_1.v6, 'success');
            })
            .catch((error) => {
                if (error.retryAfter && error.retryAfter > 0) {
                    countdown = error.retryAfter;
                    updateResendUi();
                } else {
                    resendBtn.disabled = false;
                }
                showStatus(error.message || __cfg_barangay_system_verify_otp_1.v7, 'error');
            });
        }

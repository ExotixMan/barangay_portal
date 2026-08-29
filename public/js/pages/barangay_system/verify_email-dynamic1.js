const __cfg_barangay_system_verify_email_1 = JSON.parse(document.getElementById('js-config-barangay-system-verify-email-1')?.dataset.jsConfig || '{}');

        const btn = document.getElementById('resendBtn');
        const form = document.getElementById('resendForm');

        let endTime = localStorage.getItem('verify_end_time');

        if (endTime) {
            let remainingSeconds = Math.floor((endTime - Date.now()) / 1000);

            if (remainingSeconds > 0) {
                startTimer(remainingSeconds);
            } else {
                localStorage.removeItem('verify_end_time');
                btn.disabled = false;
                btn.innerText = ' + __cfg_barangay_system_verify_email_1.v1 + ';
            }
        }

        form.addEventListener('submit', function(e) {
            if (btn.disabled) {
                e.preventDefault();
                return false;
            }

            let endTime = Date.now() + 60000;
            localStorage.setItem('verify_end_time', endTime);

            startTimer(60);

            return true;
        });

        function startTimer(seconds) {
            btn.disabled = true;

            const timer = setInterval(() => {
                let endTime = localStorage.getItem('verify_end_time');
                if (!endTime) {
                    clearInterval(timer);
                    return;
                }

                let remainingSeconds = Math.floor((endTime - Date.now()) / 1000);

                if (remainingSeconds <= 0) {
                    clearInterval(timer);
                    btn.disabled = false;
                    btn.innerText = ' + __cfg_barangay_system_verify_email_1.v1 + ';
                    localStorage.removeItem('verify_end_time');
                } else {
                    btn.innerText = ' + __cfg_barangay_system_verify_email_1.v2 + ' + remainingSeconds + "s)";
                }
            }, 1000);
        }

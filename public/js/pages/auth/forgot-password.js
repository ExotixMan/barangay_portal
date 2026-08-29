document.addEventListener('DOMContentLoaded', function() {
            const forgotForm = document.getElementById('forgotForm');
            const submitBtn = document.getElementById('submitBtn');
            const emailInput = document.getElementById('email');

            if (forgotForm && submitBtn) {
                forgotForm.addEventListener('submit', function(e) {

                    if (!emailInput.value.trim()) {
                        e.preventDefault();
                        alert('Please enter your email address.');
                        return false;
                    }

                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                });
            }

            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
        });

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registerForm");
    if (!form) return;

    // Get form elements
    const steps = document.querySelectorAll('.form-step');
    const progressFill = document.getElementById('progressFill');
    const progressSteps = document.querySelectorAll('.step');
    const nextButtons = document.querySelectorAll('.btn-next');
    const prevButtons = document.querySelectorAll('.btn-prev');
    const submitBtn = document.getElementById('createAccountBtn');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const meterFill = document.getElementById('passwordMeterFill');
    const strengthText = document.getElementById('passwordStrengthText');
    const matchText = document.getElementById('passwordMatchText');
    const termsCheckbox = document.getElementById('terms');
    const passwordChecklist = Array.from(document.querySelectorAll(".password-checklist li"));

    // Required fields for validation
    const requiredFields = [
        "firstname",
        "lastname",
        "email",
        "address",
        "birthdate",
        "contact",
        "username",
        "password",
        "confirm-password",
    ].map((id) => document.getElementById(id));

    const contactInput = document.getElementById("contact");
    const birthdateInput = document.getElementById("birthdate");

    let currentStep = 1;
    const totalSteps = 2;

    // Set max date for birthdate
    if (birthdateInput) {
        const today = new Date();
        const minAge = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        birthdateInput.max = minAge.toISOString().split("T")[0];
    }

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

    // Go to specific step
    function goToStep(stepId) {
        // Hide all steps
        steps.forEach(step => {
            step.classList.remove('active');
        });
        
        // Show target step
        document.getElementById(stepId).classList.add('active');
        
        // Update current step
        currentStep = parseInt(stepId.replace('step', ''));
        updateProgress();
    }

    // Update progress bar
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

    // Validate current step
    function validateStep(stepNumber) {
        let isValid = true;
        const currentStepElement = document.getElementById(`step${stepNumber}`);
        
        // Clear previous error messages
        const existingErrors = currentStepElement.querySelectorAll('.error-message');
        existingErrors.forEach(error => error.remove());
        
        // Reset field styles
        const allFields = currentStepElement.querySelectorAll('.form-control');
        allFields.forEach(field => {
            field.classList.remove('is-invalid');
        });
        
        // Validate required fields
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (field.type === 'checkbox') {
                if (!field.checked) {
                    isValid = false;
                    showFieldError(field, 'This field is required');
                }
                return;
            }
            
            let fieldValue = field.value.trim();
            
            if (!fieldValue) {
                isValid = false;
                showFieldError(field, 'This field is required');
                return;
            }
            
            // Email validation
            if (field.type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(fieldValue)) {
                    isValid = false;
                    showFieldError(field, 'Please enter a valid email address');
                }
            }
            
            // Phone number validation
            if (field.id === 'contact') {
                const phoneRegex = /^09\d{9}$/;
                if (!phoneRegex.test(fieldValue)) {
                    isValid = false;
                    showFieldError(field, 'Please enter a valid Philippine mobile number (09XXXXXXXXX)');
                }
            }
            
            // Birthdate validation (must be 18+)
            if (field.id === 'birthdate') {
                const birthDate = new Date(fieldValue);
                const today = new Date();
                const age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                if (age < 18) {
                    isValid = false;
                    showFieldError(field, 'You must be at least 18 years old to register');
                }
            }
        });
        
        // Step 2 specific validations
        if (stepNumber === 2) {
            // Password strength validation
            if (!validatePasswordStrength()) {
                isValid = false;
            }
            
            // Password match validation
            if (!updatePasswordMatchUI()) {
                isValid = false;
                if (confirmPasswordInput.value.trim()) {
                    showFieldError(confirmPasswordInput, 'Passwords do not match');
                }
            }
            
            // Terms validation
            if (!termsCheckbox.checked) {
                isValid = false;
                showFieldError(termsCheckbox, 'You must agree to the terms and conditions');
            }
        }
        
        return isValid;
    }

    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        // For checkboxes, add error to parent
        if (field.type === 'checkbox') {
            const parent = field.closest('.terms-checkbox');
            const errorMessage = document.createElement('div');
            errorMessage.className = 'error-message';
            errorMessage.textContent = message;
            parent.appendChild(errorMessage);
            return;
        }
        
        const errorMessage = document.createElement('div');
        errorMessage.className = 'error-message';
        errorMessage.textContent = message;
        field.parentNode.appendChild(errorMessage);
    }

    // Password strength evaluation
    function evaluatePasswordStrength(password) {
        let score = 0;
        if (password.length >= 8) score += 1;
        if (/[A-Z]/.test(password)) score += 1;
        if (/[a-z]/.test(password)) score += 1;
        if (/\d/.test(password)) score += 1;
        if (/[^A-Za-z0-9]/.test(password)) score += 1;

        if (password.length === 0) return { score: 0, label: "too weak", color: "#d16060", width: 10 };
        if (score <= 2) return { score, label: "weak", color: "#d16060", width: 35 };
        if (score <= 3) return { score, label: "fair", color: "#d38e2a", width: 60 };
        if (score <= 4) return { score, label: "good", color: "#2c7dc8", width: 82 };
        return { score, label: "strong", color: "#1e8f55", width: 100 };
    }

    function validatePasswordStrength() {
        const password = passwordInput.value;
        const { label } = evaluatePasswordStrength(password);
        
        if (password && (label === "too weak" || label === "weak")) {
            showFieldError(passwordInput, 'Password is too weak. Please make it stronger.');
            return false;
        }
        return true;
    }

    function updatePasswordUI() {
        const { label, color, width } = evaluatePasswordStrength(passwordInput.value);
        meterFill.style.width = `${width}%`;
        meterFill.style.backgroundColor = color;
        strengthText.textContent = `Password strength: ${label}`;
        strengthText.classList.toggle("good", label === "good" || label === "strong");
        strengthText.classList.toggle("bad", label === "weak" || label === "too weak");

        if (passwordChecklist.length) {
            const rules = {
                length: passwordInput.value.length >= 8,
                number: /\d/.test(passwordInput.value),
                upper: /[A-Z]/.test(passwordInput.value),
                special: /[^A-Za-z0-9]/.test(passwordInput.value),
            };
            passwordChecklist.forEach((item) => {
                const rule = item.dataset.rule;
                item.classList.toggle("is-complete", Boolean(rule && rules[rule]));
            });
        }
    }

    function updatePasswordMatchUI() {
        const password = passwordInput.value;
        const confirm = confirmPasswordInput.value;

        if (!confirm) {
            matchText.textContent = "Passwords must match";
            matchText.classList.remove("good", "bad");
            confirmPasswordInput.classList.remove("is-invalid");
            return false;
        }

        const matched = password === confirm;
        matchText.textContent = matched ? "Passwords match" : "Passwords do not match";
        matchText.classList.toggle("good", matched);
        matchText.classList.toggle("bad", !matched);
        confirmPasswordInput.classList.toggle("is-invalid", !matched);
        return matched;
    }

    // Sanitize contact number
    function sanitizeContact() {
        if (contactInput) {
            contactInput.value = contactInput.value.replace(/\D/g, "").slice(0, 11);
        }
    }

    // Event listeners
    contactInput?.addEventListener("input", sanitizeContact);

    passwordInput?.addEventListener("input", () => {
        updatePasswordUI();
        updatePasswordMatchUI();
    });

    confirmPasswordInput?.addEventListener("input", updatePasswordMatchUI);

    // Toggle password visibility
    document.querySelectorAll(".toggle-password").forEach((toggle) => {
        toggle.addEventListener("click", () => {
            const targetId = toggle.getAttribute("data-target");
            const input = targetId ? document.getElementById(targetId) : null;
            if (!input) return;

            const showing = input.type === "text";
            input.type = showing ? "password" : "text";
            const icon = toggle.querySelector("i");
            if (icon) {
                icon.classList.toggle("fa-eye", showing);
                icon.classList.toggle("fa-eye-slash", !showing);
            }
        });
    });

    // Form submission
    form.addEventListener("submit", (event) => {
        if (!validateStep(currentStep)) {
            event.preventDefault();
            
            // Shake invalid fields
            document.querySelectorAll('.is-invalid').forEach(field => {
                field.closest('.form-group')?.classList.add('shake');
                setTimeout(() => {
                    field.closest('.form-group')?.classList.remove('shake');
                }, 400);
            });
            return;
        }

        submitBtn.disabled = true;
        submitBtn.classList.add("loading");
        
        const btnText = submitBtn.querySelector(".btn-text");
        if (btnText) {
            btnText.textContent = "Creating account...";
        }
    });

    // High contrast mode
    function setContrastFromStorage() {
        const enabled = localStorage.getItem("highContrastMode") === "true";
        document.body.classList.toggle("high-contrast", enabled);
    }

    document.getElementById("increase-text")?.addEventListener("click", () => changeFontSize(2));
    document.getElementById("decrease-text")?.addEventListener("click", () => changeFontSize(-2));
    document.getElementById("high-contrast-toggle")?.addEventListener("click", () => {
        const enabled = !document.body.classList.contains("high-contrast");
        document.body.classList.toggle("high-contrast", enabled);
        localStorage.setItem("highContrastMode", String(enabled));
    });

    setContrastFromStorage();

    function changeFontSize(delta) {
        const currentSize = parseInt(getComputedStyle(document.body).fontSize, 10);
        const nextSize = Math.min(22, Math.max(12, currentSize + delta));
        document.body.style.fontSize = `${nextSize}px`;
    }

    // Initialize
    updateProgress();
    updatePasswordUI();
    updatePasswordMatchUI();
});
(function () {
    'use strict';

    const pwInput  = document.getElementById('password');
    const cfInput  = document.getElementById('password_confirmation');
    const pwStatus = document.getElementById('pwStatus');
    const cfStatus = document.getElementById('cfStatus');
    const cfError  = document.getElementById('cfError');
    const fillBar  = document.getElementById('strengthFill');
    const fillLabel= document.getElementById('strengthLabel');
    const submitBtn= document.getElementById('submitBtn');

    const reqs = {
        len: { el: document.getElementById('req-len'), test: v => v.length >= 8 },
        up:  { el: document.getElementById('req-up'),  test: v => /[A-Z]/.test(v) },
        low: { el: document.getElementById('req-low'), test: v => /[a-z]/.test(v) },
        num: { el: document.getElementById('req-num'), test: v => /[0-9]/.test(v) },
        sym: { el: document.getElementById('req-sym'), test: v => /[^A-Za-z0-9]/.test(v) },
    };

    function bindToggle(btnId, inputEl) {
        document.getElementById(btnId).addEventListener('click', function () {
            const show = inputEl.type === 'password';
            inputEl.type = show ? 'text' : 'password';
            this.querySelector('i').className = show ? 'far fa-eye-slash' : 'far fa-eye';
        });
    }

    bindToggle('togglePw', pwInput);
    bindToggle('toggleCf', cfInput);

    const levels = [
        { pct: 20, colour: '#dc2626', label: 'Very weak',  labelClass: '#dc2626' },
        { pct: 40, colour: '#ea580c', label: 'Weak',       labelClass: '#ea580c' },
        { pct: 60, colour: '#ca8a04', label: 'Fair',       labelClass: '#ca8a04' },
        { pct: 80, colour: '#16a34a', label: 'Good',       labelClass: '#16a34a' },
        { pct:100, colour: '#15803d', label: 'Strong ✓',   labelClass: '#15803d' },
    ];

    function checkPassword() {
        const val = pwInput.value;
        let met = 0;

        Object.values(reqs).forEach(r => {
            const ok = r.test(val);
            r.el.className = ok ? 'met' : '';
            r.el.querySelector('i').className = ok ? 'fas fa-check-circle' : 'fas fa-circle';
            if (ok) met++;
        });

        if (val.length === 0) {
            fillBar.style.width = '0';
            fillBar.style.background = '';
            fillLabel.textContent = 'Enter a password';
            fillLabel.style.color = '#6c757d';
            pwStatus.className = 'field-status';
            pwInput.classList.remove('is-valid', 'is-invalid');
        } else {
            const lvl = levels[met - 1] || levels[0];
            fillBar.style.width  = lvl.pct + '%';
            fillBar.style.background = lvl.colour;
            fillLabel.textContent    = lvl.label;
            fillLabel.style.color    = lvl.labelClass;

            const allMet = met === 5;
            pwInput.classList.toggle('is-valid',   allMet);
            pwInput.classList.toggle('is-invalid', !allMet && val.length > 0);
            pwStatus.innerHTML   = allMet
                ? '<i class="fas fa-circle-check"></i>'
                : '<i class="fas fa-circle-xmark"></i>';
            pwStatus.className   = 'field-status ' + (allMet ? 'valid' : 'invalid');
        }

        checkConfirm();
        updateSubmit();
    }

    function checkConfirm() {
        const pw = pwInput.value;
        const cf = cfInput.value;

        if (cf.length === 0) {
            cfInput.classList.remove('is-valid', 'is-invalid');
            cfStatus.className = 'field-status';
            cfError.style.display = 'none';
            return;
        }

        const match = pw === cf;
        cfInput.classList.toggle('is-valid',   match);
        cfInput.classList.toggle('is-invalid', !match);
        cfStatus.innerHTML   = match
            ? '<i class="fas fa-circle-check"></i>'
            : '<i class="fas fa-circle-xmark"></i>';
        cfStatus.className   = 'field-status ' + (match ? 'valid' : 'invalid');
        cfError.style.display = match ? 'none' : 'flex';
    }

    function updateSubmit() {
        const allReqsMet = Object.values(reqs).every(r => r.test(pwInput.value));
        const pwMatch    = pwInput.value === cfInput.value && cfInput.value.length > 0;
        submitBtn.disabled = !(allReqsMet && pwMatch);
    }

    pwInput.addEventListener('input', function() {

        pwInput.value = pwInput.value.replace(/\s/g, "");
        checkPassword();
    });
    cfInput.addEventListener('input', function () {

        cfInput.value = cfInput.value.replace(/\s/g, "");
        checkConfirm();
        updateSubmit();
    });

    document.getElementById('resetForm').addEventListener('submit', function (e) {

        const allReqsMet = Object.values(reqs).every(r => r.test(pwInput.value));
        const pwMatch    = pwInput.value === cfInput.value;
        if (!allReqsMet || !pwMatch) {
            e.preventDefault();
            return;
        }
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
    });

    document.querySelectorAll('.alert').forEach(function (a) {
        setTimeout(function () {
            a.style.transition = 'opacity .5s';
            a.style.opacity = '0';
            setTimeout(function () { a.style.display = 'none'; }, 500);
        }, 6000);
    });
})();

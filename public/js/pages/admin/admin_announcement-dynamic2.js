const __cfg_admin_admin_announcement_2 = JSON.parse(document.getElementById('js-config-admin-admin-announcement-2')?.dataset.jsConfig || '{}');

window.smsAudienceCounts = __cfg_admin_admin_announcement_2.v1;
window.semaphoreCreditBalance = Number(__cfg_admin_admin_announcement_2.v2);

document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('notifyTemplate');
    const subjectInput = document.getElementById('notifySubject');
    const messageInput = document.getElementById('notifyMessage');
    const channelSelect = document.getElementById('notifyChannel');
    const audienceSelect = document.getElementById('notifyAudience');
    const counter = document.getElementById('notifyCharCounter');
    const previewText = document.getElementById('notifyPreviewText');
    const previewChannel = document.getElementById('notifyPreviewChannel');
    const selectedResidentsBox = document.getElementById('selectedResidentsBox');
    const residentPickerSearch = document.getElementById('residentPickerSearch');
    const selectedResidentCounter = document.getElementById('selectedResidentCounter');

    const smsEstimatedRecipients = document.getElementById('smsEstimatedRecipients');
    const smsEstimatedSegments = document.getElementById('smsEstimatedSegments');
    const smsEstimatedCredits = document.getElementById('smsEstimatedCredits');
    const smsRemainingCredits = document.getElementById('smsRemainingCredits');
    const smsCreditStatusBadge = document.getElementById('smsCreditStatusBadge');
    const estimatedSmsCreditsInput = document.getElementById('estimatedSmsCreditsInput');

    const templates = {
        ayuda: {
            subject: 'Ayuda Distribution Notice',
            message: 'Magandang araw, {name}. May ayuda distribution po ang Barangay Hulong Duhat sa [DATE], [TIME], sa [VENUE]. Magdala po ng valid ID at requirements. Salamat po.'
        },
        emergency: {
            subject: 'Emergency Barangay Advisory',
            message: 'Magandang araw, {name}. Paalala mula sa Barangay Hulong Duhat: [WRITE EMERGENCY DETAILS HERE]. Para sa agarang tulong, makipag-ugnayan sa barangay office. Ingat po.'
        },
        documents: {
            subject: 'Barangay Document Release Reminder',
            message: 'Magandang araw, {name}. Ang inyong barangay document/request ay maaari nang i-follow up o kunin sa barangay office. Magdala po ng valid ID at reference number kung meron. Salamat po.'
        },
        event: {
            subject: 'Barangay Event Announcement',
            message: 'Magandang araw, {name}. Inaanyayahan po kayo sa barangay event: [EVENT NAME] sa [DATE], [TIME], sa [VENUE]. Kita-kits po.'
        }
    };

    function smsLength(message) {
        return Array.from(message || '').length;
    }

    function smsSegments(message) {
        const length = smsLength(message);
        return Math.max(1, Math.ceil(length / 160));
    }

    function selectedResidentCount() {
        return document.querySelectorAll('input[name="resident_ids[]"]:checked').length;
    }

    function estimatedSmsRecipients() {
        const channel = channelSelect?.value || 'sms';

        if (channel === 'email') {
            return 0;
        }

        const audience = audienceSelect?.value || 'all';

        if (audience === 'selected') {
            return selectedResidentCount();
        }

        return Number(window.smsAudienceCounts?.[audience] || 0);
    }

    function refreshSelectedResidentBox() {
        if (!audienceSelect || !selectedResidentsBox) return;

        selectedResidentsBox.style.display = audienceSelect.value === 'selected' ? 'block' : 'none';
        refreshSelectedResidentCounter();
        refreshSmsCreditEstimate();
    }

    function refreshSelectedResidentCounter() {
        if (!selectedResidentCounter) return;

        const selected = selectedResidentCount();
        selectedResidentCounter.textContent = `${selected} resident${selected === 1 ? '' : 's'} selected`;
    }

    function refreshSmsCreditEstimate() {
        const message = messageInput?.value || '';
        const channel = channelSelect?.value || 'sms';
        const recipients = estimatedSmsRecipients();
        const segments = channel === 'email' ? 0 : smsSegments(message);
        const neededCredits = channel === 'email' ? 0 : recipients * segments * 2;
        const availableCredits = Number(window.semaphoreCreditBalance || 0);
        const remainingCredits = availableCredits - neededCredits;

        if (smsEstimatedRecipients) smsEstimatedRecipients.textContent = recipients.toLocaleString();
        if (smsEstimatedSegments) smsEstimatedSegments.textContent = segments.toLocaleString();
        if (smsEstimatedCredits) smsEstimatedCredits.textContent = neededCredits.toLocaleString();
        if (smsRemainingCredits) smsRemainingCredits.textContent = remainingCredits.toLocaleString();
        if (estimatedSmsCreditsInput) estimatedSmsCreditsInput.value = neededCredits;

        if (smsCreditStatusBadge) {
            smsCreditStatusBadge.classList.remove('is-ok', 'is-danger');

            if (channel === 'email') {
                smsCreditStatusBadge.textContent = 'Email only';
                smsCreditStatusBadge.classList.add('is-ok');
            } else if (recipients > 1000) {
                smsCreditStatusBadge.textContent = 'Over 1,000 SMS limit';
                smsCreditStatusBadge.classList.add('is-danger');
            } else if (neededCredits <= availableCredits) {
                smsCreditStatusBadge.textContent = 'Enough credits';
                smsCreditStatusBadge.classList.add('is-ok');
            } else {
                smsCreditStatusBadge.textContent = 'Insufficient credits';
                smsCreditStatusBadge.classList.add('is-danger');
            }
        }
    }

    function updatePreview() {
        if (!messageInput || !previewText) return;

        const message = messageInput.value.trim();
        const channel = channelSelect?.value || 'sms';

        if (counter) {
            const length = smsLength(messageInput.value);
            const segments = smsSegments(messageInput.value);
            counter.textContent = `${length} / 1000 characters · ${segments} SMS segment${segments === 1 ? '' : 's'}`;
        }

        if (previewChannel) {
            previewChannel.textContent = channel === 'both'
                ? 'SMS + Email'
                : channel.toUpperCase();
        }

        previewText.textContent = message
            ? message
                .replaceAll('{name}', 'Juan Dela Cruz')
                .replaceAll('{firstname}', 'Juan')
                .replaceAll('{lastname}', 'Dela Cruz')
                .replaceAll('{barangay}', 'Hulong Duhat')
            : 'Your message preview will appear here.';

        refreshSmsCreditEstimate();
    }

    templateSelect?.addEventListener('change', function () {
        const selected = templates[this.value];

        if (!selected) return;

        if (subjectInput) subjectInput.value = selected.subject;
        if (messageInput) messageInput.value = selected.message;

        updatePreview();
    });

    residentPickerSearch?.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();

        document.querySelectorAll('.resident-picker-item').forEach(function (item) {
            const haystack = item.dataset.search || '';
            item.style.display = haystack.includes(keyword) ? 'flex' : 'none';
        });
    });

    document.querySelectorAll('input[name="resident_ids[]"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            refreshSelectedResidentCounter();
            refreshSmsCreditEstimate();
        });
    });

    messageInput?.addEventListener('input', updatePreview);
    channelSelect?.addEventListener('change', updatePreview);
    audienceSelect?.addEventListener('change', refreshSelectedResidentBox);

    refreshSelectedResidentBox();
    updatePreview();
});

function confirmBulkResidentNotification(event) {
    event.preventDefault();

    const form = event.target;
    const channel = document.getElementById('notifyChannel')?.value || 'sms';
    const audienceSelect = document.getElementById('notifyAudience');
    const audience = audienceSelect?.selectedOptions[0]?.textContent || 'selected audience';
    const audienceValue = audienceSelect?.value || 'all';
    const message = document.getElementById('notifyMessage')?.value.trim() || '';
    const selectedResidents = document.querySelectorAll('input[name="resident_ids[]"]:checked').length;
    const neededCredits = Number(document.getElementById('estimatedSmsCreditsInput')?.value || 0);
    const availableCredits = Number(window.semaphoreCreditBalance || 0);
    const estimatedRecipients = Number(document.getElementById('smsEstimatedRecipients')?.textContent.replaceAll(',', '') || 0);

    if (!message) {
        AdminUI.notify('Please write a message before sending.');
        return false;
    }

    if (audienceValue === 'selected' && selectedResidents === 0) {
        AdminUI.notify('Please select at least one resident.');
        return false;
    }

    if ((channel === 'sms' || channel === 'both') && estimatedRecipients > 1000) {
        AdminUI.notify('SMS bulk notification is limited to 1,000 mobile numbers only.');
        return false;
    }

    if ((channel === 'sms' || channel === 'both') && neededCredits > availableCredits) {
        AdminUI.notify(`Not enough SMS credits. Needed: ${neededCredits}, Available: ${availableCredits}`);
        return false;
    }

    const extraText = audienceValue === 'selected'
        ? `${selectedResidents} selected resident${selectedResidents === 1 ? '' : 's'}`
        : audience;

    const confirmText = `Send this ${channel.toUpperCase()} notification to ${extraText}? Estimated SMS credits: ${neededCredits}.`;

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Send bulk notification?',
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d32f2f',
            cancelButtonColor: '#667085',
            confirmButtonText: 'Yes, send now'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false;
    }

    if (AdminUI.unavailableConfirm(confirmText)) {
        form.submit();
    }

    return false;
}

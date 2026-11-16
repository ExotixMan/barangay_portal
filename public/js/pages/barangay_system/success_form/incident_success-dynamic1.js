const __cfg_barangay_system_success_form_incident_success_1 = JSON.parse(document.getElementById('js-config-barangay-system-success-form-incident-success-1')?.dataset.jsConfig || '{}');

(function(){
    const themeToggle = document.getElementById('themeToggle');
    const savedTheme = localStorage.getItem('barangay-theme');
    if (savedTheme === 'dark') document.body.classList.add('dark-mode');
    if (savedTheme === 'light') document.body.classList.remove('dark-mode');

    if (themeToggle) {
        themeToggle.addEventListener('click', function(){
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('barangay-theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
        });
    }

    function showToast(){
        const toast = document.getElementById('copyToast');
        if (!toast) return;
        toast.classList.add('show');
        setTimeout(function(){ toast.classList.remove('show'); }, 1800);
    }

    const copyBtn = document.getElementById('copyReference');
    if (copyBtn) {
        copyBtn.addEventListener('click', function(){
            const target = document.getElementById(copyBtn.dataset.copyTarget);
            const text = target ? target.textContent.trim() : '';
            if (!text) return;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(showToast);
            } else {
                const temp = document.createElement('textarea');
                temp.value = text;
                temp.setAttribute('readonly', '');
                temp.style.position = 'fixed';
                temp.style.opacity = '0';
                document.body.appendChild(temp);
                temp.select();
                document.execCommand('copy');
                document.body.removeChild(temp);
                showToast();
            }
        });
    }
})();

document.addEventListener('DOMContentLoaded', function(){
    const printButton = document.getElementById('printReport');
    if (!printButton) return;

    printButton.addEventListener('click', function(){
        const complainantName = __cfg_barangay_system_success_form_incident_success_1.v1;
        const referenceNum = __cfg_barangay_system_success_form_incident_success_1.v2;
        const currentDate = __cfg_barangay_system_success_form_incident_success_1.v3;
        const qrData = __cfg_barangay_system_success_form_incident_success_1.v4.replace(':reference', referenceNum).replace(':name', complainantName).replace(':date', currentDate);

        QRCode.toDataURL(qrData, { width: 150 }).then(function(qrDataUrl){
            const printContent = `<!DOCTYPE html>
<html>
<head>
<title>${__cfg_barangay_system_success_form_incident_success_1.v5}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body{font-family:Arial,sans-serif;margin:28px;color:#1e1c1b;background:#fff;line-height:1.5}.receipt{max-width:760px;margin:0 auto;border:1px solid #e3dbcf;border-radius:18px;padding:28px}.header{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;border-bottom:2px solid #9f2f26;padding-bottom:18px;margin-bottom:20px}.header h1{margin:0 0 6px;color:#9f2f26;font-size:22px}.subtitle{margin:0;color:#666;font-size:13px}.status{display:inline-flex;align-items:center;gap:6px;border:1px solid #dfc5bf;background:#f7e7e3;color:#9f2f26;border-radius:999px;padding:7px 12px;font-size:12px;font-weight:700;white-space:nowrap}.detail-row{display:grid;grid-template-columns:180px 1fr;gap:12px;padding:9px 0;border-bottom:1px solid #eee;font-size:14px}.detail-label{color:#666;font-weight:700}.qr{margin:22px 0;padding:18px;text-align:center;background:#f7f4ef;border:1px dashed #d1c6b7;border-radius:14px}.qr img{width:130px;height:130px;margin:10px auto;display:block;border:1px solid #e3dbcf;padding:8px;background:white}.instructions{margin-top:18px;padding:18px;background:#f7f4ef;border-radius:14px;font-size:13px}.instructions h3{color:#9f2f26;margin-top:0}.important{margin-top:14px;padding:12px;border-radius:10px;background:white;border:1px solid #e3dbcf}.footer{text-align:center;margin-top:22px;color:#666;font-size:11px;border-top:1px solid #eee;padding-top:12px}.no-print{text-align:center;margin-top:24px}.no-print button{padding:11px 18px;border-radius:999px;border:0;cursor:pointer;margin:4px;font-weight:700}.print{background:#9f2f26;color:#fff}.close{background:#555;color:#fff}@page{size:8.5in 11in;margin:.5in}@media print{body{margin:0;font-size:12px}.receipt{border:0;padding:0}.no-print{display:none!important}*{page-break-inside:avoid!important}}
</style>
</head>
<body>
<div class="receipt">
    <div class="header">
        <div><h1><i class="fas fa-clipboard-list"></i> ${__cfg_barangay_system_success_form_incident_success_1.v5}</h1><p class="subtitle">${__cfg_barangay_system_success_form_incident_success_1.v6}</p></div>
        <span class="status"><i class="fas fa-shield-halved"></i> ${__cfg_barangay_system_success_form_incident_success_1.v7}</span>
    </div>
    <div class="details">
        <div class="detail-row"><div class="detail-label">${__cfg_barangay_system_success_form_incident_success_1.v8}</div><div><strong>${referenceNum}</strong></div></div>
        <div class="detail-row"><div class="detail-label">${__cfg_barangay_system_success_form_incident_success_1.v9}</div><div>${complainantName}</div></div>
        <div class="detail-row"><div class="detail-label">${__cfg_barangay_system_success_form_incident_success_1.v10}</div><div>${currentDate}</div></div>
        <div class="detail-row"><div class="detail-label">${__cfg_barangay_system_success_form_incident_success_1.v11}</div><div><strong>${__cfg_barangay_system_success_form_incident_success_1.v7}</strong></div></div>
    </div>
    <div class="qr"><strong><i class="fas fa-qrcode"></i> ${__cfg_barangay_system_success_form_incident_success_1.v12}</strong><img src="${qrDataUrl}" alt="${__cfg_barangay_system_success_form_incident_success_1.v13} ${referenceNum}"><p style="font-size:12px;color:#666;margin:0;">${__cfg_barangay_system_success_form_incident_success_1.v14} ${referenceNum}</p></div>
    <div class="instructions">
        <h3><i class="fas fa-list-check"></i> ${__cfg_barangay_system_success_form_incident_success_1.v15}</h3>
        <p><strong>1.</strong> ${__cfg_barangay_system_success_form_incident_success_1.v16}</p>
        <p><strong>2.</strong> ${__cfg_barangay_system_success_form_incident_success_1.v17}</p>
        <p><strong>3.</strong> ${__cfg_barangay_system_success_form_incident_success_1.v18}</p>
        <p><strong>4.</strong> ${__cfg_barangay_system_success_form_incident_success_1.v19}</p>
        <div class="important"><strong><i class="fas fa-triangle-exclamation"></i> ${__cfg_barangay_system_success_form_incident_success_1.v20}</strong> ${__cfg_barangay_system_success_form_incident_success_1.v21}</div>
        <p><strong><i class="fas fa-location-dot"></i> ${__cfg_barangay_system_success_form_incident_success_1.v22}</strong><br>${__cfg_barangay_system_success_form_incident_success_1.v23}<br><strong><i class="fas fa-clock"></i> ${__cfg_barangay_system_success_form_incident_success_1.v24}</strong><br>${__cfg_barangay_system_success_form_incident_success_1.v25}<br>${__cfg_barangay_system_success_form_incident_success_1.v26}</p>
    </div>
    <div class="footer"><p>${__cfg_barangay_system_success_form_incident_success_1.v27}</p><p>${__cfg_barangay_system_success_form_incident_success_1.v28.replace(':year', new Date().getFullYear())}</p></div>
    <div class="no-print"><button onclick="window.print()" class="print"><i class="fas fa-print"></i> ${__cfg_barangay_system_success_form_incident_success_1.v29}</button><button onclick="window.close()" class="close"><i class="fas fa-times"></i> ${__cfg_barangay_system_success_form_incident_success_1.v30}</button></div>
</div>
</body>
</html>`;
            const printWindow = window.open('', '_blank', 'width=820,height=700');
            if (printWindow) {
                printWindow.document.write(printContent);
                printWindow.document.close();
                printWindow.focus();
                setTimeout(function(){ printWindow.print(); }, 300);
            } else {
                PortalFeedback.error(__cfg_barangay_system_success_form_incident_success_1.v31);
            }
        }).catch(function(error){
            console.error(__cfg_barangay_system_success_form_incident_success_1.v32, error);
            PortalFeedback.error(__cfg_barangay_system_success_form_incident_success_1.v33);
        });
    });
});

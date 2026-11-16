const __cfg_barangay_system_success_form_successform_1 = JSON.parse(document.getElementById('js-config-barangay-system-success-form-successform-1')?.dataset.jsConfig || '{}');

document.addEventListener('DOMContentLoaded', function(){
    const printButton = document.getElementById('printReceipt');
    if (!printButton) return;

    printButton.addEventListener('click', function(){
        const serviceName = __cfg_barangay_system_success_form_successform_1.v1;
        const applicantName = __cfg_barangay_system_success_form_successform_1.v2;
        const referenceNum = __cfg_barangay_system_success_form_successform_1.v3;
        const currentDate = __cfg_barangay_system_success_form_successform_1.v4;
        const rawAmount = __cfg_barangay_system_success_form_successform_1.v5;
        const feeLabel = __cfg_barangay_system_success_form_successform_1.v6;
        const numericAmount = Number.parseFloat(rawAmount);
        const freeLabel = __cfg_barangay_system_success_form_successform_1.v7;
        const normalizedFeeLabel = String(feeLabel || '').trim().toLowerCase();
        const isFree = feeLabel
            ? ['free', 'libre', 'no payment', 'walang bayad'].some(token => normalizedFeeLabel.includes(token))
            : (Number.isNaN(numericAmount) || numericAmount <= 0);
        const amountDisplay = feeLabel || (!Number.isNaN(numericAmount) && numericAmount > 0 ? `P${numericAmount.toFixed(2)}` : freeLabel);
        const paymentStepText = isFree
            ? __cfg_barangay_system_success_form_successform_1.v8
            : __cfg_barangay_system_success_form_successform_1.v9.replace(':amount', amountDisplay);
        const applicationReceiptTitle = __cfg_barangay_system_success_form_successform_1.v10.replace(':service', serviceName);
        const qrData = __cfg_barangay_system_success_form_successform_1.v11.replace(':reference', referenceNum).replace(':name', applicantName).replace(':service', serviceName).replace(':date', currentDate).replace(':amount', amountDisplay);

        QRCode.toDataURL(qrData, { width: 150 }).then(function(qrDataUrl){
            const printContent = `<!DOCTYPE html>
<html>
<head>
<title>${applicationReceiptTitle}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body{font-family:Arial,sans-serif;margin:28px;color:#1e1c1b;background:#fff;line-height:1.5}.receipt{max-width:760px;margin:0 auto;border:1px solid #e3dbcf;border-radius:18px;padding:28px}.header{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;border-bottom:2px solid #9f2f26;padding-bottom:18px;margin-bottom:20px}.header h1{margin:0 0 6px;color:#9f2f26;font-size:22px}.subtitle{margin:0;color:#666;font-size:13px}.status{display:inline-flex;align-items:center;gap:6px;border:1px solid #dfc5bf;background:#f7e7e3;color:#9f2f26;border-radius:999px;padding:7px 12px;font-size:12px;font-weight:700;white-space:nowrap}.detail-row{display:grid;grid-template-columns:180px 1fr;gap:12px;padding:9px 0;border-bottom:1px solid #eee;font-size:14px}.detail-label{color:#666;font-weight:700}.qr{margin:22px 0;padding:18px;text-align:center;background:#f7f4ef;border:1px dashed #d1c6b7;border-radius:14px}.qr img{width:130px;height:130px;margin:10px auto;display:block;border:1px solid #e3dbcf;padding:8px;background:white}.instructions{margin-top:18px;padding:18px;background:#f7f4ef;border-radius:14px;font-size:13px}.instructions h3{color:#9f2f26;margin-top:0}.important{margin-top:14px;padding:12px;border-radius:10px;background:white;border:1px solid #e3dbcf}.footer{text-align:center;margin-top:22px;color:#666;font-size:11px;border-top:1px solid #eee;padding-top:12px}.no-print{text-align:center;margin-top:24px}.no-print button{padding:11px 18px;border-radius:999px;border:0;cursor:pointer;margin:4px;font-weight:700}.print{background:#9f2f26;color:#fff}.close{background:#555;color:#fff}@page{size:8.5in 11in;margin:.5in}@media print{body{margin:0;font-size:12px}.receipt{border:0;padding:0}.no-print{display:none!important}*{page-break-inside:avoid!important}}
</style>
</head>
<body>
<div class="receipt">
    <div class="header">
        <div><h1><i class="fas fa-file-signature"></i> ${applicationReceiptTitle}</h1><p class="subtitle">${__cfg_barangay_system_success_form_successform_1.v12}</p></div>
        <span class="status"><i class="fas fa-clock"></i> ${__cfg_barangay_system_success_form_successform_1.v13}</span>
    </div>
    <div class="details">
        <div class="detail-row"><div class="detail-label">${__cfg_barangay_system_success_form_successform_1.v14}</div><div><strong>${referenceNum}</strong></div></div>
        <div class="detail-row"><div class="detail-label">${__cfg_barangay_system_success_form_successform_1.v15}</div><div>${applicantName}</div></div>
        <div class="detail-row"><div class="detail-label">${__cfg_barangay_system_success_form_successform_1.v16}</div><div>${serviceName}</div></div>
        <div class="detail-row"><div class="detail-label">${__cfg_barangay_system_success_form_successform_1.v17}</div><div>${currentDate}</div></div>
        <div class="detail-row"><div class="detail-label">${__cfg_barangay_system_success_form_successform_1.v18}</div><div><strong>${__cfg_barangay_system_success_form_successform_1.v19}</strong></div></div>
        <div class="detail-row"><div class="detail-label">${__cfg_barangay_system_success_form_successform_1.v20}</div><div><strong>${amountDisplay}</strong></div></div>
    </div>
    <div class="qr"><strong><i class="fas fa-qrcode"></i> ${__cfg_barangay_system_success_form_successform_1.v21}</strong><img src="${qrDataUrl}" alt="${__cfg_barangay_system_success_form_successform_1.v22} ${referenceNum}"><p style="font-size:12px;color:#666;margin:0;">${__cfg_barangay_system_success_form_successform_1.v23} ${referenceNum}</p></div>
    <div class="instructions">
        <h3><i class="fas fa-list-check"></i> ${__cfg_barangay_system_success_form_successform_1.v24}</h3>
        <p><strong>1.</strong> ${__cfg_barangay_system_success_form_successform_1.v25}</p>
        <p><strong>2.</strong> ${__cfg_barangay_system_success_form_successform_1.v26}</p>
        <p><strong>3.</strong> ${__cfg_barangay_system_success_form_successform_1.v27}</p>
        <p><strong>4.</strong> ${paymentStepText}</p>
        <div class="important"><strong><i class="fas fa-triangle-exclamation"></i> ${__cfg_barangay_system_success_form_successform_1.v28}</strong> ${__cfg_barangay_system_success_form_successform_1.v29.replace(':service', serviceName)}</div>
        <p><strong><i class="fas fa-location-dot"></i> ${__cfg_barangay_system_success_form_successform_1.v30}</strong><br>${__cfg_barangay_system_success_form_successform_1.v31}<br><strong><i class="fas fa-clock"></i> ${__cfg_barangay_system_success_form_successform_1.v32}</strong><br>${__cfg_barangay_system_success_form_successform_1.v33}<br><strong><i class="fas fa-phone"></i> ${__cfg_barangay_system_success_form_successform_1.v34}</strong> 8-281-1373</p>
    </div>
    <div class="footer"><p>${__cfg_barangay_system_success_form_successform_1.v35}</p><p>${__cfg_barangay_system_success_form_successform_1.v36.replace(':year', new Date().getFullYear())}</p></div>
    <div class="no-print"><button onclick="window.print()" class="print"><i class="fas fa-print"></i> ${__cfg_barangay_system_success_form_successform_1.v37}</button><button onclick="window.close()" class="close"><i class="fas fa-times"></i> ${__cfg_barangay_system_success_form_successform_1.v38}</button></div>
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
                PortalFeedback.error(__cfg_barangay_system_success_form_successform_1.v39);
            }
        }).catch(function(error){
            console.error(__cfg_barangay_system_success_form_successform_1.v40, error);
            PortalFeedback.error(__cfg_barangay_system_success_form_successform_1.v41);
        });
    });
});

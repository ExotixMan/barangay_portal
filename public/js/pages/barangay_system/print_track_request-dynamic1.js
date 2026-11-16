(() => {
    const config = JSON.parse(document.getElementById('js-config-barangay-system-print-track-request-1')?.dataset.jsConfig || '{}');
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('qrCode');
        if (!container) return;
        try {
            if (typeof window.qrcode !== 'function') throw new Error('QR library unavailable');
            const text = config.v4.replace(':reference', config.v1).replace(':name', config.v2).replace(':date', config.v3);
            const code = window.qrcode(0, 'M');
            code.addData(text);
            code.make();
            const image = document.createElement('img');
            image.src = code.createDataURL(3, 12);
            image.alt = config.v5.replace(':reference', config.v1);
            container.replaceChildren(image);
        } catch (error) {
            const message = document.createElement('p');
            message.textContent = config.v7 || 'QR code is unavailable. Please use the reference number above.';
            container.replaceChildren(message);
        }
    });
})();

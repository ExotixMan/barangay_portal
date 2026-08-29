function triggerPrint() {
            const frame = document.getElementById('printFrame');
            try {
                frame.contentWindow.focus();
                frame.contentWindow.print();
            } catch (e) {
                window.print();
            }
        }

        window.addEventListener('load', function () {
            const frame = document.getElementById('printFrame');
            frame.addEventListener('load', function () {
                setTimeout(triggerPrint, 300);
            });
        });

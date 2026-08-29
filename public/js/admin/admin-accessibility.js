document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const root = document.documentElement;

    const decreaseButton = document.getElementById('adminFontDecrease');
    const resetButton = document.getElementById('adminFontReset');
    const increaseButton = document.getElementById('adminFontIncrease');
    const sizeLabel = document.getElementById('adminFontSizeLabel');
    const announcement = document.getElementById('adminFontAnnouncement');

    if (!decreaseButton || !resetButton || !increaseButton) {
        return;
    }

    const fontSizes = [
        {
            key: 'small',
            label: '85%',
            announcement: 'Text size decreased to small'
        },
        {
            key: 'normal',
            label: '100%',
            announcement: 'Text size reset to normal'
        },
        {
            key: 'large',
            label: '115%',
            announcement: 'Text size increased to large'
        },
        {
            key: 'x-large',
            label: '130%',
            announcement: 'Text size increased to extra large'
        }
    ];

    function getCurrentIndex() {
        const currentSize = root.dataset.fontSize || 'normal';

        const index = fontSizes.findIndex(function (size) {
            return size.key === currentSize;
        });

        return index >= 0 ? index : 1;
    }

    function saveFontSize(size) {
        try {
            localStorage.setItem('bisig-admin-font-size', size);
        } catch (error) {
            console.warn('Unable to save font-size preference.', error);
        }
    }

    function updateControls(index) {
        const selectedSize = fontSizes[index];

        if (sizeLabel) {
            sizeLabel.textContent = selectedSize.label;
        }

        decreaseButton.disabled = index === 0;
        increaseButton.disabled = index === fontSizes.length - 1;

        resetButton.disabled = selectedSize.key === 'normal';

        decreaseButton.setAttribute(
            'aria-disabled',
            decreaseButton.disabled ? 'true' : 'false'
        );

        increaseButton.setAttribute(
            'aria-disabled',
            increaseButton.disabled ? 'true' : 'false'
        );

        resetButton.setAttribute(
            'aria-disabled',
            resetButton.disabled ? 'true' : 'false'
        );
    }

    function applyFontSize(index, shouldAnnounce = true) {
        const safeIndex = Math.max(
            0,
            Math.min(index, fontSizes.length - 1)
        );

        const selectedSize = fontSizes[safeIndex];

        root.dataset.fontSize = selectedSize.key;

        saveFontSize(selectedSize.key);
        updateControls(safeIndex);

        if (shouldAnnounce && announcement) {
            announcement.textContent = '';

            window.setTimeout(function () {
                announcement.textContent = selectedSize.announcement;
            }, 50);
        }
    }

    decreaseButton.addEventListener('click', function () {
        applyFontSize(getCurrentIndex() - 1);
    });

    increaseButton.addEventListener('click', function () {
        applyFontSize(getCurrentIndex() + 1);
    });

    resetButton.addEventListener('click', function () {
        const normalIndex = fontSizes.findIndex(function (size) {
            return size.key === 'normal';
        });

        applyFontSize(normalIndex);
    });

    applyFontSize(getCurrentIndex(), false);
});

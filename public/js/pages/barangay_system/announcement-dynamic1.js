const __cfg_barangay_system_announcement_1 = JSON.parse(document.getElementById('js-config-barangay-system-announcement-1')?.dataset.jsConfig || '{}');

    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.bhd-feature-slide');
        const dots = document.querySelectorAll('.bhd-carousel-dot');
        const prevBtn = document.querySelector('.bhd-carousel-btn.prev');
        const nextBtn = document.querySelector('.bhd-carousel-btn.next');
        let current = 0;

        function showSlide(index) {
            if (!slides.length) return;

            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));

            slides[index].classList.add('active');
            if (dots[index]) dots[index].classList.add('active');

            current = index;
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                showSlide((current + 1) % slides.length);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                showSlide((current - 1 + slides.length) % slides.length);
            });
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', function () {
                showSlide(index);
            });
        });

        if (slides.length > 1) {
            setInterval(function () {
                showSlide((current + 1) % slides.length);
            }, 7000);
        }

        const refreshBtn = document.getElementById('refreshAnnouncements');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('_', Date.now());
                window.location.href = url.toString();
            });
        }

        const searchInput = document.getElementById('announcementSearch');
        const sortSelect = document.getElementById('announcementSort');
        const grid = document.getElementById('announcementsGrid');
        const noResults = document.getElementById('noResultsMessage');
        const resetFilters = document.getElementById('resetFilters');

        function filterAndSort() {
            if (!grid) return;

            const query = (searchInput?.value || '').toLowerCase().trim();
            const cards = Array.from(grid.querySelectorAll('.bhd-announcement-card'));
            let visibleCount = 0;

            cards.forEach(card => {
                const text = `${card.dataset.title || ''} ${card.dataset.content || ''}`;
                const show = text.includes(query);
                card.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            const sortedCards = cards.sort(function (a, b) {
                const ad = Number(a.dataset.date || 0);
                const bd = Number(b.dataset.date || 0);
                return (sortSelect?.value || 'latest') === 'oldest' ? ad - bd : bd - ad;
            });

            sortedCards.forEach(card => grid.appendChild(card));

            if (noResults) {
                noResults.classList.toggle('d-none', visibleCount !== 0);
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterAndSort);
        if (sortSelect) sortSelect.addEventListener('change', filterAndSort);

        if (resetFilters) {
            resetFilters.addEventListener('click', function () {
                if (searchInput) searchInput.value = '';
                if (sortSelect) sortSelect.value = 'latest';
                filterAndSort();
            });
        }

        document.querySelectorAll('.modal[id^="announcementDetailModal"]').forEach(function (modalEl) {
            modalEl.addEventListener('shown.bs.modal', function () {
                const slug = modalEl.getAttribute('data-slug');

                if (!slug) return;

                fetch(`${__cfg_barangay_system_announcement_1.v1}/${encodeURIComponent(slug)}/track-view`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '' + __cfg_barangay_system_announcement_1.v2 + '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                }).catch(function () {});
            });
        });
    });

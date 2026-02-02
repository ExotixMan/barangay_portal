// Announcement Page JavaScript

document.addEventListener('DOMContentLoaded', function () {

    /* ===============================
       ANNOUNCEMENT DATA
    =============================== */

    const announcements = [ /* YOUR DATA — UNCHANGED */ 
        // (Your announcement objects stay exactly the same)
    ];

    /* ===============================
       DOM ELEMENTS
    =============================== */

    const carousel = document.getElementById('announcementCarousel');
    const carouselDots = document.querySelector('.carousel-dots');
    const announcementsGrid = document.getElementById('announcementsGrid');
    const categoryButtons = document.querySelectorAll('.category-btn');
    const searchInput = document.getElementById('announcementSearch');
    const sortSelect = document.getElementById('sortAnnouncements');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const visibleCount = document.getElementById('visibleCount');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const prevBtn = document.querySelector('.carousel-btn.prev');
    const nextBtn = document.querySelector('.carousel-btn.next');
    const autoPlayToggle = document.getElementById('autoPlayToggle');
    const carouselProgress = document.getElementById('carouselProgress');
    const subscribeBtn = document.getElementById('subscribeBtn');

    /* ===============================
       STATE
    =============================== */

    let currentCategory = 'all';
    let currentSearch = '';
    let currentSort = 'newest';
    let currentCarouselIndex = 0;
    let itemsPerLoad = 6;
    let visibleItems = itemsPerLoad;
    let carouselInterval = null;
    let isAutoPlaying = true;

    /* ===============================
       INITIALIZE
    =============================== */

    initializeCarousel();
    renderAnnouncements();
    setupEventListeners();
    startAutoPlay();

    /* ===============================
       CAROUSEL FUNCTIONS
    =============================== */

    function initializeCarousel() {
        const featured = announcements.filter(a => a.featured);
        if (!featured.length) return;

        carousel.innerHTML = '';
        carouselDots.innerHTML = '';

        featured.forEach((a, i) => {
            const item = document.createElement('div');
            item.className = `carousel-item ${i === 0 ? 'active' : ''}`;
            item.innerHTML = `
                <img src="${a.image}" alt="${a.title}">
                <div class="carousel-content">
                    <span class="category-badge">${a.type}</span>
                    <h3>${a.title}</h3>
                    <p>${a.description}</p>
                    <div class="carousel-meta">
                        <span>${a.date}</span>
                        <span>${a.views} views</span>
                    </div>
                    <button onclick="viewAnnouncementDetail(${a.id})">
                        Read Full Announcement
                    </button>
                </div>
            `;
            carousel.appendChild(item);

            const dot = document.createElement('div');
            dot.className = `carousel-dot ${i === 0 ? 'active' : ''}`;
            dot.onclick = () => goToCarouselSlide(i);
            carouselDots.appendChild(dot);
        });

        updateCarouselProgress();
    }

    function goToCarouselSlide(index) {
        const items = document.querySelectorAll('.carousel-item');
        const dots = document.querySelectorAll('.carousel-dot');
        if (!items.length) return;

        if (index >= items.length) index = 0;
        if (index < 0) index = items.length - 1;

        items.forEach((el, i) => el.classList.toggle('active', i === index));
        dots.forEach((el, i) => el.classList.toggle('active', i === index));

        carousel.style.transform = `translateX(-${index * 100}%)`;
        currentCarouselIndex = index;
        updateCarouselProgress();

        if (isAutoPlaying) restartAutoPlay();
    }

    function nextCarouselSlide() {
        goToCarouselSlide(currentCarouselIndex + 1);
    }

    function prevCarouselSlide() {
        goToCarouselSlide(currentCarouselIndex - 1);
    }

    function updateCarouselProgress() {
        const items = document.querySelectorAll('.carousel-item');
        if (!items.length) return;
        carouselProgress.style.width =
            ((currentCarouselIndex + 1) / items.length) * 100 + '%';
    }

    function startAutoPlay() {
        if (!isAutoPlaying) return;
        stopAutoPlay();
        carouselInterval = setInterval(nextCarouselSlide, 5000);
    }

    function stopAutoPlay() {
        if (carouselInterval) clearInterval(carouselInterval);
    }

    function restartAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }

    /* ===============================
       ANNOUNCEMENTS
    =============================== */

    function renderAnnouncements() {
        let filtered = announcements
            .filter(a => currentCategory === 'all' || a.category === currentCategory)
            .filter(a => {
                if (!currentSearch) return true;
                const t = currentSearch.toLowerCase();
                return (
                    a.title.toLowerCase().includes(t) ||
                    a.description.toLowerCase().includes(t) ||
                    a.type.toLowerCase().includes(t)
                );
            });

        if (currentSort === 'newest') filtered.sort((a, b) => b.id - a.id);
        if (currentSort === 'oldest') filtered.sort((a, b) => a.id - b.id);
        if (currentSort === 'title') filtered.sort((a, b) => a.title.localeCompare(b.title));

        visibleCount.textContent = Math.min(visibleItems, filtered.length);

        announcementsGrid.innerHTML = '';
        filtered.slice(0, visibleItems).forEach(a => {
            const card = document.createElement('div');
            card.className = 'announcement-card';
            card.innerHTML = `
                <img src="${a.image}">
                <div>
                    <span>${a.type}</span>
                    <h3>${a.title}</h3>
                    <p>${a.description}</p>
                    <button onclick="viewAnnouncementDetail(${a.id})">View</button>
                </div>
            `;
            announcementsGrid.appendChild(card);
        });

        noResultsMessage.style.display = filtered.length ? 'none' : 'block';
        loadMoreBtn.disabled = visibleItems >= filtered.length;
    }

    /* ===============================
       EVENT LISTENERS
    =============================== */

    function setupEventListeners() {

        categoryButtons.forEach(btn => {
            btn.onclick = () => {
                categoryButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentCategory = btn.dataset.category;
                visibleItems = itemsPerLoad;
                renderAnnouncements();
            };
        });

        searchInput.oninput = e => {
            currentSearch = e.target.value;
            visibleItems = itemsPerLoad;
            renderAnnouncements();
        };

        sortSelect.onchange = e => {
            currentSort = e.target.value;
            renderAnnouncements();
        };

        loadMoreBtn.onclick = () => {
            visibleItems += itemsPerLoad;
            renderAnnouncements();
        };

        resetFiltersBtn.onclick = () => {
            currentCategory = 'all';
            currentSearch = '';
            currentSort = 'newest';
            visibleItems = itemsPerLoad;
            renderAnnouncements();
        };

        prevBtn.onclick = prevCarouselSlide;
        nextBtn.onclick = nextCarouselSlide;

        autoPlayToggle.onclick = () => {
            isAutoPlaying = !isAutoPlaying;
            isAutoPlaying ? startAutoPlay() : stopAutoPlay();
        };

        carousel.onmouseenter = stopAutoPlay;
        carousel.onmouseleave = startAutoPlay;

        subscribeBtn.onclick = () => {
            alert('You are now subscribed to barangay announcements.');
        };
    }

});

/* ===============================
   GLOBAL FUNCTION
=============================== */

function viewAnnouncementDetail(id) {
    const url = `announcement-detail.html?id=${id}`;
    window.location.href = url;
}

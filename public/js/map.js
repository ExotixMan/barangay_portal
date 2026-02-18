document.addEventListener('DOMContentLoaded', function () {

    /* ===============================
       MAP INITIALIZATION
    =============================== */

    const map = L.map('barangay-map', {
        scrollWheelZoom: true
    }).setView([14.6589, 120.9614], 16); // Correct Hulong Duhat center

    /* ===============================
       MAP LAYERS
    =============================== */

    const streetLayer = L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        { attribution: '&copy; OpenStreetMap contributors' }
    );

    const satelliteLayer = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        { attribution: 'Tiles © Esri' }
    );

    const terrainLayer = L.tileLayer(
        'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
        { attribution: 'Map data © OpenStreetMap contributors' }
    );

    streetLayer.addTo(map);

    /* ===============================
       BARANGAY BOUNDARY
    =============================== */

    const barangayBoundary = L.polygon([
        [14.6612, 120.9585],
        [14.6608, 120.9648],
        [14.6564, 120.9640],
        [14.6560, 120.9588]
    ], {
        color: '#C62828',
        fillColor: '#C62828',
        fillOpacity: 0.1,
        weight: 2
    }).addTo(map);

    map.fitBounds(barangayBoundary.getBounds());

    /* ===============================
       FIX MAP CENTERING
    =============================== */

    setTimeout(() => {
        map.invalidateSize();
    }, 300);

    /* ===============================
       CUSTOM ICONS
    =============================== */

    function createIcon(icon) {
        return L.divIcon({
            html: `<div class="map-marker"><i class="fas ${icon}"></i></div>`,
            className: 'custom-marker',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -35]
        });
    }

    const icons = {
        hall: createIcon('fa-landmark'),
        health: createIcon('fa-clinic-medical'),
        school: createIcon('fa-school'),
        chapel: createIcon('fa-church'),
        police: createIcon('fa-shield-alt'),
        evac: createIcon('fa-home')
    };

    /* ===============================
       LOCATIONS
    =============================== */

    const locations = {
        hall: {
            latlng: [14.6589, 120.9614],
            name: 'Barangay Hall',
            address: 'M. Blas Street, Hulong Duhat'
        },
        health: {
            latlng: [14.6593, 120.9622],
            name: 'Health Center',
            address: 'F. Santos Street'
        },
        school: {
            latlng: [14.6572, 120.9598],
            name: 'Elementary School',
            address: 'R. Reyes Avenue'
        },
        chapel: {
            latlng: [14.6598, 120.9630],
            name: 'Chapel',
            address: 'San Isidro Street'
        },
        police: {
            latlng: [14.6578, 120.9634],
            name: 'Police Outpost',
            address: 'M. Blas cor. Rizal Ave'
        },
        evac: {
            latlng: [14.6564, 120.9589],
            name: 'Evacuation Center',
            address: 'Covered Court'
        }
    };

    /* ===============================
       ADD MARKERS
    =============================== */

    const markers = {};

    Object.keys(locations).forEach(key => {
        const loc = locations[key];
        markers[key] = L.marker(loc.latlng, { icon: icons[key] })
            .addTo(map)
            .bindPopup(`<b>${loc.name}</b><br>${loc.address}`);
    });

    /* ===============================
       LAYER CONTROLS
    =============================== */

    document.querySelectorAll('.map-control-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.map-control-btn')
                .forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            map.eachLayer(layer => {
                if (layer instanceof L.TileLayer) map.removeLayer(layer);
            });

            if (this.dataset.layer === 'streets') streetLayer.addTo(map);
            if (this.dataset.layer === 'satellite') satelliteLayer.addTo(map);
            if (this.dataset.layer === 'terrain') terrainLayer.addTo(map);

            barangayBoundary.addTo(map);
            Object.values(markers).forEach(m => m.addTo(map));
        });
    });

    /* ===============================
       VIEW ON MAP BUTTONS
    =============================== */

    document.querySelectorAll('.view-on-map-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.target;
            map.setView(locations[target].latlng, 18);
            markers[target].openPopup();
        });
    });

    /* ===============================
       BACK TO TOP BUTTON
    =============================== */

    const backToTopBtn = document.querySelector('.back-to-top-custom') || document.querySelector('.back-to-top');
    
    window.addEventListener('scroll', function() {
        if (backToTopBtn) {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('visible');
            } else {
                backToTopBtn.classList.remove('visible');
            }
        }
    });
    
    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

});

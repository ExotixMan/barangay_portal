const __cfg_barangay_system_map_1 = JSON.parse(document.getElementById('js-config-barangay-system-map-1')?.dataset.jsConfig || '{}');

    let map = null;
    let markers = {};
    let layers = {};
    let currentLayer = 'streets';
    let barangayBoundary = null;
    let activePulseTimeout = null;

    const BARANGAY_CENTER = [14.677913823133792, 120.94193632084244];

    const locations = {
        hall: {
            coords: [14.677913823133792, 120.94193632084244],
            name: __cfg_barangay_system_map_1.v1,
            address: 'Gabriel 2, 425, 74 Gov Andres Gabriel St, Hulong Duhat, Malabon, Metro Manila',
            type: 'hall',
            icon: 'fa-landmark'
        },
        health: {
            coords: [14.677860632259058, 120.94163792509893],
            name: __cfg_barangay_system_map_1.v2,
            address: 'MWHR+4MR, Don Basilio Bautista Blvd, Malabon, 1470 Metro Manila',
            type: 'health',
            icon: 'fa-clinic-medical'
        },
        school: {
            coords: [14.67703962222242, 120.94099488318462],
            name: 'Malabon Elementary School',
            address: 'MWGR+Q9X, Naval St, Malabon, Metro Manila',
            type: 'school',
            icon: 'fa-school'
        },
        chapel: {
            coords: [14.678660686615306, 120.94038622417362],
            name: __cfg_barangay_system_map_1.v3,
            address: '31 A Gervacio St, Malabon, 1470 Metro Manila',
            type: 'chapel',
            icon: 'fa-church'
        },
        police: {
            coords: [14.677847, 120.942039],
            name: __cfg_barangay_system_map_1.v4,
            address: '27, 1471 Don Basilio Bautista Blvd, Malabon, Metro Manila',
            type: 'police',
            icon: 'fa-shield-alt'
        },
        evac: {
            coords: [14.67723781254434, 120.94165939357208],
            name: __cfg_barangay_system_map_1.v5,
            address: 'Naval St, Malabon, Metro Manila',
            type: 'evac',
            icon: 'fa-home'
        },
        market: {
            coords: [14.676013456903119, 120.9412932266691],
            name: __cfg_barangay_system_map_1.v6,
            address: 'Women\'s Club St, Malabon, Metro Manila',
            type: 'market',
            icon: 'fa-shopping-cart'
        },
        playground: {
            coords: [14.676231517438561, 120.94201588080502],
            name: __cfg_barangay_system_map_1.v7,
            address: 'Women\'s Club St, Malabon, 1470 Metro Manila',
            type: 'playground',
            icon: 'fa-child'
        }
    };

    function createMarkerIcon(location, shouldPulse = false) {
        return L.divIcon({
            html: `
                <div class="custom-marker marker-${location.type}${shouldPulse ? ' marker-pulse' : ''}">
                    <i class="fas ${location.icon}"></i>
                </div>
            `,
            className: 'custom-marker-div',
            iconSize: shouldPulse ? [50, 50] : [40, 40],
            iconAnchor: shouldPulse ? [25, 50] : [20, 40],
            popupAnchor: shouldPulse ? [0, -48] : [0, -38]
        });
    }

    function showMapLoadError(message) {
        const mapElement = document.getElementById('barangay-map');

        if (!mapElement) {
            return;
        }

        mapElement.innerHTML = `
            <div style="height:100%;display:grid;place-items:center;padding:24px;text-align:center;">
                <div>
                    <i class="fas fa-triangle-exclamation" style="font-size:2rem;margin-bottom:12px;"></i>
                    <p style="margin:0;font-weight:800;">${message}</p>
                </div>
            </div>
        `;
    }

    function initializeMap() {
        const mapElement = document.getElementById('barangay-map');

        if (!mapElement || map) {
            return;
        }

        if (typeof L === 'undefined') {
            showMapLoadError('The map library could not be loaded. Please check your internet connection and refresh the page.');
            console.error('Leaflet failed to load.');
            return;
        }

        map = L.map(mapElement, {
            zoomControl: true,
            scrollWheelZoom: true
        }).setView(BARANGAY_CENTER, 18);

        layers.streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            minZoom: 16,
            attribution: '&copy; OpenStreetMap contributors'
        });

        layers.satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            {
                maxZoom: 19,
                minZoom: 16,
                attribution: 'Tiles &copy; Esri'
            }
        );

        layers.streets.addTo(map);

        const boundaryCoordinates = [
            [14.677626, 120.946067],
            [14.675282, 120.940417],
            [14.677820, 120.939894],
            [14.680481795397423, 120.93851439586514],
            [14.682998651828333, 120.9366379395298],
            [14.684251, 120.937283],
            [14.684374, 120.938723],
            [14.682156, 120.942751]
        ];

        barangayBoundary = L.polygon(boundaryCoordinates, {
            color: '#C62828',
            fillColor: '#C62828',
            fillOpacity: 0.1,
            weight: 3,
            dashArray: '5, 5'
        }).addTo(map);

        Object.entries(locations).forEach(([key, location]) => {
            markers[key] = L.marker(location.coords, {
                icon: createMarkerIcon(location)
            })
                .addTo(map)
                .bindPopup(`
                    <div style="min-width:200px;">
                        <h6 style="color:#C62828;margin:0 0 5px;">${location.name}</h6>
                        <p style="margin:0 0 5px;font-size:0.9rem;">${location.address}</p>
                        <small style="color:#666;">Use the location cards to navigate.</small>
                    </div>
                `);
        });

        fitAllLocations(false);

        requestAnimationFrame(() => {
            map.invalidateSize(true);
        });

        setTimeout(() => {
            if (map) {
                map.invalidateSize(true);
            }
        }, 250);
    }

    function setupEventListeners() {
        document.querySelectorAll('.map-layer-btn[data-layer]').forEach(button => {
            button.addEventListener('click', function () {
                const nextLayer = this.dataset.layer;

                if (!map || !layers[nextLayer] || currentLayer === nextLayer) {
                    return;
                }

                document.querySelectorAll('.map-layer-btn[data-layer]').forEach(item => {
                    item.classList.remove('active');
                });

                this.classList.add('active');
                map.removeLayer(layers[currentLayer]);
                layers[nextLayer].addTo(map);
                currentLayer = nextLayer;
            });
        });

        window.addEventListener('resize', () => {
            if (!map) {
                return;
            }

            clearTimeout(window.__barangayMapResizeTimer);
            window.__barangayMapResizeTimer = setTimeout(() => {
                map.invalidateSize(true);
            }, 150);
        });
    }

    function getMapBoundsGroup() {
        const mapItems = [...Object.values(markers)];

        if (barangayBoundary) {
            mapItems.push(barangayBoundary);
        }

        return mapItems.length ? L.featureGroup(mapItems) : null;
    }

    function fitAllLocations(animate = true) {
        if (!map) {
            return;
        }

        const group = getMapBoundsGroup();

        if (!group) {
            return;
        }

        map.invalidateSize(true);
        map.fitBounds(group.getBounds().pad(0.1), {
            animate,
            duration: animate ? 0.7 : 0
        });
    }

    function restoreMarkerIcons() {
        Object.entries(markers).forEach(([key, marker]) => {
            marker.setIcon(createMarkerIcon(locations[key]));
        });
    }

    window.showAllLocations = function () {
        if (!map) {
            initializeMap();
        }

        if (!map) {
            return;
        }

        if (activePulseTimeout) {
            clearTimeout(activePulseTimeout);
            activePulseTimeout = null;
        }

        restoreMarkerIcons();
        map.closePopup();
        fitAllLocations(true);
    };

    window.showLocation = function (locationId) {
        if (!map) {
            initializeMap();
        }

        const marker = markers[locationId];
        const location = locations[locationId];

        if (!map || !marker || !location) {
            console.error('Unknown or unavailable map location:', locationId);
            return;
        }

        if (activePulseTimeout) {
            clearTimeout(activePulseTimeout);
        }

        restoreMarkerIcons();
        marker.setIcon(createMarkerIcon(location, true));

        map.invalidateSize(true);
        map.flyTo(marker.getLatLng(), window.innerWidth <= 768 ? 17 : 18, {
            animate: true,
            duration: 0.8
        });

        setTimeout(() => {
            marker.openPopup();
        }, 350);

        activePulseTimeout = setTimeout(() => {
            marker.setIcon(createMarkerIcon(location));
            marker.openPopup();
            activePulseTimeout = null;
        }, 2600);
    };

    window.scrollToMapAndShowLocation = function (locationId) {
        const mapSection = document.getElementById('interactive-map');

        if (!mapSection) {
            window.showLocation(locationId);
            return;
        }

        const fixedHeader = document.querySelector(
            '.navbar, .bhd-navbar, .bhd-header, .site-header, header[role="banner"], body > header'
        );

        const headerHeight = fixedHeader ? fixedHeader.getBoundingClientRect().height : 0;
        const sectionTop = mapSection.getBoundingClientRect().top + window.scrollY;
        const targetTop = Math.max(0, sectionTop - headerHeight - 16);

        window.scrollTo({
            top: targetTop,
            behavior: 'smooth'
        });

        setTimeout(() => {
            if (map) {
                map.invalidateSize(true);
            }

            window.showLocation(locationId);
        }, window.innerWidth <= 768 ? 450 : 550);
    };

    function startBarangayMap() {
        initializeMap();
        setupEventListeners();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startBarangayMap, { once: true });
    } else {
        startBarangayMap();
    }

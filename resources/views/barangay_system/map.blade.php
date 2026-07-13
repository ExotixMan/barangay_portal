@php
    $pageTitle = 'Barangay Map';
    $activePage = 'map';
@endphp

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .bhd-page-hero {
        padding: 58px 0 30px;
    }

    .bhd-page-hero-card {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 28px;
        align-items: end;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 30px;
        padding: clamp(24px, 4vw, 42px);
        box-shadow: var(--shadow);
    }

    .bhd-page-hero-card::before {
        content: '';
        position: absolute;
        inset: -140px auto auto -120px;
        width: 330px;
        height: 330px;
        border-radius: 999px;
        background: var(--brand-soft);
        pointer-events: none;
    }

    .bhd-page-kicker {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        width: fit-content;
        margin-bottom: 16px;
        padding: 7px 11px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--surface-soft);
        color: var(--brand);
        font-size: 0.78rem;
        font-weight: 950;
    }

    .bhd-page-hero-card h1 {
        position: relative;
        z-index: 1;
        font-family: 'Space Grotesk', 'Inter', sans-serif;
        font-size: clamp(2.2rem, 5vw, 4rem);
        line-height: 0.98;
        letter-spacing: -0.055em;
        margin: 0 0 16px;
        color: var(--ink);
        max-width: 760px;
    }

    .bhd-page-hero-card p {
        position: relative;
        z-index: 1;
        max-width: 650px;
        color: var(--muted);
        font-size: clamp(1rem, 1.5vw, 1.1rem);
        margin: 0;
    }

    .bhd-page-actions {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .bhd-map-symbol {
        width: 150px;
        height: 150px;
        display: grid;
        place-items: center;
        border-radius: 28px;
        border: 1px dashed var(--line-strong);
        background: var(--surface-soft);
        color: var(--brand);
        font-size: 3rem;
    }

    .bhd-map-panel {
        overflow: hidden;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 28px;
        box-shadow: var(--shadow);
    }

    .bhd-map-controls {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
        padding: 14px;
        border-bottom: 1px solid var(--line);
        background: var(--surface-soft);
    }

    .map-layer-btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--ink-soft);
        padding: 9px 13px;
        border-radius: 999px;
        font-size: 0.84rem;
        font-weight: 900;
        transition: transform .16s ease, background .16s ease, border-color .16s ease, color .16s ease;
    }

    .map-layer-btn:hover,
    .map-layer-btn.active {
        transform: translateY(-1px);
        border-color: var(--brand);
        background: var(--brand-soft);
        color: var(--brand);
    }

    #barangay-map {
        width: 100%;
        height: min(560px, 68vh);
        min-height: 380px;
        background: var(--surface-soft);
    }

    .bhd-location-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .location-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 18px;
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .location-card:hover {
        transform: translateY(-3px);
        border-color: color-mix(in srgb, var(--brand) 45%, var(--line));
        box-shadow: 0 16px 36px rgba(184, 50, 42, 0.09);
    }

    .location-icon {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        background: var(--brand-soft);
        color: var(--brand);
        margin-bottom: 16px;
    }

    .location-card h3 {
        color: var(--ink);
        font-size: 1rem;
        font-weight: 950;
        line-height: 1.25;
        margin: 0 0 8px;
    }

    .address-text {
        color: var(--muted);
        font-size: 0.84rem;
        margin: 0 0 12px;
        line-height: 1.45;
    }

    .location-services {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin: 4px 0 14px;
    }

    .service-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 8px;
        background: var(--success-soft);
        color: var(--success);
        font-size: 0.7rem;
        font-weight: 950;
    }

    .location-hours {
        display: flex;
        align-items: center;
        gap: 7px;
        color: var(--muted);
        font-size: 0.8rem;
        font-weight: 800;
        padding-top: 12px;
        border-top: 1px solid var(--line);
        margin: auto 0 14px;
    }

    .btn-view-map {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: var(--surface-soft);
        color: var(--brand);
        font-size: 0.82rem;
        font-weight: 950;
        width: 100%;
        transition: background .16s ease, border-color .16s ease, color .16s ease;
    }

    .btn-view-map:hover {
        background: var(--brand-soft);
        border-color: var(--brand);
        color: var(--brand);
    }

    .map-target {
        position: absolute;
        top: -84px;
    }

    .custom-marker {
        background: var(--brand);
        border: 3px solid #fff;
        border-radius: 50%;
        width: 40px !important;
        height: 40px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.22);
    }

    .marker-hall { background: #b8322a; }
    .marker-health { background: #217a46; }
    .marker-school { background: #2563eb; }
    .marker-chapel { background: #946200; }
    .marker-police { background: #7c3aed; }
    .marker-evac { background: #795548; }
    .marker-market { background: #607d8b; }
    .marker-playground { background: #009688; }


    .custom-marker-div {
        background: transparent !important;
        border: 0 !important;
    }

    .marker-pulse {
        animation: markerPulse 0.8s ease-in-out infinite alternate;
    }

    @keyframes markerPulse {
        from {
            transform: scale(1);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.22);
        }

        to {
            transform: scale(1.22);
            box-shadow: 0 0 0 12px rgba(184, 50, 42, 0.18);
        }
    }

    @media (max-width: 1024px) {
        .bhd-location-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .bhd-page-hero-card {
            grid-template-columns: 1fr;
        }

        .bhd-map-symbol {
            width: 110px;
            height: 110px;
            font-size: 2.3rem;
        }
    }

    @media (max-width: 575px) {
        .bhd-page-hero {
            padding-top: 36px;
        }

        .bhd-page-hero-card {
            border-radius: 24px;
            padding: 22px;
        }

        .bhd-page-actions .bhd-btn,
        .bhd-report-band .bhd-btn {
            width: 100%;
        }

        .bhd-location-grid {
            grid-template-columns: 1fr;
        }

        #barangay-map {
            height: 360px;
            min-height: 320px;
        }

        .map-layer-btn {
            width: 100%;
        }
    }
</style>
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker">
                    <i class="fa-solid fa-map-location-dot"></i>
                    {{ __('messages.nav_barangay_map') }}
                </span>

                <h1>{{ __('messages.map_hero_title') }}</h1>
                <p>{{ __('messages.map_hero_subtitle') }}</p>

                <div class="bhd-page-actions">
                    <a href="#interactive-map" class="bhd-btn bhd-btn-primary">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ __('messages.blade_map_blade_open_interactive_map') }}
                    </a>

                    <a href="{{ route('contacts') }}" class="bhd-btn bhd-btn-soft">
                        <i class="fas fa-phone"></i>
                        {{ __('messages.blade_contacts_blade_contact_barangay') }}
                    </a>
                </div>
            </div>

            <div class="bhd-map-symbol" aria-hidden="true">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
        </div>
    </div>
</section>

<section class="bhd-section pt-0" id="interactive-map">
    <div class="map-target"></div>

    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_map_blade_interactive_barangay_map') }}</h2>
                <p>{{ __('messages.blade_map_blade_switch_map_views_show_all_important_places_or_select_a_') }}</p>
            </div>
        </div>

        <div class="bhd-map-panel">
            <div class="bhd-map-controls">
                <button class="map-layer-btn active" data-layer="streets" type="button">
                    <i class="fas fa-road"></i>
                    {{ __('messages.blade_map_blade_street_view') }}
                </button>

                <button class="map-layer-btn" data-layer="satellite" type="button">
                    <i class="fas fa-satellite"></i>
                    {{ __('messages.blade_map_blade_satellite') }}
                </button>

                <button class="map-layer-btn" onclick="showAllLocations()" type="button">
                    <i class="fas fa-eye"></i>
                    {{ __('messages.blade_map_blade_show_all_locations') }}
                </button>
            </div>

            <div id="barangay-map"></div>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.map_locations_title') }}</h2>
                <p>{{ __('messages.map_locations_subtitle') }}</p>
            </div>
        </div>

        <div class="bhd-location-grid">
            <article class="location-card">
                <div class="location-icon"><i class="fas fa-landmark"></i></div>
                <h3>{{ __('messages.blade_map_blade_barangay_hall_office') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_contacts_blade_1_m_blas_st_barangay_hulong_duhat_malabon_city_metro_manil') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_government') }}</span><span class="service-badge">{{ __('messages.services') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_permits') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_mon_fri_8_00am_5_00pm') }}</p>
                <button class="btn-view-map" onclick="scrollToMapAndShowLocation('hall')" type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-clinic-medical"></i></div>
                <h3>{{ __('messages.blade_map_blade_hulong_duhat_health_center') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_gov_andres_gabriel_st') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_medical') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_vaccination') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_checkup') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_mon_sat_7_00am_7_00pm') }}</p>
                <button class="btn-view-map" onclick="scrollToMapAndShowLocation('health')" type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-school"></i></div>
                <h3>{{ __('messages.blade_map_blade_hulong_duhat_elementary_school') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_naval_st_near_barangay_hall') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_education') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_public_school') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_k_6') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_mon_fri_7_30am_4_30pm') }}</p>
                <button class="btn-view-map" onclick="scrollToMapAndShowLocation('school')" type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-church"></i></div>
                <h3>{{ __('messages.blade_map_blade_san_lorenzo_ruiz_chapel') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_31_a_gervacio_st') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_religious') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_worship') }}</span><span class="service-badge">{{ __('messages.community') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_mass_sat_5pm_sun_8am') }}</p>
                <button class="btn-view-map" onclick="scrollToMapAndShowLocation('chapel')" type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>{{ __('messages.blade_map_blade_barangay_police_outpost') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_gabriel_st_corner') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_security') }}</span><span class="service-badge">{{ __('messages.footer_emergency_label') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_assistance') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_24_7_service_available') }}</p>
                <button class="btn-view-map" onclick="scrollToMapAndShowLocation('police')" type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-home"></i></div>
                <h3>{{ __('messages.blade_map_blade_multi_purpose_evacuation_center') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_adjacent_to_barangay_hall') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.footer_emergency_label') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_shelter') }}</span><span class="service-badge">{{ __('messages.blade_announcement_blade_events') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_open_for_emergencies') }}</p>
                <button class="btn-view-map" onclick="scrollToMapAndShowLocation('evac')" type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-shopping-cart"></i></div>
                <h3>{{ __('messages.blade_map_blade_hulong_duhat_public_market') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_women_s_club_st_area') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_fresh_produce') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_groceries') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_food') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_daily_5_00am_8_00pm') }}</p>
                <button class="btn-view-map" onclick="scrollToMapAndShowLocation('market')" type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-child"></i></div>
                <h3>{{ __('messages.blade_map_blade_children_s_playground_park') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_near_elementary_school') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_recreation') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_family') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_exercise') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_open_6_00am_8_00pm') }}</p>
                <button class="btn-view-map" onclick="scrollToMapAndShowLocation('playground')" type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.blade_map_blade_looking_for_barangay_services') }}</h2>
                <p>{{ __('messages.blade_map_blade_after_checking_the_map_residents_can_request_documents_') }}</p>

                <div class="bhd-tags">
                    <span>{{ __('messages.blade_index_blade_barangay_hall') }}</span>
                    <span>{{ __('messages.footer_health_label') }}</span>
                    <span>{{ __('messages.blade_map_blade_school') }}</span>
                    <span>{{ __('messages.incident_type_public_title') }}</span>
                </div>
            </div>

            <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary">
                <i class="fas fa-folder-open"></i>
                {{ __('messages.blade_contacts_blade_view_services') }}
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map = null;
    let markers = {};
    let layers = {};
    let currentLayer = 'streets';
    let barangayBoundary = null;
    let activePulseTimeout = null;

    // Approximate center of Barangay Hulong Duhat, Malabon.
    const BARANGAY_CENTER = [14.677913823133792, 120.94193632084244];

    const locations = {
        hall: {
            coords: [14.677913823133792, 120.94193632084244],
            name: @json(__('messages.blade_map_marker_barangay_hall')),
            address: 'Gabriel 2, 425, 74 Gov Andres Gabriel St, Hulong Duhat, Malabon, Metro Manila',
            type: 'hall',
            icon: 'fa-landmark'
        },
        health: {
            coords: [14.677860632259058, 120.94163792509893],
            name: @json(__('messages.blade_map_marker_health_center')),
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
            name: @json(__('messages.blade_map_marker_barangay_chapel')),
            address: '31 A Gervacio St, Malabon, 1470 Metro Manila',
            type: 'chapel',
            icon: 'fa-church'
        },
        police: {
            coords: [14.677847, 120.942039],
            name: @json(__('messages.blade_map_marker_police_outpost')),
            address: '27, 1471 Don Basilio Bautista Blvd, Malabon, Metro Manila',
            type: 'police',
            icon: 'fa-shield-alt'
        },
        evac: {
            coords: [14.67723781254434, 120.94165939357208],
            name: @json(__('messages.blade_map_marker_evacuation_area')),
            address: 'Naval St, Malabon, Metro Manila',
            type: 'evac',
            icon: 'fa-home'
        },
        market: {
            coords: [14.676013456903119, 120.9412932266691],
            name: @json(__('messages.blade_map_marker_public_market')),
            address: 'Women\'s Club St, Malabon, Metro Manila',
            type: 'market',
            icon: 'fa-shopping-cart'
        },
        playground: {
            coords: [14.676231517438561, 120.94201588080502],
            name: @json(__('messages.blade_map_marker_childrens_playground')),
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

        // Leaflet can calculate an incorrect size while the page is still laying out.
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

        // Refresh Leaflet after the map becomes visible, then focus the marker.
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
</script>
@endpush

@include('barangay_system.partials.public_layout_end')
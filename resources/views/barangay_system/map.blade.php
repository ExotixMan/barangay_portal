@php
    $pageTitle = 'Barangay Map';
    $activePage = 'map';
@endphp

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="{{ asset('css/pages/barangay_system/map.css') }}">
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

                <button class="map-layer-btn" data-click-call="showAllLocations" type="button">
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
                <button class="btn-view-map" data-click-call="scrollToMapAndShowLocation" data-click-args='["hall"]' type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-clinic-medical"></i></div>
                <h3>{{ __('messages.blade_map_blade_hulong_duhat_health_center') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_gov_andres_gabriel_st') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_medical') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_vaccination') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_checkup') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_mon_sat_7_00am_7_00pm') }}</p>
                <button class="btn-view-map" data-click-call="scrollToMapAndShowLocation" data-click-args='["health"]' type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-school"></i></div>
                <h3>{{ __('messages.blade_map_blade_hulong_duhat_elementary_school') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_naval_st_near_barangay_hall') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_education') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_public_school') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_k_6') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_mon_fri_7_30am_4_30pm') }}</p>
                <button class="btn-view-map" data-click-call="scrollToMapAndShowLocation" data-click-args='["school"]' type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-church"></i></div>
                <h3>{{ __('messages.blade_map_blade_san_lorenzo_ruiz_chapel') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_31_a_gervacio_st') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_religious') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_worship') }}</span><span class="service-badge">{{ __('messages.community') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_mass_sat_5pm_sun_8am') }}</p>
                <button class="btn-view-map" data-click-call="scrollToMapAndShowLocation" data-click-args='["chapel"]' type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>{{ __('messages.blade_map_blade_barangay_police_outpost') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_gabriel_st_corner') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_security') }}</span><span class="service-badge">{{ __('messages.footer_emergency_label') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_assistance') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_24_7_service_available') }}</p>
                <button class="btn-view-map" data-click-call="scrollToMapAndShowLocation" data-click-args='["police"]' type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-home"></i></div>
                <h3>{{ __('messages.blade_map_blade_multi_purpose_evacuation_center') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_adjacent_to_barangay_hall') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.footer_emergency_label') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_shelter') }}</span><span class="service-badge">{{ __('messages.blade_announcement_blade_events') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_open_for_emergencies') }}</p>
                <button class="btn-view-map" data-click-call="scrollToMapAndShowLocation" data-click-args='["evac"]' type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-shopping-cart"></i></div>
                <h3>{{ __('messages.blade_map_blade_hulong_duhat_public_market') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_women_s_club_st_area') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_fresh_produce') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_groceries') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_food') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_daily_5_00am_8_00pm') }}</p>
                <button class="btn-view-map" data-click-call="scrollToMapAndShowLocation" data-click-args='["market"]' type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
            </article>

            <article class="location-card">
                <div class="location-icon"><i class="fas fa-child"></i></div>
                <h3>{{ __('messages.blade_map_blade_children_s_playground_park') }}</h3>
                <p class="address-text"><i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.blade_map_blade_near_elementary_school') }}</p>
                <div class="location-services"><span class="service-badge">{{ __('messages.blade_map_blade_recreation') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_family') }}</span><span class="service-badge">{{ __('messages.blade_map_blade_exercise') }}</span></div>
                <p class="location-hours"><i class="fas fa-clock"></i> {{ __('messages.blade_map_blade_open_6_00am_8_00pm') }}</p>
                <button class="btn-view-map" data-click-call="scrollToMapAndShowLocation" data-click-args='["playground"]' type="button"><i class="fas fa-map-marker-alt"></i> {{ __('messages.blade_map_blade_view_on_map') }}</button>
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
@include('shared.js-config', [
    'id' => 'js-config-barangay-system-map-1',
    'data' => [
        'v1' => __('messages.blade_map_marker_barangay_hall'),
        'v2' => __('messages.blade_map_marker_health_center'),
        'v3' => __('messages.blade_map_marker_barangay_chapel'),
        'v4' => __('messages.blade_map_marker_police_outpost'),
        'v5' => __('messages.blade_map_marker_evacuation_area'),
        'v6' => __('messages.blade_map_marker_public_market'),
        'v7' => __('messages.blade_map_marker_childrens_playground'),
    ],
])
<script src="{{ asset('js/pages/barangay_system/map-dynamic1.js') }}"></script>
@endpush

@include('barangay_system.partials.public_layout_end')

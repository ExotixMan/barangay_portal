@php
    $pageTitle = 'History';
    $activePage = 'history';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/barangay_system/history.css') }}">
@endpush

@include('barangay_system.partials.public_layout_start')

<section class="bhd-page-hero">
    <div class="bhd-shell">
        <div class="bhd-page-hero-card">
            <div>
                <span class="bhd-page-kicker">
                    <i class="fa-solid fa-landmark-flag"></i>
                    {{ __('messages.footer_link_history') }}
                </span>

                <h1>{{ __('messages.history_hero_title') }}</h1>
                <p>{{ __('messages.history_hero_subtitle') }}</p>

                <div class="bhd-page-actions">
                    <a href="{{ route('officials') }}" class="bhd-btn bhd-btn-primary">
                        <i class="fas fa-users"></i>
                        {{ __('messages.blade_history_blade_view_officials') }}
                    </a>

                    <a href="{{ route('map') }}" class="bhd-btn bhd-btn-soft">
                        <i class="fas fa-map"></i>
                        {{ __('messages.blade_history_blade_view_barangay_map') }}
                    </a>
                </div>
            </div>

            <div class="bhd-history-year-card">
                <span>{{ __('messages.blade_history_blade_established') }}</span>
                <strong>1861</strong>
            </div>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell bhd-history-intro-grid">
        <div class="bhd-history-copy">
            <div class="bhd-section-head mb-0">
                <div>
                    <h2>{{ __('messages.history_our_beginnings') }}</h2>
                    <p>{{ __('messages.history_info') }}</p>
                </div>
            </div>

            <div class="bhd-history-facts">
                <div class="bhd-history-fact">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ __('messages.blade_history_blade_year_established') }}</span>
                    <strong>1861</strong>
                </div>

                <div class="bhd-history-fact">
                    <i class="fas fa-users"></i>
                    <span>{{ __('messages.history_residents') }}</span>
                    <strong>12,265 {{ __('messages.history_total_residents') }}</strong>
                </div>

                <div class="bhd-history-fact">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>{{ __('messages.blade_history_blade_area') }}</span>
                    <strong>15 {{ __('messages.history_puroks') }}</strong>
                </div>
            </div>
        </div>

        <div class="bhd-history-photo">
            <img src="{{ asset('Images/history.jpg') }}" alt="{{ __('messages.history_early_settlement') }}" loading="lazy">
            <div class="bhd-history-photo-caption">
                <i class="fas fa-location-dot"></i>
                {{ __('messages.history_early_farming') }}
            </div>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.history_timeline') }}</h2>
                <p>{{ __('messages.blade_history_blade_key_milestones_that_shaped_barangay_hulong_duhat_into_t') }}</p>
            </div>
        </div>

        <div class="bhd-timeline">
            <article class="bhd-timeline-item">
                <div class="bhd-timeline-year">1861</div>
                <div class="bhd-timeline-card">
                    <h3><i class="fas fa-seedling"></i> {{ __('messages.history_early_settlement') }}</h3>
                    <p>{{ __('messages.history_early_settlement_desc') }}</p>
                </div>
            </article>

            <article class="bhd-timeline-item">
                <div class="bhd-timeline-year">1975</div>
                <div class="bhd-timeline-card">
                    <h3><i class="fas fa-flag"></i> {{ __('messages.history_official_recognition') }}</h3>
                    <p>{{ __('messages.history_recognition_desc') }}</p>
                </div>
            </article>

            <article class="bhd-timeline-item">
                <div class="bhd-timeline-year">1990</div>
                <div class="bhd-timeline-card">
                    <h3><i class="fas fa-road"></i> {{ __('messages.history_infrastructure') }}</h3>
                    <p>{{ __('messages.history_infrastructure_desc') }}</p>
                </div>
            </article>

            <article class="bhd-timeline-item">
                <div class="bhd-timeline-year">2010</div>
                <div class="bhd-timeline-card">
                    <h3><i class="fas fa-building"></i> {{ __('messages.history_modernization') }}</h3>
                    <p>{{ __('messages.history_modernization_desc') }}</p>
                </div>
            </article>

            <article class="bhd-timeline-item">
                <div class="bhd-timeline-year">2026</div>
                <div class="bhd-timeline-card">
                    <h3><i class="fas fa-laptop"></i> {{ __('messages.history_digital') }}</h3>
                    <p>{{ __('messages.history_digital_desc') }}</p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.history_cultural_heritage') }}</h2>
                <p>{{ __('messages.blade_history_blade_community_traditions_and_values_that_continue_to_define') }}</p>
            </div>
        </div>

        <div class="bhd-card-grid">
            <article class="bhd-simple-card">
                <span class="bhd-task-icon"><i class="fas fa-glass-cheers"></i></span>
                <h3>{{ __('messages.history_annual_fiesta') }}</h3>
                <p>{{ __('messages.history_fiesta_desc') }}</p>
            </article>

            <article class="bhd-simple-card">
                <span class="bhd-task-icon"><i class="fas fa-utensils"></i></span>
                <h3>{{ __('messages.history_local_cuisine') }}</h3>
                <p>{{ __('messages.history_cuisine_desc') }}</p>
            </article>

            <article class="bhd-simple-card">
                <span class="bhd-task-icon"><i class="fas fa-hands-helping"></i></span>
                <h3>{{ __('messages.history_community_values') }}</h3>
                <p>{{ __('messages.history_values_desc') }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.history_landmarks') }}</h2>
                <p>{{ __('messages.blade_history_blade_notable_places_connected_to_the_barangays_growth_and_co') }}</p>
            </div>
        </div>

        <div class="bhd-card-grid">
            <article class="bhd-simple-card bhd-landmark-card">
                <img src="{{ asset('Images/chapel.webp') }}" alt="{{ __('messages.history_chapel') }}" loading="lazy">
                <div class="bhd-landmark-body">
                    <h3>{{ __('messages.history_chapel') }}</h3>
                    <p>{{ __('messages.history_chapel_desc') }}</p>
                    <span class="bhd-year-pill">
                        <i class="fas fa-calendar-check"></i>
                        {{ __('messages.history_established') }} 1965
                    </span>
                </div>
            </article>

            <article class="bhd-simple-card bhd-landmark-card">
                <img src="{{ asset('Images/barangay.jfif') }}" alt="{{ __('messages.history_first_hall') }}" loading="lazy">
                <div class="bhd-landmark-body">
                    <h3>{{ __('messages.history_first_hall') }}</h3>
                    <p>{{ __('messages.history_first_hall_desc') }}</p>
                    <span class="bhd-year-pill">
                        <i class="fas fa-calendar-check"></i>
                        {{ __('messages.history_established') }} 1978
                    </span>
                </div>
            </article>

            <article class="bhd-simple-card bhd-landmark-card">
                <img src="{{ asset('Images/plaza.avif') }}" alt="{{ __('messages.history_plaza') }}" loading="lazy">
                <div class="bhd-landmark-body">
                    <h3>{{ __('messages.history_plaza') }}</h3>
                    <p>{{ __('messages.history_plaza_desc') }}</p>
                    <span class="bhd-year-pill">
                        <i class="fas fa-calendar-check"></i>
                        {{ __('messages.history_established') }} 1985
                    </span>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.history_community_growth') }}</h2>
                <p>{{ __('messages.blade_history_blade_quick_community_facts_presented_in_the_same_clean_card_') }}</p>
            </div>
        </div>

        <div class="bhd-growth-grid">
            <article class="bhd-growth-card">
                <strong>12,265</strong>
                <span>{{ __('messages.history_total_residents') }}</span>
                <small><i class="fas fa-arrow-up"></i> 15% {{ __('messages.history_since_2015') }}</small>
            </article>

            <article class="bhd-growth-card">
                <strong>2,990</strong>
                <span>{{ __('messages.history_households') }}</span>
                <small><i class="fas fa-arrow-up"></i> 12% {{ __('messages.history_since_2015') }}</small>
            </article>

            <article class="bhd-growth-card">
                <strong>85%</strong>
                <span>{{ __('messages.history_infrastructure') }}</span>
                <small><i class="fas fa-arrow-up"></i> 25% {{ __('messages.history_since_2000') }}</small>
            </article>

            <article class="bhd-growth-card">
                <strong>15</strong>
                <span>{{ __('messages.history_active_programs') }}</span>
                <small><i class="fas fa-plus"></i> 5 {{ __('messages.history_new_since_2020') }}</small>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section pt-0">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.blade_history_blade_continue_exploring_barangay_hulong_duhat') }}</h2>
                <p>{{ __('messages.blade_history_blade_view_the_barangay_map_officials_mission_and_vision_or_s') }}</p>

                <div class="bhd-tags">
                    <span>{{ __('messages.nav_history') }}</span>
                    <span>{{ __('messages.blade_history_blade_officials') }}</span>
                    <span>{{ __('messages.blade_history_blade_map') }}</span>
                    <span>{{ __('messages.blade_history_blade_resident_services') }}</span>
                </div>
            </div>

            <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary">
                <i class="fas fa-file-circle-plus"></i>
                {{ __('messages.blade_history_blade_request_a_service') }}
            </a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')

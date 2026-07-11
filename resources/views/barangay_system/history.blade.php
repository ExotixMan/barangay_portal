@php
    $pageTitle = 'History';
    $activePage = 'history';
@endphp

@push('styles')
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

    .bhd-history-year-card {
        min-width: 170px;
        padding: 20px;
        border: 1px dashed var(--line-strong);
        border-radius: 22px;
        background: var(--surface-soft);
        text-align: center;
    }

    .bhd-history-year-card span {
        display: block;
        color: var(--muted);
        font-size: 0.75rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .bhd-history-year-card strong {
        display: block;
        margin-top: 5px;
        font-family: 'Space Grotesk', sans-serif;
        color: var(--brand);
        font-size: 2.35rem;
        line-height: 1;
        letter-spacing: -0.05em;
    }

    .bhd-history-intro-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(300px, 0.95fr);
        gap: 30px;
        align-items: center;
    }

    .bhd-history-copy {
        display: grid;
        gap: 16px;
    }

    .bhd-history-copy p {
        color: var(--muted);
        font-size: 0.98rem;
        margin: 0;
    }

    .bhd-history-facts {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-top: 8px;
    }

    .bhd-history-fact {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 16px;
    }

    .bhd-history-fact i {
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        background: var(--brand-soft);
        color: var(--brand);
        margin-bottom: 14px;
    }

    .bhd-history-fact span {
        display: block;
        color: var(--muted);
        font-size: 0.76rem;
        font-weight: 900;
        margin-bottom: 4px;
    }

    .bhd-history-fact strong {
        display: block;
        color: var(--ink);
        font-size: 1rem;
        font-weight: 950;
        line-height: 1.25;
    }

    .bhd-history-photo {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 26px;
        padding: 10px;
        box-shadow: var(--shadow);
    }

    .bhd-history-photo img {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        border-radius: 20px;
        filter: saturate(0.94) contrast(0.98);
    }

    .bhd-history-photo-caption {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 12px 8px 4px;
        color: var(--muted);
        font-size: 0.84rem;
        font-weight: 800;
    }

    .bhd-timeline {
        position: relative;
        display: grid;
        gap: 14px;
        max-width: 920px;
        margin-inline: auto;
    }

    .bhd-timeline::before {
        content: '';
        position: absolute;
        left: 24px;
        top: 24px;
        bottom: 24px;
        width: 1px;
        background: var(--line-strong);
    }

    .bhd-timeline-item {
        position: relative;
        display: grid;
        grid-template-columns: 50px minmax(0, 1fr);
        gap: 14px;
        align-items: start;
    }

    .bhd-timeline-year {
        position: relative;
        z-index: 1;
        min-height: 50px;
        border-radius: 999px;
        background: var(--brand);
        color: #fff;
        display: grid;
        place-items: center;
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.84rem;
        font-weight: 900;
        box-shadow: 0 12px 26px rgba(184, 50, 42, 0.18);
    }

    .bhd-timeline-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 18px;
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .bhd-timeline-card:hover {
        transform: translateY(-2px);
        border-color: color-mix(in srgb, var(--brand) 45%, var(--line));
        box-shadow: 0 16px 36px rgba(184, 50, 42, 0.09);
    }

    .bhd-timeline-card h3 {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--ink);
        font-size: 1rem;
        font-weight: 950;
        margin: 0 0 7px;
    }

    .bhd-timeline-card h3 i {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--brand-soft);
        color: var(--brand);
        font-size: 0.86rem;
        flex: 0 0 auto;
    }

    .bhd-timeline-card p {
        color: var(--muted);
        font-size: 0.9rem;
        margin: 0;
    }

    .bhd-card-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-simple-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 20px;
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .bhd-simple-card:hover {
        transform: translateY(-3px);
        border-color: color-mix(in srgb, var(--brand) 45%, var(--line));
        box-shadow: 0 16px 36px rgba(184, 50, 42, 0.09);
    }

    .bhd-simple-card h3 {
        color: var(--ink);
        font-size: 1rem;
        font-weight: 950;
        margin: 0 0 7px;
    }

    .bhd-simple-card p {
        color: var(--muted);
        font-size: 0.88rem;
        margin: 0;
    }

    .bhd-landmark-card {
        overflow: hidden;
        padding: 0;
    }

    .bhd-landmark-card img {
        width: 100%;
        aspect-ratio: 16 / 10;
        object-fit: cover;
    }

    .bhd-landmark-body {
        padding: 18px;
    }

    .bhd-year-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        width: fit-content;
        margin-top: 14px;
        padding: 6px 10px;
        border-radius: 999px;
        background: var(--success-soft);
        color: var(--success);
        font-size: 0.76rem;
        font-weight: 950;
    }

    .bhd-growth-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .bhd-growth-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 18px;
    }

    .bhd-growth-card strong {
        display: block;
        font-family: 'Space Grotesk', sans-serif;
        color: var(--brand);
        font-size: 2rem;
        line-height: 1;
        letter-spacing: -0.04em;
        margin-bottom: 8px;
    }

    .bhd-growth-card span {
        display: block;
        color: var(--ink);
        font-size: 0.9rem;
        font-weight: 950;
        line-height: 1.25;
        margin-bottom: 9px;
    }

    .bhd-growth-card small {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--success);
        font-size: 0.78rem;
        font-weight: 900;
    }

    @media (max-width: 900px) {
        .bhd-page-hero-card,
        .bhd-history-intro-grid {
            grid-template-columns: 1fr;
        }

        .bhd-history-year-card {
            width: fit-content;
        }

        .bhd-card-grid,
        .bhd-growth-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
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

        .bhd-page-actions .bhd-btn {
            width: 100%;
        }

        .bhd-history-facts,
        .bhd-card-grid,
        .bhd-growth-grid {
            grid-template-columns: 1fr;
        }

        .bhd-timeline {
            gap: 12px;
        }

        .bhd-timeline-item {
            grid-template-columns: 44px minmax(0, 1fr);
            gap: 12px;
        }

        .bhd-timeline::before {
            left: 22px;
        }

        .bhd-timeline-year {
            min-height: 44px;
            font-size: 0.75rem;
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

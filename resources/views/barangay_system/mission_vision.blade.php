@php
    $pageTitle = 'Mission & Vision';
    $activePage = 'mission_vision';
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

    .bhd-hero-symbol {
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

    .bhd-mv-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .bhd-mv-card {
        position: relative;
        overflow: hidden;
        min-height: 310px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 26px;
        padding: clamp(22px, 3vw, 32px);
        box-shadow: 0 14px 34px rgba(24, 24, 27, 0.04);
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .bhd-mv-card:hover {
        transform: translateY(-3px);
        border-color: color-mix(in srgb, var(--brand) 45%, var(--line));
        box-shadow: 0 18px 42px rgba(184, 50, 42, 0.10);
    }

    .bhd-mv-card::before {
        content: '';
        position: absolute;
        top: -70px;
        right: -70px;
        width: 190px;
        height: 190px;
        border-radius: 999px;
        background: var(--brand-soft);
        pointer-events: none;
    }

    .bhd-mv-icon {
        position: relative;
        z-index: 1;
        width: 54px;
        height: 54px;
        display: grid;
        place-items: center;
        border-radius: 18px;
        background: var(--brand-soft);
        color: var(--brand);
        font-size: 1.25rem;
        margin-bottom: 22px;
    }

    .bhd-mv-card h2 {
        position: relative;
        z-index: 1;
        color: var(--ink);
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.45rem, 2.5vw, 2rem);
        letter-spacing: -0.04em;
        line-height: 1.05;
        margin: 0 0 14px;
    }

    .bhd-mv-card p {
        position: relative;
        z-index: 1;
        color: var(--muted);
        font-size: 0.98rem;
        line-height: 1.75;
        margin: 0;
    }

    .bhd-values-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .bhd-value-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 18px;
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .bhd-value-card:hover {
        transform: translateY(-2px);
        border-color: color-mix(in srgb, var(--brand) 45%, var(--line));
        box-shadow: 0 16px 36px rgba(184, 50, 42, 0.08);
    }

    .bhd-value-card i {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        background: var(--brand-soft);
        color: var(--brand);
        margin-bottom: 15px;
    }

    .bhd-value-card h3 {
        color: var(--ink);
        font-size: 0.98rem;
        font-weight: 950;
        margin: 0 0 7px;
    }

    .bhd-value-card p {
        color: var(--muted);
        font-size: 0.84rem;
        margin: 0;
    }

    .bhd-goal-list {
        display: grid;
        gap: 12px;
    }

    .bhd-goal-item {
        display: grid;
        grid-template-columns: 46px minmax(0, 1fr);
        gap: 14px;
        align-items: start;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 16px;
    }

    .bhd-goal-number {
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 999px;
        background: var(--brand);
        color: #fff;
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 900;
    }

    .bhd-goal-item h3 {
        color: var(--ink);
        font-size: 0.98rem;
        font-weight: 950;
        margin: 0 0 5px;
    }

    .bhd-goal-item p {
        color: var(--muted);
        font-size: 0.86rem;
        margin: 0;
    }

    .bhd-quote-card {
        position: relative;
        overflow: hidden;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 28px;
        padding: clamp(24px, 4vw, 38px);
        text-align: center;
        box-shadow: var(--shadow);
    }

    .bhd-quote-card::before {
        content: '“';
        position: absolute;
        top: -38px;
        left: 24px;
        color: var(--brand-soft);
        font-family: Georgia, serif;
        font-size: 11rem;
        line-height: 1;
        pointer-events: none;
    }

    .bhd-quote-card blockquote {
        position: relative;
        z-index: 1;
        color: var(--ink);
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(1.25rem, 2.4vw, 1.8rem);
        line-height: 1.35;
        letter-spacing: -0.03em;
        margin: 0 auto 16px;
        max-width: 760px;
    }

    .bhd-quote-card span {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--brand);
        font-size: 0.88rem;
        font-weight: 950;
    }

    @media (max-width: 900px) {
        .bhd-page-hero-card,
        .bhd-mv-grid {
            grid-template-columns: 1fr;
        }

        .bhd-hero-symbol {
            width: 110px;
            height: 110px;
            font-size: 2.3rem;
        }

        .bhd-values-grid {
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

        .bhd-page-actions .bhd-btn,
        .bhd-report-band .bhd-btn {
            width: 100%;
        }

        .bhd-values-grid {
            grid-template-columns: 1fr;
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
                    <i class="fas fa-bullseye"></i>
                    {{ __('messages.mv_hero_title') }}
                </span>

                <h1>{{ __('messages.mv_hero_title') }}</h1>
                <p>{{ __('messages.mv_hero_subtitle') }}</p>

                <div class="bhd-page-actions">
                    <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary">
                        <i class="fas fa-file-circle-plus"></i>
                        {{ __('messages.blade_history_blade_request_a_service') }}
                    </a>

                    <a href="{{ route('officials') }}" class="bhd-btn bhd-btn-soft">
                        <i class="fas fa-users"></i>
                        {{ __('messages.blade_history_blade_view_officials') }}
                    </a>
                </div>
            </div>

            <div class="bhd-hero-symbol" aria-hidden="true">
                <i class="fas fa-bullseye"></i>
            </div>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_mission_vision_blade_our_direction') }}</h2>
                <p>{{ __('messages.blade_mission_vision_blade_the_mission_and_vision_below_guide_how_the_barangay_ser') }}</p>
            </div>
        </div>

        <div class="bhd-mv-grid">
            <article class="bhd-mv-card">
                <div class="bhd-mv-icon">
                    <i class="fas fa-bullseye"></i>
                </div>

                <h2>{{ __('messages.mv_mission_title') }}</h2>
                <p>{{ __('messages.mv_mission_subtitle') }}</p>
            </article>

            <article class="bhd-mv-card">
                <div class="bhd-mv-icon">
                    <i class="fas fa-eye"></i>
                </div>

                <h2>{{ __('messages.mv_vision_title') }}</h2>
                <p>{{ __('messages.mv_vision_subtitle') }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.mv_values_title') }}</h2>
                <p>{{ __('messages.blade_mission_vision_blade_simple_principles_that_support_better_resident_service_') }}</p>
            </div>
        </div>

        <div class="bhd-values-grid">
            <article class="bhd-value-card">
                <i class="fas fa-handshake"></i>
                <h3>{{ __('messages.blade_mission_vision_blade_service') }}</h3>
                <p>{{ __('messages.blade_mission_vision_blade_resident_needs_are_handled_with_care_respect_and_clear_') }}</p>
            </article>

            <article class="bhd-value-card">
                <i class="fas fa-shield-heart"></i>
                <h3>{{ __('messages.blade_mission_vision_blade_integrity') }}</h3>
                <p>{{ __('messages.blade_mission_vision_blade_public_service_should_remain_transparent_honest_and_acc') }}</p>
            </article>

            <article class="bhd-value-card">
                <i class="fas fa-people-group"></i>
                <h3>{{ __('messages.community') }}</h3>
                <p>{{ __('messages.blade_mission_vision_blade_barangay_progress_depends_on_cooperation_between_offici') }}</p>
            </article>

            <article class="bhd-value-card">
                <i class="fas fa-laptop"></i>
                <h3>{{ __('messages.blade_mission_vision_blade_accessibility') }}</h3>
                <p>{{ __('messages.blade_mission_vision_blade_services_should_be_easier_to_understand_request_and_tra') }}</p>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_mission_vision_blade_service_goals') }}</h2>
                <p>{{ __('messages.blade_mission_vision_blade_these_goals_connect_the_mission_and_vision_to_practical') }}</p>
            </div>
        </div>

        <div class="bhd-goal-list">
            <article class="bhd-goal-item">
                <div class="bhd-goal-number">01</div>
                <div>
                    <h3>{{ __('messages.blade_mission_vision_blade_make_barangay_services_easier_to_access') }}</h3>
                    <p>{{ __('messages.blade_mission_vision_blade_residents_can_find_services_submit_requests_and_follow_') }}</p>
                </div>
            </article>

            <article class="bhd-goal-item">
                <div class="bhd-goal-number">02</div>
                <div>
                    <h3>{{ __('messages.blade_mission_vision_blade_improve_transparency_in_request_processing') }}</h3>
                    <p>{{ __('messages.blade_mission_vision_blade_residents_can_track_request_status_and_receive_updates_') }}</p>
                </div>
            </article>

            <article class="bhd-goal-item">
                <div class="bhd-goal-number">03</div>
                <div>
                    <h3>{{ __('messages.blade_mission_vision_blade_support_faster_communication') }}</h3>
                    <p>{{ __('messages.blade_mission_vision_blade_announcements_events_and_important_reminders_are_shown_') }}</p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-quote-card">
            <blockquote>
                {{ __('messages.blade_mission_vision_blade_a_clearer_and_more_accessible_barangay_service_helps_re') }}
            </blockquote>

            <span>
                <i class="fas fa-location-dot"></i>
                {{ __('messages.blade_mission_vision_blade_barangay_hulong_duhat_resident_service_portal') }}
            </span>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.blade_mission_vision_blade_need_help_from_the_barangay') }}</h2>
                <p>{{ __('messages.blade_mission_vision_blade_use_the_online_portal_to_request_a_service_track_your_a') }}</p>

                <div class="bhd-tags">
                    <span>{{ __('messages.blade_contacts_blade_clearance') }}</span>
                    <span>{{ __('messages.profile_counter_residency') }}</span>
                    <span>{{ __('messages.profile_counter_indigency') }}</span>
                    <span>{{ __('messages.nav_blotter_report') }}</span>
                </div>
            </div>

            <a href="{{ route('services') }}" class="bhd-btn bhd-btn-primary">
                <i class="fas fa-file-circle-plus"></i>
                {{ __('messages.blade_contacts_blade_view_services') }}
            </a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')

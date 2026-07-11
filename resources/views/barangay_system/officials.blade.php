@php
    $pageTitle = 'Barangay Officials';
    $activePage = 'officials';
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
        max-width: 780px;
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

    .bhd-official-term-card {
        min-width: 190px;
        padding: 20px;
        border: 1px dashed var(--line-strong);
        border-radius: 22px;
        background: var(--surface-soft);
        text-align: center;
    }

    .bhd-official-term-card span {
        display: block;
        color: var(--muted);
        font-size: 0.75rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .bhd-official-term-card strong {
        display: block;
        margin-top: 6px;
        font-family: 'Space Grotesk', sans-serif;
        color: var(--brand);
        font-size: 1.85rem;
        line-height: 1;
        letter-spacing: -0.05em;
    }

    .bhd-overview-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .bhd-overview-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 18px;
    }

    .bhd-overview-card strong {
        display: block;
        color: var(--brand);
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2rem;
        line-height: 1;
        letter-spacing: -0.04em;
        margin-bottom: 8px;
    }

    .bhd-overview-card span {
        color: var(--muted);
        font-size: 0.78rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.07em;
    }

    .bhd-captain-card {
        display: grid;
        grid-template-columns: 280px minmax(0, 1fr);
        gap: 0;
        overflow: hidden;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 28px;
        box-shadow: var(--shadow);
    }

    .bhd-official-visual {
        position: relative;
        min-height: 280px;
        display: grid;
        place-items: center;
        background:
            radial-gradient(circle at top left, var(--brand-soft), transparent 55%),
            var(--surface-soft);
        border-right: 1px solid var(--line);
    }

    .bhd-avatar {
        width: 150px;
        height: 150px;
        display: grid;
        place-items: center;
        border-radius: 999px;
        background: var(--surface);
        color: var(--brand);
        border: 1px solid var(--line-strong);
        font-size: 4rem;
    }

    .bhd-crown-badge {
        position: absolute;
        top: 22px;
        right: 22px;
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 999px;
        background: var(--brand);
        color: #fff;
        box-shadow: 0 12px 24px rgba(184, 50, 42, 0.24);
    }

    .bhd-captain-info {
        padding: clamp(24px, 4vw, 36px);
    }

    .bhd-captain-info h3,
    .bhd-official-card h3 {
        color: var(--ink);
        font-size: clamp(1.2rem, 2vw, 1.7rem);
        font-weight: 950;
        line-height: 1.15;
        margin: 0 0 10px;
    }

    .bhd-position-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        width: fit-content;
        padding: 7px 11px;
        border-radius: 999px;
        background: var(--brand-soft);
        color: var(--brand);
        font-size: 0.78rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .bhd-captain-highlight {
        margin-top: 18px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 14px;
        background: var(--surface-soft);
        color: var(--ink-soft);
        font-size: 0.88rem;
        font-weight: 850;
    }

    .bhd-focus-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 18px;
    }

    .bhd-focus-chips span {
        padding: 7px 10px;
        border-radius: 999px;
        background: var(--surface);
        border: 1px solid var(--line);
        color: var(--muted);
        font-size: 0.78rem;
        font-weight: 900;
    }

    .bhd-official-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .bhd-other-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        max-width: 760px;
        margin-inline: auto;
    }

    .bhd-official-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 22px;
        overflow: hidden;
        transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
    }

    .bhd-official-card:hover {
        transform: translateY(-3px);
        border-color: color-mix(in srgb, var(--brand) 45%, var(--line));
        box-shadow: 0 16px 36px rgba(184, 50, 42, 0.09);
    }

    .bhd-official-card-top {
        min-height: 135px;
        display: grid;
        place-items: center;
        background: var(--surface-soft);
        border-bottom: 1px solid var(--line);
        position: relative;
    }

    .bhd-official-card-top .bhd-avatar {
        width: 92px;
        height: 92px;
        font-size: 2.3rem;
    }

    .bhd-card-label {
        position: absolute;
        top: 12px;
        left: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 999px;
        background: var(--surface);
        color: var(--brand);
        border: 1px solid var(--line);
        font-size: 0.72rem;
        font-weight: 950;
    }

    .bhd-official-card-body {
        padding: 18px;
        text-align: center;
    }

    .bhd-official-card h3 {
        font-size: 1rem;
        margin-bottom: 12px;
    }

    .bhd-seat {
        display: block;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--line);
        color: var(--muted);
        font-size: 0.74rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .bhd-empty-card {
        background: var(--surface);
        border: 1px dashed var(--line-strong);
        border-radius: 18px;
        padding: 24px;
        color: var(--muted);
        text-align: center;
        font-weight: 850;
    }

    .bhd-org-chart {
        display: grid;
        gap: 14px;
        max-width: 900px;
        margin-inline: auto;
    }

    .bhd-org-row {
        display: flex;
        justify-content: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .bhd-org-card {
        min-width: 230px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 18px;
        text-align: center;
    }

    .bhd-org-card.primary {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }

    .bhd-org-card i {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        margin: 0 auto 10px;
        border-radius: 14px;
        background: var(--brand-soft);
        color: var(--brand);
    }

    .bhd-org-card.primary i {
        background: rgba(255,255,255,0.18);
        color: #fff;
    }

    .bhd-org-card h4 {
        color: inherit;
        font-size: 0.98rem;
        font-weight: 950;
        margin: 0 0 4px;
    }

    .bhd-org-card p {
        color: inherit;
        opacity: .78;
        font-size: 0.82rem;
        margin: 0;
        font-weight: 750;
    }

    @media (max-width: 1024px) {
        .bhd-official-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .bhd-overview-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .bhd-page-hero-card,
        .bhd-captain-card {
            grid-template-columns: 1fr;
        }

        .bhd-official-visual {
            border-right: 0;
            border-bottom: 1px solid var(--line);
        }

        .bhd-official-term-card {
            width: fit-content;
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

        .bhd-overview-grid,
        .bhd-official-grid,
        .bhd-other-grid {
            grid-template-columns: 1fr;
        }

        .bhd-avatar {
            width: 120px;
            height: 120px;
            font-size: 3rem;
        }

        .bhd-official-visual {
            min-height: 220px;
        }

        .bhd-org-card {
            min-width: 100%;
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
                    <i class="fas fa-users"></i>
                    {{ __('messages.nav_barangay_officials') }}
                </span>

                <h1>{{ __('messages.officials_hero_title') }}</h1>
                <p>{{ __('messages.officials_hero_subtitle') }}</p>

                <div class="bhd-page-actions">
                    <a href="{{ route('contacts') }}" class="bhd-btn bhd-btn-primary">
                        <i class="fas fa-phone"></i>
                        {{ __('messages.blade_contacts_blade_contact_barangay') }}
                    </a>

                    <a href="{{ route('services') }}" class="bhd-btn bhd-btn-soft">
                        <i class="fas fa-folder-open"></i>
                        {{ __('messages.blade_contacts_blade_view_services') }}
                    </a>
                </div>
            </div>

            <div class="bhd-official-term-card">
                <span>{{ __('messages.blade_officials_blade_current_term') }}</span>
                <strong>2023-2026</strong>
            </div>
        </div>
    </div>
</section>

<section class="bhd-section pt-0">
    <div class="bhd-shell">
        <div class="bhd-overview-grid">
            <article class="bhd-overview-card">
                <strong>{{ $officialCounts['total'] ?? 0 }}</strong>
                <span>{{ __('messages.blade_officials_blade_total_officials') }}</span>
            </article>

            <article class="bhd-overview-card">
                <strong>{{ $officialCounts['kagawads'] ?? 0 }}</strong>
                <span>{{ __('messages.blade_officials_blade_sangguniang_members') }}</span>
            </article>

            <article class="bhd-overview-card">
                <strong>{{ $officialCounts['appointed'] ?? 0 }}</strong>
                <span>{{ __('messages.blade_officials_blade_appointed_officers') }}</span>
            </article>

            <article class="bhd-overview-card">
                <strong>2026</strong>
                <span>{{ __('messages.blade_officials_blade_term_ends') }}</span>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.officials_punong_label') }}</h2>
                <p>{{ __('messages.officials_punong_desc') }}</p>
            </div>
        </div>

        <article class="bhd-captain-card">
            <div class="bhd-official-visual">
                <div class="bhd-avatar">
                    <i class="fas fa-user"></i>
                </div>

                <div class="bhd-crown-badge">
                    <i class="fas fa-crown"></i>
                </div>
            </div>

            <div class="bhd-captain-info">
                <h3>{{ $captain ? 'KAP. ' . strtoupper($captain->full_name) : 'KAP. TO BE ANNOUNCED' }}</h3>

                <span class="bhd-position-pill">
                    <i class="fas fa-user-tie"></i>
                    {{ __('messages.officials_punong_label') }}
                </span>

                <div class="bhd-captain-highlight">
                    <i class="fas fa-calendar-check"></i>
                    <span>{{ __('messages.blade_officials_blade_public_service_term_2023_2026') }}</span>
                </div>

                <div class="bhd-focus-chips">
                    <span>{{ __('messages.blade_officials_blade_governance') }}</span>
                    <span>{{ __('messages.blade_officials_blade_community_safety') }}</span>
                    <span>{{ __('messages.blade_officials_blade_public_service_delivery') }}</span>
                    <span>{{ __('messages.blade_officials_blade_barangay_development') }}</span>
                </div>
            </div>
        </article>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_officials_blade_sangguniang_barangay') }}</h2>
                <p>{{ __('messages.officials_sangunian_desc') }}</p>
            </div>
        </div>

        @if($kagawads->count())
            <div class="bhd-official-grid">
                @foreach ($kagawads as $index => $kagawad)
                    <article class="bhd-official-card">
                        <div class="bhd-official-card-top">
                            <span class="bhd-card-label">
                                <i class="fas fa-gavel"></i>
                                {{ __('messages.blade_officials_seat_number', ['number' => str_pad($index + 1, 2, '0', STR_PAD_LEFT)]) }}
                            </span>

                            <div class="bhd-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>

                        <div class="bhd-official-card-body">
                            <h3>{{ 'KAG. ' . strtoupper($kagawad->full_name) }}</h3>

                            <span class="bhd-position-pill">{{ __('messages.officials_kagawad_label') }}</span>
                            <span class="bhd-seat">Council {{ __('messages.blade_officials_seat_number', ['number' => str_pad($index + 1, 2, '0', STR_PAD_LEFT)]) }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="bhd-empty-card">
                {{ __('messages.blade_officials_blade_no_sangguniang_barangay_members_are_currently_assigned') }}
            </div>
        @endif
    </div>
</section>

<section class="bhd-section bhd-section-muted">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_officials_blade_other_officials') }}</h2>
                <p>{{ __('messages.officials_other_officials') }}</p>
            </div>
        </div>

        <div class="bhd-other-grid">
            <article class="bhd-official-card">
                <div class="bhd-official-card-top">
                    <span class="bhd-card-label">
                        <i class="fas fa-file-alt"></i>
                        {{ __('messages.blade_officials_blade_secretary') }}
                    </span>

                    <div class="bhd-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="bhd-official-card-body">
                    <h3>{{ $secretary ? 'SEC. ' . strtoupper($secretary->full_name) : 'SEC. TO BE ANNOUNCED' }}</h3>
                    <span class="bhd-position-pill">{{ __('messages.officials_secretary_label') }}</span>
                    <span class="bhd-seat">{{ __('messages.blade_officials_blade_administrative_office') }}</span>
                </div>
            </article>

            <article class="bhd-official-card">
                <div class="bhd-official-card-top">
                    <span class="bhd-card-label">
                        <i class="fas fa-coins"></i>
                        {{ __('messages.blade_officials_blade_treasurer') }}
                    </span>

                    <div class="bhd-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="bhd-official-card-body">
                    <h3>{{ $treasurer ? 'TREAS. ' . strtoupper($treasurer->full_name) : 'TREAS. TO BE ANNOUNCED' }}</h3>
                    <span class="bhd-position-pill">{{ __('messages.officials_treasurer_label') }}</span>
                    <span class="bhd-seat">{{ __('messages.blade_officials_blade_finance_office') }}</span>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="bhd-section">
    <div class="bhd-shell">
        <div class="bhd-section-head">
            <div>
                <h2>{{ __('messages.blade_officials_blade_organizational_structure') }}</h2>
                <p>{{ __('messages.officials_organizational_chart') }}</p>
            </div>
        </div>

        <div class="bhd-org-chart">
            <div class="bhd-org-row">
                <article class="bhd-org-card primary">
                    <i class="fas fa-crown"></i>
                    <h4>{{ __('messages.officials_punong_label') }}</h4>
                    <p>{{ __('messages.blade_officials_blade_chief_executive') }}</p>
                </article>
            </div>

            <div class="bhd-org-row">
                <article class="bhd-org-card">
                    <i class="fas fa-file-alt"></i>
                    <h4>{{ __('messages.blade_officials_blade_secretary') }}</h4>
                    <p>{{ __('messages.blade_officials_blade_records_documentation') }}</p>
                </article>

                <article class="bhd-org-card">
                    <i class="fas fa-coins"></i>
                    <h4>{{ __('messages.blade_officials_blade_treasurer') }}</h4>
                    <p>{{ __('messages.blade_officials_blade_financial_management') }}</p>
                </article>
            </div>

            <div class="bhd-org-row">
                <article class="bhd-org-card">
                    <i class="fas fa-users"></i>
                    <h4>{{ __('messages.blade_officials_blade_sangguniang_barangay') }}</h4>
                    <p>{{ __('messages.blade_officials_blade_7_kagawads_legislative_body') }}</p>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="bhd-section pt-0">
    <div class="bhd-shell">
        <div class="bhd-report-band">
            <div>
                <h2>{{ __('messages.blade_officials_blade_need_to_contact_the_barangay') }}</h2>
                <p>{{ __('messages.blade_officials_blade_for_documents_concerns_and_assistance_residents_may_use') }}</p>

                <div class="bhd-tags">
                    <span>{{ __('messages.blade_history_blade_officials') }}</span>
                    <span>{{ __('messages.blade_officials_blade_public_service') }}</span>
                    <span>{{ __('messages.blade_officials_blade_resident_assistance') }}</span>
                    <span>{{ __('messages.blade_officials_blade_community_support') }}</span>
                </div>
            </div>

            <a href="{{ route('contacts') }}" class="bhd-btn bhd-btn-primary">
                <i class="fas fa-phone"></i>
                {{ __('messages.contact') }}
            </a>
        </div>
    </div>
</section>

@include('barangay_system.partials.public_layout_end')

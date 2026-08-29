@php
    $pageTitle = 'Barangay Officials';
    $activePage = 'officials';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/barangay_system/officials.css') }}">
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

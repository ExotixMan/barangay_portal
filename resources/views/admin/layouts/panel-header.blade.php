<div class="admin-table-head">
    <div class="table-head-title">
        <div class="table-head-icon">
            <i class="{{ $icon ?? 'fas fa-table-list' }}"></i>
        </div>

        <div class="min-w-0">
            @if(!empty($kicker))
                <div class="table-kicker">{{ $kicker }}</div>
            @endif
            <h3>{{ $title }}</h3>
            @if(!empty($description))
                <p>{{ $description }}</p>
            @endif
        </div>
    </div>

    @hasSection('panel_actions')
        <div class="d-flex flex-wrap gap-2">
            @yield('panel_actions')
        </div>
    @endif
</div>

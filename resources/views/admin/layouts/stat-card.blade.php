<div class="stat-card d-flex align-items-center justify-content-between">
    <div>
        <div class="stat-label">{{ $label }}</div>
        <div class="stat-number">{{ $value }}</div>

        @if(!empty($helper))
            <small class="{{ $helperClass ?? '' }}">
                @if(!empty($helperIcon))
                    <i class="{{ $helperIcon }}"></i>
                @endif
                {{ $helper }}
            </small>
        @endif
    </div>

    <div class="stat-icon {{ !empty($tone) ? 'stat-icon-' . $tone : '' }}">
        <i class="{{ $icon ?? 'fas fa-chart-simple' }}"></i>
    </div>
</div>

<div class="fab-container">
    <div class="speed-dial" id="speedDial">
        <button class="fab-action" id="translateBtn" title="{{ __('messages.translate_text') }}">
            @if(app()->getLocale() == 'en')
                <span>{{ __('messages.fab_switch_to_filipino') }}</span>
            @else
                <span>{{ __('messages.fab_switch_to_english') }}</span>
            @endif
        </button>
        <button class="fab-action" id="darkModeBtn" title="{{ __('messages.toggle_dark') }}">
            <i class="fas fa-moon"></i>
        </button>
        <button class="fab-action" id="chatBtn" title="{{ __('messages.chat_assistant') }}">
            <i class="fas fa-comment-dots"></i>
        </button>
    </div>
    <button class="fab-main" id="fabMain">
        <i class="fas fa-gear"></i>
    </button>
</div>

<button class="back-to-top" id="backToTop" aria-label="{{ __('messages.back_to_top') }}">
    <i class="fas fa-chevron-up"></i>
</button>

</main>

@include('barangay_system.partials.public_footer')

@include('barangay_system.partials.fab')

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/floating-actions.js') }}"></script>
<script src="{{ asset('js/dark-mode.js') }}"></script>

<script src="{{ asset('js/public-layout.js') }}"></script>
<script src="{{ asset('js/action-bindings.js') }}"></script>

@stack('scripts')

</body>
</html>

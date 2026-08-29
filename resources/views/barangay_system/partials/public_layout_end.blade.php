</main>

@include('barangay_system.partials.public_footer')

@include('barangay_system.partials.fab')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/floating-actions.js') }}"></script>
<script src="{{ asset('js/dark-mode.js') }}"></script>

<script src="{{ asset('js/public-layout.js') }}"></script>
<script src="{{ asset('js/action-bindings.js') }}"></script>

@stack('scripts')

</body>
</html>

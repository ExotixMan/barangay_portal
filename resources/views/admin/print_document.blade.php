<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Document</title>
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/admin/print_document.css') }}">
</head>
<body>
    <div class="toolbar">
        <div class="title">Print Document</div>
        <div class="actions">
            <a class="btn" href="{{ $pdfUrl }}" target="_blank" rel="noopener">Open PDF</a>
            <button class="btn" type="button" data-click-call="triggerPrint">Print</button>
        </div>
    </div>

    <div class="frame-wrap">
        <iframe id="printFrame" src="{{ $pdfUrl }}" title="Print Document"></iframe>
    </div>

    <script src="{{ asset('js/pages/admin/print_document.js') }}"></script>
<script src="{{ asset('js/action-bindings.js') }}"></script>
</body>
</html>

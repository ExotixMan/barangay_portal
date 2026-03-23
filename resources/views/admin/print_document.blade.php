<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Document</title>
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: #f6f7fb;
            font-family: Arial, sans-serif;
        }

        .toolbar {
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 12px;
            background: #ffffff;
            border-bottom: 1px solid #d9dde8;
        }

        .title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            border: 1px solid #d1d5db;
            background: #fff;
            color: #111827;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #f9fafb;
        }

        .frame-wrap {
            height: calc(100% - 49px);
            width: 100%;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        @media print {
            .toolbar {
                display: none;
            }

            .frame-wrap {
                height: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div class="title">Print Document</div>
        <div class="actions">
            <a class="btn" href="{{ $pdfUrl }}" target="_blank" rel="noopener">Open PDF</a>
            <button class="btn" type="button" onclick="triggerPrint()">Print</button>
        </div>
    </div>

    <div class="frame-wrap">
        <iframe id="printFrame" src="{{ $pdfUrl }}" title="Print Document"></iframe>
    </div>

    <script>
        function triggerPrint() {
            const frame = document.getElementById('printFrame');
            try {
                frame.contentWindow.focus();
                frame.contentWindow.print();
            } catch (e) {
                window.print();
            }
        }

        window.addEventListener('load', function () {
            const frame = document.getElementById('printFrame');
            frame.addEventListener('load', function () {
                setTimeout(triggerPrint, 300);
            });
        });
    </script>
</body>
</html>

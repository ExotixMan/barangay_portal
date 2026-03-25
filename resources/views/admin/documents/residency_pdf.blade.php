<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Residency Certificate</title>
    <style>
        @page {
            margin: 40px 55px 40px 55px;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            line-height: 1.7;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .document {
            width: 100%;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 35px;
            letter-spacing: 0.5px;
        }

        .paragraph {
            text-align: justify;
            margin-bottom: 22px;
        }

        .indent {
            text-indent: 45px;
        }

        .purpose-box {
            margin-top: 18px;
            margin-bottom: 28px;
            min-height: 35px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }

        .issued {
            text-align: justify;
            margin-top: 10px;
            margin-bottom: 85px;
        }

        .signature-block {
            margin-top: 30px;
            text-align: left;
        }

        .signature-line {
            margin-top: 35px;
            font-weight: bold;
        }

        .footer-notes {
            margin-top: 60px;
            font-size: 12px;
            line-height: 1.5;
        }

        .prepared-by {
            margin-top: 25px;
            font-size: 12px;
            font-weight: bold;
        }

        .bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="document">

        <div class="title">CERTIFICATION</div>

        <div class="paragraph indent">
            This is to certify that <span class="bold">{{ $fullName }}</span> is a bona fide resident at
            <span class="bold">{{ $address }}</span>.
        </div>

        <div class="paragraph" style="margin-top: 30px;">
            <span class="bold uppercase">THIS CERTIFICATION IS BEING ISSUED</span>
            upon the request of the above named person for the following purposes
            <span class="bold uppercase">AS PER REQUIREMENT AND/OR TO SUPPORT HIS/HER:</span>
        </div>

        <div class="purpose-box">
            {{ $purpose }}
        </div>

        <div class="issued">
            ISSUED this <span class="bold">{{ $day }}</span> day of
            <span class="bold">{{ $month }}</span>, <span class="bold">{{ $year }}</span>
            at Barangay Hulong Duhat, City of Malabon.
        </div>

        <div class="signature-block">
            <div class="signature-line">
                Print Name &amp; Signature {{ $fullName }}
            </div>
        </div>

        <div class="footer-notes">
            <div><span class="bold">CONTROL NO:</span> {{ $controlNo }}</div>

            <div style="margin-top: 18px;">Not Valid if without control number</div>
            <div>Not Valid if without dry seal</div>

            <div class="prepared-by">
                PREPARED BY: {{ $staffName }}
            </div>
        </div>

    </div>
</body>
</html>
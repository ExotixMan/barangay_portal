<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f6f9">
  <tr>
    <td align="center" style="padding:40px 16px;">

      <table width="560" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;width:100%;">

        {{-- Header --}}
        <tr>
          <td align="center" bgcolor="#c62828" style="background:#c62828;padding:32px 40px;border-radius:12px 12px 0 0;">
            <p style="margin:0 0 4px;font-size:22px;font-weight:bold;color:#ffffff;letter-spacing:0.5px;">Barangay Hulong Duhat</p>
            <p style="margin:0;font-size:13px;color:rgba(255,255,255,0.8);">BISIG – Barangay Information System</p>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td bgcolor="#ffffff" style="background:#ffffff;padding:36px 40px;">
            <p style="margin:0 0 12px;font-size:16px;font-weight:bold;color:#1c2630;">Hello, {{ $name }}!</p>
            <p style="margin:0 0 28px;font-size:14px;color:#555555;line-height:1.7;">
              We received a request to reset the password for your <strong>Barangay Hulong Duhat</strong> account.
              Click the button below to set a new password.
            </p>

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td align="center" style="padding-bottom:28px;">
                  <a href="{{ $url }}" style="display:inline-block;background:#c62828;color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:6px;font-size:15px;font-weight:bold;">Reset My Password</a>
                </td>
              </tr>
            </table>

            <hr style="border:none;border-top:1px solid #f0f0f0;margin:0 0 24px;">

            <p style="margin:0 0 8px;font-size:12px;color:#999999;line-height:1.6;">If the button above doesn't work, copy and paste this link into your browser:</p>
            <p style="margin:0 0 20px;font-size:12px;"><a href="{{ $url }}" style="color:#c62828;word-break:break-all;">{{ $url }}</a></p>

            <table width="100%" cellpadding="12" cellspacing="0" border="0" style="background:#fff8e1;border-left:4px solid #f59e0b;border-radius:6px;">
              <tr>
                <td style="font-size:12px;color:#78350f;line-height:1.6;">
                  <strong>This link expires in 60 minutes.</strong><br>
                  If you did not request a password reset, you can safely ignore this email.
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td align="center" bgcolor="#f8f9fa" style="background:#f8f9fa;padding:20px 40px;border-radius:0 0 12px 12px;">
            <p style="margin:0;font-size:12px;color:#aaaaaa;line-height:1.7;">
              <strong style="color:#888888;">Barangay Hulong Duhat</strong><br>
              Malabon City, Metro Manila, Philippines<br>
              This is an automated message — please do not reply.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
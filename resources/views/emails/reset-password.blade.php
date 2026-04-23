<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password — {{ $siteName }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f5;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f4f5;">
  <tr>
    <td align="center" valign="top" style="padding:48px 16px;">

      <table width="520" cellpadding="0" cellspacing="0" border="0" style="max-width:520px;width:100%;">

        {{-- Site Name Header --}}
        <tr>
          <td align="center" style="padding-bottom:24px;">
            <div style="font-size:12px;font-weight:700;color:#92400e;letter-spacing:3px;text-transform:uppercase;">
              {{ strtoupper($siteName) }}
            </div>
          </td>
        </tr>

        {{-- Card --}}
        <tr>
          <td style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);">

            {{-- Gold top bar --}}
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="height:4px;background:linear-gradient(90deg,#b8860b,#fbbf24 50%,#b8860b);"></td>
              </tr>
            </table>

            {{-- Body --}}
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="padding:40px 40px 36px;">

                  {{-- Title --}}
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="padding-bottom:24px;border-bottom:1px solid #fef3c7;">
                        <h1 style="margin:0;font-size:22px;font-weight:800;color:#1c1917;letter-spacing:.3px;">
                          Reset Password
                        </h1>
                        <p style="margin:6px 0 0;font-size:13px;color:#a8a29e;">Permintaan reset password akun kamu</p>
                      </td>
                    </tr>
                  </table>

                  {{-- Greeting --}}
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="padding-top:24px;padding-bottom:12px;">
                        <p style="margin:0;font-size:15px;color:#44403c;line-height:1.7;">
                          Hai, <strong style="color:#1c1917;">{{ $username }}</strong>!
                        </p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-bottom:32px;">
                        <p style="margin:0;font-size:14px;color:#78716c;line-height:1.7;">
                          Kami menerima permintaan untuk mereset password akun
                          <strong style="color:#44403c;">{{ $siteName }}</strong> kamu.
                          Klik tombol di bawah untuk membuat password baru.
                        </p>
                      </td>
                    </tr>
                  </table>

                  {{-- Button --}}
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td align="center" style="padding-bottom:32px;">
                        <a href="{{ $resetUrl }}"
                           style="display:inline-block;padding:14px 40px;background:linear-gradient(135deg,#b8860b,#d4a017 50%,#b8860b);color:#ffffff;font-size:15px;font-weight:700;text-decoration:none;border-radius:8px;letter-spacing:.3px;">
                          Reset Password Sekarang
                        </a>
                      </td>
                    </tr>
                  </table>

                  {{-- URL fallback --}}
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="padding:14px 16px;background:#fafaf9;border:1px solid #e7e5e4;border-radius:6px;">
                        <p style="margin:0 0 4px;font-size:11px;color:#a8a29e;text-transform:uppercase;letter-spacing:.5px;">Atau copy link ini ke browser</p>
                        <p style="margin:0;font-size:11px;color:#78716c;word-break:break-all;font-family:monospace;">{{ $resetUrl }}</p>
                      </td>
                    </tr>
                  </table>

                  {{-- Warning --}}
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="padding-top:20px;">
                        <p style="margin:0;font-size:13px;color:#a8a29e;line-height:1.6;">
                          Link ini akan kedaluwarsa dalam <strong style="color:#78716c;">{{ $expireMinutes }} menit</strong>.
                          Jika kamu tidak meminta reset password, abaikan email ini.
                        </p>
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>

            {{-- Gold bottom bar --}}
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="height:3px;background:linear-gradient(90deg,#b8860b,#fbbf24 50%,#b8860b);"></td>
              </tr>
            </table>

          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td align="center" style="padding:20px 16px 8px;">
            <p style="margin:0 0 4px;font-size:12px;color:#a8a29e;">
              Dikirim oleh <strong>{{ $siteName }}</strong>
            </p>
            <p style="margin:0;font-size:12px;">
              <a href="{{ $appUrl }}" style="color:#b8860b;text-decoration:none;">{{ $appUrl }}</a>
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ __t('password_reset.email_subject') }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; font-family: {{ is_rtl() ? "'Segoe UI', 'Tahoma', 'Arial', sans-serif" : "'Segoe UI', 'Arial', sans-serif" }}; background-color: #f1f5f9; direction: {{ is_rtl() ? 'rtl' : 'ltr' }}; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; width: 100%;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" style="background-color: #f1f5f9; direction: {{ is_rtl() ? 'rtl' : 'ltr' }};" role="presentation">
        <tr>
            <td align="center" style="padding: 32px 16px;">

                <!-- Logo above card -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 520px;" align="center" role="presentation">
                    <tr>
                        <td align="center" style="padding-bottom: 24px;">
                            <h1 style="margin: 0; font-size: 26px; font-weight: 700; color: #0f172a; letter-spacing: -0.5px;">{{ config('app.name', 'IT Center') }}</h1>
                        </td>
                    </tr>
                </table>

                <!-- Main Card -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" style="max-width: 520px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; direction: {{ is_rtl() ? 'rtl' : 'ltr' }};" align="center" role="presentation">

                    <!-- Top accent bar -->
                    <tr>
                        <td style="height: 4px; background: linear-gradient(90deg, #1e293b 0%, #2563eb 50%, #3b82f6 100%); font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 36px 32px 36px; direction: {{ is_rtl() ? 'rtl' : 'ltr' }}; text-align: {{ is_rtl() ? 'right' : 'left' }};" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">

                            <!-- Lock Icon -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                                            <tr>
                                                <td style="width: 56px; height: 56px; background-color: #f0f4ff; border-radius: 50%; text-align: center; line-height: 56px; font-size: 24px;">
                                                    &#128272;
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Title -->
                            <h2 style="margin: 0 0 8px 0; font-size: 22px; color: #0f172a; font-weight: 700; text-align: center; line-height: 1.4;">
                                {{ __t('password_reset.email_subject') }}
                            </h2>

                            <!-- Intro Text -->
                            <p style="margin: 0 0 32px 0; color: #64748b; font-size: 15px; line-height: 1.8; text-align: center;">
                                {{ __t('password_reset.email_intro') }}
                            </p>

                            <!-- OTP Code -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 32px 0;" role="presentation">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;" role="presentation">
                                            <tr>
                                                <td align="center" style="padding: 24px 16px;">
                                                    @if(isset($code))
                                                    <p style="margin: 0 0 12px 0; font-size: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">{{ __t('password_reset.your_code') }}</p>
                                                    @php($digits = str_split((string) $code))
                                                    <table cellpadding="0" cellspacing="0" border="0" align="center" style="direction: ltr;" role="presentation">
                                                        <tr>
                                                            @foreach($digits as $d)
                                                            <td style="padding: 0 4px;">
                                                                <div style="width: 44px; height: 52px; background-color: #ffffff; border: 2px solid #1e293b; border-radius: 8px; line-height: 52px; text-align: center; font-size: 28px; font-weight: 700; color: #0f172a; font-family: 'SF Mono', 'Consolas', 'Courier New', monospace;">{{ $d }}</div>
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                    </table>
                                                    <p style="margin: 12px 0 0 0; font-size: 12px; color: #94a3b8;">{{ __t('password_reset.code_instruction') }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Expiry Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                                            <tr>
                                                <td style="background-color: #fef9ee; border: 1px solid #fde68a; border-radius: 6px; padding: 10px 20px; text-align: center;">
                                                    <p style="margin: 0; color: #92400e; font-size: 13px; font-weight: 600;">
                                                        {{ __t('password_reset.expiry_notice') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Divider -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 24px 0;" role="presentation">
                                <tr>
                                    <td style="height: 1px; background-color: #f1f5f9;"></td>
                                </tr>
                            </table>

                            <!-- Security Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" style="margin: 0 0 20px 0; direction: {{ is_rtl() ? 'rtl' : 'ltr' }};" role="presentation">
                                <tr>
                                    <td style="padding: 14px 16px; background-color: #f8fafc; border-radius: 6px; text-align: {{ is_rtl() ? 'right' : 'left' }};">
                                        <p style="margin: 0 0 6px 0; color: #334155; font-size: 13px; font-weight: 600;">
                                            {{ __t('password_reset.security_notice') }}
                                        </p>
                                        <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.7;">
                                            {{ __t('password_reset.no_request_notice') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Footer Text -->
                            <p style="margin: 0; color: #94a3b8; font-size: 13px; line-height: 1.7; text-align: center;">
                                {{ __t('password_reset.email_footer') }}
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Footer outside card -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 520px;" align="center" role="presentation">
                    <tr>
                        <td align="center" style="padding: 28px 16px 0 16px;">
                            <p style="margin: 0 0 6px 0; color: #94a3b8; font-size: 12px; line-height: 1.6;">
                                {{ __t('password_reset.need_help') }}
                            </p>
                            <p style="margin: 0; color: #cbd5e1; font-size: 11px;">
                                &copy; {{ date('Y') }} {{ config('app.name', 'IT Center') }}. {{ __t('password_reset.all_rights_reserved') }}
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>

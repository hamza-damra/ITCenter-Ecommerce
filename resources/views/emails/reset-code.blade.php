<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ __t('password_reset.email_subject') }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: {{ is_rtl() ? "'Segoe UI', 'Tahoma', 'Arial', sans-serif" : "'Segoe UI', 'Arial', sans-serif" }}; background-color: #f4f6f9; direction: {{ is_rtl() ? 'rtl' : 'ltr' }}; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 5% 3%;" role="presentation">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);" align="center" role="presentation">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; font-size: 32px; font-weight: bold; color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">{{ config('app.name', 'IT Center') }}</h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 16px;">{{ __t('password_reset.email_subject') }}</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 6% 5%;">
                            <!-- Greeting -->
                            <h2 style="margin: 0 0 20px 0; font-size: 24px; color: #333; font-weight: 600;">{{ __t('password_reset.email_greeting') }}</h2>
                            
                            <!-- Intro Text -->
                            <p style="margin: 0 0 30px 0; color: #666; font-size: 16px; line-height: 1.6;">{{ __t('password_reset.email_intro') }}</p>
                            
                            <!-- Code Container -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 5% 0;" role="presentation">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%); border: 3px dashed #667eea; border-radius: 12px; padding: 5%; text-align: center;">
                                        <p style="margin: 0 0 15px 0; font-size: 14px; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">{{ __t('password_reset.your_code') }}</p>
                                        @php($digits = str_split((string) $code))
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 10px 0;" role="presentation">
                                            <tr>
                                                @foreach($digits as $d)
                                                    <td align="center" style="width:25%; font-size: 40px; font-weight: bold; color: #667eea; font-family: 'Courier New', monospace; padding: 6px 0; text-shadow: 0 2px 4px rgba(102,126,234,0.2);">{{ $d }}</td>
                                                @endforeach
                                            </tr>
                                        </table>
                                        <p style="margin: 15px 0 0 0; font-size: 13px; color: #999;">{{ __t('password_reset.code_instruction') }}</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Warning Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 4% 0;" role="presentation">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%); border-{{ is_rtl() ? 'right' : 'left' }}: 5px solid #ffc107; padding: 16px; border-radius: 8px;">
                                        <p style="margin: 0 0 10px 0; color: #856404; font-weight: bold; font-size: 16px;">
                                            <span style="font-size: 20px; margin-{{ is_rtl() ? 'left' : 'right' }}: 8px;">⚠️</span>
                                            {{ __t('password_reset.security_notice') }}
                                        </p>
                                        <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">{{ __t('password_reset.expiry_notice') }}</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 3% 0;" role="presentation">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #e8f4f8 0%, #d1ecf1 100%); border-{{ is_rtl() ? 'right' : 'left' }}: 4px solid #17a2b8; padding: 14px; border-radius: 8px; color: #0c5460; font-size: 14px; line-height: 1.6;">
                                        {{ __t('password_reset.no_request_notice') }}
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Divider -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 5% 0;" role="presentation">
                                <tr>
                                    <td style="height: 1px; background: #e0e0e0;"></td>
                                </tr>
                            </table>
                            
                            <!-- Footer Text -->
                            <p style="margin: 0; color: #6c757d; font-size: 14px; text-align: center;">{{ __t('password_reset.email_footer') }}</p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0 0 15px 0; color: #6c757d; font-size: 14px; line-height: 1.6;">{{ __t('password_reset.need_help') }}</p>
                            <p style="margin: 20px 0 0 0; color: #999; font-size: 12px;">&copy; {{ date('Y') }} {{ config('app.name', 'IT Center') }}. {{ __t('password_reset.all_rights_reserved') }}</p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>IT Centre - رمز استعادة كلمة المرور</title>
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
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Arial, sans-serif; background-color: #f1f5f9; direction: rtl; text-align: right; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; width: 100%;">

    <!-- خلفية الصفحة -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" dir="rtl" style="background-color: #f1f5f9; direction: rtl;" role="presentation">
        <tr>
            <td align="center" style="padding: 32px 16px;">

                <!-- الشعار فوق البطاقة -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 520px;" align="center" role="presentation">
                    <tr>
                        <td align="center" style="padding-bottom: 24px;">
                            <h1 style="margin: 0; font-size: 26px; font-weight: 700; color: #0f172a; letter-spacing: -0.5px;">IT Centre</h1>
                        </td>
                    </tr>
                </table>

                <!-- البطاقة الرئيسية -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" dir="rtl" style="max-width: 520px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; direction: rtl;" align="center" role="presentation">

                    <!-- شريط علوي ملوّن -->
                    <tr>
                        <td style="height: 4px; background: linear-gradient(90deg, #1e293b 0%, #2563eb 50%, #3b82f6 100%); font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- المحتوى -->
                    <tr>
                        <td style="padding: 40px 36px 32px 36px; direction: rtl; text-align: right;" dir="rtl">

                            <!-- أيقونة القفل -->
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

                            <!-- العنوان -->
                            <h2 style="margin: 0 0 8px 0; font-size: 22px; color: #0f172a; font-weight: 700; text-align: center; line-height: 1.4;">
                                رمز استعادة كلمة المرور
                            </h2>

                            <!-- النص التمهيدي -->
                            <p style="margin: 0 0 32px 0; color: #64748b; font-size: 15px; line-height: 1.8; text-align: center;">
                                أدخل الرمز التالي لإعادة تعيين كلمة المرور الخاصة بحسابك.
                            </p>

                            <!-- رمز التحقق -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 32px 0;" role="presentation">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px 16px;" role="presentation">
                                            <tr>
                                                <td align="center" style="padding: 24px 16px;">
                                                    <table cellpadding="0" cellspacing="0" border="0" align="center" style="direction: ltr;" role="presentation">
                                                        <tr>
                                                            @foreach(str_split($otp) as $index => $digit)
                                                            <td style="padding: 0 4px;">
                                                                <div style="width: 44px; height: 52px; background-color: #ffffff; border: 2px solid #1e293b; border-radius: 8px; line-height: 52px; text-align: center; font-size: 28px; font-weight: 700; color: #0f172a; font-family: 'SF Mono', 'Consolas', 'Courier New', monospace;">{{ $digit }}</div>
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- صلاحية الرمز -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                                            <tr>
                                                <td style="background-color: #fef9ee; border: 1px solid #fde68a; border-radius: 6px; padding: 10px 20px; text-align: center;">
                                                    <p style="margin: 0; color: #92400e; font-size: 13px; font-weight: 600;">
                                                        صالح لمدة 60 دقيقة فقط
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- خط فاصل -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 24px 0;" role="presentation">
                                <tr>
                                    <td style="height: 1px; background-color: #f1f5f9;"></td>
                                </tr>
                            </table>

                            <!-- ملاحظة أمان -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" dir="rtl" style="margin: 0 0 20px 0; direction: rtl;" role="presentation">
                                <tr>
                                    <td style="padding: 14px 16px; background-color: #f8fafc; border-radius: 6px; text-align: right;">
                                        <p style="margin: 0 0 6px 0; color: #334155; font-size: 13px; font-weight: 600; text-align: right;">
                                            تنبيه أمان
                                        </p>
                                        <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.7; text-align: right;">
                                            لا تشارك هذا الرمز مع أي شخص. فريق IT Centre لن يطلب منك هذا الرمز أبداً.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- ملاحظة التجاهل -->
                            <p style="margin: 0; color: #94a3b8; font-size: 13px; line-height: 1.7; text-align: right;">
                                إذا لم تقم بطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا البريد الإلكتروني بأمان ولن يتم إجراء أي تغيير على حسابك.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- التذييل خارج البطاقة -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 520px;" align="center" role="presentation">
                    <tr>
                        <td align="center" style="padding: 28px 16px 0 16px;">
                            <p style="margin: 0 0 6px 0; color: #94a3b8; font-size: 12px; line-height: 1.6;">
                                هذا البريد الإلكتروني تم إرساله تلقائياً، يرجى عدم الرد عليه.
                            </p>
                            <p style="margin: 0; color: #cbd5e1; font-size: 11px;">
                                &copy; {{ date('Y') }} IT Centre. جميع الحقوق محفوظة.
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>

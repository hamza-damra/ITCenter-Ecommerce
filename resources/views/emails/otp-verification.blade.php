<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>IT Centre - رمز استعادة كلمة المرور</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Arial, sans-serif; background-color: #f0f2f5; direction: rtl; text-align: right; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" dir="rtl" style="background-color: #f0f2f5; padding: 40px 20px; direction: rtl;" role="presentation">
        <tr>
            <td align="center">
                <!-- الحاوية الرئيسية -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" dir="rtl" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); direction: rtl;" align="center" role="presentation">

                    <!-- الترويسة -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #ffffff; letter-spacing: 1px;">IT Centre</h1>
                            <p style="margin: 14px 0 0 0; color: rgba(255,255,255,0.9); font-size: 16px; font-weight: 400;">رمز استعادة كلمة المرور الخاص بك</p>
                        </td>
                    </tr>

                    <!-- المحتوى -->
                    <tr>
                        <td style="padding: 36px 32px 0 32px; direction: rtl; text-align: right;" dir="rtl">
                            <!-- التحية -->
                            <p style="margin: 0 0 18px 0; font-size: 20px; color: #1a237e; font-weight: 600; text-align: right;">مرحباً،</p>

                            <!-- النص التمهيدي -->
                            <p style="margin: 0 0 28px 0; color: #555555; font-size: 15px; line-height: 1.9; text-align: right;">
                                لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك في
                                <strong style="color: #1a237e;">IT Centre</strong>.
                                استخدم الرمز التالي لإتمام العملية:
                            </p>

                            <!-- صندوق رمز التحقق -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 28px 0;" role="presentation">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #e8eaf6 0%, #f5f5ff 100%); border: 2px solid #c5cae9; border-radius: 12px; padding: 28px 20px; text-align: center;">
                                        <p style="margin: 0 0 14px 0; font-size: 14px; color: #7986cb; font-weight: 600; letter-spacing: 1px;">رمز التحقق</p>
                                        <table cellpadding="0" cellspacing="0" border="0" align="center" style="direction: ltr;" role="presentation">
                                            <tr>
                                                @foreach(str_split($otp) as $digit)
                                                <td style="padding: 0 5px;">
                                                    <div style="width: 50px; height: 58px; background-color: #ffffff; border: 2px solid #1a237e; border-radius: 10px; line-height: 58px; text-align: center; font-size: 34px; font-weight: 700; color: #1a237e; font-family: 'Courier New', monospace; box-shadow: 0 2px 8px rgba(26,35,126,0.1);">{{ $digit }}</div>
                                                </td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- تنبيه انتهاء الصلاحية -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" dir="rtl" style="margin: 0 0 22px 0; direction: rtl;" role="presentation">
                                <tr>
                                    <td style="background-color: #fff8e1; border-right: 4px solid #ffa000; padding: 16px 18px; border-radius: 6px; text-align: right;">
                                        <p style="margin: 0; color: #e65100; font-size: 14px; line-height: 1.7; font-weight: 500; text-align: right;">
                                            &#9201; هذا الرمز صالح لمدة <strong>60 دقيقة</strong> فقط.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- تنبيه أمان -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" dir="rtl" style="margin: 0 0 22px 0; direction: rtl;" role="presentation">
                                <tr>
                                    <td style="background-color: #e8f5e9; border-right: 4px solid #43a047; padding: 16px 18px; border-radius: 6px; text-align: right;">
                                        <p style="margin: 0; color: #2e7d32; font-size: 14px; line-height: 1.7; font-weight: 500; text-align: right;">
                                            &#128274; لا تشارك هذا الرمز مع أي شخص. فريق IT Centre لن يطلب منك هذا الرمز أبداً.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- ملاحظة التجاهل -->
                            <p style="margin: 0 0 8px 0; color: #888888; font-size: 13px; line-height: 1.8; text-align: right;">
                                إذا لم تقم بطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا البريد الإلكتروني بأمان ولن يتم إجراء أي تغيير على حسابك.
                            </p>
                        </td>
                    </tr>

                    <!-- الفاصل -->
                    <tr>
                        <td style="padding: 24px 32px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                                <tr><td style="height: 1px; background-color: #e0e0e0;"></td></tr>
                            </table>
                        </td>
                    </tr>

                    <!-- التذييل -->
                    <tr>
                        <td style="background-color: #fafafa; padding: 28px 32px; text-align: center; border-top: 1px solid #eeeeee;" dir="rtl">
                            <p style="margin: 0 0 10px 0; color: #1a237e; font-size: 15px; font-weight: 600;">
                                مع تحيات فريق عمل IT Centre
                            </p>
                            <p style="margin: 0 0 6px 0; color: #9e9e9e; font-size: 13px;">
                                هذا البريد الإلكتروني تم إرساله تلقائياً، يرجى عدم الرد عليه.
                            </p>
                            <p style="margin: 16px 0 0 0; color: #bdbdbd; font-size: 11px;">
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

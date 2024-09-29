<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">
  <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings xmlns:o="urn:schemas-microsoft-com:office:office">
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <style>
    td,th,div,p,a,h1,h2,h3,h4,h5,h6 {font-family: "Segoe UI", sans-serif; mso-line-height-rule: exactly;}
    .mso-break-all {word-break: break-all;}
  </style>
  <![endif]-->
  <title>Daily report</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" media="screen">
  <style>
    .hover-bg-slate-800:hover {
      background-color: #1e293b !important
    }
    @media (max-width: 600px) {
      .sm-p-6 {
        padding: 24px !important
      }
      .sm-px-4 {
        padding-left: 16px !important;
        padding-right: 16px !important
      }
      .sm-px-6 {
        padding-left: 24px !important;
        padding-right: 24px !important
      }
    }
  </style>
</head>
<body style="margin: 0; width: 100%; background-color: #f8fafc; padding: 0; -webkit-font-smoothing: antialiased; word-break: break-word">
  <div role="article" aria-roledescription="email" aria-label="Daily report" lang="en">
    <div class="sm-px-4" style="background-color: #f8fafc; font-family: Inter, ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif">
      <table style="margin: 0 auto" cellpadding="0" cellspacing="0" role="none">
        <tr>
          <td style="width: 552px; max-width: 100%">
            <div role="separator" style="line-height: 24px">&zwj;</div>
            <table style="width: 100%" cellpadding="0" cellspacing="0" role="none">
              <tr>
                <td class="sm-p-6" style="border-radius: 8px; background-color: #fffffe; padding: 24px 36px">
                  <a href="{{$url}}">
                    <img src="{{$image_url}}" width="120" alt="ChillIn" style="max-width: 100%; vertical-align: middle">
                  </a>
                  <div role="separator" style="line-height: 24px">&zwj;</div>
                  <h1 style="margin: 0 0 24px; font-size: 24px; line-height: 32px; font-weight: 600; color: #0f172a">
                    Daily report
                  </h1>
                  <p style="margin: 0 0 24px; font-size: 16px; line-height: 24px; color: #475569">
                    Your new daily report just hit the box!
                  </p>
                  <div role="separator" style="line-height: 24px">&zwj;</div>
                  <table style="width: 100%" cellpadding="0" cellspacing="0" role="none">
                    <tr style="vertical-align: text-top">
                      <td style="width: 66.666667%">
                        <table cellpadding="0" cellspacing="0" role="none">
                          <tr>
                            <td>Income:</td>
                            <td>{{ $totalIncome }}</td>
                          </tr>
                          <tr>
                            <td>Expense:</td>
                            <td>{{ $totalExpenses }}</td>
                          </tr>
                          <tr>
                            <td>Saldo:</td>
                            <td>{{ $saldo }}</td>
                          </tr>
                        </table>
                      </td>
                      <td>
                        {{ $date }}
                      </td>
                    </tr>
                  </table>
                  <div role="separator" style="line-height: 24px">&zwj;</div>
                  <div>
                    <a href="{{ $url }}" style="margin: 0 0 24px; display: inline-block; border-radius: 4px; background-color: #020617; padding: 16px 24px; font-size: 16px; line-height: 1; font-weight: 600; color: #f8fafc; text-decoration: none" class="hover-bg-slate-800">
                      <!--[if mso]>
      <i style="mso-font-width: 150%; mso-text-raise: 30px" hidden>&emsp;</i>
    <![endif]-->
                      <span style="mso-text-raise: 16px">
                                    Check report details here
                                </span>
                      <!--[if mso]>
      <i hidden style="mso-font-width: 150%">&emsp;&#8203;</i>
    <![endif]-->
                    </a>
                  </div>
                  <p style="margin: 0; font-size: 16px; line-height: 24px; color: #475569">
                    Thanks,
                    <br>
                    <span style="font-weight: 600">Vladimir</span>
                  </p>
                  <div role="separator" style="height: 1px; line-height: 1px; background-color: #cbd5e1; margin-top: 24px; margin-bottom: 24px">&zwj;</div>
                  <p class="mso-break-all" style="margin: 0; font-size: 12px; line-height: 20px; color: #475569">
                    If you're having trouble clicking the "Check your report details here" button, copy
                    and paste the following URL into your web browser:
                    <a href="{{ $url }}" style="color: #1e293b; text-decoration: underline">"{{ $url }}</a>
                  </p>
                </td>
              </tr>
            </table>
            <table style="width: 100%" cellpadding="0" cellspacing="0" role="none">
              <tr>
                <td class="sm-px-6" style="padding: 24px 36px">
                  <p style="margin: 0; font-size: 12px; color: #64748b">
                    &copy; 2024 Chill In Pattaya. All rights reserved.
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </div>
</body>
</html>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $shortDescription or '' }}</title>
    <style type="text/css">
        img {
            -ms-interpolation-mode: bicubic; max-width: 100%;
        }
        body {
            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; height: 100% !important; line-height: 1.6em; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; width: 100% !important;
        }
        .ExternalClass {
            width: 100%;
        }
        .ExternalClass {
            line-height: 100%;
        }
        body {
            background-color: #f6f6f6;
        }
        @media only screen and (max-width: 620px) {
            table[class=body] h1 {
                font-weight: 600 !important;
            }
            table[class=body] h2 {
                font-weight: 600 !important;
            }
            table[class=body] h3 {
                font-weight: 600 !important;
            }
            table[class=body] h4 {
                font-weight: 600 !important;
            }
            table[class=body] h1 {
                font-size: 22px !important;
            }
            table[class=body] h2 {
                font-size: 18px !important;
            }
            table[class=body] h3 {
                font-size: 16px !important;
            }
            table[class=body] .content {
                padding: 10px !important;
            }
            table[class=body] .wrapper {
                padding: 10px !important;
            }
            table[class=body] .container {
                padding: 0 !important; width: 100% !important;
            }
            table[class=body] .btn table {
                width: 100% !important;
            }
        }
    </style>
</head>

<body style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; height: 100% !important; line-height: 1.6em; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; width: 100% !important; background: #f6f6f6; margin: 0; padding: 0;">

<table class="body" style="box-sizing: border-box; border-collapse: separate !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -premailer-width: 100%; width: 100%; background: #f6f6f6;" width="100%">
    <tr>
        <td style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 14px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; vertical-align: top;" valign="top"></td>
        <td class="container" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 14px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; vertical-align: top; display: block; max-width: 580px; width: 580px; margin: 0 auto; padding: 10px;" valign="top">
            <div class="content" style="box-sizing: border-box; display: block; max-width: 580px; margin: 0 auto; padding: 10px;">
                <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">{{ $shortDescription or '' }}</span>
                <div class="header" style="box-sizing: border-box; margin-bottom: 30px; margin-top: 20px; width: 100%;">
                    <table style="box-sizing: border-box; border-collapse: separate !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -premailer-width: 100%;" width="100%">
                        <tr>
                            <td class="align-center" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 14px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; vertical-align: top; text-align: center;" align="center" valign="top">
                                <img src="{{ $message->embed(public_path('assets/images/logo-small.png')) }}">
                            </td>
                        </tr>
                    </table>
                </div>
                <table class="main" style="box-sizing: border-box; border-collapse: separate !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -premailer-width: 100%; border-radius: 3px; width: 100%; background: #fff; border: 1px solid #e9e9e9;" width="100%">
                    <tr>
                        <td class="wrapper" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 14px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; vertical-align: top; padding: 30px;" valign="top">
                            <table style="box-sizing: border-box; border-collapse: separate !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -premailer-width: 100%;" width="100%">
                                <tr>
                                    <td style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 14px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; vertical-align: top;" valign="top">
                                        <p style="font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">
                                            @if(isset($firstname))
                                                Bonjour {{ $firstname }},<br><br>
                                            @else
                                                Bonjour,<br><br>
                                            @endif

                                            @if(isset($content))
                                                {!! $content !!}
                                            @else
                                                @yield('content')
                                            @endif
                                        </p>
                                        <p style="font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 14px; font-weight: normal; margin: 0 0 15px;">
                                            Bien cordialement,<br>
                                            L'équipe SeekYourJob de la FGES.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="footer" style="box-sizing: border-box; clear: both; width: 100%; color: #999; font-size: 12px;">
                    <table style="box-sizing: border-box; border-collapse: separate !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -premailer-width: 100%; color: #999; font-size: 12px;" width="100%">
                        <tr style="color: #999; font-size: 12px;">
                            <td class="align-center" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 12px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; vertical-align: top; color: #999; text-align: center; padding: 20px 0;" align="center" valign="top">
                                <p style="color: #999; font-size: 12px; font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-weight: normal; margin: 0 0 15px;"><a href="https://jobforum.myfges.fr" style="box-sizing: border-box; color: #999; font-size: 12px; text-decoration: underline;">JOBFORUM.MYFGES.FR</a></p>
                            </td>
                        </tr>
                    </table>
                </div></div>
        </td>
        <td style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 14px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; vertical-align: top;" valign="top"></td>
    </tr>
</table>

</body>
</html>
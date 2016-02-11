<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,300,600,700,900,200italic,300italic,400italic,600italic,700italic' rel='stylesheet' type='text/css'>
    <style>
        * {
            font-family: 'Source Sans Pro', sans-serif !important;
        }
        h1, h2, h3 {
            font-weight: normal;
            margin: 0;
            line-height: 1;
        }
        h1 {
            margin-top: 42px;
            margin-bottom: 0;
        }
        h2 {
            margin-bottom: 42px;
        }
        .header {
            text-align: center;
        }
        .recapTitle {
            text-align: center;
            font-size: 16px;
            margin: 21px 0;
        }
        .tableWrapper {
            width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .tableWrapper table {
            border-collapse: collapse;
            vertical-align: middle;
            width: 100%;
        }
        .tableWrapper table tr {
            vertical-align: middle;
        }
        .tableWrapper table td {
            border: solid 1px lightgrey;
            height: 35px;
            text-align: center;
        }
        .footer {
            position: absolute;
            text-align: center;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 11px;
            color: #5e5e5e;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    @foreach($recruiters as $recruiter)
        <div class="header">
            <h1>{{ $recruiter['company'] }}</h1>
            <h2>{{ $recruiter['firstname'] }} {{ $recruiter['lastname'] }}</h2>
        </div>
        <p class="recapTitle"><i>Récapitulatif de vos entretiens</i></p>
        <div class="tableWrapper">
            <table border="1">
                <tr>
                    <td width="33%;" style="background: #f5f5f5; text-align: center;">Horaire</td>
                    <td style="background: #f5f5f5; text-align: center;">Candidat</td>
                </tr>
                @foreach($recruiter['interviews'] as $interview)
                <tr>
                    <td>{{ $interview['slot']['begins_at'] }} - {{ $interview['slot']['ends_at'] }}</td>
                    <td>
                        @if($interview['status'] == 'taken')
                            {{ $interview['candidate']['firstname'] }} {{ $interview['candidate']['lastname'] }} ({{ $interview['candidate']['grade'] }})
                        @else
                            –
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

        <p class="footer">
            <img src="{{ public_path('assets/images/logo-small.png') }}" height="42px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <img src="{{ public_path('assets/images/logo-rizomm.png') }}" height="42px;" /><br><br><br>
            &copy; 2016 SeekYourJob - Réalisation web par Valentin Polo et Nicolas Ducom.
        </p>
        <div class="page-break"></div>
    @endforeach
</body>
</html>
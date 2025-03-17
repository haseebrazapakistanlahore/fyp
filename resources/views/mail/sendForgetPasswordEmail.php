<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <style type="text/css">
        body {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            -webkit-text-size-adjust: 100% !important;
            -ms-text-size-adjust: 100% !important;
            -webkit-font-smoothing: antialiased !important;
        }

        .tableContent img {
            border: 0 !important;
            display: block !important;
            outline: none !important;
        }

        a {
            color: #382F2E;
            text-decoration: none !important;
        }

        p,
        h1,
        h2,
        ul,
        ol,
        li,
        div {
            margin: 0;
            padding: 0;
        }

        h1,
        h2 {
            font-weight: normal;
            background: transparent !important;
            border: none !important;
        }

        @media only screen and (max-width:480px) {

            table[class="MainContainer"],
            td[class="cell"] {
                width: 100% !important;
                height: auto !important;
            }

            td[class="specbundle"] {
                width: 100% !important;
                float: left !important;
                font-size: 13px !important;
                line-height: 17px !important;
                display: block !important;
                padding-bottom: 15px !important;
            }

            td[class="specbundle2"] {
                width: 80% !important;
                float: left !important;
                font-size: 13px !important;
                line-height: 17px !important;
                display: block !important;
                padding-bottom: 10px !important;
                padding-left: 10% !important;
                padding-right: 10% !important;
            }

            td[class="spechide"] {
                display: none !important;
            }

            img[class="banner"] {
                width: 100% !important;
                height: auto !important;
            }

            td[class="left_pad"] {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

        }

        @media only screen and (max-width:540px) {

            table[class="MainContainer"],
            td[class="cell"] {
                width: 100% !important;
                height: auto !important;
            }

            td[class="specbundle"] {
                width: 100% !important;
                float: left !important;
                font-size: 13px !important;
                line-height: 17px !important;
                display: block !important;
                padding-bottom: 15px !important;
            }

            td[class="specbundle2"] {
                width: 80% !important;
                float: left !important;
                font-size: 13px !important;
                line-height: 17px !important;
                display: block !important;
                padding-bottom: 10px !important;
                padding-left: 10% !important;
                padding-right: 10% !important;
            }

            td[class="spechide"] {
                display: none !important;
            }

            img[class="banner"] {
                width: 100% !important;
                height: auto !important;
            }

            td[class="left_pad"] {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

        }

        td,
        table {
            vertical-align: top;
        }

        td.middle {
            vertical-align: middle;
        }

        a.link1 {
            font-size: 13px;
            color: #27A1E5;
            line-height: 24px;
            text-decoration: none;
        }

        a {
            text-decoration: none !important;
        }

        .bgBody {
            background: #ffffff;
        }

        img {
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
            width: auto;
            max-width: 100%;
            clear: both;
        }

        h3 {
            font-size: 18px;
            color: #ef0017;
        }

    </style>

</head>

<body paddingwidth="0" paddingheight="0" bgcolor="#fff" style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; width: 100%!important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; font-family: Tahoma, Geneva, sans-serif; font-size: 14px;"
    offset="0" toppadding="0" leftpadding="0">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td>
                    <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td bgcolor="#fff" style="padding:8px; border-bottom:#EAEAEA solid 1px;">
                            </td>
                        </tr>
                        <tr>
                            <td height="50">&nbsp; </td>
                        </tr>
                        <tr>
                            <td style="line-height:25px;">
                                <p>Dear,
                                    <?=$first_name?>
                                </p>
                                <div>A forgot password request has been submitted by you.</div>
                                <div>Please click the following link to reset your password:</div>
                                <div><a href="<?=$baseUrl?>" style="color:#00F;" target="_blank" ;>Click to reset
                                        password.</a></div>
                                <div>&nbsp;</div>
                                <div>Regards</div>
                                <div>&nbsp;</div>


                                <div>PostQuam Admin Team</div>

                            </td>
                        </tr>


                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>

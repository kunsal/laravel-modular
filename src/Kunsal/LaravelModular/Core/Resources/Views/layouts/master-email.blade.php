<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
    <style type="text/css">
        body,td,th {
            font-family: "Century Gothic", Tahoma, Geneva, sans-serif;
        }
        a:link {
            color: #d81a0e;
        }
        a:visited {
            color: #d81a0e;
        }
        a:hover {
            color: #d81a0e;
        }
        a:active {
            color: #d81a0e;
        }
    </style>
</head>

<body>
<table width="600" height="" cellspacing="10" cellpadding="10">
    <tr>
        <td height="10" bgcolor="#000912">&nbsp;</td>
    </tr>
    <tr>
        <td height="100" bgcolor="#f5f5f5">
            <a href="http://goo.gl/FP97QU" target="_blank">
                <img src="{{ url('uploads/'.$setting->logo) }}" alt="{{ $setting->app_name }}" width="187" height="102" border="0" align="left"  />
            </a>
        </td>
    </tr>
    @yield('content')
    <tr>
        <td bgcolor="#000912">
            <p align="center">
                <a href="{{ url('/') }}/faq">FAQ</a>
                &nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
                <a href="{{ url('/') }}/login">Manage your Account</a>
            </p>
        </td>
    </tr>
</table>
</body>
</html>
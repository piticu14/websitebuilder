<?php
// source: C:\xampp\htdocs\websitebuilder\app/presenters/templates/Emails/resetPasswordEmail.latte

use Latte\Runtime as LR;

class Template996bdecb51 extends Latte\Runtime\Template
{

	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Forgot Password</title>

    <style>

        body {

            background-color: #FFFFFF; padding: 0; margin: 0;

        }

    </style>

</head>

<body style="background-color: #FFFFFF; padding: 0; margin: 0;">

<table border="0" cellpadding="0" cellspacing="10" height="100%" bgcolor="#FFFFFF" width="100%" style="max-width: 650px;" id="bodyTable">

    <tr>

        <td align="center" valign="top">

            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="emailContainer" style="font-family:Arial; color: #333333;">

                <!-- Logo -->

                <tr>

                    <td align="left" valign="top" colspan="2" style="border-bottom: 1px solid #CCCCCC; padding-bottom: 10px;">

                        <img alt="FastWeb" border="0" src="http://piticu.hys.cz/websitebuilder/www/img/logo_websitebuilder.png" title="FastWeb" class="sitelogo" width="60%" style="max-width:250px;">

                    </td>

                </tr>

                <!-- Title -->

                <tr>

                    <td align="left" valign="top" colspan="2" style="border-bottom: 1px solid #CCCCCC; padding: 20px 0 10px 0;">

                        <span style="font-size: 18px; font-weight: normal;">FORGOT PASSWORD</span>

                    </td>

                </tr>

                <!-- Messages -->

                <tr>

                    <td align="left" valign="top" colspan="2" style="padding-top: 10px;">

                        <span style="font-size: 12px; line-height: 1.5; color: #333333;">

                            We have sent you this email in response to your request to reset your password on FastWeb. After you reset your password, any credit card information stored in My Account will be deleted as a security measure.

                            <br><br>

                            To reset your password for <a href="http://piticu.hys.cz/websitebuilder/www/">FastWeb</a>, please follow the link below:

                            <a href="http://piticu.hys.cz/websitebuilder/www/account/reset?email=<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($email)) /* line 71 */ ?>&token=<?php
		echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($token)) /* line 71 */ ?>">http://piticu.hys.cz/websitebuilder/www/account/reset?email=<?php
		echo LR\Filters::escapeHtmlText($email) /* line 71 */ ?>&token=<?php echo LR\Filters::escapeHtmlText($token) /* line 71 */ ?></a>

                            <br><br>

                            We recommend that you keep your password secure and not share it with anyone.If you feel your password has been compromised, you can change it by going to your FastWeb My Account Page and clicking on the "Change Email Address or Password" link.

                            <br><br>

                            If you need help, or you have any other questions, feel free to email support@fastweb.cz, or call FastWeb customer service toll-free at 123456789.

                            <br><br>

                            FastWeb Customer Service

                        </span>

                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>

</body>

</html>

<?php
		return get_defined_vars();
	}

}
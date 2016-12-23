<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
    <meta name="viewport" content="minimum-scale=1.0, initial-scale=1.0, maximum-scale=1.0, user-scalable=1" />
    <meta name="format-detection" content="telephone=no" />
    <title>登录微看头</title>
    @requireResources('common.css,account.css', 'css','{@themePath}/css')
</head>
<body>
<div class="bodyer">
    <header>
        <div class="header">
            <div class="pg-title"><span class="title-txt">登录</span></div>
            <a class="pg-back" href="javascript:history.go(-1);"><span class="back-ico">&#xe603</span><span class="back-txt">返回</span></a>
        </div>
    </header>
    <main>
        <div class="mainer">
            <div class="mobile-accounts">
                <form method="post" action="@requireUrl('/account/dologin')">
                    <div class="form-element form-element-first">
                        <label><strong>帐号</strong> <input type="text" name="form_email" placeholder="邮箱 / 手机号" value="" /> </label>
                    </div>
                    <div class="form-element form-pwd">
                        <label> <strong>请输入密码</strong> <input type="password" name="form_password" id="form_password" placeholder="密码" /> <span class="openpwd"></span> </label>
                    </div>
                    <div class="form-element submit-button">
                        <input type="hidden" name="redir" value="@requireUrl('/')" />
                        <input class="btn-submit" type="submit" value="登录" />
                    </div>
                </form>
                <div class="item item-3rd">
                    <div class="more-login-btn">
                        其他登录方式 &amp; 找回密码
                    </div>
                    <script type="text/template" id="more-login-tpl">
                        <a href="#"
                           class="item">用手机验证码登录</a>
                        <a href="@requireUrl('/account/resetpwd')"
                           class="item">找回密码</a>
                    </script>
                </div>
            </div>
        </div>
    </main>
    @includeBlade('inc.footer')
</div>
@requireResources('jquery.min.js,jquery-migrate.js,Class.js', 'js','{@themePath}/js/libs/')
@requireResources('BRender.js,AccountRender.js', 'js','{@themePath}/js/renders')
<script type="text/javascript">
    $(document).ready(function () {
        // accountRender对象
        var accountRender = new AccountRender('Login');
        accountRender.action().init();
    });
</script>
</body>
</html>
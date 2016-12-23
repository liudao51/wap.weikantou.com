<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="format-detection" content="telephone=no" />
    <title>404 错误</title>
    @requireResources('common.css', 'css','{@themePath}/css')
</head>
<body>
<div class="bodyer">
    <header>
        <div class="header">
            <div class="pg-title"><span class="title-txt">错误</span></div>
            <a class="pg-back" href="javascript:history.go(-1);"><span class="back-ico">&#xe603</span><span class="back-txt">返回</span></a>
        </div>
    </header>
    <main>
        <div class="mainer">
            <section>
                <div class="wrap-404_1">
                    <span class="txt1"><img src="@requireUrl('/'){{$data['doc']['themePath']}}/images/404_1.png" /></span>
                    <span class="txt2">word的天，页面不在了！</span>
                    <a href="/" class="txt3">返回首页吧</a>
                </div>
            </section>
        </div>
    </main>
    @includeBlade('inc.footer')
</div>
</body>
</html>
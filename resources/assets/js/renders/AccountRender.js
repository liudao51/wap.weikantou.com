/**
 * Created by liuzw on 2017/1/5.
 */

/**
 使用方法：
 $(document).ready(function () {
    // accountRender对象
    var accountRender = new AccountRender('Login');
    accountRender1.action().init();
});
 */
(function () {
    /**
     * AccountRender类：页面映射对象类
     */
    this.AccountRender = BRender.extend({
        controller: function () {
            switch (this._page) {
                case 'Login':
                {
                    this._action = this.module().Login;
                    break;
                }
                case 'Dologin':
                {
                    this._action = this.module().Dologin;
                    break;
                }
                default:
                {
                    break;
                }
            }
        },
        module: function () {
            var handle = {
                //---Login页面操作
                Login: {
                    init: function () {
                        this.bind();
                    },
                    bind: function () {
                        $('input.btn-submit').on("click", function () {
                            $(this).val('正在登录···');
                        });

                        $('span.openpwd').on("click", function () {
                            $(this).hasClass("open") ? $(this).removeClass("open").siblings("input")[0].type = "password" : $(this).addClass("open").siblings("input")[0].type = "text";
                        });
                    }
                },
                //---Dologin页面操作
                Dologin: {
                    init: function () {
                        this.bind();
                    },
                    bind: function () {
                    }
                }
            };

            return handle;
        }
    });
}());

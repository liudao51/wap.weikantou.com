/**
 * Created by liuzw on 2017/1/2.
 */

Do(function () {

    $('span.openpwd').on("click", function (e) {
        $(this).hasClass("open") ? $(this).removeClass("open").siblings("input")[0].type = "password" : $(this).addClass("open").siblings("input")[0].type = "text";
    });

    $('.more-login-btn').on("click", function (e) {
        more_login_dialog.open();
        more_login_dialog.isOpen = true;
        e.stopPropagation();
        e.preventDefault();
    });


    var more_login_dialog = dui.Dialog({
        cls: 'more-login-dialog',
        title: '',
        content: $('#more-login-tpl').html(),
        modal: true,
        nodeId: 'more-login-dialog',
        width: window.innerWidth - 18 * 2
    }, true);


    $('.dui-dialog-msk').click(function (e) {
        if (more_login_dialog.isOpen) {
            more_login_dialog.close();
            more_login_dialog.isOpen = false;
        }
    });

});


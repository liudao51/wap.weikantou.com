(function () {
    /**
     * BRender类：页面映射对象基类
     */
    this.BRender = Class.extend({
        _page: 'Index',
        _action: {},
        init: function (page) {
            this._page = page;
            this.controller();
        },
        controller: function () {
        },
        module: function () {
        },
        action: function () {
            return this._action;
        }
    });
}());
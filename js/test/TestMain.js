require.config( {
        baseUrl: "/js/",
        paths: {
                jquery: "lib/jquery/jquery_w",
                jqueryui: "lib/jquery_plugin/jqueryui/jqueryui_w",
                jquery_color: "lib/jquery_plugin/jquery_color/jquery_color_w",
                bootstrap_tooltip: "lib/jquery_plugin/bootstrap/bootstrap_tooltip_w",
                underscore: "lib/underscore/underscore_w",
                backbone: "lib/backbone/backbone_w",
                backboneLocalStorage: "lib/backbone/backboneLocalStorage_w",
                md5: "lib/md5/md5_w",
                autogrow: "lib/jquery_plugin/autogrow/autogrow_w",
                maxlength: "lib/jquery_plugin/maxlength/maxlength",
                mustache: "lib/mustache/mustache_w",
                //home 页面的方案信息流
                SchemeStreamApp: "app/SchemeStream/v/App",
                //服务器对于用户操作的反馈信息(通用,显示在 header 下方)
                SysResInfoModel: "app/SchemeStream/m/SysResInfo",
                SysResInfoView: "app/SchemeStream/v/SysResInfo",
                helper: "mod/helper",
                //tests
                //Models
                //Views
                TestV_DialogBase: "test/TestV_DialogBase"
                //Collections
        },
        shim: {
                "backbone": {
                        deps: ["underscore", "jquery"],
                        exports: "Backbone"
                },
                "backboneLocalStorage": {
                        deps: ["backbone"]
                },
                "jqueryui": {
                        deps: ["jquery"]
                },
                "jquery_color": {
                        deps: ["jquery"]
                },
                "autogrow": {
                        deps: ["jquery"]
                },
                "helper": {
                        deps: ["jquery"]
                },
                "maxlength": {
                        deps: ["jquery"]
                }
        }
});

$(document).ready(function() {
    var page = new Vue({
        el: "#page-wrapper",
        data: {
            message: '',
            name: '',
            contact: '',
        },
        methods: {
            publish: function(event) {
                __request("api.v1.info.update", {message: page.message, name: page.name, contact: page.contact}, function(res) {
                    console.debug(res);
                    showTip("成功", "错误描述已上传，我们会尽快处理。<br />感谢您的配合，祝您生活愉快~");
                });
            }
        }
    });

    var reload_data = function() {
        __request("api.v1.info.message", {}, function(res) {
            console.debug(res);
            if (res.data == null) {
                return;
            }
            page.message = res.data.message;
            page.name = res.data.name;
            page.contact = res.data.contact;
        });
    }

    reload_data();

    $('[data-toggle="tooltip"]').tooltip();
});


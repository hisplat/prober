$(document).ready(function() {
    var page = new Vue({
        el: "#page-content",
        data: {
            infos: null,
            model: {
                id: 0,
                token: "",
                time: "",
                messageip: "",
                uploadip: "",
                contact: "",
                message: "",
                product: "",
                device: "",
                builddate: "",
                comment: "",
                filename: "",
                fileurl: "",
            },
        },
        methods: {
            publish: function(event) {
            },
            edit: function(event) {
                var k = $(event.currentTarget).attr("vk");
                var r = this.infos[k];
                console.debug(r);
                this.model.id = r.id;
                this.model.token = r.token;
                this.model.time = r.time;
                this.model.messageip = r.messageip;
                this.model.uploadip = r.uploadip;
                this.model.message = r.message;
                this.model.contact = r.contact;
                this.model.product = r.productname;
                this.model.device = r.device;
                this.model.builddate = r.builddate;
                this.model.comment = r.comment;
                this.model.filename = r.filename;
                this.model.fileurl = r.fileurl;
                $("#theModal").modal();
            },
            remove: function(event) {
                var id = this.model.id;
                __request("api.v1.info.remove", {id: id}, function(res) {
                    console.debug(res);
                    $("#theModal").modal("hide");
                    reload_data();
                });
            },
            ensure: function(event) {
                var id = this.model.id;
                var comment = this.model.comment;
                __request("api.v1.info.updatecomment", {id: id, comment: comment}, function(res) {
                    console.debug(res);
                    $("#theModal").modal("hide");
                    reload_data();
                });
            }
        }
    });

    var reload_data = function() {
        __request("api.v1.info.list", {}, function(res) {
            console.debug(res);
            page.infos = [];
            for (var k in res.data) {
                page.infos.push(res.data[k]);
            }
        });
    }

    reload_data();

    $('[data-toggle="tooltip"]').tooltip();
});


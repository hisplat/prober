$(document).ready(function() {
    var page = new Vue({
        el: "#page-content",
        data: {
            infos: null,
        },
        methods: {
            publish: function(event) {
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


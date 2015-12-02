define(['jquery', 'bootstrapTableExport'], function ($) {
    return {
        table: function() {
            $('#table').bootstrapTable({responseHandler:responseHandler});
            function responseHandler (res) {
                return res.body;
            }
        }
    };
});
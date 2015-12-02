define(['jquery', 'bootstraptable', 'bootstrapTableExport'], function ($) {
    return {
        table: function() {
            function responseHandler (res) {
                console.log(res);
                return res.body;
            }

            var $table = $('#table');
            $table.bootstrapTable({responseHandler:responseHandler});
        }
    };
});
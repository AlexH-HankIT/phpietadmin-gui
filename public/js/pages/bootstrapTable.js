define(['jquery', 'bootstrapTableExport'], function ($) {
    return {
        table: function() {
            var $table = $('#table');
            $table.bootstrapTable({responseHandler:responseHandler});
            function responseHandler(res) {
                return res[$table.attr('data-data-field')];
            }
        }
    };
});
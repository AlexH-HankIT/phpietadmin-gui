define(['jquery', 'bootstrapTableExport'], function ($) {
    return {
        table: function() {
            var $table = $('#table');
            $table.bootstrapTable({
                responseHandler:responseHandler,
                ajaxOptions: {global: false},
                searchAlign: 'left',
                showRefresh: true,
                search: true,
                showColumns: true,
                pagination: true,
                showPaginationSwitch: true,
                pageList: [10, 25, 50, 100, 'ALL']
            });
            function responseHandler(res) {
                return res[$table.attr('data-data-field')];
            }
        }
    };
});
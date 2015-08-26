define(['jquery', 'mylibs', 'highchart', 'qtip', 'jqueryui'], function($, mylibs, chart, qtip, jqueryui) {
    var Methods;
    return Methods = {
        drag_drop: function() {
            $(function() {
                $( "#table1, #table2 tbody.content" ).sortable({
                    connectWith: ".connectedSortable",
                    appendTo: "tbody.content"
                }).disableSelection();

                //$("#table1 tbody.content").sortable();
               // $("#table2 tbody.content").sortable();
            });

            /*$( "#table1 tr" ).draggable({
                cursor: "move",
                appendTo: "#table2",
                helper: "clone"
            });

            $( "#table2" ).droppable({
                drop: function( event, ui ) {
                    $(this).appendTo('#table2');
                }
            });*/
        }
    };
});
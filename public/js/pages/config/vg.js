define(['jquery', 'mylibs', 'highchart', 'qtip', 'jqueryui'], function($, mylibs, chart, qtip, jqueryui) {
    var Methods;
    return Methods = {
        drag_drop: function() {
            $(function() {
                $( "ul.vg").sortable({
                    connectWith: "ul"
                });
                $( "#used, #all" ).sortable({
                    connectWith: ".vg",
                    stop: function(event, ui) {
                        console.log(ui.item.text());
                    }
                }).disableSelection();
            });
        }
    };
});
define(['jquery'], function($) {
    $(function() {
        /* Configuration menu */
        $(document).on('click', '#config-menu a', function(){
            if( $('span', this).hasClass('glyphicon-pencil') ) {
                input = $(this).prev();
                input.removeAttr("disabled");
                $('span', this).removeClass("glyphicon-pencil");
                $('span', this).addClass("glyphicon-ok");
            } else {
                clicked = $(this);
                option = clicked.attr("href").substring(1);
                value = clicked.prev().val();
                $.ajax({url: "/phpietadmin/config/edit?option=" + option + "&value=" + value, success: function(result){
                    if (result.indexOf("Success")  >= 0) {
                        clicked.next('.bestaetigung').removeClass("label-danger");
                        clicked.next('.bestaetigung').addClass("label-success");
                        clicked.next('.bestaetigung').text("Success");
                        clicked.next('.bestaetigung').show(500);
                        clicked.next('.bestaetigung').delay(1000).hide(0);
                        input = clicked.prev();
                        input.prop('disabled', true);
                        $('span', clicked).removeClass("glyphicon-ok");
                        $('span', clicked).addClass("glyphicon-pencil");
                    } else {
                        clicked.next('.bestaetigung').removeClass("label-success");
                        clicked.next('.bestaetigung').addClass("label-danger");
                        clicked.next('.bestaetigung').text("Failed");
                        clicked.next('.bestaetigung').show(500);
                        clicked.next('.bestaetigung').delay(1000).hide(0);
                    }
                }
                })
            }
        });
    });
});
define(['jquery'], function ($) {
    var methods;

    return methods = {
        add_event_handler_config: function () {
            /* Configuration menu */
            $(document).once('click', '#config-menu a', function () {
                var $this = $(this);
                if ($('span', this).hasClass('glyphicon-pencil')) {
                    $this.prev().removeAttr("disabled");
                    $('span', this).removeClass("glyphicon-pencil");
                    $('span', this).addClass("glyphicon-ok");
                } else {
                    var option = $this.attr("href").substring(1);
                    var value = $this.prev().val();
                    $.ajax({
                        url: "/phpietadmin/config/edit_config?option=" + option + "&value=" + value,
                        success: function (result) {
                            if (result.indexOf("Success") >= 0) {
                                $this.next('.bestaetigung').removeClass("label-danger").addClass("label-success").text("Success").show(500).delay(1000).hide(0);
                                $this.prev().prop('disabled', true);
                                $('span', $this).removeClass("glyphicon-ok").addClass("glyphicon-pencil");
                            } else {
                                $this.next('.bestaetigung').removeClass("label-success").addClass("label-danger").text("Failed").show(500).delay(1000).hide(0);
                            }
                        }
                    })
                }
            });
        }
    };
});
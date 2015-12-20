define(['jquery'], function ($) {
    return {
        add_event_handler_config: function () {
            /* Configuration menu */
            $('.workspace').once('click', '#config-menu a', function () {
                var $this = $(this);
                if ($('span', $this).hasClass('glyphicon-pencil')) {
                    $this.prev().removeAttr("disabled");
                    $('span', $this).removeClass("glyphicon-pencil").addClass("glyphicon-ok");
                } else {
                    var option = $this.attr("href").substring(1);
                    var value = $this.prev().val();

                    $.ajax({
                        url: '/phpietadmin/config/edit_config',
                        data: {
                            "option": option,
                            "value": value
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] === 0) {
                                $this.next('.bestaetigung').removeClass("label-danger").addClass("label-success").text("Success").show(500).delay(1000).hide(0);
                                $this.prev().prop('disabled', true);
                                $('span', $this).removeClass("glyphicon-ok").addClass("glyphicon-pencil");
                            } else {
                                $this.next('.bestaetigung').removeClass("label-success").addClass("label-danger").text("Failed").show(500).delay(1000).hide(0);
                            }
                        },
                        error: function () {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: 'Something went wrong while submitting!'
                            });
                        }
                    });
                }
            });
        }
    };
});
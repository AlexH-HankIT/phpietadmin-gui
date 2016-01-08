define(['jquery', 'mylibs'], function ($, mylibs) {
    return {
        addEventHandlerConfigMenu: function () {
            $('.workspace').once('click', '#config-menu button', function () {
                var $this = $(this),
                    $icon = $('span.glyphicon', $this),
                    $text = $('span', $this).last();

                if ($icon.hasClass('glyphicon-pencil')) {
                    $this.prev().removeAttr("disabled");
                    $icon.removeClass("glyphicon-pencil").addClass("glyphicon-ok");
                    $text.html('Save');
                } else {
                    var option = $this.data('target'),
                        value = $this.prev().val();

                    $.ajax({
                        url: require.toUrl('../config/edit_config'),
                        beforeSend: mylibs.checkAjaxRunning(),
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
                                $icon.removeClass("glyphicon-ok").addClass("glyphicon-pencil");
                                $text.html('Edit');
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
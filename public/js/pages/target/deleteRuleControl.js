define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    return {
        addEventHandler: function() {
            var _this = this;

            $('input[name="ruleType"]').once('change', function () {
                _this.loadData();
            });

            $('.deleteRuleButton').once('click', function () {
                var $button = $(this);

                $('.object_delete_checkbox:checked').each(function () {
                    $button.button('loading');
                    $.ajax({
                        url: require.toUrl('../targets/configure/' + $('#targetSelect').find('option:selected').val() + '/deleterule'),
                        beforeSend: mylibs.checkAjaxRunning(),
                        data: {
                            'value': $(this).closest('tr').find('.objectValue').text(),
                            'ruleType': $("input[name='ruleType']:checked").val()
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] == 0) {
                                $button.button('reset');
                                _this.loadData();
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: data['message']
                                }, function() {
                                    $button.button('reset');
                                });
                            }
                        },
                        error: function () {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: 'Something went wrong while submitting!'
                            }, function() {
                                $button.button('reset');
                            });
                        }
                    });
                });
            });
        },
        loadData: function() {
            var $deleteRuleData = $('#deleteRuleData');
            $deleteRuleData.fadeOut('fast', function () {
                $deleteRuleData.load(require.toUrl('../targets/configure/') + $('#targetSelect').find('option:selected').val() + '/deleterule',
                    {ruleType: $("input[name='ruleType']:checked").val()},
                    function (response, status) {
                        $deleteRuleData.fadeIn('fast');
                        if (status == 'error') {
                            $(this).html(
                                "<div class='container'>" +
                                "<div class='alert alert-warning' role='alert'>" +
                                "<h3 class='text-center'>" +
                                response +
                                "</h3>" +
                                "</div>" +
                                '</div>');
                        }
                    });
            });
        }
    };
});
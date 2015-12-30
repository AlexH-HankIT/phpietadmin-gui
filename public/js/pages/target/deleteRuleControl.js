define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    return {
        addEventHandler: function() {
            var _this = this;

            $('input[name="ruleType"]').once('change', function () {
                _this.loadData();
            });

            $('.deleteRuleButton').once('click', function () {
                $('.object_delete_checkbox:checked').each(function () {
                    $.ajax({
                        url: require.toUrl('../targets/configure/' + $('#targetSelect').find('option:selected').val() + '/deleterule'),
                        data: {
                            'value': $(this).closest('tr').find('.objectValue').text(),
                            'ruleType': $("input[name='ruleType']:checked").val()
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] == 0) {
                                _this.loadData();
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: data['message']
                                });
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
                                "<h3 align='center'>" +
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
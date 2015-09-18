define(['jquery', 'mylibs', 'sweetalert', 'qtip', 'once'], function ($, mylibs, swal, qtip, once) {
    var methods;

    return methods = {
        add_event_handler_settings_table_checkbox: function () {
            $(function() {
                $(document).once('input', '.value', function () {
                    var oldvalue = $(this).closest('tr').find('.default_value_before_change').val();
                    var newvalue = $(this).val();
                    var settingstablecheckbox = $(this).closest('tr').find('.settingstablecheckbox');

                    if (oldvalue != newvalue) {
                        settingstablecheckbox.prop('checked', true)
                    } else {
                        settingstablecheckbox.prop('checked', false)
                    }
                });
            });
        },
        add_event_handler_save_value: function () {
            $(function() {
                $(document).once('click', '.savevalueinput', function (e) {
                    var thisrow = $(this).closest('tr');

                    var newvalue;
                    var type;

                    var oldvalue = thisrow.find('.default_value_before_change').val();
                    newvalue = thisrow.find('.value').val();

                    // If value is not defined
                    if (typeof newvalue === 'undefined') {
                        newvalue = thisrow.find('.optionselector option:selected').text();
                        type = 'select';
                    } else {
                        type = 'input';
                    }

                    if (oldvalue == newvalue) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'No changes made!'
                        });
                    } else if (newvalue == '') {
                        thisrow.find('.value').addClass('focusedInputerror')
                    } else {
                        var data = {
                            "option": thisrow.find('.option').text(),
                            "oldvalue": oldvalue,
                            "newvalue": newvalue,
                            "iqn": $('#targetselection').find("option:selected").val(),
                            "type": type
                        };


                        $.ajax({
                            url: '/phpietadmin/targets/configure/settings',
                            data: data,
                            dataType: 'json',
                            type: 'post',
                            success: function (data) {
                                if (data['code'] == 0) {
                                    swal({
                                        title: 'Success',
                                        type: 'success',
                                        text: data['message']
                                    }, function () {
                                        newvalue = thisrow.find('.value').val();

                                        // If value is not defined
                                        if (typeof newvalue === 'undefined') {
                                            newvalue = thisrow.find('.optionselector option:selected').text();
                                        }

                                        thisrow.find('.default_value_before_change').val(newvalue);
                                    });
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
                    }
                });
            });
        },
        remove_error: function () {
            $(function() {
                var input = $('.value');
                /* remove error if field is clicked */
                input.click(function () {
                    input.removeClass("focusedInputerror");
                });
            });
        }
    }
});
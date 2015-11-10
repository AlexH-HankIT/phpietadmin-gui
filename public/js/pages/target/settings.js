define(['jquery', 'mylibs', 'sweetalert', 'once', 'touchspin'], function ($, mylibs, swal, once, touchspin) {
    var methods;

    return methods = {
        add_event_handler_settings_table_checkbox: function () {
            var value = $('.value');

            value.TouchSpin({
                verticalbuttons: true,
                verticalupclass: 'glyphicon glyphicon-plus',
                verticaldownclass: 'glyphicon glyphicon-minus',
                max: 10000000
            });

            value.once('input', function () {
                var $this = $(this);
                var $this_row = $this.closest('tr');
                var oldvalue = $this_row.find('.default_value_before_change').val();
                var newvalue = $this.val();
                var settingstablecheckbox = $this_row.find('.settingstablecheckbox');

                if (oldvalue !== newvalue) {
                    settingstablecheckbox.prop('checked', true)
                } else {
                    settingstablecheckbox.prop('checked', false)
                }
            });
        },
        add_event_handler_save_value: function () {
            $('.savevalueinput').once('click', function () {
                var this_row = $(this).closest('tr'),
                    newvalue,
                    type,
                    oldvalue = this_row.find('.default_value_before_change').val();

                newvalue = this_row.find('.value').val();

                // If value is not defined
                if (typeof newvalue === 'undefined') {
                    newvalue = this_row.find('.optionselector option:selected').text();
                    type = 'select';
                } else {
                    type = 'input';
                }

                if (oldvalue === newvalue) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'No changes made!'
                    });
                } else if (newvalue === '') {
                    this_row.find('.value').addClass('focusedInputerror')
                } else {
                    $.ajax({
                        url: '/phpietadmin/targets/configure/settings',
                        data: {
                            'option': this_row.find('.option').text(),
                            'oldvalue': oldvalue,
                            'newvalue': newvalue,
                            'iqn': $('#target_selector').find('option:selected').val(),
                            'type': type
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] === 0) {
                                swal({
                                    title: 'Success',
                                    type: 'success',
                                    text: data['message']
                                }, function () {
                                    newvalue = this_row.find('.value').val();

                                    // If value is not defined
                                    if (typeof newvalue === 'undefined') {
                                        newvalue = this_row.find('.optionselector option:selected').text();
                                    }

                                    this_row.find('.default_value_before_change').val(newvalue);
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
        },
        remove_error: function () {
            var input = $('.value');
            /* remove error if field is clicked */
            input.click(function () {
                input.removeClass('focusedInputerror');
            });
        }
    }
});
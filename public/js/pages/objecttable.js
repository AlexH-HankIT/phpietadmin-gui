define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});
        },
        add_event_handler_addobjectrowbutton: function () {
            $('#addobjectrowbutton').once('click', function () {
                $('#template').clone().prependTo('#addobjectstbody').removeAttr('id hidden').addClass('newrow');
                $(this).hide();
            });
        },
        add_event_handler_typeselection: function () {
            // If type is "all" change input fields to "all"
            $('#workspace').once('change', '.typeselection', function () {
                var $thisrow = $(this).closest('tr');
                var type = $thisrow.find('.typeselection option:selected').val();
                var $objectname = $thisrow.find('.objectname');
                var $objectvalue = $thisrow.find('.objectvalue');

                if (type === 'all') {
                    $objectname.prop('disabled', true).val('ALL');
                    $objectvalue.prop('disabled', true).val('ALL');
                } else {
                    if ($objectname.val() === 'ALL') {
                        $objectname.prop('disabled', false).val('');
                    }
                    if ($objectvalue.val() === 'ALL') {
                        $objectvalue.prop('disabled', false).val('');
                    }
                }
            });
        },
        add_event_handler_saveobjectrow: function () {
            $('#workspace').once('click', '.saveobjectrow', function (event) {
                var $this_row = $(this).closest("tr");
                var type = $this_row.find('.typeselection option:selected').val();
                var name = $this_row.find('.objectname').val();
                var value = $this_row.find('.objectvalue').val();

                if (type === 'Select type...') {
                    $this_row.find('.typeselection').next('.bestaetigung').addClass('label-danger').text('Required').show(500).delay(2000).hide(0);
                } else if (name === '') {
                    $this_row.find('.objectname').addClass('focusedInputerror').next('.bestaetigung').addClass('label-danger').text('Required').show(500).delay(2000).hide(0);
                } else if (value === '') {
                    $this_row.find(".objectvalue").addClass('focusedInputerror').next('.bestaetigung').addClass('label-danger').text('Required').show(500).delay(2000).hide(0);
                } else {
                    if (type === 'hostv4' && !mylibs.validateipv4(value)) {
                        $this_row.find('.objectvalue').addClass('focusedInputerror').next('.bestaetigung').addClass('label-danger').text('Invalid IPv4').show(500).delay(2000).hide(0);
                    } else {
                        if (type === 'networkv4' && !mylibs.validateipv4network(value)) {
                            $this_row.find('.objectvalue').addClass('focusedInputerror').next('.bestaetigung').addClass('label-danger').text('Invalid IPv4 Network').show(500).delay(2000).hide(0);
                        } else {
                            var $this_row_object_value = $this_row.find('.objectvalue');
                            $.ajax({
                                url: '/phpietadmin/objects/add',
                                data: {
                                    "type": type,
                                    "name": name,
                                    "value": value
                                },
                                dataType: 'json',
                                type: 'post',
                                success: function (data) {
                                    if (data['code'] === 0) {
                                        return mylibs.load_workspace('/phpietadmin/objects');
                                    } else if (data['code'] === 4 || data['code'] === 6) {
                                        if (data['field'] === 'value') {
                                            $this_row_object_value.addClass("focusedInputerror").next('.bestaetigung').addClass("label-danger").text('Value already exists!').show(500).delay(2000).hide(0);
                                        } else {
                                            $this_row.find(".objectname").addClass("focusedInputerror").next('.bestaetigung').addClass("label-danger").text('Name already exists!').show(500).delay(2000).hide(0);
                                        }
                                    } else {
                                        $this_row_object_value.addClass("focusedInputerror").next('.bestaetigung').addClass("label-danger").text("Unknown error").show(500).delay(2000).hide(0);
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
                    }
                }
            });
        },
        add_event_handler_objectvalue: function () {
            $('#workspace').once('focus', '.objectvalue', function () {
                var $objectvalue = $('.objectvalue');
                $objectvalue.hasClass('focusedInputerror') && $objectvalue.removeClass('focusedInputerror');
            });
        },
        add_event_handler_objectname: function () {
            $('#workspace').once('focus', '.objectname', function () {
                var $objectname = $('.objectname');
                $objectname.hasClass('focusedInputerror') && $objectname.removeClass('focusedInputerror');
            });
        },
        add_event_handler_deleteobjectrow: function () {
            $('#workspace').once('click', '.deleteobjectrow', function (event) {
                event.preventDefault();
                var $this_row = $(this).closest('tr');

                if ($this_row.hasClass('newrow')) {
                    $this_row.remove();
                    $('#addobjectrowbutton').show();
                } else {
                    swal({
                            title: 'Are you sure?',
                            text: 'The object won\'t be deleted from the iet allow files!',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes, delete it!',
                            closeOnConfirm: true
                        },
                        function () {
                            $.ajax({
                                url: '/phpietadmin/objects/delete',
                                data: {
                                    "id": $this_row.find('.id').text()
                                },
                                dataType: 'json',
                                type: 'post',
                                success: function (data) {
                                    if (data['code'] === 0) {
                                        $this_row.remove();
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
                }
            });
        }
    };
});
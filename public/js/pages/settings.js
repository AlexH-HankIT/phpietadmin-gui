define(['jquery', 'mylibs', 'sweetalert', 'qtip', 'once'], function($, mylibs, swal, qtip, once) {
    var methods;

    return methods = {
        add_event_handler_settingstablecheckbox: function() {
            $(document).ready(function(){
                $(document).once('input', '.value', function() {
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
        add_event_handler_savevalue: function() {
            $(document).ready(function(){
                $(document).once('click', '.savevalueinput', function(e) {
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
                    } else {
                        var data = {
                            "option": thisrow.find('.option').text(),
                            "oldvalue": oldvalue,
                            "newvalue": newvalue,
                            "iqn": $('#targetselection').find("option:selected").val(),
                            "type": type
                        };

                        var request = mylibs.doajax('/phpietadmin/targets/settings', data);

                        request.done(function() {
                            if (request.readyState == 4 && request.status == 200) {
                                if (request.responseText == 'Success') {
                                    swal({
                                            title: 'Success',
                                            type: 'success'
                                        },
                                        function () {
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
                                        text: request.responseText
                                    });
                                }
                            }
                        });
                    }
                    e.preventDefault();
                });
            });
        },
        remove_error: function () {
            $(document).ready(function(){
                var input = $('.value');
                /* remove error if field is clicked */
                input.click(function () {
                    input.removeClass("focusedInputerror");
                });
            });
        },
        add_event_handler_resetvalue: function() {
            $(document).ready(function(){
                $(document).once('click', '.resetvalue', function(e) {
                    var $this = $(this);
                    swal({
                            title: "Are you sure?",
                            text: "The value will be reseted to default immediately!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, reset it!",
                            closeOnConfirm: false
                        },
                        function() {
                            var thisrow = $this.closest('tr');
                            var valuefield = thisrow.find('.value');

                            var data = {
                                "option": thisrow.find('.option').text(),
                                "value": thisrow.find('.value').val(),
                                "action": 'reset',
                                "iqn": $('#targetselection').find("option:selected").val()
                            };

                            var request = mylibs.doajax('/phpietadmin/targets/settings', data);

                            request.done(function() {
                                if (request.readyState == 4 && request.status == 200) {
                                    if (request.responseText == 'false') {
                                        swal({
                                            title: 'Success',
                                            type: 'success'
                                        },
                                            function () {
                                                valuefield.val('');
                                            });
                                    } else if (request.responseText == 'error') {
                                        swal({
                                            title: 'Error',
                                            type: 'error'
                                        });
                                    } else {
                                        swal({
                                            title: 'Success',
                                            type: 'success'
                                            },
                                            function () {
                                                valuefield.val(request.responseText);
                                            });
                                    }
                                }
                            });
                        });
                    e.preventDefault();
                });
            });
        }
    }
});
define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        add_event_handler_targetsettings: function() {
            $(document).ready(function(){
                $(document).off('change', '#targetsettings');
                $(document).on('change', '#targetsettings', function() {
                    var selector_targetsettings = $('#targetsettings');
                    var iqn = selector_targetsettings.find("option:selected").val();
                    var defaultvalue = selector_targetsettings.find('#default').val();

                    if (iqn !== defaultvalue) {
                        var data = {
                            "iqn": iqn
                        };

                        request = mylibs.doajax("/phpietadmin/targets/settings", data);

                        request.done(function () {
                            if (request.readyState == 4 && request.status == 200) {
                                //$('#settingstable').html(request.responseText);

                                console.log(request.responseText);

                                $(document).on('change', '.settingstableinput', function () {
                                    // select old and new value and delete newlines
                                    var beforechange = $(this).closest('tr').find('.valuebeforechange').text().replace(/(\r\n|\n|\r)/gm, "");
                                    var afterchange = $(this).val();

                                    // compare them
                                    if (beforechange != afterchange) {
                                        // if different check the checkbox
                                        $(this).closest('tr').find('.settingstablecheckbox').prop('checked', true);
                                    } else {
                                        // if not uncheck it
                                        $(this).closest('tr').find('.settingstablecheckbox').prop('checked', false);
                                    }
                                });

                                $(document).on('change', '.settingstableselect', function () {
                                    var beforechange = $(this).closest('tr').find('.valuebeforechange').text().replace(/(\r\n|\n|\r)/gm, "");
                                    var afterchange = $(this).find("option:selected").val();

                                    // compair them
                                    if (beforechange != afterchange) {
                                        // if different check the checkbox
                                        $(this).closest('tr').find('.settingstablecheckbox').prop('checked', true);
                                    } else {
                                        // if not uncheck it
                                        $(this).closest('tr').find('.settingstablecheckbox').prop('checked', false);
                                    }
                                });
                            }
                        });
                    } else {
                        $('#settingstable').html('');
                        //console.log(request.responseText);
                    }
                });
            });
        },
        add_event_handler_savesettingsbutton: function() {
            $(document).ready(function(){
                $(document).off('click', '#savesettingsbutton');
                $(document).on('click', '#savesettingsbutton', function() {
                    // validate if a iqn is selected
                    // validate if even one option was changed
                    // loop through changed options and ajax them to the server
                    // display success or failure message


                    var settingstablecheckbox = $(".settingstablecheckbox:checked");

                    if (!settingstablecheckbox.val()) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'You didn\'t change anything!'
                        });
                    } else {
                        settingstablecheckbox.each(function () {
                            var value = $(this).closest('tr').find('.settingstableinput').val();

                            // If input is undefined, there is a select in this line
                            if (value == undefined) {
                                value = $(this).closest('tr').find('.settingstableselect').find("option:selected").val();
                            }

                            var data = {
                                "option": $(this).closest('tr').find('.option').text(),
                                "oldvalue": $(this).closest('tr').find('.valuebeforechange').text().replace(/(\r\n|\n|\r)/gm, ""),
                                "newvalue": value
                            };

                            request = mylibs.doajax("/phpietadmin/targets/settings", data);

                            request.done(function () {
                                if (request.readyState == 4 && request.status == 200) {
                                    console.log(request.responseText);
                                }
                            });
                        });
                    }
                });
            });
        }
    };
});
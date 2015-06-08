define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        /* Used in views/permissions/deleterule.php */
        $(document).on('change', '#targetselection', function() {
            var selector_targetselection = $('#targetselection');
            var iqn = selector_targetselection.find("option:selected").val();
            var ruletype = $("input[name='deleteruletype']:checked").val();
            var defaultvalue = selector_targetselection.find('#default').val();

            if (iqn !== defaultvalue) {
                var data = {
                    "iqn": iqn,
                    "ruletype": ruletype
                };

                request = mylibs.doajax("/phpietadmin/permission/deleterule", data);

                request.done(function () {
                    if (request.readyState == 4 && request.status == 200) {
                        if (request.responseText == "false") {
                            swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: 'No rules set for this target!'
                                },
                                function () {
                                    selector_targetselection.val('default');
                                    $('#deleteruletable').html('');
                                });
                        } else {
                            $('#deleteruletable').html(request.responseText);
                        }
                    }
                });
            } else {
                $('#deleteruletable').html('');
            }
        });

        $(document).on('change', 'input[name="deleteruletype"]', function(){
            var selector_targetselection = $('#targetselection');
            var iqn = selector_targetselection.find("option:selected").val();
            var ruletype = $("input[name='deleteruletype']:checked").val();
            var defaultvalue = selector_targetselection.find('#default').val();

            if (iqn !== defaultvalue) {
                var data = {
                    "iqn": iqn,
                    "ruletype": ruletype
                };

                request = mylibs.doajax("/phpietadmin/permission/deleterule", data);

                request.done(function () {
                    if (request.readyState == 4 && request.status == 200) {
                        if (request.responseText == "false") {
                            swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: 'No rules set for this target!'
                                },
                                function () {
                                    selector_targetselection.val('default');
                                    $('#deleteruletable').html('');
                                });
                        } else {
                            $('#deleteruletable').html(request.responseText);
                        }
                    }
                });
            } else {
                $('#deleteruletable').html('');
            }
        });

        $(document).on('click', '#deleterulebutton', function(){
            var selector_targetselection = $('#targetselection');
            var iqn = selector_targetselection.find("option:selected").val();
            var ruletype = $("input[name='deleteruletype']:checked").val();
            var defaultvalue = selector_targetselection.find('#default').val();
            var objectdeletecheckbox = $(".objectdeletecheckbox:checked");


            if (iqn !== defaultvalue) {
                if (!objectdeletecheckbox.val()) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a object/orphan'
                    });
                } else {
                    objectdeletecheckbox.each(function () {
                        var data = {
                            "iqn": iqn,
                            "value": $(this).closest('tr').find('.objectvalue').text(),
                            "ruletype": ruletype
                        };

                        request = mylibs.doajax("/phpietadmin/permission/deleterule", data);

                        request.done(function () {
                            if (request.readyState == 4 && request.status == 200) {
                                if (request.responseText == "Success") {
                                    swal({
                                            title: 'Success',
                                            type: 'success'
                                        },
                                        function () {
                                            location.reload();
                                        });
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: request.responseText
                                    });
                                }
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: request.responseText
                                });
                            }
                        });
                    });
                }
            } else {
                swal({
                    title: 'Error',
                    type: 'error',
                    text: 'Please select a target!'
                });
            }
        });


       /* $(document).on('click', '#deleterulebutton', function(){
            var selector_targetselection = $('#targetselection');
            var iqn = selector_targetselection.find("option:selected").val();
            var ruletype = $("input[name='deleteruletype']:checked").val();
            var defaultvalue = selector_targetselection.find('#default').val();

            if (iqn !== defaultvalue) {
                var deleteobjectradio = $("input[name='deleteobjectradio']:checked").closest("tr").find('.objectvalue').text();

                if (deleteobjectradio == "") {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a object/orphan'
                    });
                } else {

                    var data = {
                        "iqn": iqn,
                        "value": deleteobjectradio,
                        "ruletype": ruletype
                    };

                    request = mylibs.doajax("/phpietadmin/permission/deleterule", data);

                    request.done(function () {
                        if (request.readyState == 4 && request.status == 200) {
                            if (request.responseText == "Success") {
                                swal({
                                        title: 'Success',
                                        type: 'success'
                                    },
                                    function () {
                                        location.reload();
                                    });
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: request.responseText
                                });
                            }
                        } else {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: request.responseText
                            });
                        }
                    });
                }
            } else {
                swal({
                    title: 'Error',
                    type: 'error',
                    text: 'Please select a target!'
                });
            }
        });*/
    });
});
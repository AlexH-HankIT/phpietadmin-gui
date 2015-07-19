define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        add_event_handler_deleteruletype: function() {
            $(document).ready(function(){
                $(document).once('change', 'input[name="deleteruletype"]', function(){
                    var selector_targetselection = $('#targetselection');
                    var iqn = selector_targetselection.find("option:selected").val();
                    var ruletype = $("input[name='deleteruletype']:checked").val();
                    var defaultvalue = selector_targetselection.find('#default').val();

                    if (iqn !== defaultvalue) {
                        var data = {
                            "iqn": iqn,
                            "ruletype": ruletype
                        };

                        mylibs.loadconfiguretargetbody('/phpietadmin/permission/deleterule', data);
                    } else {
                        $('#configuretargetbody').html('');
                    }
                });
            });
        },
        add_event_handler_deleterulebutton: function() {
            $(document).ready(function(){
                $(document).once('click', '#deleterulebutton', function(e){
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
                                var $this = $(this);
                                var data = {
                                    "iqn": iqn,
                                    "value": $this.closest('tr').find('.objectvalue').text(),
                                    "ruletype": ruletype
                                };

                                var request = mylibs.doajax("/phpietadmin/permission/deleterule", data);

                                request.done(function () {
                                    if (request.readyState == 4 && request.status == 200) {
                                        if (request.responseText == "Success") {
                                            $this.closest('tr').remove();

                                            swal({
                                                title: 'Success',
                                                type: 'success'
                                            });
                                        } else {
                                            swal({
                                                title: 'Error',
                                                type: 'error',
                                                text: request.responseText
                                            });
                                        }
                                        var data = {
                                            iqn: iqn,
                                            // initiators.allow is used as default
                                            ruletype: 'initiators.allow'
                                        };

                                        mylibs.loadconfiguretargetbody('/phpietadmin/permission/deleterule', data);
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
                    e.preventDefault();
                });
            });
        }
    };
});
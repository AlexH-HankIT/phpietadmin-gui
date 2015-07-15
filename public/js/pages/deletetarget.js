define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        add_event_handler_deletetargetbutton: function() {
            $(document).ready(function(){
                $(document).once('click', '#deletetargetbutton', function() {
                    var deleteaacl;
                    if($("#deleteacl").prop('checked')) {
                        deleteaacl = 'true';
                    } else {
                        deleteaacl = 'false';
                    }

                    var force;
                    if($('#force').prop('checked')) {
                        force = 'true';
                    } else {
                        force = 'false';
                    }

                    if (force == 'true' && deleteaacl == 'false') {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'Force needs the Delete acl option!'
                        });
                    } else {
                        var data = {
                            "iqn": $('#targetselection').find("option:selected").val(),
                            "action": $("input[name='lundeletion']:checked").val(),
                            "deleteaacl": deleteaacl,
                            "force": force
                        };

                        var request = mylibs.doajax("/phpietadmin/targets/deletetarget", data);

                        request.done(function() {
                            if (request.readyState == 4 && request.status == 200) {
                                swal({
                                    title: "Result",
                                    text: request.responseText
                                },function() {
                                    window.location.reload();
                                });
                            }
                        });
                    }
                });
            });
        }
    };
});
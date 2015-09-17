define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        add_event_handler_deletetargetbutton: function() {
            $(document).ready(function(){
                $(document).once('click', '#deletetargetbutton', function() {
                    var deleteacl;
                    if($("#deleteacl").prop('checked')) {
                        deleteacl = 1;
                    } else {
                        deleteacl = 0;
                    }

                    var force;
                    if($('#force').prop('checked')) {
                        force = 1;
                    } else {
                        force = 0;
                    }

                    if (force == 1 && deleteacl == 0) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'Force needs the \'Delete acl\' option!'
                        });
                    } else {
                        $.ajax({
                            url: '/phpietadmin/targets/configure/deletetarget',
                            data: {
                                "iqn": $('#targetselection').find("option:selected").val(),
                                "delete_lun": $("input[name='lundeletion']:checked").val(),
                                "delete_acl": deleteacl,
                                "force": force
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function(data) {
                                if (data['code'] == 0) {
                                    swal({
                                        title: 'Success',
                                        type: 'success',
                                        text: data['message']
                                    },function() {
                                        window.location.reload();
                                    });
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: data['message']
                                    },function() {
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function() {
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
        }
    };
});
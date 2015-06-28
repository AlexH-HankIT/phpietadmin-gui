define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        $(document).on('click', '#adduserbutton', function(){
            var selector_targetselection = $('#targetselection');
            var iqn = selector_targetselection.find("option:selected").val();
            var defaultvalue = selector_targetselection.find('#default').val();

            if (iqn == defaultvalue) {
                swal({
                    title: 'Error',
                    type: 'error',
                    text: 'Please select a iqn!'
                });
            } else if (!$(".addusercheckbox:checked").val()) {
                swal({
                    title: 'Error',
                    type: 'error',
                    text: 'Please select a user!'
                });
            } else {
                // Select radio
                var type = $("input[name='type']:checked").val();

                // loop through checkboxes
                $(".addusercheckbox:checked").each(function () {
                    var id = $(this).closest('tr').find('.userid').text();

                    var data = {
                        "iqn": iqn,
                        "type": type,
                        "id": id
                    };

                    request = mylibs.doajax("/phpietadmin/permission/adduser", data);

                    request.done(function() {
                        if (request.readyState == 4 && request.status == 200) {
                            console.log(request.responseText);

                            if (request.responseText == "Success") {
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
                            mylibs.loadconfiguretargetbody('/phpietadmin/permission/adduser', iqn);
                        }
                    });
                });

            }
        });
    });
});
define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        /* Used in views/permisssion/addrule.php */
        $(document).on('click', '#addallowrulebutton', function(){
            var selector_targetselection = $('#targetselection');

            var iqn = selector_targetselection.find("option:selected").val();
            var defaultvalue = selector_targetselection.find('#default').val();

            if (iqn == defaultvalue) {
                swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a iqn!'
                    });
            } else if (!$("input[name='objectradio']:checked").val()) {
                swal({
                    title: 'Error',
                    type: 'error',
                    text: 'Please select a object!'
                });
            } else {
                var id = $("input[name='objectradio']:checked").closest('tr').find('.objectid').text();
                var type = $("input[name='type']:checked").val();

                var data = {
                    "iqn": iqn,
                    "type": type,
                    "id": id
                };

                request = mylibs.doajax("/phpietadmin/permission/addrule", data);

                request.done(function() {
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
                                },
                                function () {
                                    location.reload();
                                });
                        }
                    }
                });
            }
        });
    });
});
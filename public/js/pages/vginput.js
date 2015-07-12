define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        add_event_handler_vgselection: function() {
            $(document).ready(function(){
                $(document).once('change', '#vgselection', function(){
                    var volumegroup = $('#vgselection').find('option:selected').text();

                    var data = {
                        "vg": volumegroup
                    };

                    var request = mylibs.doajax("/phpietadmin/lvm/add", data);

                    request.done(function() {
                        if (request.readyState == 4 && request.status == 200) {
                            // Insert logical volume selection
                            $('#lv').html(request.responseText);

                            var sizefield = $('#sizefield');
                            var rangeinput = $('#rangeinput');
                            var nameinput = $('#nameinput');

                            // Focus name input field
                            nameinput.focus();

                            // Insert max value data
                            var freesize = $('#freesize').text();
                            $('#maxvalue').text(freesize);
                            rangeinput.prop('max', freesize);

                            // oninput sizefield update slider
                            $(document).once('input', '#sizefield', function(){
                                var sizevalue = sizefield.val();

                                // Check if value is 0
                                if (sizevalue == 0) {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: 'The size can\'t be zero!'
                                    });
                                    // Set value to one
                                    sizefield.val(1);
                                }

                                // Check if value is higher than max value
                                if (sizevalue >= freesize) {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: 'The volume group ' + volumegroup + ' has only ' + freesize + ' GB left!'
                                    });
                                    sizefield.val(freesize);
                                }

                                // Update slider
                                rangeinput.val(sizevalue);
                            });

                            // onchange slider update size field
                            $(document).once('input', '#rangeinput', function(){
                                var rangevalue = rangeinput.val();
                                sizefield.val(rangevalue)
                            });

                            $(document).once('click', '#createvolumebutton', function(){
                                // Check if name is not empty
                                if (nameinput.val() == '') {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: 'Please choose a name!'
                                    });
                                } else {
                                    // ajax data to server
                                    var data = {
                                        "vg": volumegroup,
                                        "name": nameinput.val(),
                                        "size": $('#sizefield').val()
                                    };

                                    request = mylibs.doajax("/phpietadmin/lvm/add", data);

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
                                                        nameinput.val('');
                                                    });
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    });
                });
            });
        }
    };
});
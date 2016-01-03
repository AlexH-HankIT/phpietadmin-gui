define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    return {
        enableFilterTablePlugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});
        },
        addObjectModal: function() {
            var addObjectModalTypeSelect = $('#addObjectModalTypeSelect'),
                addObjectModalNameInput = $('#addObjectModalNameInput'),
                addObjectModalValueInput = $('#addObjectModalValueInput'),
                addObjectModalValueInputDiv = addObjectModalValueInput.parent('div'),
                addObjectModalNameInputDiv = addObjectModalNameInput.parent('div'),
                addObjectModalNameInputError = addObjectModalNameInput.next('span'),
                addObjectModalValueInputError = addObjectModalValueInput.next('span'),
                workspace =  $('.workspace'),
                showErrorInModal = $('#showErrorInModal'),
                addObjectModal = $('#addObjectModal');

            // Clean all previously set classes and values
            function clean() {
                addObjectModalNameInputDiv.removeClass('has-error has-success');
                addObjectModalValueInputDiv.removeClass('has-error has-success invalidIpv6 invalidIpv4 invalidIpv4Network');
                addObjectModalNameInput.val('');
                addObjectModalValueInput.val('');
                showErrorInModal.text('');
                addObjectModalNameInputError.hide().text('');
                addObjectModalValueInputError.hide().text('');
            }

            addObjectModal.once('shown.bs.modal', function () {
                addObjectModalNameInput.focus();
            });

            clean();

            // Remove errors on focus
            addObjectModalValueInput.add(addObjectModalNameInput).once('focus', function() {
                $(this).parent('div').removeClass('has-error');
                addObjectModalNameInputError.hide().text('');
                addObjectModalValueInputError.hide().text('');
            });

            addObjectModalTypeSelect.once('change', function() {
                clean();
                if ($(this).val() === 'all') {
                    addObjectModalNameInput.prop('disabled', true).val('ALL');
                    addObjectModalValueInput.prop('disabled', true).val('ALL');
                } else {
                    addObjectModalNameInput.focus();
                    addObjectModalNameInput.prop('disabled', false);
                    addObjectModalValueInput.prop('disabled', false);
                }
            });

            workspace.once('click', '#saveObjectButton', function() {
                var addObjectModalTypeSelectVal = addObjectModalTypeSelect.find('option:selected').val(),
                    addObjectModalNameInputVal = addObjectModalNameInput.val(),
                    addObjectModalValueInputVal = addObjectModalValueInput.val(),
                    $button = $(this);

                if(addObjectModalNameInputVal === '') {
                    addObjectModalNameInputDiv.addClass('has-error');
                }

                if (addObjectModalValueInput === '') {
                    addObjectModalValueInputDiv.addClass('has-error');
                }

                if (addObjectModalTypeSelectVal === 'hostv4') {
                    if(!mylibs.validateIpv4(addObjectModalValueInputVal)) {
                        addObjectModalValueInputDiv.addClass('has-error invalidIpv4');
                    }
                } else if(addObjectModalTypeSelectVal === 'hostv6') {
                    if(!mylibs.validateIpv6(addObjectModalValueInputVal)) {
                        addObjectModalValueInputDiv.addClass('has-error invalidIpv6');
                    }
                } else if(addObjectModalTypeSelectVal === 'networkv4') {
                    if(!mylibs.validateIpv4Network(addObjectModalValueInputVal)) {
                        addObjectModalValueInputDiv.addClass('has-error invalidIpv4Network');
                    }
                }

                if(addObjectModalNameInputDiv.hasClass('has-error')) {
                    addObjectModalNameInputError.show().text("Required");
                } else if (addObjectModalValueInputDiv.hasClass('has-error')) {
                    if (addObjectModalValueInputDiv.hasClass('invalidIpv4')) {
                        addObjectModalValueInputError.show().text("Invalid IPv4");
                    } else if (addObjectModalValueInputDiv.hasClass('invalidIpv6')) {
                        addObjectModalValueInputError.show().text("Invalid IPv6");
                    } else if (addObjectModalValueInputDiv.hasClass('invalidIpv4Network')) {
                        addObjectModalValueInputError.show().text("Invalid network");
                    } else {
                        addObjectModalValueInputError.show().text("Invalid input");
                    }
                } else {
                    var url = require.toUrl('../objects');
                    $button.button('loading');
                    $.ajax({
                        url: url + '/add',
                        beforeSend: mylibs.checkAjaxRunning(),
                        data: {
                            "type": addObjectModalTypeSelectVal,
                            "name": addObjectModalNameInputVal,
                            "value": addObjectModalValueInputVal
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] === 0) {
                                addObjectModalValueInputDiv.addClass('has-success');
                                addObjectModalNameInputDiv.addClass('has-success');

                                setTimeout(function() {
                                    addObjectModal.modal('hide');
                                    $button.button('reset');
                                }, 400);

                                addObjectModal.once('hidden.bs.modal', function() {
                                    clean();
                                    return mylibs.load_workspace(url);
                                });
                            } else if (data['code'] === 4 ) {
                                // name or value already in use
                                if (data['field'] === 'value') {
                                    addObjectModalValueInputError.show().text("In use!");
                                    addObjectModalValueInputDiv.addClass('has-error');
                                } else if (data['field'] === 'name') {
                                    addObjectModalNameInputError.show().text("In use!");
                                    addObjectModalNameInputDiv.addClass('has-error');
                                }
                                $button.button('reset');
                            } else if (data['code'] === 6) {
                                // cant write to database
                                $button.button('reset');
                            } else {
                                // unknown error
                                $button.button('reset');
                            }
                        },
                        error: function () {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: 'Something went wrong while submitting!'
                            }, function() {
                                $button.button('reset');
                            });
                        }
                    });
                }
            });
        },
        addEventHandlerDeleteObject: function () {
            $('.workspace').once('click', '.objectDelete', function () {
                var url = require.toUrl('../objects'),
                    $this = $(this);

                $this.button('loading');
                swal({
                        title: 'Are you sure?',
                        text: 'The object won\'t be deleted from the iet allow files!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes, delete it!',
                        closeOnConfirm: false
                    },
                    function (status) {
                        if (status === true) {
                            $.ajax({
                                url: url + '/delete',
                                beforeSend: mylibs.checkAjaxRunning(),
                                data: {
                                    "id": $this.closest('tr').attr('id')
                                },
                                dataType: 'json',
                                type: 'post',
                                success: function (data) {
                                    if (data['code'] === 0) {
                                        swal.close();
                                        return mylibs.load_workspace(url);
                                    } else {
                                        swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: data['message']
                                        }, function() {
                                            $this.button('reset');
                                        });
                                    }
                                },
                                error: function () {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: 'Something went wrong while submitting!'
                                    }, function() {
                                        $this.button('reset');
                                    });
                                }
                            });
                        } else {
                            $this.button('reset');
                        }
                    });
            });
        }
    };
});
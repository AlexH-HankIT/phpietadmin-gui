define(['mylibs', 'touchspin', 'qtip', 'jqueryUiSlider'], function (mylibs, touchspin, qtip) {
    return {
        extend: function () {
            var $slider = $('#extend_slider'),
                $extend_size_input = $('#extend_size'),
                data = $slider.data();

            $('#remapLunExplanation').qtip({
                content: {
                    text: 'Delete and attach the lun to inform ietd and the initiator of the size change. ' +
                    'The MS iscsi inititator seems to handle this well. ' +
                    'However, don\'t use it, unless you know what you are doing! ' +
                    'If the volume is not mapped on a target, this does nothing.'
                },
                style: {
                    classes: 'qtip-youtube'
                }
            });

            if (data !== undefined) {
                var max = parseInt(data.max),
                    min = parseInt(data.min),
                    value = parseInt(data.value);

                $slider.slider({
                    range: 'min',
                    max: max,
                    min: min,
                    value: value,
                    animate: 'fast',
                    slide: function (event, ui) {
                        $extend_size_input.val(ui.value);
                    },
                    change: function (event, ui) {
                        $extend_size_input.val(ui.value);
                    }
                }).slider('pips').slider('float');

                $extend_size_input.TouchSpin({
                    min: min,
                    max: max,
                    postfix: 'GB'
                });

                $extend_size_input.once('input change', function () {
                    var $this = $(this);
                    var value = $this.val();

                    if (mylibs.is_int(value) === false) {
                        value = min;
                    }

                    if (value < min) {
                        $this.val(min);
                        $slider.slider('value', min);
                    } else if (value > max) {
                        $this.val(max);
                        $slider.slider('value', max);
                    } else {
                        $slider.slider('value', value);
                    }
                });

                $('#extend_lv_button').once('click', function () {
                    var new_size = $extend_size_input.val(),
                        $button = $(this);

                    if (new_size <= min) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'No changes made!'
                        });
                    } else {
                        var $selected = $('#logical_volume_selector').find("option:selected"),
                            url = require.toUrl('../lvm/configure/extent'),
                            remap = $('#remapLun').prop('checked');

                        $button.button('loading');
                        $.ajax({
                            url: url,
                            beforeSend: mylibs.checkAjaxRunning(),
                            data: {
                                'vg': $selected.data('subtext'),
                                'lv': $selected.text(),
                                'size': new_size,
                                'remap': remap
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function (data) {
                                if (data['code'] === 0) {
                                    swal({
                                        title: 'Success',
                                        type: 'success',
                                        text: data['message']
                                    }, function () {
                                        mylibs.load_lvm_target_body(url)
                                    });
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: data['message']
                                    }, function() {
                                        $button.button('reset');
                                    });
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
            }
        }
    };
});
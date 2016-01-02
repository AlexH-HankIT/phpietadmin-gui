define(['mylibs', 'touchspin', 'jqueryUiSlider'], function (mylibs, touchspin) {
    return {
        add_snapshot: function () {
            var $slider = $("#add_snapshot_size_slider"),
                $add_snapshot_size_input = $('#add_snapshot_size_input'),
                data = $slider.data();

            // if the user clicks to fast
            // this var might be undefined and causes a type error
            if (data !== undefined) {
                var max = parseInt(data.max);

                $slider.slider({
                    range: 'min',
                    max: max,
                    min: 1,
                    value: 1,
                    animate: 'fast',
                    slide: function (event, ui) {
                        $add_snapshot_size_input.val(ui.value);
                    },
                    change: function (event, ui) {
                        $add_snapshot_size_input.val(ui.value);
                    }
                }).slider('pips').slider('float');

                $add_snapshot_size_input.TouchSpin({
                    min: 1,
                    max: max,
                    postfix: 'GB',
                    initval: 1
                });

                $add_snapshot_size_input.once('input change', function () {
                    var $this = $(this);
                    var value = $this.val();

                    if (mylibs.is_int(value) === false) {
                        value = 1;
                    }

                    if (value > max) {
                        $this.val(max);
                        $slider.slider('value', max);
                    } else {
                        $slider.slider('value', value);
                    }
                });

                $('#create_snapshot').once('click', function () {
                    var $selected = $('#logical_volume_selector').find("option:selected"),
                        url = require.toUrl('../lvm/configure/snapshot/add'),
                        $button = $(this);

                    $button.button('loading');
                    $.ajax({
                        url: url,
                        beforeSend: mylibs.checkAjaxRunning(),
                        data: {
                            'vg': $selected.data('subtext'),
                            'lv': $selected.text(),
                            'size': $add_snapshot_size_input.val()
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
                });
            }
        }
    }
});
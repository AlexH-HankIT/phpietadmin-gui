define(['mylibs', 'touchspin', 'jqueryUiSlider'], function (mylibs, touchspin) {
    return {
        shrink: function () {
            var $slider = $('#shrink_slider'),
                $shrink_input = $('#shrink_input'),
                data = $slider.data();

            if (data !== undefined) {
                var max = parseInt(data.max);

                $slider.slider({
                    range: 'max',
                    max: max,
                    min: 1,
                    value: max,
                    animate: 'fast',
                    slide: function (event, ui) {
                        $shrink_input.val(ui.value);
                    },
                    change: function (event, ui) {
                        $shrink_input.val(ui.value);
                    }
                }).slider('pips').slider('float');

                $shrink_input.TouchSpin({
                    min: 1,
                    max: max,
                    postfix: 'GB',
                    initval: max
                });

                $shrink_input.once('input change', function () {
                    var $this = $(this),
                        value = $this.val();

                    if (mylibs.is_int(value) === false) {
                        value = max;
                    }

                    if (value > max) {
                        $this.val(max);
                        $slider.slider('value', max);
                    } else {
                        $slider.slider('value', value);
                    }
                });

                $('#shrink_volume_button').once('click', function () {
                    var new_size = $shrink_input.val(),
                        $button = $(this);

                    if (new_size >= max) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'No changes made!'
                        });
                    } else {
                        swal({
                                title: "Are you sure?",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Yes, do it!",
                                closeOnConfirm: false
                            },
                            function () {
                                var $selected = $('#logical_volume_selector').find("option:selected"),
                                    url = require.toUrl('../lvm/configure/shrink');

                                $button.button('loading');
                                $.ajax({
                                    url: url,
                                    beforeSend: mylibs.checkAjaxRunning(),
                                    data: {
                                        'vg': $selected.data('subtext'),
                                        'lv': $selected.text(),
                                        'size': new_size
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
                                            }, function () {
                                                $button.button('reset');
                                            });
                                        }
                                    },
                                    error: function () {
                                        swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: 'Something went wrong while submitting!'
                                        }, function () {
                                            $button.button('reset');
                                        });
                                    }
                                });
                            });
                    }
                });
            }
        }
    };
});
define(['mylibs', 'touchspin', 'jqueryui_slider'], function (mylibs, touchspin) {
    var methods;

    return methods = {
        extend: function () {
            $(function () {
                var slider = $("#extend_slider");
                var extend_size_input = $('#extend_size');
                var data = slider.data();

                if (data !== undefined) {
                    var max = parseInt(data.max);
                    var min = parseInt(data.min);
                    var value = parseInt(data.value);

                    slider.slider({
                        range: "min",
                        max: max,
                        min: min,
                        value: value,
                        animate: "fast",
                        slide: function (event, ui) {
                            extend_size_input.val(ui.value);
                        },
                        change: function (event, ui) {
                            extend_size_input.val(ui.value);
                        }
                    }).slider("pips").slider("float");

                    extend_size_input.TouchSpin({
                        min: min,
                        max: max,
                        postfix: 'GB'
                    });

                    extend_size_input.once('input change', function () {
                        var $this = $(this);
                        var value = $this.val();

                        if (mylibs.is_int(value) === false) {
                            value = min;
                        }

                        if (value < min) {
                            $this.val(min);
                            slider.slider('value', min);
                        } else if (value > max) {
                            $this.val(max);
                            slider.slider('value', max);
                        } else {
                            slider.slider('value', value);
                        }
                    });

                    $('#extend_lv_button').once('click', function () {
                        var new_size = extend_size_input.val();

                        if (new_size <= min) {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: 'No changes made!'
                            });
                        } else {
                            var data = $('#logical_volume_selector').find("option:selected").data();
                            var url = '/phpietadmin/lvm/configure/extent';

                            $.ajax({
                                url: url,
                                data: {
                                    "vg": data.vg,
                                    "lv": data.lv,
                                    "size": new_size
                                },
                                dataType: 'json',
                                type: 'post',
                                success: function (data) {
                                    if (data['code'] == 0) {
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
                                        });
                                    }
                                },
                                error: function () {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: 'Something went wrong while submitting!'
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }
    };
});
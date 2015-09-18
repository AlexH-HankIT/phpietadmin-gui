define(['mylibs', 'touchspin', 'jqueryui_slider'], function (mylibs, touchspin) {
    var methods;

    return methods = {
        shrink: function () {
            $(function () {
                var slider = $("#shrink_slider");
                var shrink_input = $('#shrink_input');
                var data = slider.data();
                var max = parseInt(data.max);

                slider.slider({
                    range: "max",
                    max: max,
                    min: 1,
                    value: max,
                    animate: "fast",
                    slide: function (event, ui) {
                        shrink_input.val(ui.value);
                    },
                    change: function (event, ui) {
                        shrink_input.val(ui.value);
                    }
                }).slider("pips").slider("float");

                shrink_input.TouchSpin({
                    min: 1,
                    max: max,
                    postfix: 'GB',
                    initval: max
                });

                shrink_input.once('input change', function () {
                    var $this = $(this);
                    var value = $this.val();

                    if (mylibs.is_int(value) === false) {
                        value = max;
                    }

                    if (value > max) {
                        $this.val(max);
                        slider.slider('value', max);
                    } else {
                        slider.slider('value', value);
                    }
                });

                $('#shrink_volume_button').once('click', function () {
                    var new_size = shrink_input.val();

                    if (new_size >= max) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'No changes made!'
                        });
                    } else {
                        var data = $('#logical_volume_selector').find("option:selected").data();
                        var url = '/phpietadmin/lvm/configure/shrink';

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
            });
        }
    };
});
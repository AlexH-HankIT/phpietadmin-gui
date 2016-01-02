define(['mylibs', 'touchspin', 'bootstrapSelect', 'jqueryUiSlider'], function (mylibs, touchspin) {
    return {
        slider: function () {
            var $slider = $('.add_slider'),
                $size_input = $('.size_input'),
                $name_input = $('#name_input'),
                $input_name_row = $('#input_name_row'),
                $vg_selector = $('#vg_selector'),
                $size_row = $('.size_row'),
                $add_lv_panel_footer = $('#add_lv_panel_footer'),
                $too_small_row = $('#too_small_row');

            $vg_selector.selectpicker();

            $vg_selector.once('change', function () {
                // get this via ajax
                var selected = $(this).find('option:selected'),
                    free = parseInt(selected.data('free'));

                if (free === 0 || free === 1) {
                    $add_lv_panel_footer.fadeOut('fast');
                    $size_row.fadeOut('fast');
                    $input_name_row.fadeOut('fast');
                    $too_small_row.fadeIn('fast');
                } else {
                    $input_name_row.fadeIn('fast', function() {
                        $name_input.focus();
                    });
                    $too_small_row.fadeOut('fast');

                    $size_input.TouchSpin({
                        min: 1,
                        postfix: 'GB'
                    });

                    $slider.slider({
                        range: "min",
                        min: 1,
                        max: free,
                        value: 1,
                        animate: "fast",
                        slide: function (event, ui) {
                            $size_input.val(ui.value);
                        },
                        change: function (event, ui) {
                            $size_input.val(ui.value);
                        }
                    }).slider("pips").slider("float");

                    $size_input.trigger("touchspin.updatesettings", {max: free});
                    $size_row.fadeIn('fast');
                    $add_lv_panel_footer.fadeIn('fast');
                }
            });

            $size_input.once('input change', function () {
                var $this = $(this),
                    value = $this.val(),
                    selected = $vg_selector.find('option:selected'),
                    free = parseInt(selected.data('free'));

                if (mylibs.is_int(value) === false) {
                    value = 1;
                }

                if (value > free) {
                    $this.val(free);
                    $slider.slider('value', free);
                } else {
                    $slider.slider('value', value);
                }
            });

            $('#create_volume_button').once('click', function () {
                var name = $name_input.val(),
                    url = require.toUrl('../lvm/add'),
                    $button = $(this);

                if (name.length === 0 || name === undefined) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please choose a name!'
                    });
                } else {
                    $button.button('loading');
                    $.ajax({
                        url: url,
                        beforeSend: mylibs.checkAjaxRunning(),
                        data: {
                            "vg": $vg_selector.find('option:selected').text(),
                            "name": name,
                            "size": $size_input.val()
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
                                    return mylibs.load_workspace(url);
                                });
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: data['message']
                                }, function() {
                                    $button.button('reset');
                                    $name_input.val('').focus();
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
                                $name_input.val('').focus();
                            });
                        }
                    });
                }
            });
        }
    };
});
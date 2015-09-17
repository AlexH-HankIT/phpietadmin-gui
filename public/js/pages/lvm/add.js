define(['mylibs', 'touchspin', 'jqueryui_slider'], function (mylibs, touchspin) {
    var Methods;

    return Methods = {
        slider: function() {
            $(document).ready(function(){
                var slider = $('#add_slider');
                var size_input = $('#size_input');
                var vg_selector = $('#vg_selector');
                var size_row = $('#size_row');
                var add_lv_panel_footer = $('#add_lv_panel_footer');
                var to_small_row = $('#to_small_row');

                vg_selector.once('change', function() {
                    // get this via ajax
                    var selected = $(this).find('option:selected');

                    if (selected.attr('id') !== 'default') {
                        var data = selected.data();

                        var free = parseInt(data.free);

                        if (free === 0 || free === 1) {
                            add_lv_panel_footer.hide();
                            size_row.hide();
                            to_small_row.show();
                        } else {
                            to_small_row.hide();

                            size_input.TouchSpin({
                                min: 1,
                                postfix: 'GB'
                            });

                            slider.slider({
                                range: "min",
                                min: 1,
                                max: free,
                                value: 1,
                                animate: "fast",
                                slide: function( event, ui ) {
                                    $('#size_input').val(ui.value);
                                },
                                change: function( event, ui ) {
                                    $('#size_input').val(ui.value);
                                }
                            }).slider("pips").slider("float");

                            size_input.trigger("touchspin.updatesettings", {max: free});
                            size_row.show();
                            add_lv_panel_footer.show();
                        }
                    } else {
                        if (slider.is(":visible")) {
                            slider.slider("destroy");
                        }
                        if (size_row.is(":visible")) {
                            size_row.hide();
                        }
                        if (to_small_row.is(":visible")) {
                            to_small_row.hide();
                        }
                        if (add_lv_panel_footer.is(":visible")) {
                            add_lv_panel_footer.hide();
                        }
                    }
                });

                size_input.once('input change', function() {
                    var $this = $(this);
                    var value = $this.val();
                    var selected = vg_selector.find('option:selected');

                    if (selected.attr('id') !== 'default') {
                        var data = selected.data();
                        var free = parseInt(data.free);

                        if(mylibs.is_int(value) === false) {
                            value = 1;
                        }

                        if (value > free) {
                            $this.val(free);
                            slider.slider('value', free);
                        } else {
                            slider.slider('value', value);
                        }
                    }
                });

                $('#create_volume_button').once('click', function() {
                    var vg = vg_selector.find('option:selected').data('vg');
                    var name = $('#name_input').val();
                    var size = $('#size_input').val();

                    if (name === '' || name === undefined) {
                        swal({
                            title: 'Error',
                            type: 'error',
                            text: 'Please choose a name!'
                        });
                    } else {
                        $.ajax({
                            url: '/phpietadmin/lvm/add',
                            data: {
                                "vg": vg,
                                "name": name,
                                "size": size
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function(data) {
                                if (data['code'] == 0) {
                                    swal({
                                        title: 'Success',
                                        type: 'success',
                                        text: data['message']
                                    }, function() {
                                        return mylibs.load_workspace('/phpietadmin/lvm/add');
                                    });
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: data['message']
                                    });
                                }
                            },
                            error: function() {
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
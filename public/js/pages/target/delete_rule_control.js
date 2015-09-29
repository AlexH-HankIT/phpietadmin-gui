define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});
        },
        load_data: function (param) {
            mylibs.load_data('/phpietadmin/targets/configure/deleterule/' + param);
        },
        add_event_handler_delete_rule_type: function () {
            $('input[name="delete_rule_type"]').once('change', function () {
                mylibs.load_data('/phpietadmin/targets/configure/deleterule/' + $(this).val());
            });
        },
        toggle_checkboxes: function () {
            $('#data').once('click', '#object_delete_checkbox_all', function () {
                $('.object_delete_checkbox').each(function () {
                    var $this = $(this);
                    $this.prop('checked', !$this.prop("checked"));
                });
            });
        },
        add_event_handler_deleterulebutton: function () {
            $('#delete_rule_button').once('click', function () {
                // validate if checkboxes are checked
                function load($this, rule_type) {
                    return $.ajax({
                        url: '/phpietadmin/targets/configure/deleterule',
                        data: {
                            'iqn': $('#targetselection').find("option:selected").val(),
                            'value': $this.closest('tr').find('.object_value').text(),
                            'rule_type': rule_type
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] == 0) {
                                swal({
                                    title: 'Success',
                                    type: 'success',
                                    text: data['message']
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

                var rule_type = $("input[name='delete_rule_type']:checked").val();
                var def = [];

                $('.object_delete_checkbox:checked').each(function () {
                    def.push(load($(this), rule_type));
                });

                $.when.apply($, def).done(function () {
                    methods.load_data(rule_type)
                });
            });
        }
    };
});
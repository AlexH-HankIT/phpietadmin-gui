define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function () {
            $(document).ready(function () {
                // Enable filter table plugin
                $('.searchabletable').filterTable({minRows: 0});
            });
        },
        load_data: function (param) {
            $(document).ready(function () {
                mylibs.load_data('/phpietadmin/targets/configure/deleterule/' + param);
            });
        },
        add_event_handler_delete_rule_type: function() {
            $(document).once('change', 'input[name="delete_rule_type"]', function () {
                mylibs.load_data('/phpietadmin/targets/configure/deleterule/' + $(this).val());
            });
        },
        toggle_checkboxes: function () {
            $(document).ready(function () {
                $(document).once('click', '#object_delete_checkbox_all', function () {
                    $('.object_delete_checkbox').each(function () {
                        var $this = $(this);
                        $this.prop("checked", !$this.prop("checked"));
                    });
                });
            });
        },
        add_event_handler_deleterulebutton: function () {
            $(document).ready(function () {
                $(document).once('click', '#deleterulebutton', function () {
                    var iqn = $('#targetselection').find("option:selected").val();
                    var ruletype = $("input[name='delete_rule_type']:checked").val();
                    var objectdeletecheckbox = $(".object_delete_checkbox:checked");
                    var def = [];

                    // validate if checkboxes are checked

                    function load($this) {
                        return $.ajax({
                            url: '/phpietadmin/targets/configure/deleterule',
                            data: {
                                "iqn": iqn,
                                "value": $this.closest('tr').find('.object_value').text(),
                                "rule_type": ruletype
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function(data) {
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
                            error: function() {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: 'Something went wrong while submitting!'
                                });
                            }
                        });
                    }

                    objectdeletecheckbox.each(function () {
                        def.push(load($(this)));
                    });

                    $.when.apply($, def).done(function() {
                        methods.load_data(ruletype)
                    });
                });
            });
        }
    };
});
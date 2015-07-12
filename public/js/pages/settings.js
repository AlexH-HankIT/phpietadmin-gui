define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        add_event_handler_settingstablecheckbox: function() {
            $(document).ready(function(){
                $(document).once('input', '.value', function() {
                    var oldvalue = $(this).closest('tr').find('.default_value_before_change').val();
                    var newvalue = $(this).val();
                    var settingstablecheckbox = $(this).closest('tr').find('.settingstablecheckbox');

                    if (oldvalue != newvalue) {
                        settingstablecheckbox.prop('checked', true)
                    } else {
                        settingstablecheckbox.prop('checked', false)
                    }
                });
            });
        },
        add_event_handler_save_settings: function() {
            $(document).ready(function(){

            });
        }
    }
});
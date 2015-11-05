define(['jquery'], function ($) {
    var methods;
    return methods = {
        checkversion: function () {
            var $versionCheck = $('#versionCheck');
            $.ajax({
                url: '/phpietadmin/dashboard/get_version',
                dataType: 'json',
                type: 'post',
                global: false,
                success: function (data) {
                    var val;

                    if (data['version'][1].version_nr === $('#phpietadminversion').text()) {
                        val = true;
                    } else {
                        val = data['version'][1].version_nr
                    }

                    if (val === true) {
                        $versionCheck.removeClass('label-info').addClass('label-success').text('Up2date');
                    } else {
                        $versionCheck.removeClass('label-info').addClass('label-danger').text(val + ' available!');
                    }
                },
                error: function () {
                    $versionCheck.removeClass('label-info').addClass('label-warning').text('Version unknown!');
                }
            });
        }
    };
});
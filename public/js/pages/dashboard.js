define(['jquery'], function ($) {
    var methods;
    return methods = {
        checkversion: function () {
            var $versioncheck = $('#versioncheck');

            $.ajax({
                url: '/phpietadmin/dashboard/get_version',
                dataType: 'json',
                type: 'post',
                success: function (data) {
                    var val;

                    if (data['version'][1].version_nr === $('#phpietadminversion').text()) {
                        val = true;
                    } else {
                        val = data['version'][1].version_nr
                    }

                    if (val === true) {
                        $versioncheck.addClass('label-success').text('Up2date');
                    } else {
                        $versioncheck.addClass('label-danger').text(val + ' available!');
                    }
                },
                error: function () {
                    $versioncheck.addClass('label-warning').text('Version unknown!');
                }
            });

        }
    };
});
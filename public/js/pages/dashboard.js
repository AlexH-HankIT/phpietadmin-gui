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
                    var $phpietadminVersion = $('#phpietadminversion'),
                        val,
                        answerVersionString = data['version'][1].version_nr,
                        answerVersionNumber = answerVersionString.split('.').join(''),
                        installedVersionNumber = $phpietadminVersion.text().split('.').join('');

                    if (answerVersionNumber > installedVersionNumber) {
                        val = answerVersionString;
                    } else {
                        val = true;
                    }

                    if (val === true) {
                        $versionCheck.removeClass('label-info').addClass('label-success').text('Up2date');
                    } else {
                        $versionCheck.removeClass('label-info').addClass('label-war').text(val + ' available!');
                    }
                },
                error: function () {
                    $versionCheck.removeClass('label-info').addClass('label-warning').text('Version unknown!');
                }
            });
        }
    };
});
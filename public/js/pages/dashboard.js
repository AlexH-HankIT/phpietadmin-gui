define(['jquery'], function ($) {
    var methods;
    return methods = {
        checkversion: function () {
            var $versionCheck = $('#versionCheck');
            $.ajax({
                url: '/phpietadmin/config/checkUpdate',
                dataType: 'json',
                type: 'post',
                global: false,
                success: function (data) {
                    var $phpietadminVersion = $('#phpietadminversion'),
                        val,
                        answerVersionNumber = data['version'].split('.').join(''),
                        installedVersionNumber = $phpietadminVersion.text().split('.').join('');

                    if (answerVersionNumber > installedVersionNumber) {
                        val = data['version'];
                    } else {
                        val = true;
                    }

                    if (val === true) {
                        $versionCheck.removeClass('label-info').addClass('label-success').text('Up2date');
                    } else {
                        $versionCheck.removeClass('label-info').addClass('label-warning').text(val + ' available!');
                    }
                },
                error: function () {
                    $versionCheck.removeClass('label-info').addClass('label-warning').text('Version unknown!');
                }
            });
        }
    };
});
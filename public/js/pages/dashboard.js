define(['jquery'], function ($) {
    return {
        checkVersion: function () {
            var $versionCheck = $('#versionCheck');
            $.ajax({
                url: '/phpietadmin/config/checkUpdate',
                dataType: 'json',
                type: 'get',
                global: false,
                success: function (data) {
                    var $phpietadminVersion = $('#phpietadminversion'),
                        answerVersionNumber = data.version.split('.').join(''),
                        installedVersionNumber = $phpietadminVersion.attr('data-version').split('.').join('');

                    if (answerVersionNumber > installedVersionNumber) {
                        $versionCheck.removeClass('label-info').addClass('label-warning').text(data.version + ' available!');
                    } else {
                        $versionCheck.removeClass('label-info').addClass('label-success').text('Up2date');
                    }
                },
                error: function () {
                    $versionCheck.removeClass('label-info').addClass('label-warning').text('Version unknown!');
                }
            });
        }
    };
});
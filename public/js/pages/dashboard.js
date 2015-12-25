define(['jquery', 'mylibs'], function ($, mylibs) {
    return {
        checkVersion: function () {
            var $versionCheck = $('#versionCheck');
            $.ajax({
                url: '/phpietadmin/config/checkUpdate',
                dataType: 'json',
                type: 'get',
                global: false,
                success: function (data) {
                    var $phpietadminVersion = $('#phpietadminVersion'),
                        answerVersionNumber = data.version.split('.').join(''),
                        installedVersionNumber = $phpietadminVersion.attr('data-version').split('.').join('');

                    if (answerVersionNumber > installedVersionNumber) {
                        $versionCheck.removeClass('btn-info').addClass('btn-warning').text(data.version + ' available!');
                    } else {
                        $versionCheck.removeClass('btn-info').addClass('btn-success').text('Up2date');
                    }
                },
                error: function () {
                    $versionCheck.removeClass('btn-info').addClass('btn-warning').text('Version unknown!');
                }
            });

            $versionCheck.once('click', function() {
                mylibs.load_workspace($(this).data('url'), $(".workspaceTab[href*='config/release']"));
            });
        }
    };
});
define(['jquery'], function ($) {
    return {
        addEventHandler: function() {
            $('.form-control').once('change', function() {
                $.ajax({
                    url: '/phpietadmin/config/release',
                    data: {
                        'release': $(this).find('option:selected').text().toLowerCase()
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function (data) {
                        var $currentVersion = $('#currentVersion'),
                            currentVersionText = $currentVersion.text();

                        $('#versionNew').text('v' + data['version_nr']);
                        $('#downloadNew').html('<a href=' + data['downloadurl'] + '>Download</a>');
                        $('#docNew').html('<a href=' + data['doc'] + '>Doc</a>');
                        $('#releaseNew').text(data['release']);
                    },
                    error: function () {
                        console.log("error");
                    }
                });
            });
        }
    };
});
define(['jquery', 'mylibs'], function($, mylibs) {
    $(function() {
        var installedversion = $('#phpietadminversion').text();
        var request = mylibs.doajax('/phpietadmin/dashboard/get_version');

        request.done(function () {
            if (request.readyState == 4 && request.status == 200) {
                if (request.responseText == installedversion) {
                    var val = true;
                } else {
                    var val = request.responseText;
                }

                var versioncheck = $('#versioncheck');

                if (val == true) {
                    versioncheck.addClass("label-success");
                    versioncheck.text('Up2date');
                } else {
                    versioncheck.addClass("label-danger");
                    versioncheck.text(val + ' available!');
                }
            }
        });
    });
});
define(['jquery', 'mylibs'], function($, mylibs) {
    $(function() {
        var version = mylibs.checkversion();
        var versioncheck = $('#versioncheck');

        if (version) {
            versioncheck.addClass("label-success");
            versioncheck.text('Up2date');
        } else {
            versioncheck.addClass("label-danger");
            versioncheck.text(version + ' available!');
        }
    });
});
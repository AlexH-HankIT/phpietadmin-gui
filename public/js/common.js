requirejs.config({
    baseUrl: "/phpietadmin/js",
    paths: {
        jquery: 'lib/jquery-2.1.3.min',
        bootstrap: 'lib/bootstrap.min.amd',
        filtertable: 'lib/jquery.filtertable.min.amd',
        qtip: 'lib/jquery.qtip.min',
        imagesloaded: 'lib/imagesloaded.pkg.min.amd',
        sweetalert: 'lib/sweetalert.min',
        mylibs: 'lib/mylibs',
        sha256: 'lib/sha256'
    }
});

define(['jquery', 'qtip', 'filtertable', 'mylibs', 'bootstrap'], function($, qtip, filterTable, mylibs) {
    $(function() {
        $('#menulogout').qtip({
            content: {
                text: 'Logout'
            },
            style: {
                classes: 'qtip-youtube'
            }
        });

        // Enable filter table plugin
        $('.searchabletable').filterTable({minRows:0});

        // Updates footer in case ietd is stopped or started
        // it also reloads the page, if the session terminates
        setInterval(mylibs.reloadfooter, (5 * 1000));

        /* Select active menu element
        var path = window.location.pathname;
        path = path.replace(/\/$/, "");
        path = decodeURIComponent(path);

        $(".nav a").each(function () {
            var href = $(this).attr('href');
            if (path.substring(0, href.length) === href) {
                $(this).closest('li').addClass('active');
                $(this).closest('li').parents().addClass('active');
            }
        });*/
    });
});
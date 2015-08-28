
define(['jquery', 'mylibs'], function($, mylibs) {
    var Methods;
    return Methods = {
        checkversion: function() {
            $(document).ready(function(){
                var versioncheck = $('#versioncheck');

                $.ajax({
                    url: '/phpietadmin/dashboard/get_version',
                    dataType: 'json',
                    type: 'post',
                    success: function(data) {
                        var val;

                        if (data['version'][1].version_nr == $('#phpietadminversion').text()) {
                            val = true;
                        } else {
                            val = data['version']
                        }

                        if (val == true) {
                            versioncheck.addClass('label-success');
                            versioncheck.text('Up2date');
                        } else {
                            versioncheck.addClass('label-danger');
                            versioncheck.text(val + ' available!');
                        }
                    },
                    error: function() {
                        versioncheck.addClass('label-warning');
                        versioncheck.text('Version unknown!');
                    }
                });
            });
        }
    };
});
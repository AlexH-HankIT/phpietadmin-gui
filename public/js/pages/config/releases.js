define(['jquery'], function ($) {
    var methods;

    return methods = {
        addEventHandler: function() {
            $('.form-control').once('change', function() {
                $.ajax({
                    url: '/phpietadmin/config/release',
                    data: {
                        "release": $(this).find('option:selected').text().toLowerCase()
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function (data) {
                        console.log(data);
                    },
                    error: function () {
                        console.log("error");
                    }
                });
            });
        }
    };
});
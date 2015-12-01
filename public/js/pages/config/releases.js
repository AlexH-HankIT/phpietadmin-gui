define(['jquery', 'bootstrapSelect'], function ($) {
    return {
        addEventHandler: function() {
            var $releaseSelect = $('select');

            $releaseSelect.selectpicker();

            function getReleaseData(release) {
                var $newVersionPanel = $('#newVersionPanel');

                $.ajax({
                    url: '/phpietadmin/config/release',
                    data: {
                        'release': release.toLowerCase()
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function (data) {
                        var newVersionInt = data['version_nr'].split('.').join(''),
                            currentVersionInt = $('#installedVersion').text().split('.').join('');

                        if (newVersionInt > currentVersionInt) {
                            $newVersionPanel.hide().html(
                                '<div class="panel panel-warning">' +
                                '<div class="panel-heading">Available version</div>' +
                                '<ul class="list-group">' +
                                '<li class="list-group-item">v' + data['version_nr'] + '</li>' +
                                '<li class="list-group-item"><a href=' + data['downloadurl'] + '>Download</a></li>' +
                                '<li class="list-group-item"><a href=' + data['doc'] + '>Doc</a></li>' +
                                '<li class="list-group-item">' + data['release'] + '</li>' +
                                '</ul>' +
                                '</div>'
                                ).fadeIn('fast');
                        } else {
                            $newVersionPanel.hide().html(
                                '<div class="panel panel-default">' +
                                '<div class="panel-body">' +
                                'No update available!' +
                                '</div>' +
                                '</div>'
                            ).fadeIn('fast');
                        }
                    },
                    error: function () {
                        $newVersionPanel.hide().html(
                            '<div class="panel panel-default">' +
                            '<div class="panel-body">' +
                            'Can\'t load data!' +
                            '</div>' +
                            '</div>'
                        ).fadeIn('fast');
                    }
                });
            }

            $releaseSelect.once('change', function() {
                getReleaseData($(this).selectpicker('val'));

                // ajax change to database
            });

            setTimeout(function(){
                getReleaseData($releaseSelect.selectpicker('val'));
            }, 500);
        }
    };
});
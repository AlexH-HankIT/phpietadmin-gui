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
                        var newVersionInt = data['version'].split('.').join(''),
                            currentVersionInt = $('#installedVersion').text().split('.').join('');

                        if (newVersionInt > currentVersionInt) {
                            $newVersionPanel.hide().html(
                                '<div class="panel panel-warning">' +
                                '<div class="panel-heading">Available version</div>' +
                                '<ul class="list-group">' +
                                '<li class="list-group-item">v' + data['version'] + '</li>' +
                                '<li class="list-group-item">' + data['release'] + '</li>' +
                                '<li class="list-group-item"><a target="_blank" href=' + data['download'] + '>Download</a></li>' +
                                '<li class="list-group-item"><a target="_blank" href=' + data['doc'] + '>Documentation</a></li>' +
                                '</ul>' +
                                '</div>'
                                ).fadeIn('fast');
                        } else {
                            $newVersionPanel.hide().html('<div class="alert alert-danger center panel-aligned" role="alert">No update available!</div>').fadeIn('fast');
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
            });

            setTimeout(function(){
                getReleaseData($releaseSelect.selectpicker('val'));
            }, 500);
        }
    };
});
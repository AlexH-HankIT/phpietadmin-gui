define(['jquery', 'once', 'mylibs', 'bootstrapSelect'], function ($, once, mylibs) {
    return {
        addEventHandler: function() {
            var $targetSelect = $('#targetSelect'),
                _this = this;

            $targetSelect.once('change', function () {
                _this.initialLoad();
            });

            $targetSelect.selectpicker();
        },
        loadMenu: function (iqnVal, hash) {
            var $configureTargetMenu = $('#configureTargetMenu');

            $configureTargetMenu.fadeOut('fast', function(){
                $configureTargetMenu.load(require.toUrl('../targets/configure/') + iqnVal + '/menu', function (response, status) {
                    $configureTargetMenu.fadeIn('fast');
                    if (status === 'error') {
                        // Display error message
                        $(this).html(
                            "<div class='container'>" +
                            "<div class='alert alert-warning' role='alert'>" +
                            '<h3 class="text-center">Sorry, can\'t load the menu!</h3>' +
                            '</div>' +
                            '</div>'
                        );
                    } else {
                        var $configureTargetBodyMenu = $('.configureTargetBodyMenu');

                        // Set active based on url
                        $configureTargetBodyMenu.children('a').each(function () {
                            var $this = $(this);
                            if ($this.attr('href') !== undefined) {
                                if ($this.attr('href') === window.location.pathname + hash) {
                                    $this.closest('li').addClass('active').parents().addClass('active');
                                }
                            }
                        });

                        // Add/Remove active classes and set event handler for body content
                        $configureTargetBodyMenu.once('click', function () {
                            var url = $(this).children('a').attr('href'),
                                hash = url.substring(url.indexOf('#'));

                            $('#dynamicBreadcrumb').attr('href', hash).text(hash.substring(1)).prev().addClass('active').prev().removeClass('active');
                            $('#configureTargetMenu').find('ul').children('li').removeClass('active');
                            $(this).addClass('active').parents('li').addClass('active');

                            mylibs.loadConfigureTargetBody(hash, iqnVal);
                        });
                    }
                });
            });
        },
        initialLoad: function () {
            var iqn = $('#targetSelect').find("option:selected"),
                iqnVal = iqn.val(),
                hash = window.location.hash,
                link;

            if (iqn.hasClass('bs-title-option')) {
                link = require.toUrl('../targets/configure');
                $('#configureTargetMenu').fadeOut('fast', function() { $(this).html(''); });
                $('#configureTargetBody').fadeOut('fast', function() { $(this).html(''); });
            } else {
                // Always load the maplun menu
                if (hash === '') {
                    hash = '#maplun';
                }

                this.loadMenu(iqnVal, hash);
                mylibs.loadConfigureTargetBody(hash, iqnVal);
                link = require.toUrl('../targets/configure/') + iqnVal + hash;
            }

            window.history.pushState({path: link}, '', link);
        }
    };
});
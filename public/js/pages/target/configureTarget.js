define(['jquery', 'once', 'mylibs'], function ($, once, mylibs) {
    var methods;

    return methods = {
        addEventHandler: function() {
            $('#targetSelect').once('change', function () {
                methods.initialLoad($(this));
            });
        },
        loadMenu: function (iqnVal, hash) {
            $('#configureTargetMenu').fadeOut('fast', function(){
                $(this).load('/phpietadmin/targets/configure/' + iqnVal + '/menu', function (response, status) {
                    $(this).fadeIn('fast');
                    if (status === 'error') {
                        // Display error message
                        $(this).html(
                            "<div class='container'>" +
                            "<div class='alert alert-warning' role='alert'>" +
                            '<h3 align="center">Sorry, can\'t load the menu!</h3>' +
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

                        // Add/Remove active classes and se event handler for body content
                        $configureTargetBodyMenu.once('click', function () {
                            var url = $(this).children('a').attr('href'),
                                hash = url.substring(url.indexOf('#'));

                            $('div.container').find('ul').children('li').removeClass('active');
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

            if (iqn.attr('id') === 'default') {
                link = '/phpietadmin/targets/configure';
                $('#configureTargetMenu').html('');
                $('#configureTargetBody').html('');
            } else {
                // Always load the maplun menu
                if (hash === '') {
                    hash = '#maplun';
                }

                methods.loadMenu(iqnVal, hash);
                mylibs.loadConfigureTargetBody(hash, iqnVal);
                link = '/phpietadmin/targets/configure/' + iqnVal + hash;
            }

            window.history.pushState({path: link}, '', link);
        }
    };
});
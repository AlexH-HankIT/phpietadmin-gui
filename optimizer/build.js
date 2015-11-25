({
    appDir: '/usr/share/phpietadmin/public',
    baseUrl: './js',
    dir: './public_min',
    modules: [
        {
            name: 'common'
        }
    ],
    fileExclusionRegExp: /^(r|build)\.js$/,
    optimizeCss: 'standard',
    removeCombined: true,
    paths: {
        jquery: 'lib/jquery-2.1.4',
        jqueryui: 'lib/jquery-ui',
        jqueryui_slider: 'lib/jquery-ui-slider-pips',
        bootstrap: 'lib/bootstrap.amd',
        filtertable: 'lib/jquery.filtertable.amd',
        qtip: 'lib/jquery.qtip',
        imagesloaded: 'lib/imagesloaded.pkg.amd',
        sweetalert: 'lib/sweetalert',
        mylibs: 'lib/mylibs',
        blockUI: 'lib/jquery.blockUI',
        once: 'lib/once',
        bootstraptable: 'lib/bootstrap-table',
        touchspin: 'lib/jquery.bootstrap-touchspin.amd',
        domReady: 'lib/domReady',
        pingjs: 'lib/ping.amd',
        nprogress: 'lib/nprogress',
        hideShowPassword: 'lib/hideShowPassword'
    },
    shim: {
        jqueryui: {
            export: "$",
            deps: ['jquery']
        },
        jqueryui_slider: {
            export: "$",
            deps: ['jqueryui']
        }
    }
})
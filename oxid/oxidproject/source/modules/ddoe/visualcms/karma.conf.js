/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law.
 *
 * Any unauthorized use of this software will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 * @version   OXID eSales Visual CMS
 */

// Karma configuration

module.exports = function ( config )
{
    config.set( {

        // base path that will be used to resolve all patterns (eg. files, exclude)
        basePath: '',


        // frameworks to use
        // available frameworks: https://npmjs.org/browse/keyword/karma-adapter
        frameworks: [ 'jasmine' ],


        // list of files / patterns to load in the browser
        files: [
            // Backend dependencies
            'out/src/js/jquery.min.js',
            'out/src/js/jquery-ui.min.js',
            'out/src/js/bootstrap.min.js',
            'out/src/js/admin.min.js',

            // Build dependencies
            "build/vendor/lodash/js/lodash.js",
            'build/vendor/gridstack/js/gridstack.js',
            "build/vendor/selectize/js/selectize.js",
            "build/vendor/minicolors/js/jquery.minicolors.js",
            "build/vendor/clipboardjs/js/clipboard.js",

            // Backend scripts
            'build/js/plugins/*.js',
            'build/js/*.js',

            // Test files
            'tests/karma/**/*.js'
        ],


        // list of files to exclude
        exclude: [
            // Exclude frontend files
            'build/js/frontend.js',
            'build/js/backend.js',

            // Exclude base files
            'build/js/base/helper.js',
            'build/js/base/medialibrary.js'
        ],


        // preprocess matching files before serving them to the browser
        // available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
        preprocessors: {},


        // test results reporter to use
        // possible values: 'dots', 'progress'
        // available reporters: https://npmjs.org/browse/keyword/karma-reporter
        reporters: [ 'progress' ],


        // web server port
        port: 9876,


        // enable / disable colors in the output (reporters and logs)
        colors: true,


        // level of logging
        // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
        logLevel: config.LOG_INFO,


        // enable / disable watching file and executing tests whenever any file changes
        autoWatch: true,


        // start these browsers
        // available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
        browsers: [ 'Chrome', 'Firefox', 'Safari' ],


        // Continuous Integration mode
        // if true, Karma captures browsers, runs the tests and exits
        singleRun: false,

        // Concurrency level
        // how many browser should be started simultanous
        concurrency: Infinity
    } )
};

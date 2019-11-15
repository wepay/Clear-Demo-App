var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('style', [
        './assets/css/bootstrap.min.css',
        './assets/css/bootstrap-grid.min.css',
        './assets/css/font-awesome.css',
        './assets/css/style.css'
    ])
    .addEntry('app', [
        './assets/js/app.js'
    ])

    .autoProvidejQuery()

    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();

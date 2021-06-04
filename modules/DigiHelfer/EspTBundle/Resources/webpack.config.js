let merge = require('webpack-merge');
let path = require('path');
let baseConfig = require(path.join(process.env.WEBPACK_BASE_PATH, 'webpack.config.base.js'));

let webpackConfig = {
    entry: {
        'css/espt': './assets/less/espt.less',
        'css/schedule': './assets/less/schedule.less',
        'js/espt': './assets/js/espt.js',
        'js/scheduler': 'js/vue-scheduler-lite.js',
        'js/schedule': 'js/schedule.js',

        'img/interview.png': './assets/img/interview.png',
    },
};

module.exports = merge(baseConfig.get(__dirname), webpackConfig);
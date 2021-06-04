let merge = require('webpack-merge');
let path = require('path');
let baseConfig = require(path.join(process.env.WEBPACK_BASE_PATH, 'webpack.config.base.js'));

let webpackConfig = {
    entry: {
        'css/espt': './assets/less/espt.less',
        'css/schedule': './assets/less/schedule.less',
        'js/espt': './assets/js/espt.js',
        'js/scheduler': './assets/js/vue-scheduler-lite.js',
        'js/schedule': './assets/js/schedule.js',

        'img/interview.png': './assets/img/interview.png',
    },
    output: {
        path: path.resolve(__dirname, '../../../../public/assets/dh-espt'),
    },
};

module.exports = merge(baseConfig.get(__dirname), webpackConfig);
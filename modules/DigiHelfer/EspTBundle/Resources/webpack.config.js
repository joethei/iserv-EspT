let merge = require('webpack-merge');
let path = require('path');
let baseConfig = require(path.join(process.env.WEBPACK_BASE_PATH, 'webpack.config.base.js'));

let webpackConfig = {
    entry: {
        'espt/css/espt': './assets/less/espt.less',
        'espt/css/schedule': './assets/less/schedule.less',
        'espt/js/espt': './assets/js/espt.js',
        'espt/js/scheduler': './assets/js/vue-scheduler-lite.js',
        'espt/js/schedule': './assets/js/schedule.js',

        'espt/webpacimg/interview.svg': './assets/img/interview.svg'
    },
    output: {
        path: path.resolve(__dirname, 'public/assets/espt'),
    },
};

module.exports = merge(baseConfig.get(__dirname), webpackConfig);
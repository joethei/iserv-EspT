let merge = require('webpack-merge');
let path = require('path');
let baseConfig = require(path.join(process.env.WEBPACK_BASE_PATH, 'webpack.config.base.js'));

let webpackConfig = {
    entry: {
        'css/espt': './assets/less/espt.less',
        'css/schedule': './assets/less/schedule.less',
        'css/jquery-ui': './assets/css/jquery-ui.css',
        'css/jquery-ui.structure': './assets/css/jquery-ui.structure.css',
        'css/jquery-ui.theme': './assets/css/jquery-ui.theme.css',
        'css/jq.schedule': './assets/css/jq-schedule.css',
        'js/espt': './assets/js/espt.js',
        'js/scheduler': './assets/js/vue-scheduler-lite.js',
        'js/jquery-ui': './assets/js/jquery-ui.js',
        'js/jq.schedule': './assets/js/jq.schedule.js',
        'js/schedule': './assets/js/schedule.js',

        'img/interview.svg': './assets/img/interview.svg'
    },
    output: {
        path: path.resolve(__dirname, 'public/assets/espt/'),
    },
};

module.exports = merge(baseConfig.get(__dirname), webpackConfig);
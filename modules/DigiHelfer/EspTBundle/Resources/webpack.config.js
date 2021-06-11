let merge = require('webpack-merge');
let path = require('path');
let base = require(path.join(process.env.WEBPACK_BASE_PATH, 'webpack.config.base.js'));

let webpackConfig = {
    entry: {
        'css/espt': './assets/less/espt.less',
        'css/schedule': './assets/less/schedule.less',
        'js/espt': './assets/js/espt.js',
        'js/schedule': './assets/js/schedule.js',

        'img/interview.svg': './assets/img/interview.svg'
    },
    resolve: {
        modules: [path.resolve(__dirname, './assets/node_modules'), 'node_modules'],
    },
    output: {
        path: path.join(__dirname, '/public/assets/espt/'),
        publicPath: '/iserv/assets/espt/'
    },
};

let baseConfig = base.get(__dirname);
baseConfig.module.rules.find(rule => rule.test.test("any.js")).exclude = /(node_modules\/(?!matrix-js-sdk)|public\/components)/;
module.exports = merge.smart(baseConfig, webpackConfig);
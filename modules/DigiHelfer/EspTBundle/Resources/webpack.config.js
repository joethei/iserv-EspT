let merge = require('webpack-merge');
let path = require('path');
let base = require(path.join(process.env.WEBPACK_BASE_PATH, 'webpack.config.base.js'));

let webpackConfig = {
    entry: {
        'js/espt': './assets/js/espt.js',
        'img/espt.svg': './assets/img/espt.svg',
        'css/espt': './assets/less/espt.less'
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
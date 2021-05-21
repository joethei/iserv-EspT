let baseConfig = require(process.env.WEBPACK_BASE_PATH + '/webpack.config.base.js');

let webpackConfig = {
    entry: {
        'css/espt': './assets/less/espt.less',

        'img/interview.png': './assets/img/interview.png',
    },
};

module.exports = baseConfig.merge(baseConfig.get(__dirname), webpackConfig);
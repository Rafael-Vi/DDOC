const path = require('path');

module.exports = {
    mode: 'development',
    devtool: 'eval-source-map',
    entry: [
        './src/js/accountLC.js',
        './src/js/checkFollowList.js',
        './src/js/createPost.js',
        './src/js/editPost.js',
        './src/js/editProfile.js',
        './src/js/filterTheme.js',
        './src/js/follow.js',
        './src/js/like.js',
        './src/js/notification.js',
        './src/js/orderRank.js',
        './src/js/sendMessages.js',
        './src/js/social.js',
        './src/js/timer.js'
    ],
    output: {
        filename: 'bundle.js',
        path: path.resolve(__dirname, './dist'),
    },
};

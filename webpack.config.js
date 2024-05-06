const path = require('path');

module.exports = {
    mode: 'development',
    devtool:'eval-source-map',
  entry: ['./src/js/accountLC.js', './src/js/createPost.js', './src/js/editPost.js', './src/js/filterTheme.js'],
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, './dist'),
  },
  
};

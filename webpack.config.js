const path = require('path');

module.exports = {
    mode: 'development',
    devtool:'eval-source-map',
  entry: ['./src/js/index.js', './src/js/accountLC.js'],
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, './dist'),
  },
  
};

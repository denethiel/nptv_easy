var webpack = require('webpack');
var path = require('path');

//Naming and path settings
var appName = 'app';
var entryPoint = './src/main.js';
var exportPath = path.resolve(__dirname, './build');

//Enrioment flag
var plugins = [];
var env = process.env.WEBPACK_ENV;

function resolve(dir){
    return path.join(__dirname, '..', dir)
}


//Differ settings based on production flags
if(env == 'production'){
    var UglifyJsPlugin = webpack.optimize.UglifyJsPlugin;

    plugins.push(new UglifyJsPlugin({minimize: true}));
    plugins.push(new webpack.DefinePlugin({
        'process.env':{
            NODE_ENV: '"production"'
        }
    }));

    appName = appName + '.min.js';
}else{
    appName = appName + '.js';
}

//main Settings config
module.exports = {
    entry : entryPoint,
    output:{
        path: exportPath,
        filename: appName
    },
    devtool:'cheap-eval-source-map',
    module:{
        rules:[
            {
                test: /\.css$/,
                use:[
                    'vue-style-loader',
                    'css-loader'
                ],
            },
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components|js)/,
                loader:'babel-loader',
                query:{
                    presets:['env']
                }
            },
            {
                test:/\.vue$/,
                loader: 'vue-loader'
            },
            {
                test:/\.(png|jpg|gif|svg)$/,
                loader: 'file-loader',
                options:{
                    name:'[name].[ext]?[hash]'
                }
            },
            {
                test:/\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                loader: 'url-loader',
                options:{
                    limit: 10000,
                    name:'[name].[ext]?[hash]'
                }
            }
        ]
    },
    resolve:{
        alias:{
            'vue$':'vue/dist/vue.esm.js',
        },
        extensions:['*','.js','.vue','.json']
    },
    plugins
};

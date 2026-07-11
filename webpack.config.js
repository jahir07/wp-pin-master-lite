'use strict';

const autoprefixer = require('autoprefixer');
const browsers = require('@wordpress/browserslist-config');
const globImporter = require( 'node-sass-glob-importer' );
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const path = require('path');
const webpack = require('webpack');

module.exports = function (env, options) {

  const entry = {
   'extend-editor' : './_dev/js/guten-block/index.js',
   'pin-master' : './_dev/js/index.js',
   'style': './_dev/js/style.js'
 };

 const paths = {
  css: 'assets/css/',
  images: 'assets/images/',
  fonts: 'assets/fonts/',
  js: 'assets/js/',
  lang: 'languages/',
};

const mode = process.env.NODE_ENV || 'development';
const extensionPrefix = mode === 'production' ? '.min' : '';

const loaders = {
  css: {
    loader: 'css-loader',
    options: {
      sourceMap: true,
    },
  },
  postCss: {
    loader: 'postcss-loader',
    options: {
      plugins: [
      require('cssnano')({ safe: true }),
      autoprefixer( {
        browsers,
        flexbox: 'no-2009',
      } ),
      ],
      minimize: true,
      sourceMap: true,
    },
  },
  sass: {
    loader: 'sass-loader',
    options: {
      importer: globImporter(),
      sourceMap: true,
    },
  },
};


const config = {
  mode,
  entry,
  output: {
    path: path.join( __dirname, '/' ),
    filename: `${ paths.js }[name]${ extensionPrefix }.js`,
  },
  module: {
    rules: [
    {
      enforce: 'pre',
      test: /\.js|.jsx/,
      loader: 'import-glob',
      exclude: /(node_modules)/,
    },
    {
      test: /\.js|.jsx/,
      loader: 'babel-loader',
      query: {
        presets: [
        '@wordpress/default',
        ],
        plugins: [
        [
        '@wordpress/babel-plugin-makepot',
        {
          'output': `${ paths.lang }pin-master.pot`,
        }
        ],
        'transform-class-properties',
        ],
      },
      exclude: /(node_modules|bower_components)/,
    },
    {
      test: /\.html$/,
      loader: 'raw-loader',
      exclude: /node_modules/,
    },
    {
      test: /\.css$/,
      use: [
      MiniCssExtractPlugin.loader,
      loaders.css,
      loaders.postCss,
      ],
      exclude: /node_modules/,
    },
    {
      test: /\.scss$/,
      use: [
      MiniCssExtractPlugin.loader,
      loaders.css,
      loaders.postCss,
      loaders.sass,
      ],
      exclude: /node_modules/,
    },
    {
      test: /\.(ttf|eot|svg|woff2?)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
      use: [
      {
        loader: 'file-loader',
        options: {
          name: '[name].[ext]',
          outputPath: paths.fonts,
        },
      },
      ],
      exclude: /(assets)/,
    },
    {
      test: /\.(png|jpg|gif)$/,
      loader: 'file-loader',
      options: {
        outputPath: paths.images,
        name: '[name].[ext]'
      },
    },
    ],
  },
  optimization: {
    splitChunks: {
      cacheGroups: {
        styles: {
          name: 'style',
          test: /\.css$/,
          chunks: 'all',
          enforce: true,
        },
      },
    },
  },
  plugins: [
  new MiniCssExtractPlugin( {
    filename: `${ paths.css }[name]${ extensionPrefix }.css`,
  } ),
  new webpack.DefinePlugin( {
    'process.env.NODE_ENV': JSON.stringify( mode ),
  } ),
  ],
    // devtool: 'source-map',
    externals: {
      '@wordpress/a11y': 'wp.a11y',
      '@wordpress/api-fetch': 'wp.apiFetch',
      '@wordpress/api-request': 'wp.apiRequest',
      '@wordpress/autop': 'wp.autop',
      '@wordpress/blob': 'wp.blob',
      '@wordpress/block-library': 'wp.blockLibrary',
      '@wordpress/blocks': 'wp.blocks',
      '@wordpress/block-serialization-default-parser': 'wp.blockSerializationDefaultParser',
      '@wordpress/components': 'wp.components',
      '@wordpress/compose': 'wp.compose',
      '@wordpress/core-data': 'wp.coreData',
      '@wordpress/data': 'wp.data',
      '@wordpress/date': 'wp.date',
      '@wordpress/deprecated': 'wp.deprecated',
      '@wordpress/dom': 'wp.dom',
      '@wordpress/dom-ready': 'wp.domReady',
      '@wordpress/editor': 'wp.editor',
      '@wordpress/edit-post': 'wp.editPost',
      '@wordpress/element': 'wp.element',
      '@wordpress/escape-html': 'wp.escapeHtml',
      '@wordpress/format-library': 'wp.formatLibrary',
      '@wordpress/hooks': 'wp.hooks',
      '@wordpress/html-entities': 'wp.htmlEntities',
      '@wordpress/i18n': 'wp.i18n',
      '@wordpress/is-shallow-equal': 'wp.isShallowEqual',
      '@wordpress/keycodes': 'wp.keycodes',
      '@wordpress/notices': 'wp.notices',
      '@wordpress/nux': 'wp.nux',
      '@wordpress/plugins': 'wp.plugins',
      '@wordpress/redux-routine': 'wp.reduxRoutine',
      '@wordpress/rich-text': 'wp.richText',
      '@wordpress/shortcode': 'wp.shortcode',
      '@wordpress/token-list': 'wp.tokenList',
      '@wordpress/url': 'wp.url',
      '@wordpress/viewport': 'wp.viewport',
      '@wordpress/wordcount': 'wp.wordcount',
      backbone: 'Backbone',
      jquery: 'jQuery',
      lodash: 'lodash',
      moment: 'moment',
      react: 'React',
      'react-dom': 'ReactDOM',
      tinymce: 'tinymce',
      underscore: '_',
    },
    
  };

  return config;

};
//
// WebHiro gulp config file
// www.webhiro.com
//

var gulp = require('gulp'),
    $ = require('gulp-load-plugins')();

$.sync = require('browser-sync');
$.run = require('run-sequence');
$.del = require('del');
$.merge = require('merge-stream');
$.lazypipe = require('lazypipe');

process.env.NODE_ENV = 'development';

var config = {
    src: 'public',
    dev: '.tmp',
    dist: 'public/build',

    html: {
        watch: 'public/**/*.html',
        src: ['public/index.html', 'public/partials/**/*.html'],
        dev: '.tmp',
        dist: 'public/build'
    },
    js: {
        watch: 'public/assets/js/**/*.js',
        src: 'public/assets/js/**/*.js',
        dev: '.tmp/assets/js/',
        dist: 'public/build/assets/js/'
    },
    css: {
        watch: 'public/assets/css/**/*.{css,less}',
        src: 'public/assets/css/application.less',
        dev: '.tmp/assets/css',
        dist: 'public/build/assets/css'
    },
    img: {
        watch: 'public/assets/img/**/**',
        src: 'public/assets/img/**/*',
        dev: '.tmp/assets/img',
        dist: 'public/build/assets/img'
    },
    font: {
        watch: 'public/assets/font/**/*',
        src: 'public/assets/font/**/*',
        dev: '.tmp/assets/font',
        dist: 'public/build/assets/font'
    }
};

var notify = {
    html: { errorHandler: $.notify.onError('VIEWS: BUILD FAILED!\n' + 'Error:\n<%= error.message %>') },
    css: { errorHandler: $.notify.onError('STYLES: BUILD FAILED!\n' + 'Error:\n<%= error.message %>') },
    js: { errorHandler: $.notify.onError('SCRIPTS: BUILD FAILED!\n' + 'Error:\n<%= error.message %>') },
    img: { errorHandler: $.notify.onError('IMAGES: SOMETHING WRONG!\n' + 'Error:\n<%= error.message %>') },
    font: { errorHandler: $.notify.onError('FONTS: SOMETHING WRONG!\n' + 'Error:\n<%= error.message %>') }
};

gulp.task('font', function () {
    var dest;
    if (process.env.NODE_ENV === 'development') {
        dest = $.lazypipe().pipe(gulp.dest, config.font.dev);
    } else {
        dest = $.lazypipe().pipe(gulp.dest, config.font.dist);
    }

    return gulp
        .src(config.font.src)
        .pipe( $.newer(config.font.dev) )
        .pipe( $.plumber(notify.font) )
        .pipe( dest() )
        .pipe( $.sync.reload({ stream: true }) );
});

gulp.task('image', function () {
    var dest;
    if (process.env.NODE_ENV === 'development') {
        dest = $.lazypipe().pipe(gulp.dest, config.img.dev);
    } else {
        dest = $.lazypipe().pipe(gulp.dest, config.img.dist);
    }

    return gulp
        .src(config.img.src)
        .pipe( $.newer(config.img.dev) )
        .pipe( $.plumber(notify.img) )
        .pipe( $.imagemin({
            optimizationLevel: 5,
            progressive: true,
            interlaced: true
        }))
        .pipe( dest() )
        .pipe( $.sync.reload({ stream: true }) );
});

gulp.task('html', function () {
    var index, dest;
    var filter = $.filter(['index.html']);

    if (process.env.NODE_ENV === 'development') {
        index = $.lazypipe().pipe($.usemin, { js: ['concat'] });
        dest = $.lazypipe().pipe(gulp.dest, config.html.dev);
    } else {
        index = $.lazypipe().pipe($.usemin, { js: ['concat',  $.uglify({ mangle: false })] });
        dest = $.lazypipe().pipe(gulp.dest, config.html.dist);
    }

    return gulp
        .src(config.html.src, { base: 'public' })
        .pipe( $.newer(config.html.dev) )
        .pipe( $.plumber(notify.html ) )
        .pipe( $.preprocess() )
        .pipe(filter)
        .pipe(index())
        .pipe(filter.restore())
        .pipe(dest())
        .pipe($.sync.reload({ stream: true }));
});

gulp.task('css', function () {
    var dest;

    if (process.env.NODE_ENV === 'development') {
        dest = $.lazypipe().pipe(gulp.dest, config.css.dev);
    } else {
        dest = $.lazypipe()
            .pipe( $.minifyCss )
            .pipe( $.rename, 'application.min.css' )
            .pipe(gulp.dest, config.css.dist);
    }

    return gulp
        .src(config.css.src)
        .pipe( $.newer(config.css.dev) )
        .pipe( $.plumber(notify.css) )
        // .pipe( $.sourcemaps.init() )
        .pipe( $.less() )
        .pipe( $.autoprefixer() )
        // .pipe( $.sourcemaps.write() )
        .pipe( dest() )
        .pipe( $.sync.reload({ stream: true }) );
});

gulp.task('js', function () {
    $.run('html');
    return gulp.src([
            config.js.src,
            '!public/assets/js/application.js',
            '!public/assets/js/router/routes.js'
        ])
        .pipe( $.if( process.env.NODE_ENV === 'development', $.newer(config.js.dev)))
        .pipe( $.if( process.env.NODE_ENV === 'production', $.newer(config.js.dist)))
        .pipe( $.plumber(notify.js) )
        .pipe( $.if( process.env.NODE_ENV === 'development', gulp.dest(config.js.dev)))
        .pipe( $.if( process.env.NODE_ENV === 'production', gulp.dest(config.js.dist)))
        .pipe( $.sync.reload({ stream: true }) );
});

gulp.task('clean', function (cb) {
    $.del([config.dev, config.dist], { dot: true, read: false }, cb);
});

gulp.task('deploy', ['clean', 'build'], function () {
    return gulp
        .src('public/build/**/*')
        .pipe( $.sftp({
            host: 'your_domain.com',
            auth: 'auth_name_in_.ftpass_file',
            remotePath: "remote_path_here"
        }) );
});

gulp.task('serve', ['default'], function () {
    $.sync.init(null, {
        server: {
            baseDir: config.dev
        }
    });

    gulp.watch(config.html.watch, ['html'] );
    gulp.watch(config.js.watch, ['js'] );
    gulp.watch(config.css.watch, ['css']);
    gulp.watch(config.img.watch, ['image']);
});

gulp.task('build', ['clean'], function (cb) {
    process.env.NODE_ENV = 'production';
    $.run(['js', 'css','html','font','image'], cb);
});

gulp.task('default', ['clean'], function (cb) {
    $.run(['js','css','html','font','image'], cb);
});

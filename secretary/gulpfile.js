var gulp = require('gulp');
var version = require('gulp-version-number'),
    bs = require('browser-sync').create();

var css = [
            'assets/stylesheets/*.css'
        ];

var js = [
            'themes/default/assets/js/*.js'
        ];

var versionConfig = {
  	value: '%MDS%',
	append: {
	    key: 'v',
	    to: ['css', 'js']
	},
	output: {
	    file: 'version.json'
	}
};

gulp.task('version', function(){
  return gulp.src(['!themes/default/views/header.php', '!themes/default/views/footer.php', '!themes/default/views/auth/**', 'themes/default/views/**'])
    .pipe(version(versionConfig))
    .pipe(gulp.dest('themes/default/views/'));
});

gulp.task('bs', function() {
  bs.init({
  	files: [
           'themes/default/assets/js/*.js',
           '*.html',
           '*.php',
           'assets/stylesheets/*.css'
    ],
    browser: "chrome.exe",
    proxy: "http://localhost/secretary",
  });
});

gulp.task('script', function() {
    return gulp.src(js)
     .pipe(gulp.dest('themes/default/assets/js'));
});

gulp.task('style', function(){
  return gulp.src(css)
    .pipe(gulp.dest('assets/stylesheets'))
    .pipe(bs.reload({
        stream: true
    }));
});

gulp.task('watch', function() {
    gulp.watch(css, ['style']);
    gulp.watch(js, ['script']);
});
gulp.task('default', [ 'style', 'script', 'version', 'bs', 'watch' ]);
const gulp         = require('gulp');
const gettext      = require('gulp-gettext');
const jshint       = require('gulp-jshint');
const plumber      = require('gulp-plumber');
const rename       = require('gulp-rename');
const sort         = require('gulp-sort');
const uglify       = require('gulp-uglify');
const wppot        = require('gulp-wp-pot');

gulp.task('default', function() {
	console.log('Use the following commands');
	console.log('--------------------------');
	console.log('gulp wordpress-lang to compile the to-specials.pot, to-specials-en_EN.po and to-specials-en_EN.mo');
});

gulp.task('wordpress-pot', function(done) {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-specials',
			package: 'to-specials',
			bugReport: 'https://bitbucket.org/feedmycode/to-specials',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/to-specials.pot')),
		done();
});

gulp.task('wordpress-po', function(done) {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-specials',
			package: 'to-specials',
			bugReport: 'https://bitbucket.org/feedmycode/to-specials',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/to-specials-en_EN.po')),
		done();
});

gulp.task('wordpress-po-mo', gulp.series( ['wordpress-po'], function(done) {
	return gulp.src('languages/to-specials-en_EN.po')
		.pipe(gettext())
		.pipe(gulp.dest('languages')),
		done();
}));

gulp.task('wordpress-lang', gulp.series( ['wordpress-pot', 'wordpress-po-mo'] , function(done) {
	done();
}));

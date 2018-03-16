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

gulp.task('wordpress-pot', function() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-specials',
			package: 'to-specials',
			bugReport: 'https://bitbucket.org/feedmycode/to-specials',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/to-specials.pot'))
});

gulp.task('wordpress-po', function() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-specials',
			package: 'to-specials',
			bugReport: 'https://bitbucket.org/feedmycode/to-specials',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/to-specials-en_EN.po'))
});

gulp.task('wordpress-po-mo', ['wordpress-po'], function() {
	return gulp.src('languages/to-specials-en_EN.po')
		.pipe(gettext())
		.pipe(gulp.dest('languages'))
});

gulp.task('wordpress-lang', (['wordpress-pot', 'wordpress-po-mo']));

import gulp from 'gulp';
import sass from 'gulp-sass';
import browserify from 'browserify';
import source from 'vinyl-source-stream';
import buffer from 'vinyl-buffer';
import sourcemaps from 'gulp-sourcemaps';
import log from 'gulplog';
import autoprefixer from 'autoprefixer';
import spritesmith from 'gulp.spritesmith';
import postcss from 'gulp-postcss';
import uglify from 'gulp-uglify';
import rename from 'gulp-rename';
import cleanCSS from 'gulp-clean-css';
import gulpConnect from 'gulp-connect';
import concat from 'gulp-concat';
import pug from 'gulp-pug';
import del from 'del';
import nodeSass from 'node-sass';
import sassVariables from 'gulp-sass-variables';

sass.compiler = nodeSass;

const paths = {
  styles: {
    src: 'assets/styles/**/*.scss',
    dest: 'public/build/styles/',
  },
  fonts: {
    src: 'assets/fonts/*',
    dest: 'public/build/fonts/',
  },
  scripts: {
    src: 'assets/scripts/**/*.js',
    dest: 'public/build/scripts/',
  },
  images: {
    src: 'assets/images/**/*.{jpg,jpeg,png,svg}',
    dest: 'public/build/',
  },
  sprite: {
    src: 'assets/sprite/**/*.{jpg,jpeg,png,svg}',
    dest: 'public/build/',
  },
};

/*
 * For small tasks you can export arrow functions
 */
export const clean = () => del(['build/*']);
// export const externalClean = () => del(['public_external/*']);

export function connect() {
  console.info('Start connect port ' + 5000);
  gulpConnect.server({
    name: 'Dev App',
    root: 'public/build',
    port: 5000,
    livereload: true,
  });
}


//                  _ _
//   ___ _ __  _ __(_) |_ ___
//  / __| '_ \| '__| | __/ _ \
//  \__ \ |_) | |  | | ||  __/
//  |___/ .__/|_|  |_|\__\___|
//      |_|


export function sprites() {
  var spriteData = gulp.src('./sprites/*.png').pipe(spritesmith({
    imgName: 'sprite.png',
    // cssName: 'sprite.css'
    cssName: `_sprite.scss`,
    padding: 10,
    cssTemplate: './assets/sprite.handlebars',
  }));

  var css = spriteData.css
    .pipe(rename('_sprite.scss'))
    .pipe(gulp.dest('./assets/styles'));

  var img = spriteData.img
    .pipe(buffer())
    .pipe(gulp.dest('./build'))
    // .pipe(connect.stream())
    .pipe(gulpConnect.reload());

  return merge(img, css);
}

export function spriteClean() {
  return del([
    // paths.sprite.dest
  ])
}

/*
 * You can also declare named functions and export them as tasks
 */
export function styles() {
  return (
    gulp
      .src('./assets/styles/main.scss')
      .pipe(sassVariables({
        $contrast: 0,
      }))
      .pipe(sass({
      }).on('error', sass.logError))
      .pipe(postcss([autoprefixer()]))
      .pipe(cleanCSS())
      .pipe(
        rename({
          basename: 'main',
          suffix: '.min',
        }),
      )
      .pipe(gulp.dest(paths.styles.dest))
      .pipe(gulpConnect.reload())
  );
}

export function stylesContrast() {
  return (
    gulp
      .src('./assets/styles/main.scss')
      .pipe(sassVariables({
        $contrast: 1,
      }))
      .pipe(sass({
      }).on('error', sass.logError))
      .pipe(postcss([autoprefixer()]))
      .pipe(cleanCSS())
      .pipe(
        rename({
          basename: 'contrast',
          suffix: '.min',
        }),
      )
      .pipe(gulp.dest(paths.styles.dest))
      .pipe(gulpConnect.reload())
  );
}

export function scripts() {

  return gulp.src([
    'plugins/*.js',
    'components/*.js',
  ].map(d => `./assets/scripts/${d}`))
    .pipe(sourcemaps.init())
    .pipe(concat('main.min.js'))
    .pipe(uglify())
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(paths.scripts.dest));
}

export function images() {
  return gulp
    .src(paths.images.src, { since: gulp.lastRun(images) })
    .pipe(gulp.dest(paths.images.dest));
}

export function fonts() {
  return gulp
    .src(paths.fonts.src)
    .pipe(gulp.dest(paths.fonts.dest));
}

export function views() {
  return gulp.src(paths.view.src)
    .pipe(pug({
      pretty: true,
    }))
    .pipe(gulp.dest(paths.view.dest));
}

/*
 * You could even use `export as` to rename exported tasks
 */
function watchFiles() {
  gulp.series(clean, gulp.parallel(sprites, images, scripts, stylesContrast, fonts, styles, connect))();
  gulp.watch(paths.scripts.src, scripts);
  gulp.watch(paths.styles.src, gulp.parallel(styles, stylesContrast));
  gulp.watch(paths.images.src, images);
  gulp.watch(paths.sprite.src, gulp.series([spriteClean, sprites]))
}

gulp.task('sprites', sprites);

export { watchFiles as watch };

export const build = gulp.series(clean, gulp.parallel(sprites, images, styles, stylesContrast, scripts, fonts));

export default build;

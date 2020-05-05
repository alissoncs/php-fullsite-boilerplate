import gulp from 'gulp';
import sass from 'gulp-sass';
import browserify from 'browserify';
import source from 'vinyl-source-stream';
import buffer from 'vinyl-buffer';
import sourcemaps from 'gulp-sourcemaps';
import log from 'gulplog';
import autoprefixer from 'autoprefixer';
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
    dest: 'public/build/images/',
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
    'plugins/jquery.js',
    'plugins/bootstrap.js',
    'plugins/slick.js',
    'plugins/select2.js',
    'plugins/validate.js',
    'plugins/validate-pt-br.js',
    'plugins/mask.js',
    // 'plugins/slick.js',

    'components/functions.js',
    'components/header.js',
    'components/home.js',
    'components/search.js',
    'components/encontre-seu-plano.js',
    'components/encontre-um-medico.js',
    'components/criacao-usuario.js',
    'components/fale-conosco.js',
    'components/blog.js',
    'components/acessibilidade.js',
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
  gulp.series(clean, gulp.parallel(scripts, stylesContrast, fonts, styles, images, connect))();
  gulp.watch(paths.scripts.src, scripts);
  gulp.watch(paths.styles.src, gulp.parallel(styles, stylesContrast));
  gulp.watch(paths.images.src, images);

}

export { watchFiles as watch };

export const build = gulp.series(clean, gulp.parallel(styles, stylesContrast, scripts, images, fonts));

export default build;

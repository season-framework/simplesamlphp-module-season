const gulp = require('gulp'),
	debug = require('gulp-debug'),
	clean = require('gulp-clean'),
	sass = require('gulp-sass'),
	postcss = require('gulp-postcss'),
	header = require('gulp-header'),
	cleanCSS = require('gulp-clean-css'),
	minifyJS = require('gulp-minify'),
	rename = require('gulp-rename'),
	purgecss = require('gulp-purgecss'),
	rollupStream = require('@rollup/stream'),
	rollupBabel = require('rollup-plugin-babel'),
	rollupCleanup = require('rollup-plugin-cleanup'),
	{nodeResolve} = require('@rollup/plugin-node-resolve'),
	rollupCommonjs = require('@rollup/plugin-commonjs'),
	vinylSource = require('vinyl-source-stream'),
	vinylBuffer = require('vinyl-buffer'),
	critical = require('critical').stream,
	browserSync = require('browser-sync'),
	glob = require('glob'),
	spawn = require('cross-spawn'),
	fs = require('fs'),
	path = require('path'),
	YAML = require('yaml'),
	yargs = require('yargs/yargs'),
	cp = require('child_process'),
	pkg = require('./package.json'),
	year = new Date().getFullYear(),
	argv = yargs(process.argv).argv;


let BUILD = false,
	distDir = './.tmp',
	demoDir = './.tmp',
	srcDir = './src';

/**
 * Enable BUILD mode and set directories
 */
gulp.task('build-on', (cb) => {
	BUILD = true;
	distDir = './dist';
	demoDir = './demo';

	cb();
});

/**
 * Return banner added to CSS and JS dist files
 */
const getBanner = () => {
	return `/*!
* Tabler v${pkg.version} (${pkg.homepage})
* @version ${pkg.version}
* @link ${pkg.homepage}
* Copyright 2018-${year} The Tabler Authors
* Copyright 2018-${year} codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
*/
`;
};

/**
 * Array.flat polyfill
 */
if (!Array.prototype.flat) {
	Object.defineProperty(Array.prototype, 'flat', {
		value: function (depth = 1) {
			return this.reduce(function (flat, toFlatten) {
				return flat.concat((Array.isArray(toFlatten) && (depth > 1)) ? toFlatten.flat(depth - 1) : toFlatten);
			}, []);
		}
	});
}

/**
 * Import tabler-icons form npm and generate Jekyll `.yml` data files
 */
gulp.task('svg-icons', (cb) => {
	const prepareSvgFile = (svg) => {
		return svg.replace(/\n/g, '').replace(/>\s+</g, '><');
	};

	const generateIconsYml = (dir, filename) => {
		const files = glob.sync(dir);
		let svgList = {};

		files.forEach((file) => {
			const basename = path.basename(file, '.svg');
			svgList[basename] = prepareSvgFile(fs.readFileSync(file).toString());
		});

		fs.writeFileSync(filename, YAML.stringify(svgList));
	};

	generateIconsYml("./node_modules/@tabler/icons/icons/*.svg", `${srcDir}/pages/_data/icons.yml`);

	cb();
});

/**
 * Check unused Jekyll partials
 */
gulp.task('unused-files', (cb) => {
	let foundFiles = [];

	glob.sync(`${srcDir}/pages/**/*.{html,md}`).forEach((file) => {
		let fileContent = fs.readFileSync(file);

		fileContent.toString().replace(/\{% include(_cached)? ([a-z0-9\/_-]+\.html)/g, (f, c, filename) => {
			filename = `${srcDir}/pages/_includes/${filename}`;

			if (!foundFiles.includes(filename)) {
				foundFiles.push(filename);
			}
		});
	});

	let includeFiles = glob.sync(`${srcDir}/pages/_includes/**/*.html`);

	includeFiles.forEach((file) => {
		if (!foundFiles.includes(file)) {
			console.log('file', file);
		}
	});

	cb();
});

/**
 * Clean `dist` folder before build
 */
gulp.task('clean-dirs', () => {
	return gulp
		.src(`{${distDir}/*,${demoDir}/*}`, { read: false })
		.pipe(clean());
});

gulp.task('clean-jekyll', (cb) => {
	return spawn('bundle', ['exec', 'jekyll', 'clean'], { stdio: 'inherit' })
		.on('close', cb);
});

/**
 * Compile SASS to CSS and move it to dist directory
 */
gulp.task('sass', () => {
	const g = gulp
		.src(`${srcDir}/scss/*.scss`)
		.pipe(sass({
			style: 'expanded',
			precision: 7,
			importer: (url, prev, done) => {
				if (url[0] === '~') {
					url = path.resolve('node_modules', url.substr(1));
				}

				return { file: url };
			},
		}).on('error', sass.logError))
		.pipe(postcss([
			require('autoprefixer'),
		]))
		.pipe(gulp.dest(`${distDir}/css/`))
		.pipe(browserSync.reload({
			stream: true,
		}));

	if (BUILD) {
		g.pipe(cleanCSS())
			.pipe(rename((path) => {
				path.basename += '.min';
			}))
			.pipe(gulp.dest(`${distDir}/css/`));
	}

	return g;
});

/**
 * Compile JS files to dist directory
 */
let cache;
gulp.task('js', () => {
	const g = rollupStream({
		input: `${srcDir}/js/tabler.js`,
		cache,
		output: {
			name: 'tabler.js',
			format: 'umd',
		},
		plugins: [
			rollupBabel({
				exclude: 'node_modules/**'
			}),
			nodeResolve(),
			rollupCommonjs(),
			rollupCleanup()
		]
	})
		.on('bundle', (bundle) => {
			cache = bundle;
		})
		.pipe(vinylSource('tabler.js'))
		.pipe(vinylBuffer())
		.pipe(rename((path) => {
			path.dirname = '';
		}))
		.pipe(gulp.dest(`${distDir}/js/`))
		.pipe(browserSync.reload({
			stream: true,
		}));

	if (BUILD) {
		g.pipe(minifyJS({
			ext: {
				src: '.js',
				min: '.min.js'
			},
		}))
			.pipe(gulp.dest(`${distDir}/js/`));
	}

	return g;
});

/**
 * Watch Jekyll files and build it to demo directory
 */
gulp.task('watch-jekyll', (cb) => {
	browserSync.notify('Building Jekyll');
	return spawn('bundle', ['exec', 'jekyll', 'build', '--watch', '--destination', demoDir, '--trace'], { stdio: 'inherit' })
		.on('close', cb);
});

/**
 * Build Jekyll files do demo directory
 */
gulp.task('build-jekyll', (cb) => {
	var env = Object.create(process.env);

	if(argv.preview) {
		env.JEKYLL_ENV = 'preview';
	}
	else {
		env.JEKYLL_ENV = 'production';
	}

	return spawn('bundle', ['exec', 'jekyll', 'build', '--destination', demoDir, '--trace'], { env: env, stdio: 'inherit' })
		.on('close', cb);
});

gulp.task('build-cleanup', () => {
	return gulp
		.src(`${demoDir}/redirects.json`, { read: false, allowEmpty: true })
		.pipe(clean());
});

gulp.task('build-purgecss', (cb) => {
	if(argv.preview) {
		return gulp.src('demo/dist/{libs,css}/**/*.css')
			.pipe(purgecss({
				content: ['demo/**/*.html']
			}))
			.pipe(gulp.dest('demo/dist/css'))
	}

	cb();
});

gulp.task('build-critical', (cb) => {
	if(argv.preview) {
		return gulp
			.src('demo/**/*.html')
			.pipe(
				critical({
					base: 'demo/',
					inline: true,
					css: ['demo/dist/css/tabler.css'],
					ignore: {
						atrule: ['@font-face', '@import'],
						decl: (node, value) => {
							/url\(/.test(value)
						},
					},
				})
			)
			.on('error', err => {
				console.log(err.message);
			})
			.pipe(gulp.dest('demo'));
	}

	cb();
});

/**
 * Watch JS and SCSS files
 */
gulp.task('watch', (cb) => {
	gulp.watch('./src/scss/**/*.scss', gulp.series('sass'));
	gulp.watch('./src/js/**/*.js', gulp.series('js'));
	cb();
});

/**
 * Create BrowserSync server
 */
gulp.task('browser-sync', () => {
	browserSync({
		watch: true,
		server: {
			baseDir: demoDir,
			routes: {
				'/node_modules': 'node_modules',
				'/dist/css': `${distDir}/css`,
				'/dist/js': `${distDir}/js`,
				'/dist/img': `${srcDir}/img`,
				'/static': `${srcDir}/static`,
			},
		},
		port: 3000,
		open: false,
		host: 'localhost',
		notify: false,
		reloadOnRestart: true
	});
});

/**
 * Copy libs used in tabler from npm to dist directory
 */
gulp.task('copy-libs', (cb) => {
	const allLibs = require(`${srcDir}/pages/_data/libs`);

	let files = [];

	Object.keys(allLibs.js).forEach((lib) => {
		files.push(Array.isArray(allLibs.js[lib]) ? allLibs.js[lib] : [allLibs.js[lib]]);
	});

	Object.keys(allLibs.css).forEach((lib) => {
		files.push(Array.isArray(allLibs.css[lib]) ? allLibs.css[lib] : [allLibs.css[lib]]);
	});

	files = files.flat();

	files.forEach((file) => {
		if (!file.match(/^https?/)) {
			let dirname = path.dirname(file).replace('@', '');
			let cmd = `mkdir -p "dist/libs/${dirname}" && cp -r node_modules/${file} ${distDir}/libs/${file.replace('@', '')}`;

			cp.exec(cmd)
		}
	});

	cb();
});

/**
 * Copy static files (flags, payments images, etc) to dist directory
 */
gulp.task('copy-images', () => {
	return gulp
		.src(`${srcDir}/img/**/*`)
		.pipe(gulp.dest(`${distDir}/img`));
});

/**
 * Copy static files (demo images, etc) to demo directory
 */
gulp.task('copy-static', () => {
	return gulp
		.src(`${srcDir}/static/**/*`)
		.pipe(gulp.dest(`${demoDir}/static`));
});

/**
 * Copy Tabler dist files to demo directory
 */
gulp.task('copy-dist', () => {
	return gulp
		.src(`${distDir}/**/*`)
		.pipe(gulp.dest(`${demoDir}/dist/`));
});

/**
 * Add banner to build JS and CSS files
 */
gulp.task('add-banner', () => {
	return gulp.src(`${distDir}/{css,js}/**/*.{js,css}`)
		.pipe(header(getBanner()))
		.pipe(gulp.dest(`${distDir}`))
});

gulp.task('clean', gulp.series('clean-dirs', 'clean-jekyll'));

gulp.task('start', gulp.series('clean', 'sass', 'js', 'build-jekyll', gulp.parallel('watch-jekyll', 'watch', 'browser-sync')));

gulp.task('build-core', gulp.series('build-on', 'clean', 'sass', 'js', 'copy-images', 'copy-libs', 'add-banner'));
gulp.task('build-demo', gulp.series('build-on', 'build-jekyll', 'copy-static', 'copy-dist', 'build-cleanup', 'build-purgecss'/*, 'build-critical'*/));
gulp.task('build', gulp.series('build-core', 'build-demo'));

'use strict';
module.exports = function(grunt) {

	grunt.initConfig({

		// js minification
		uglify: {
			dist: {
				files: {
					'js/saved-tabs-pro.min.js': [ 
						'js/saved-tabs-pro.js'
					],
					'js/settings.min.js': [ 
						'js/settings.js'
					],
					'js/shared.min.js': [
						'js/shared.js'
					],
					'js/support.min.js': [
						'js/support.js'
					],
				}
			}
		},

		// css minify all contents of our directory and add .min.css extension
		cssmin: {
			target: {
				files: [
					{
						expand: true,
						cwd: 'css',
						src: [
							'saved-tabs-pro.css',
							'settings.css'
						],
						dest: 'css',
						ext: '.min.css'
					}
				]
			}
		},
		
		pot: {
			options: {
				text_domain: 'custom-product-tabs-pro', 
				dest: 'languages/', 
		        keywords: [
		        	'__:1',
		        	'_e:1',
					'_x:1,2c',
					'esc_html__:1',
					'esc_html_e:1',
					'esc_html_x:1,2c',
					'esc_attr__:1', 
					'esc_attr_e:1', 
					'esc_attr_x:1,2c', 
					'_ex:1,2c',
					'_n:1,2', 
					'_nx:1,2,4c',
					'_n_noop:1,2',
					'_nx_noop:1,2,3c'
				],
			},
			files: {
				src:  [ '**/*.php' ],
				expand: true,
			}
		}

	});

	// load tasks
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-pot' );

	// register task
	grunt.registerTask( 'default', [
			'uglify',
			'cssmin',
			'pot'
	]);

};

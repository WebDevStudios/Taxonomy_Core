module.exports = function(grunt) {

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					mainFile: 'Taxonomy_Core.php',
					potFileName: 'taxonomy-core.pot',
					type: 'wp-plugin'
				}
			}
	   },

		addtextdomain: {
			theme: {
				options: {
					textdomain: 'taxonomy-core'
				},
				target: {
					files: {
						src: [ '*.php' ]
					}
				}
			},
		}
	});

	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	grunt.registerTask('default', ['makepot']);

};

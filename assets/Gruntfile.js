module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        jshint: {
            files: ['Gruntfile.js', 'js/src/**/*.js', 'js/lib/**/*.js'],
            options: {
                globals: {
                    jQuery: true,
                    console: true,
                    module: true
                }
            }
        },

        concat: {
            files: {
                src: [
                    'js/lib/*.js'
                ],
                dest: 'js/libs.js'
            },
            forms: {
                src: [
                    'js/src/forms/*.js'
                ],
                dest: 'js/forms.js'
            }
        },

        uglify: {
            libs: {
                src: 'js/libs.js',
                dest: 'js/libs.min.js'
            },
            forms: {
                src: 'js/forms.js',
                dest: 'js/forms.min.js'
            }
        },

        watch: {
            scripts: {
                files: ['<%= jshint.files %>'],
                tasks: ['jshint', 'concat', 'uglify'],
                options: {
                    spawn: false
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');

    grunt.registerTask('default', ['concat']);

};
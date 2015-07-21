module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        jshint: {
            files: ['Gruntfile.js', 'js/**/*.js'],
            options: {
                globals: {
                    jQuery: true,
                    console: true,
                    module: true
                }
            }
        },
        concat: {
            libs: {
                src: [
                    'js/lib/*.js'
                ],
                dest: 'js/libs.js'
            },
            forms: {
                src: [
                    'js/src/*.js'
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
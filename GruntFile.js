module.exports = function(grunt) {
    grunt.initConfig({
        pkg : grunt.file.readJSON('package.json'),
        jshint : {
            myFiles : [
                './Server/index.js',
                './Server/db.js',
                './Routes/index.js']
        },
        nodemon : {
            script : './Server/'
        }
    });
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-nodemon');
    grunt.registerTask('default', ['jshint','nodemon']);
};
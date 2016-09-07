module.exports = function(grunt) {

  var message = grunt.option('m') || 'commit';  // Сообщение коммита, использование:
                                                // gtunt --m="commit message goes here"

  grunt.initConfig({
    clean: ["journal-cache/*", "vqmod/logs/*", "vqmod/vqcache/*", "vqmod/checked.cache", "vqmod/mods.cache"],
    dploy: {                                            // Task
      stage: {                                          // Target
        host: "uashared06.twinservers.net",                  // Your FTP host
        user: "ihappy@ihappy.webformyself.co.ua",  // Your FTP user
        pass: "xoxol22s",                               // Your FTP secret-password
        exclude: ["Gruntfile.js", "package.json", "node_modules/*","readme.md","dev/*",".htaccess","config.php","admin/config.php"], // Убираем из деплоя на ftp ненужные там файлы
        path: {
            local: "",          // The local folder that you want to upload
            remote: "/"          // Where the files from the local file will be uploaded at in your remote server
        }
      }
    },
    gitcommit: {
      task:{
        options: {
          message: message,
          noVerify: false,
          noStatus: false,
          verbose: false
        },
        files:{
          src: ['.']
        }
      }
    },
    gitpull: {
      task: {
        options: {
          verbose: true
        }
      }
    },
    gitpush: {
      task: {
        options: {
          verbose: true
        }
      }
    },
  });

  grunt.loadNpmTasks('grunt-dploy');
  grunt.loadNpmTasks('grunt-git');
  grunt.loadNpmTasks('grunt-contrib-clean');
  // 4. Указываем, какие задачи выполняются, когда мы вводим «grunt» в терминале
  grunt.registerTask('default', ['clean', 'gitcommit', 'gitpull', 'gitpush', 'dploy']);

};
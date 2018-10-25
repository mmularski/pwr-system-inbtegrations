set :deploy_to, '/home/www/<projec_name>/www/<host_name>'

server '<replace_me_server_name>',
       user: '<user_name>',
       roles: %w{web app},
       ssh_options: {
           keys: %w(~/.ssh/id_rsa),
           forward_agent: false,
           auth_methods: %w(publickey)
       },
       port: '60022'

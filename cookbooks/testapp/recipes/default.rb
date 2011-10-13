#
# Cookbook Name:: wordpress
# Recipe:: default
#

if ['solo', 'app', 'app_master'].include?(node[:instance_role])

  node[:applications].each do |app_name,data|
    user = node[:users].first
    
    remote_file "/etc/init.d/spawn-fcgi-php" do
      owner "root"
      group "root"
      mode 0755
      source "spawn-fcgi-php"
      backup false
      action :create
    end

#    template "/data/#{app_name}/shared/config/wp-config.php" do
#      owner user[:username]
#      group user[:username]
#      mode 0644
#      source "wp-config.php.erb"
#      variables({
#        :app_name => app_name,
#        :dbpass => node[:users].first[:password]
#      })
#    end

    template "/etc/nginx/servers/#{app_name}-chef.conf" do
      owner user[:username]
      group user[:username]
      mode 0644
      source "testapp.conf.erb"
      variables({
        :app_name => app_name
      })
    end
  end
end
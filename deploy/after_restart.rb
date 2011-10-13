# after_restart.rb

# thank you dennis j. bell for the wizardy here
# take all the .monitrc files and rename them to .monitrc.bak so monit won't find them
# when we quit monit it will no longer try to start a "Ruby" application

sudo "for old in /etc/monit.d/*.monitrc; do new=`echo $old | sed -e 's/monitrc$/monitrc.bak/'`; mv $old $new; done"
sudo "monit quit"


node[:applications].each do |app_name,data|

  # rename the auto-generated application config so the chef config can replace it
  sudo "touch /etc/nginx/servers/#{app_name}.conf && mv /etc/nginx/servers/#{app_name}.conf /etc/nginx/servers/#{app_name}.conf.bak"
  
  # symlink the wp-config.php file created by custom chef recipe
  #sudo "ln -nfs /data/#{app_name}/shared/config/wp-config.php /data/#{app_name}/current/wp-config.php"
  sudo "ln -nfs /data/#{app_name}/shared/config/index.php /data/#{app_name}/current/index.php"

end

# start php
sudo "/etc/init.d/spawn-fcgi-php start"

# restart nginx
# this is required because of the change in nginx files above
sudo "/etc/init.d/nginx restart"

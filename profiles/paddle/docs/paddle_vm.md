# How to use Paddle VM
Follow these steps to setup up your local Paddle VM.

Install vagrant([https://www.vagrantup.com/downloads.html](https://www.vagrantup.com/downloads.html))
Install VirtualBox [https://www.virtualbox.org](https://www.virtualbox.org)

Execute these commandline steps:

 ```
git clone https://bitbucket.org/kanooh/paddle-all.git
git clone https://github.com/Kanooh/drupal-vm.git
cd drupal-vm
git checkout paddle-master
cp example.local.config.yml local.config.yml
```
Ensure the local_path in local.config.yml file points to the path where the
Paddle Drupal files are on your local machine.

Next run the following commands to start vagrant and install add-ons and ssh to your Paddle VM.
```
vagrant up
vagrant box update
vagrant plugin update
vagrant plugin install vagrant-vbguest
vagrant ssh
```

Run following command in order to use correct Drush version for Drupal 7
```
cd ~
composer global require drush/drush:8.*
echo "export DRUSH_LAUNCHER_FALLBACK=~/.composer/vendor/bin/drush" | tee -a ~/.bash_profile
source ~/.bash_profile
```

Install distribution.
In the settings.php file of Drupal change database configuration to:
```

$databases['default']['default'] = array(
      'driver' => 'mysql',
      'database' => 'drupal',
      'username' => 'root',
      'password' => 'root',
      'host' => 'localhost',
      'prefix' => '',
    );
```

Run the following to install distribution.
```
cd /var/www/drupalvm
drush site-install -y paddle --db-url=mysql://root:root@localhost/drupal --account-name=admin --account-pass=admin install_configure_form.site_default_country=BE install_configure_form.date_default_timezone=Europe/Brussels install_configure_form.update_status_module='array(FALSE,FALSE)';./profiles/paddle/create_demo_users.sh
```

It can easily take 30 minutes - depending on the speed of your laptop and 
internet connection - to download and set up a virtual machine and install the 
Paddle distribution.

You can now surf to your local Paddle website at 
[http://drupalvm.test](http://drupalvm.test).

You can either login with the demo user:
Username: demo
Password: demo 

Or the admin user
Username: admin
Password: admin



## Background information
Paddle VM is built on the strong foundation of 
[Drupal VM](https://www.drupalvm.com/). Which is built with 
[Vagrant](https://www.vagrantup.com/) and [Ansible](https://www.ansible.com/). 
Read more about the prerequisites at the 
[Drupal VM documentation](http://docs.drupalvm.com/)

It has been tested with [VirtualBox](https://www.virtualbox.org/) but should 
work with other virtualization software as well.

## Difference compared to Drupal VM
Drupal VM does not include config.yml but Paddle VM does. That way Paddle 
developers can share configuration while leaving the upstream Drupal VM 
default.config.yml untouched.

To override settings from Drupal VM's default.config.yml and/or Paddle VM's 
config.yml, you can use local.config.yml.

## Memcache (optional)
Improve performance by enabling Memcache support. Add these lines to your 
settings.php file:
```
$conf['cache_backends'][] = 'sites/all/modules/paddle/memcache/memcache.inc';
$conf['cache_default_class'] = 'MemCacheDrupal';
$conf['memcache_servers'] = array("localhost:11211" => "default");
// The 'cache_form' bin must be assigned to non-volatile storage.
$conf['cache_class_cache_form'] = 'DrupalDatabaseCache';
```

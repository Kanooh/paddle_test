Paddle
------

This is the base distribution created for the Kanooh project of the Flemish
community.



Installation:
-------------

1. Install Drush [1].
2. Install the Drupal.org Drush extension [2][3]:
   $ cd ~/.drush
   $ git clone --branch master http://git.drupal.org/project/drupalorg_drush.git
   $ drush cache-clear drush
3. Install Composer [4]
4. Run build.sh:
   $ ./build.sh

This will download all required components from drupal.org and install the
distribution in the "build" sub-folder.


Performance optimization:
-------------------------

The performance improvements that don't require specific server components get
enabled by default. But some need non default server components and thus manual
setup. Such as Memcache.

To enable Memcache support, add these lines to your settings.php file:
  $conf['cache_backends'][] = 'sites/all/modules/paddle/memcache/memcache.inc';
  $conf['cache_default_class'] = 'MemCacheDrupal';
  $conf['memcache_servers'] = array("localhost:11211" => "default");
  // The 'cache_form' bin must be assigned to non-volatile storage.
  $conf['cache_class_cache_form'] = 'DrupalDatabaseCache';


References:
-----------

[1] Drush project page
    http://drupal.org/project/drush

[2] Drupal.org Drush extension project page
    http://drupal.org/project/drupalorg_drush

[3] Installation instructions for the Drupal.org Drush extension
    http://drupal.org/node/1432296#comment-5811466

[4] Composer main website
    https://getcomposer.org/

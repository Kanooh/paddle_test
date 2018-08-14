Paddle Search
-------------

This module integrates Solr search into the Paddle distribution installation.


Installation:
-------------

1. Add the variable $conf['paddle_search_solr_url'] to your settings.php.

   Example:
   $conf['paddle_search_solr_url'] = 'http://user:pass@hostname:8080/path';

2. Enable the module or when already enabled, revert your features.
   $ drush fr -y paddle_search

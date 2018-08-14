# Update Paddle apps overview

The Paddle app overview page 'Paddle Store' uses 
[Search API](https://www.drupal.org/project/search_api) with a 
[database backend](https://www.drupal.org/project/search_api_db), 
[Facet API](https://www.drupal.org/project/facetapi) for filtering and 
[Entity Cache](https://www.drupal.org/project/entitycache) for caching.

When app's [meta information](meta_information) is changed, you have to execute 
the following Drush commands (or do the equivalent through the web interface) 
to update the overview.

    drush search-api-disable paddle_apps
    drush search-api-enable paddle_apps
    drush search-api-index paddle_apps

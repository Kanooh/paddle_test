# Paddle developer documentation

Explains how to develop extra functionality, in the form of Paddle apps, for 
the Drupal 7 [Paddle distribution](https://www.drupal.org/project/paddle).

A Paddle app can be installed by non technical users with sufficient 
permissions. They log in to their Paddle website, navigate to the Paddle Store 
and choose an app to install.

## Development environment
You can use the same development environment the Paddle maintainers use. Years 
of testing several tools have lead to this selection:
- Integrated development environment: 
[PhpStorm](https://www.jetbrains.com/phpstorm/).
- Local web server: Paddle VM, built on the strong foundation of 
[Drupal VM](https://www.drupalvm.com/). Read [how to use Paddle VM](paddle_vm).

## Create a Paddle app
1. [Meta information](meta_information) (required)
2. [App configuration](configuration)
3. [Permissions](permissions)
4. Panes
5. [Common page type features](common_page_type_features)
6. Global and local content ... panelizer

## Install an app
1. [Update app overview](update_overview)
2. [Actual installation](app_installation) 


## Get your app on the Kanooh hosted platform

[Kanooh](http://www.kanooh.be/) offers Paddle websites as a service. Users 
don't have to know the ins and outs of Drupal. They get a login, a user 
friendly interface, support and automatic software updates.

To get your app in their Paddle Store:

1. [Publish the source code](kanooh/create_repository)
2. [Ensure code quality](kanooh/code_quality)
3. [Check browser compatibility](kanooh/browser_compatibility)
4. Write tests ... Simpletest, Selenium
5. Get your app approved ... Contact Kanooh, they'll review the app

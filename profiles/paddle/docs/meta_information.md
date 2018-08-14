# Paddle app meta information

Paddle apps are Drupal modules with some extra [info file](https://www.drupal.org/node/542202) 
properties. Those properties define how the app appears on the Paddle Store.

Paddle apps are inspired on the ones compatible with the 
[Apps module](https://www.drupal.org/project/apps) although the Paddle Apps 
module uses more object oriented code.

You need to define at least 1 property, anyone of them will do. The rest of 
them are optional.

## App basic info

    apps[name] = App name
    apps[description] = This app does this and that.
    apps[logo] = apps/logo.png
    apps[paddle][vendor] = Author company

`apps[name]` defaults to `name`, `apps[description]` defaults to `description`.

## App detail page info
The detail page of an app shows all of the above plus:

    apps[screenshots][] = apps/screenshots/0.png
    apps[screenshots][] = apps/screenshots/1.png
    apps[screenshots][] = apps/screenshots/2.png
    apps[faq][] = http://www.link.to/faq1
    apps[faq][] = http://www.link.to/faq2
    apps[paddle][vendor_link] = http://www.authorcompany.com
    apps[paddle][third_party_service] = boolean

`apps[paddle][third_party_service]` indicates whether the app user 3rd party 
services, like Google Analytics.

If you need a long description, just put it in an HTML file called 
`detailed_description.nl.html` in the `apps` directory in the main module 
directory.

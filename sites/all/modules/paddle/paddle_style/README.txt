Paddle Style
============

This module defines a "paddle_style" CTools plugin type and a number of plugins.
These plugins provide forms that allow site administrators to determine how the
front end should be styled.

Each plugin is responsible for a specific part of the frontend, and usually maps
to CSS properties. For example the "Font" plugin provides some form elements to
choose a font and font styling, and applies these to the CSS properties
"font-family", "font-size", "font-weight" etc.

Apart from providing CSS properties, the plugins can also run custom code on
each page, which allows to do whatever is necessary to get the frontend to look
like it should. For example some javascript could be included in the page, or
variables could be manipulated by changing the global $conf variable.

By itself this module and the plugins it provides are not really useful, they
are intended to be used in combination with other modules that provide a
framework for these plugins to work in. An example implementation is the Paddle
Themer module [1] which provides an interface that splits up a theme into
several "Style sets" ("Header settings", "Footer settings", ...) in which these
style plugins are integrated.

Other implementations could be made that for example allow to theme parts of the
front end using contextual links, in the Panels In Place Editor, ...


References
----------
[1] https://drupal.org/project/paddle_themer

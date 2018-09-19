# Code quality

## Standards
Code - PHP, CSS and Javascript - should comply with the 
[Drupal coding standards](https://www.drupal.org/coding-standards). Those can 
be easily checked with [Coder](https://www.drupal.org/project/coder). 

Don't manually adjust automatically generated code, like some 
[Features](https://www.drupal.org/project/features) exports, that doesn't 
comply with the coding standards. Use a 
[phpcs-ruleset.xml](https://pear.php.net/manual/en/package.php.php-codesniffer.annotated-ruleset.php) 
file to exclude them from coding standard checks.

## Write comments
[API documentation and comment standards](https://www.drupal.org/node/1354) 
help with documenting your code. It should be self-explanatory. Rather explain 
why things are done instead of what's done.

## Secure your code
Read this nice page about 
[writing secure Drupal code](https://www.drupal.org/writing-secure-code). 
You need to: 

- Use check functions on output to prevent cross site scripting attacks
- Use the database abstraction layer to avoid SQL injection attacks
- Use db_rewrite_sql to respect node access restrictions

## Make the interface translatable
All interface text should be 
[translatable with default Drupal methodologies](https://www.drupal.org/node/299085).

## Make the interface accessible
Strive for [WCAG 2.0](http://www.w3.org/TR/WCAG20/) A compliance. 
[Accessibility best practices](https://www.drupal.org/node/1637990) can help 
with that.

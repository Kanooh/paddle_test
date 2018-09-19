CONTENTS OF THIS FILE
---------------------

 - Introduction
 - Installation
 - Configuration


INTRODUCTION
------------

Reference Tracker keeps track of references between entities made through
fields. It does this by keeping a separate tracking table which is kept in sync
while your content changes. This allows for very fast retrieval of references
made from and to an entity.

What does it provide out of the box? Two blocks become available, displaying
references made to and from the entity that is being viewed. Although this may
come in handy for content editors, the real strength of this module is that it
can act as a data provider for other functionality such as cache expiration,
entity dependencies, etc.

In current state, this module is compatible with the following field types:

 - Taxonomy term reference (core)
 - File (core)
 - Long text (core)
 - Long text and summary (core)
 - Entity reference
 - Node reference and User reference (References)
 - Field collection
 - Paragraphs

Is your desired field type not in the list? Don't worry. This module was
designed to be easily extensible. All the necessary hooks to introduce new
field types are provided.


INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. See:
   https://drupal.org/documentation/install/modules-themes/modules-7
   for further information.


CONFIGURATION
-------------

The Reference Tracker can be configured at Admin » Configuration » Content
authoring » Reference Tracker (admin/config/content/reference-tracker).

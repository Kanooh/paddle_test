<?php

/**
 * @file
 * Page template for page__paddle_content_manager__node_iframe theme
 * implementation.
 */

?>

<!-- Output page title. -->
<h1 id="page-title"><?php print check_plain($page['content']['system_main']['#node']->title); ?></h1>

<!-- Output and render the content. -->
<?php print render($page['content']); ?>

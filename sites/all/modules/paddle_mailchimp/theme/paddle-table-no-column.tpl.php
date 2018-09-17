<?php
/**
 * @file
 * Template for a table 4/8 two column layout
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['middle']: The middle panel in the layout.
 */

?>

<table class="newsletter-layout-paddle_table_no_column row">
  <tr>
    <td class="wrapper last">

      <table class="twelve columns layout-column">
        <tr>
          <td>
            <?php if (!empty($content['middle'])) : print $content['middle']; endif; ?>
          </td>
          <td class="expander"></td>
        </tr>
      </table>

    </td>
  </tr>
</table>

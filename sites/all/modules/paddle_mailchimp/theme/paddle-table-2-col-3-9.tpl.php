<?php
/**
 * @file
 * Template for a table 3/9 two column layout
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['left']: The output of the left region in the layout.
 *   - $content['right']: The output of the right region in the layout.
 */

?>

<table class="newsletter-layout-paddle_table_2_col_3_9 row">
  <tr>
    <td class="wrapper">

      <table class="four columns layout-column">
        <tr>
          <td>
            <?php if (!empty($content['left'])) : print $content['left']; endif; ?>
          </td>
          <td class="expander"></td>
        </tr>
      </table>

    </td>
    <td class="wrapper last">

      <table class="eight columns layout-column">
        <tr>
          <td>
            <?php if (!empty($content['right'])) : print $content['right']; endif; ?>
          </td>
          <td class="expander"></td>
        </tr>
      </table>

    </td>
  </tr>
</table>

<?php
/**
 * @file
 * Default theme implementation to display the basic html structure of a single
 * Drupal page.
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width"/>
  <title>*|MC:SUBJECT|*</title>
</head>
<body>
  <?php foreach ($styles as $style): ?>
    <style type="text/css">
      <?php print $style; ?>
    </style>
  <?php endforeach; ?>

  <table class="body">
    <tr>
      <td class="center" align="center" valign="top">
        <center>

          <table class="row header">
            <tr>
              <td class="center" align="center">
                <center>

                  <table class="container">
                    <tr>
                      <td class="wrapper last">

                        <table class="twelve columns">
                          <tr>
                            <td class="text-pad">
                              <a href="<?php print $front_page; ?>"><strong><?php print $site_name; ?></strong></a> / *|MC:SUBJECT|*
                            </td>
                            <td class="expander"></td>
                          </tr>
                        </table>

                      </td>
                    </tr>
                  </table>

                </center>
              </td>
            </tr>
          </table>

          <?php if ($archive_view): ?>
          <table class="row archive">
            <tr>
              <td class="center" align="center">
                <center>

                  <table class="container">
                    <tr>
                      <td class="wrapper last">

                        <table class="twelve columns">
                          <tr>
                            <td class="center">
                              <center>
                                <?php print $archive_view; ?>
                              </center>
                            </td>
                            <td class="expander"></td>
                          </tr>
                        </table>

                      </td>
                    </tr>
                  </table>

                </center>
              </td>
            </tr>
          </table>
          <?php endif; ?>

          <table class="container content">
            <tr>
              <td>

                <?php if ($node_body): ?>
                <table class="row">
                  <tr>
                    <td class="wrapper last">

                      <table class="twelve columns">
                        <tr>
                          <td class="text-pad">
                            <?php print $node_body; ?>
                          </td>
                          <td class="expander"></td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>
                <?php endif; ?>

              </td>
            </tr>
          </table>

          <table class="container content campaign-content">
            <tr>
              <td>

                <?php print $content; ?>

              </td>
            </tr>
          </table>

          <table class="row footer">
            <tr>
              <td class="center" align="center">
                <center>

                  <table class="container">
                    <tr>

                      <td class="wrapper last">
                        <table class="twelve columns">
                          <tr>
                            <td class="text-pad">
                              <a href="<?php print $front_page; ?>"><strong><?php print $site_name; ?></strong></a>
                            </td>
                            <td class="expander"></td>
                          </tr>
                        </table>
                      </td>

                    </tr>
                  </table>

                  <table class="container">
                    <tr>

                      <td class="wrapper">
                        <table class="six columns">
                          <tr>
                            <td class="left-text-pad">
                              *|REWARDS|*
                            </td>
                            <td class="expander"></td>
                          </tr>
                        </table>
                      </td>

                      <td class="wrapper last">
                        <table class="six columns">
                          <tr>
                            <td class="right-text-pad right-text-align middle-text-align">
                              <a href="*|UNSUB|*"><?php print t('Unsubscribe'); ?></a>
                            </td>
                            <td class="expander"></td>
                          </tr>
                        </table>
                      </td>

                    </tr>
                  </table>

                </center>
              </td>
            </tr>
          </table>

        </center>
      </td>
    </tr>
  </table>
</body>
</html>

<?php

/**
 * @file
 * Contains
 *   \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRedirect\ConfigurePage\ConfigurePageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRedirect\ConfigurePage;

use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageContextualToolbarBase;

/**
 * The contextual toolbar for the configure page of the redirect paddlet.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Back" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCreateRedirect
 *   The "Create Redirect" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonImportRedirect
 *   The "Import Redirects" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonExportCSV
 *   The "Export CSV" button.
 */
class ConfigurePageContextualToolbar extends ConfigurePageContextualToolbarBase
{
    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        return array(
            'Back' => array(
                'title' => 'Back',
            ),
            'CreateRedirect' => array(
                'title' => 'Create Redirect',
            ),
            'ImportRedirect' => array(
                'title' => 'Import Redirects',
            ),
            'ExportCSV' => array(
                'title' => 'Export CSV',
            ),
        );
    }
}

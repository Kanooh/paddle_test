<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Redirect\RedirectTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\Redirect;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class RedirectTableRow
 *
 * @property string $from
 *   The base path of the redirect.
 * @property string $to
 *   The redirect path of the redirect.
 * @property string $status
 *   The status of the redirect.
 * @property int $rid
 *   The Redirect ID.
 * @property string $linkEdit
 *   The edit link of the redirect.
 * @property string $linkDelete
 *   The delete link of the redirect.
 */
class RedirectTableRow extends Row
{
    /**
     * The webdriver element of the redirect table row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new RedirectTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the redirect table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * Magic getter for the widget list item's properties.
     */
    public function __get($name)
    {
        $rid = $this->element->attribute('data-redirect-id');
        $redirect = redirect_load($rid, true);

        switch ($name) {
            case 'from':
                $url = url($redirect->source, array('alias' => true));
                $cell = $this->element->byXPath('.//a[contains(@href, "' . $url . '")]');
                return $cell->text();
                break;
            case 'to':
                // This is a workaround for a bug that the url itself is not
                // aliased properly. So we just check for the node title
                // (because that should be the alias anyway).
                $parts = explode('/', $redirect->redirect);
                if ($node = node_load($parts[1])) {
                    $url = strtolower($node->title);
                } else {
                    $url = $redirect->redirect;
                }

                $cell = $this->element->byXPath('.//a[contains(@href, "' . $url . '")]');
                return $cell->text();
                break;
            case 'status':
                if (empty($redirect->status_code)) {
                    $status = '301 Moved Permanently';
                } else {
                    $redirect_option = redirect_status_code_options();
                    $status = $redirect_option[$redirect->status_code];
                }

                $cell = $this->element->byXPath('.//td[text()="' . $status . '"]');
                return $cell->text();
                break;
            case 'linkEdit':
                return $this->element->byXPath('.//td//a[@title="Edit the redirect."]');
                break;
            case 'linkDelete':
                return $this->element->byXPath('.//td//a[@title="Delete the redirect."]');
                break;
            case 'rid':
                return $rid;
                break;
        }
    }
}

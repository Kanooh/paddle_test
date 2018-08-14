<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentitiesTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\SocialIdentities;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class SocialIdentitiesTableRow
 *
 * @property string $name
 *   Name of the social identity.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 *   The social identity's edit link.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 *   The social identity's delete link.
 * @property int $psiid
 *   The social identity ID.
 */
class SocialIdentitiesTableRow extends Row
{
    /**
     * The webdriver element of the social identities table row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new SocialIdentitiesTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the social identity table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * Magic getter for the social identity list item's properties.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'name':
                $cell = $this->element->byXPath('.//td[contains(@class, "identity-name")]');
                return $cell->text();
                break;
            case 'linkEdit':
                return $this->element->byXPath('.//td[contains(@class, "identity-edit")]//a');
                break;
            case 'linkDelete':
                return $this->element->byXPath('.//td[contains(@class, "identity-delete")]//a');
                break;
            case 'psiid':
                return $this->element->attribute('data-identity-id');
                break;
        }
    }
}

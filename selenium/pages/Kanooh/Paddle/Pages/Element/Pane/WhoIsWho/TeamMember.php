<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\WhoIsWho\TeamMember.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\WhoIsWho;

/**
 * Represents a team member on a who is who pane.
 *
 * @property string $name
 * @property string $function
 * @property string $email
 * @property string $phone
 * @property string $mobile
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $featuredImage
 */
class TeamMember
{
    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * {@inheritdoc}
     */
    public function __construct(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'name':
                $xpath = './/h3[contains(@class, "team-member-name")]';
                return $this->element->byXPath($xpath)->text();
                break;

            case 'function':
                $xpath = './/div[contains(@class, "team-member-function")]';
                return $this->element->byXPath($xpath)->text();
                break;

            case 'email':
                $xpath = './/div[contains(@class, "team-member-email")]';
                return $this->element->byXPath($xpath)->text();
                break;

            case 'phone':
                $xpath = './/div[contains(@class, "team-member-phone")]';
                return $this->element->byXPath($xpath)->text();
                break;

            case 'mobile':
                $xpath = './/div[contains(@class, "team-member-mobile")]';
                return $this->element->byXPath($xpath)->text();
                break;

            case 'featuredImage':
                $xpath = './/div[contains(@class, "team-member-featured-image")]//img';
                return $this->element->byXPath($xpath);
                break;
        }

        throw new \Exception("Property with name $property not defined");
    }

    /**
     * Clicks on the element.
     */
    public function click()
    {
        $this->element->click();
    }
}

<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewPageFilterForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class representing the apps overview page filter form.
 *
 * @property Checkbox $levelFree
 *   Checkbox for the "free" value of the level filter.
 * @property Checkbox $levelExtra
 *   Checkbox for the "extra" value of the level filter.
 * @property RadioButton $statusAll
 *   Radio button for the "all" value of the status filter.
 * @property RadioButton $statusDisabled
 *   Radio button for the "disabled" value of the status filter.
 * @property RadioButton $statusEnabled
 *   Radio button for the "enabled" value of the status filter.
 * @property Checkbox $thirdParty
 *   Checkbox for the third-party subscription filter.
 * @property Checkbox $vendorKanooh
 *   Checkbox for the "Kanooh" value of the vendor filter.
 */
class AppsOverviewPageFilterForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        // Detect status filter values.
        if (strpos($property, 'status') === 0) {
            $value = strtolower(substr($property, 6));
            return $this->radioButton('status', $value);
        }

        // Detect level filter values.
        if (strpos($property, 'level') === 0) {
            $value = strtolower(substr($property, 5));
            return $this->checkbox('level[' . $value . ']');
        }

        // Loop through the other known properties.
        switch ($property) {
            case 'thirdParty':
                return $this->checkbox('third_party');
                break;
            case 'vendorKanooh':
                return $this->vendorCheckbox('kañooh');
                break;
        }

        // If we still haven't found the property, it's not defined.
        throw new FormFieldNotDefinedException($property);
    }

    /**
     * Generates an XPath selector for an input element.
     *
     * @param string $name
     *   Name attribute of the input element.
     * @param string $value
     *   Value attribute of the input element. (Optional)
     *
     * @param string
     *   XPath selector for the input element.
     */
    protected function inputXPathSelector($name, $value = '')
    {
        $xpath = './/input[@name="' . $name . '"]';
        if (!empty($value)) {
            $xpath .= '[@value="' . $value . '"]';
        }
        return $xpath;
    }

    /**
     * Fetches a radio button from the form.
     *
     * @param string $name
     *   Name attribute of the radio button.
     * @param string $value
     *   Value attribute of the radio button. (Optional)
     *
     * @return RadioButton
     *   Radio button that has the specified name and value.
     */
    protected function radioButton($name, $value = '')
    {
        $element = $this->element->byXPath($this->inputXPathSelector($name, $value));
        return new RadioButton($this->webdriver, $element);
    }

    /**
     * Fetches a checkbox from the form.
     *
     * @param string $name
     *   Name attribute of the checkbox.
     *
     * @return CheckBox
     *   Checkbox that has the specified name.
     */
    protected function checkbox($name)
    {
        $element = $this->element->byXPath($this->inputXPathSelector($name));
        return new Checkbox($this->webdriver, $element);
    }

    /**
     * Fetches a checkbox of the vendor filter.
     *
     * @param string $value
     *   Value attribute of the checkbox.
     *
     * @return Checkbox
     *   Checkbox corresponding with the specified name.
     */
    public function vendorCheckbox($value)
    {
        return $this->checkbox('vendor[' . $value . ']');
    }

    /**
     * Fetched the facet count of a specific filter value.
     *
     * @param \Kanooh\Paddle\Pages\Element\Form\FormField $filter_input
     *   Radio button or checkbox of the filter value.
     *
     * @return int
     *   Facet count for the filter value.
     */
    public function facetCount($filter_input)
    {
        $filter_input_id = $filter_input->getWebdriverElement()->attribute('id');

        $facet_xpath = './/label[@for="' . $filter_input_id . '"]//span[contains(@class, "facet-number")]';
        $facet = $this->element->byXPath($facet_xpath);

        return (int) $facet->attribute('data-number');
    }

    /**
     * Returns all possible vendor values.
     *
     * @return string[]
     *   Array of vendor value strings.
     */
    public function getVendorValues()
    {
        $vendors = array();

        $xpath = './/div[contains(@class, "form-item-vendor")]//input[@type="checkbox"]';
        $criteria = $this->element->using('xpath')->value($xpath);
        $elements = $this->element->elements($criteria);

        /* @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $elements */
        foreach ($elements as $element) {
            $vendors[] = $element->attribute('value');
        }

        return $vendors;
    }
}

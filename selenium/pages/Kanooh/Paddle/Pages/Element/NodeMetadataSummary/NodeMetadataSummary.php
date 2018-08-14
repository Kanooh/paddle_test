<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\NodeSummary\NodeSummary.
 */

namespace Kanooh\Paddle\Pages\Element\NodeMetadataSummary;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Element;

class NodeMetadataSummary extends Element
{
    protected $xpathSelector = '//div[@id="node-metadata"]';

    /**
     * Gets a metadata group.
     *
     * @param string $group_name
     *   Name of the metadata group, as defined in hook_node_metadata_groups(),
     *   and cleaned by drupal_html_class(). For example, my_group would
     *   be my-group.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getMetadataGroup($group_name)
    {
        // See http://stackoverflow.com/questions/8808921/selecting-a-css-class-with-xpath.
        $group_selector = '//div[contains(concat(" ", normalize-space(@class), " "), " node-metadata-group-' .
            $group_name . ' ")]//ul';

        $xpath = $this->xpathSelector . $group_selector;
        return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
    }

    /**
     * Gets a metadata item as a Selenium element.
     *
     * @param string $group_name
     *   Name of the metadata group the item belongs to.
     * @param string $item_name
     *   Name of the metadata item, as defined in
     *   hook_node_metadata_GROUP_items() and cleaned by drupal_html_class. For
     *   example, my_item would become my-item.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getMetadataItem($group_name, $item_name)
    {
        $group = $this->getMetadataGroup($group_name);

        // See http://stackoverflow.com/questions/8808921/selecting-a-css-class-with-xpath.
        $xpath = '//li[contains(concat(" ", normalize-space(@class), " "), " node-metadata-item-' .
            $item_name . ' ")]';

        return $group->element($group->using('xpath')->value($xpath));
    }

    /**
     * Gets an associative array with label and value of a metadata item.
     *
     * @param string $group_name
     *   Name of the metadata group the item belongs to.
     * @param string $item_name
     *   Name of the metadata item, as defined in
     *   hook_node_metadata_GROUP_items() and cleaned by drupal_html_class. For
     *   example, my_item would become my-item.
     *
     * @return array
     *   Associative array with "label", "label_raw" "value", and "value_raw"
     *   keys.
     */
    public function getMetadata($group_name, $item_name)
    {
        $metadata = array();

        // Get the metadata item.
        $item = $this->getMetadataItem($group_name, $item_name);

        // Get the label span.
        $label_element = $item->element($item->using('xpath')->value('.//span[@class="label"]'));

        // Store the "raw" label, and a processed label that doesn't have a
        // trailing space or colon.
        $metadata['label_raw'] = $label_element->text();
        $metadata['label'] = rtrim($metadata['label_raw'], ': ');

        // Get the value span.
        $value_element = $item->element($item->using('xpath')->value('.//span[@class="value"]'));

        // Store the "raw" value from the data attribute, and the clean value
        // from the text.
        $metadata['value_raw'] = $value_element->attribute('data-raw');
        $metadata['value'] = $value_element->text();

        return $metadata;
    }

    /**
     * Shows all metadata if the summary is collapsible.
     */
    public function showAllMetadata()
    {
        // Try to find a legend inside a collapsed fieldset in the node summary.
        $legend_xpath = $this->getXPathSelector() .
            '//fieldset[contains(@class, "collapsed")]/legend//a';
        $criteria = $this->webdriver->using('xpath')->value($legend_xpath);
        $elements = $this->webdriver->elements($criteria);

        // If we found one, click it so the fieldset opens.
        if (!empty($elements)) {
            // Get the last metadata group in the fieldset.
            $groups_xpath = $this->getXPathSelector() .
                '//fieldset[contains(@class, "collapsed")]//div[contains(@class, "node-metadata-summary")]';
            $groups = $this->webdriver->elements($this->webdriver->using('xpath')->value($groups_xpath));
            /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $last_group */
            $last_group = end($groups);

            // Click the expand link.
            /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $link */
            $link = $elements[0];
            $this->webdriver->moveto($link);
            $link->click();

            // Wait until the last group is visible.
            $callable = new SerializableClosure(
                function () use ($last_group) {
                    if ($last_group->displayed()) {
                        return true;
                    }
                }
            );
            $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
        }
    }
}

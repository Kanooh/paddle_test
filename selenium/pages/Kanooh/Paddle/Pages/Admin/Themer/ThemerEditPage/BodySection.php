<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\BodySection.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;

/**
 * Class BodySection
 *
 * @package Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage
 *
 * @property Checkbox $showBreadcrumbTrailCheckboxForBasicPages
 * @property Checkbox $showBreadcrumbTrailCheckboxForLandingPages
 * @property Checkbox $showBreadcrumbTrailCheckboxForOverviewPages
 * @property Checkbox $showBreadcrumbTrailCheckboxForOtherPages
 * @property Checkbox $displayPaneTopAsH2
 */
class BodySection extends Section
{
    public function __get($name)
    {
        switch ($name) {
            case 'showBreadcrumbTrailCheckboxForBasicPages':
                return $this->getShowBreadcrumbTrailCheckboxByContentType('basic_page');
            case 'showBreadcrumbTrailCheckboxForLandingPages':
                return $this->getShowBreadcrumbTrailCheckboxByContentType('landing_page');
            case 'showBreadcrumbTrailCheckboxForOverviewPages':
                return $this->getShowBreadcrumbTrailCheckboxByContentType('paddle_overview_page');
            case 'showBreadcrumbTrailCheckboxForOtherPages':
                $element = $this->webdriver->byXPath(
                    './/div[@id="paddle-style-plugin-instance-show-breadcrumbs-for-other-pages"]//input'
                );
                return new Checkbox($this->webdriver, $element);
            case 'displayPaneTopAsH2':
                $element = $this->webdriver->byXPath('.//div[@id="paddle-style-plugin-instance-display-pane-top-as-h2"]//input');
                return new Checkbox($this->webdriver, $element);
        }

        return parent::__get($name);
    }

    /**
     * Gets the breadcrumb checkbox related to the given content type.
     *
     * @param string $content_type
     *   Content type machine name.
     *
     * @return \Kanooh\Paddle\Pages\Element\Form\Checkbox
     */
    public function getShowBreadcrumbTrailCheckboxByContentType($content_type)
    {
        $element = $this->webdriver->byXPath('.//div[@id="paddle-style-plugin-instance-show-breadcrumbs-for-' . str_replace('_', '-', $content_type) . '"]//input');
        return new Checkbox($this->webdriver, $element);
    }

    /**
     * Checks if the next level checkboxes are all unchecked.
     *
     * @return bool
     *   Return true if all checkboxes are unchecked, false otherwise.
     */
    public function nextLevelCheckboxesUnchecked()
    {
        foreach (node_type_get_types() as $type) {
            $next_level = new Checkbox(
                $this->webdriver,
                $this->webdriver->byName('body[breadcrumbs_navigation][sections][form_elements][show_level_below_' . $type->type .  '][show_level_below_' . $type->type . ']')
            );

            if ($next_level->isChecked()) {
                return false;
                break;
            };
        }

        return true;
    }
}

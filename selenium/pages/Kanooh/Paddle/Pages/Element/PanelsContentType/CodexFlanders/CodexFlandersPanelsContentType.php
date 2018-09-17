<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\CodexFlanders\CodexFlandersPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\CodexFlanders;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SectionedPanelsContentType;

/**
 * The 'CodexFlanders' Panels content type.
 */
class CodexFlandersPanelsContentType extends SectionedPanelsContentType
{

    /**
    * {@inheritdoc}
    */
    const TYPE = 'codex_flanders';

    /**
    * {@inheritdoc}
    */
    const TITLE = 'Codex Flanders';

    /**
    * {@inheritdoc}
    */
    const DESCRIPTION = 'Add codices';

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $this->fillInSections();
    }

    /**
    * {@inheritdoc}
    */
    public function getForm(Element $element = null)
    {
        if (!isset($this->form)) {
            $this->form = new CodexFlandersPanelsContentTypeForm(
                $this->webdriver,
                $this->webdriver->byXpath('//form[contains(@id, "paddle-codex-flanders-codex-flanders-content-type-edit-form")]')
            );
        }

        return $this->form;
    }
}

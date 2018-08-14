<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Element\Toolbar;

/**
 * Base class for contextual toolbars.
 */
abstract class ContextualToolbar extends Toolbar
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[@id="block-paddle-contextual-toolbar-contextual-toolbar"]';

    /**
     * {@inheritdoc}
     */
    protected $xpathSelectorLinkList = '//ul[@id="contextual-actions-list"]';
}

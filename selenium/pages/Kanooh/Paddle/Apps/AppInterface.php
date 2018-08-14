<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Apps\App.
 */

namespace Kanooh\Paddle\Apps;

/**
 * Interface for declaring Apps.
 */
interface AppInterface
{
    /**
     * Returns the app ID.
     *
     * This is a lowercase hyphen-separated version of the app name, as used in
     * the CSS class on the app overview.
     *
     * @return string
     *   The app ID.
     */
    public function getId();

    /**
     * Returns the module name.
     *
     * @return string
     *   The module name.
     */
    public function getModuleName();

    /**
     * Returns whether or not the app is configurable.
     *
     * @return bool
     */
    public function isConfigurable();
}

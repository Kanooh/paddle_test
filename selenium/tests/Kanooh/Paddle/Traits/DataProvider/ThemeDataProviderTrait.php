<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Traits\DataProvider\ThemeDataProviderTrait.
 */

namespace Kanooh\Paddle\Traits\DataProvider;

/**
 * Class ThemeDataProviderTrait.
 */
trait ThemeDataProviderTrait
{
    /**
     * Data provider for the themes to test.
     *
     * @return array
     *   An array of theme names and the modules that needs to be enabled, if any.
     */
    public function themeDataProvider()
    {
        return array(
            array('vo_standard'),
            array('vo_strict'),
            array('go_theme', array('paddle_go_themes')),
            array('kanooh_theme_v2'),
        );
    }
}

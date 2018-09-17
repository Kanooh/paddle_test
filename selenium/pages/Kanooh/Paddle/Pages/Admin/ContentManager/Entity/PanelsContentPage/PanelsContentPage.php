<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Entity\PanelsContentPage\PanelsContentPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Entity\PanelsContentPage;

use Kanooh\Paddle\Pages\Element\Display\ContentRegionDisplayPage;

/**
 * The configuration settings of the Paddle Content Region module.
 */
class PanelsContentPage extends ContentRegionDisplayPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/entity/%/panels_content';
}

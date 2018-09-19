<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\MenuManager\EditPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\MenuManager;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The page containing the menu link edit form in the menu manager.
 *
 * This form is usually presented in a modal dialog, but we can access the nojs
 * version of the form directly. This is very handy for example to quickly
 * check if a menu item exists or change its settings.
 *
 * @todo Add the Form that is present on this page so we can interact with it.
 */
class EditPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/structure/menu_manager/nojs/%/%/edit';
}

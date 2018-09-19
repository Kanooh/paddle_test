<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\CleanUpService.
 */

namespace Kanooh\Paddle\Utilities;

use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Utilities\MailChimpService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Utility class to help clean-up content, terms, users, etc....
 */
class CleanUpService
{
    /**
     * @var MailChimpService
     */
    protected $mailChimpService;

    /**
     * Constructs a CleanUpService object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $drupal = new DrupalService();
        $drupal->bootstrap($webdriver);

        $this->mailChimpService = new MailChimpService($webdriver, getenv('mailchimp_api_key'));
    }

    /**
     * Delete all entities of a certain type and optionally bundle.
     *
     * @param string $entity_type
     *   The entity type machine name.
     * @param bool|string $entity_bundle
     *   The entity bundle machine name. Fallback to all bundles.
     * @param array $ids
     *   Array holding the ids of the entities that should be deleted.
     * @param array $exclude_bundles
     *   Array containing the names of the bundles for which the entities should
     *   not be deleted.
     */
    public function deleteEntities($entity_type, $entity_bundle = false, $ids = array(), $exclude_bundles = array())
    {
        // When deleting nodes, expire module tries to clear reference caches
        // which does not seem to work waterproof.
        $original = variable_get('expire_node_reference_pages', EXPIRE_NODE_REFERENCE_PAGES);
        variable_set('expire_node_reference_pages', EXPIRE_NODE_REFERENCE_PAGES);

        // If no IDs are specified get all the IDs.
        if (!count($ids)) {
            // Build a EntityFieldQuery.
            $query = new \EntityFieldQuery();
            $query->entityCondition('entity_type', $entity_type);

            if ($entity_bundle) {
                $query->entityCondition('bundle', $entity_bundle);
            }

            if (count($exclude_bundles)) {
                $query->entityCondition('bundle', $exclude_bundles, 'NOT IN');
            }

            $results = $query->execute();

            $ids = empty($results[$entity_type]) ? array() : array_keys($results[$entity_type]);
        }

        if (count($ids)) {
            // Newsletter pages need some extra care.
            if (in_array($entity_bundle, array('newsletter', false)) && !in_array('newsletter', $exclude_bundles)) {
                // Remove the related MailChimp campaigns before deleting the
                // newsletter pages to avoid hitting the limit of maximum
                // 32,000 campaigns per MailChimp account.
                $nodes = node_load_multiple($ids, array('type' => 'newsletter'));
                foreach ($nodes as $node) {
                    $campaign_id = $this->mailChimpService->getCampaignIdFromNewsletter($node);
                    if ($campaign_id !== false) {
                        // Ignore problems with automatically trying to delete
                        // MailChimp campaigns from within tests because it's
                        // not the responsibility of this code to ensure the
                        // campaign deletion functionality always works.
                        $throw_exceptions = false;
                        $this->mailChimpService->deleteCampaign($campaign_id, $throw_exceptions);
                    }
                }
            }

            // Delete the entities.
            entity_delete_multiple($entity_type, $ids);
        }

        variable_set('expire_node_reference_pages', $original);
    }

    /**
     * Delete all user roles that were not created by Drupal or Paddle.
     */
    public function deleteCustomUserRoles()
    {
        if (!module_exists('paddle_protected_content')) {
            // Include the Paddle Protected Content module file to be able to
            // call paddle_protected_content_custom_user_roles().
            module_load_include('module', 'paddle_protected_content');
        }

        foreach (array_keys(paddle_protected_content_custom_user_roles()) as $rid) {
            user_role_delete($rid);
        }
    }

    /**
     * Delete all users that were not created by Drupal or Paddle.
     */
    public function deleteCustomUsers()
    {
        $users = entity_load('user');

        // @TODO: We need a list of Paddle users somewhere.
        $paddle_users = array(
            'demo_chief_editor',
            'demo_editor',
            'demo',
            'demo_read_only'
        );

        foreach ($users as $user) {
            // The users with uid 0 and uid 1 are the anonymous and admin users respectively.
            if (!in_array($user->name, $paddle_users) && $user->uid != 1 && $user->uid != 0) {
                user_delete($user->uid);
            }
        }
    }

    /**
     * Deletes all menu items of the paddle menus.
     */
    public function deleteMenuItems()
    {
        $paddle_menus = array(
          MenuOverviewPage::FOOTER_MENU_NAME,
          MenuOverviewPage::TOP_MENU_NAME,
          MenuOverviewPage::DISCLAIMER_MENU_NAME,
          MenuOverviewPage::MAIN_MENU_NAME
        );

        foreach ($paddle_menus as $menu_name) {
            menu_delete_links($menu_name);
        }
    }

    /**
     * Deletes all contexts.
     */
    public function deleteContexts()
    {
        $contexts = context_load();
        foreach ($contexts as $context) {
            context_delete($context);
        }
    }
}

<?php
/**
 * @file
 * API documentation for the search engine implementations.
 */

/**
 * Defines search engines which can be used.
 *
 * These search engines can be enabled and disabled in the themer.
 *
 * Returns an array consisting of the machine name of the search engine, which
 * is the key of the array. It has 3 values:
 * - label: The label that will be used in the themer.
 * - text: The text that is shown behind the radio buttons in the front end.
 *   This can be adjusted in the themer.
 * - enabled: Whether the search engine should be enabled by default or not.
 * - weight: Used to determine the relative order in which the search engine
 *   will be displayed.
 */
function hook_paddle_search_engines() {
  return array(
    'engine_machine_name' => array(
      'label' => t('Label shown in the themer'),
      'text' => t('Text shown behind the radio button'),
      'enabled' => TRUE,
      'weight' => 0,
    ),
  );
}

/**
 * Submit handler for each specific search engine.
 *
 * The module containing the new search engine should implement this hook and
 * specify what should happen when its search engine has been selected for use.
 */
function hook_paddle_search_SEARCH_ENGINE_ID_form_submit($form, &$form_state) {

}

/**
 * Defines contexts for placing additional panes on pages.
 *
 * This provides a way for apps to inject panes on existing pages. A special
 * "Additional panes" pane which is placed on the page is assigned a certain
 * 'context'. Apps can target these contexts and supply panes for it.
 *
 * For every context that is supplied in this hook there should be a matching
 * "Additional panes" pane created which is configured for the context.
 *
 * For example, an app that extends the search functionality might want to place
 * search facets panes on all search results pages. We can put the "Additional
 * panes" pane on all the search results pages and give it the context
 * 'search_results'. Now our app can supply a number of search facet panes for
 * the 'search_results' context, and they will be shown there.
 *
 * @see hook_paddle_core_additional_panes()
 *
 * @return array
 *   An associative array, keyed by context name. Each element is an associative
 *   array with the following keys:
 *   - 'name': The human readable context name.
 *   - 'regions': An associative array of regions to which the context applies,
 *     keyed on machine name, with the human readable name as value. Mind that
 *     these do not map to actual regions within a Panels layout. These contexts
 *     can be used across different pages and variants which can all have a
 *     different Panels layout. It is intended to target different positions
 *     within the same page. Try to use generic semantic naming that makes sense
 *     in your specific content like 'article', 'header', 'sidebar', 'aside',
 *     'main_content' etc.
 */
function hook_paddle_core_additional_panes_contexts() {
  return array(
    'search_results' => array(
      'name' => t('Search results'),
      'regions' => array(
        'sidebar' => t('Sidebar'),
        'results_top' => t('Above search results'),
        'results_bottom' => t('Below search results'),
      ),
    ),
  );
}

/**
 * Returns panes to show on a certain region within a certain context.
 *
 * This hook allows apps to inject panes on pages that have the "Additional
 * panes" pane. Each "Additional panes" pane is linked to an arbitrary 'context'
 * and a region within the page to expose these to apps.
 *
 * For example a search results page can define a 'search_results' context and
 * two regions: 'sidebar' and 'results'. An app can implement this hook to add
 * some search facet panes to the sidebar.
 *
 * @see hook_paddle_core_additional_panes_contexts()
 *
 * @param string $context
 *   A context machine name from hook_paddle_core_additional_panes_contexts().
 * @param string $region
 *   A region from hook_paddle_core_additional_panes_contexts().
 *
 * @return array
 *   An array of pane data. Each item is an associative array with keys:
 *   - 'pane': The pane to render.
 *   - 'weight': The position of the pane within the "Additional panes" pane.
 */
function hook_paddle_core_additional_panes($context, $region) {
  $panes = array();

  if ($context == 'search_results' && $region == 'sidebar') {
    $panes[] = array(
      0 => array(
        'pane' => panels_new_pane('paddle_search_facets_overview', 'paddle_search_facets_overview', TRUE),
        'weight' => 0,
      ),
      1 => array(
        'pane' => panels_new_pane('paddle_search_facets_tags', 'paddle_search_facets_tags', TRUE),
        'weight' => 1,
      ),
    );
  }

  return $panes;
}

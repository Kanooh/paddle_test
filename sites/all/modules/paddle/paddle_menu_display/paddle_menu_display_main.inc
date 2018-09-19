<?php
/**
 * @file
 * Generates the menu display output. Contains the main data generation function
 * and one helper function.
 */

/**
 * The main function generation the menu display HTML.
 *
 * @param PaddleMenuDisplay $menu_display
 *   The menu display object.
 * @param array $menu_tree
 *   The menu items structured in an array to display.
 *
 * @return string
 *   The HTML of the menu to be used in the block.
 */
function paddle_menu_display_generate_menu($menu_display, $menu_tree) {
  $menu_items = array();
  $leaf_counter = 0;

  if (count($menu_tree) == 0) {
    return '';
  }

  foreach ($menu_tree as $mlid => $menu_item) {
    // No links handling.
    $no_link = in_array($menu_item['mlid'], $menu_display->no_links);

    $menu_items[$mlid] = array(
      'menu_item' => $menu_item,
    );

    $menu_items[$mlid]['depth'] = $menu_item['depth'];
    $menu_items[$mlid]['link'] = !$no_link ? $menu_item['href'] : '#';
    $menu_items[$mlid]['text'] = !empty($menu_item['title']) ? $menu_item['title'] : t('Unknown title');

    $li_class = 'menu-item';
    $a_class = '';

    if (isset($menu_display->a_class) && strlen($menu_display->a_class) > 0) {
      paddle_menu_display_add_class($a_class, $menu_display->a_class);
    }

    if ($no_link) {
      paddle_menu_display_add_class($a_class, 'no-link');
    }

    if (isset($menu_display->li_class) && strlen($menu_display->li_class) > 0) {
      paddle_menu_display_add_class($li_class, $menu_display->li_class);
    }

    // Add the active and/or active-trail classes.
    if (_paddle_menu_display_me_or_children_in_active_trail($menu_item, $menu_display->active_trail)) {
      if (!empty($menu_display->add_active_class) && $menu_item['mlid'] == $menu_display->getActiveMlid()) {
        paddle_menu_display_add_class($li_class, 'active');
      }
      if (!empty($menu_display->add_active_trail)) {
        paddle_menu_display_add_class($li_class, 'active-trail');
      }
    }

    if (isset($menu_display->add_odd_even) && $menu_display->add_odd_even) {
      if (($leaf_counter + 1) % 2 == 0) {
        paddle_menu_display_add_class($li_class, 'even');
      }
      else {
        paddle_menu_display_add_class($li_class, 'odd');
      }
    }

    if (isset($menu_display->add_level_class) && $menu_display->add_level_class) {
      paddle_menu_display_add_class($li_class, 'level-' . $menu_item['depth']);
    }

    if (isset($menu_display->add_item_specific_class) && $menu_display->add_item_specific_class) {
      paddle_menu_display_add_class($li_class, 'item-' . $leaf_counter);
    }
    if ($menu_item['has_children'] == 1) {
      paddle_menu_display_add_class($li_class, 'has-children');
    }

    $should_display_below = $menu_item['depth'] + 1 <= $menu_display->toLevel();
    $should_display_below = $should_display_below && (!$menu_display->isFollowingActiveTrail() || _paddle_menu_display_me_or_children_in_active_trail($menu_item, $menu_display->active_trail));
    $should_display_below = $should_display_below && $menu_item['has_children'] == 1;
    if ($should_display_below) {
      $menu_items[$mlid]['content'] = paddle_menu_display_generate_menu($menu_display, $menu_item['below']);
    }
    paddle_menu_display_add_class($a_class, 'mlid-' . $mlid);
    $menu_items[$mlid]['li_class'] = $li_class;
    $menu_items[$mlid]['a_class'] = $a_class;
    $leaf_counter++;
  }
  $ul_class = $menu_display->ul_class;
  paddle_menu_display_add_class($ul_class, 'level-' . $menu_item['depth']);
  $data = array(
    'items' => $menu_items,
    'menu_display' => $menu_display,
    'ul_class' => $ul_class,
  );

  drupal_alter('paddle_menu_display_menu_items', $data['items'], $menu_display);

  return theme('paddle_menu_display_menu_items', $data);
}

/**
 * Calculates options for the current display once.
 *
 * @param object $menu_display
 *   The menu display object for which the options will be set.
 *
 * Calculates different settings and sets variables needed to generate the menu
 * display like the root path, the from level, to level, active trail mlids...
 */
function paddle_menu_display_setup_current_display(&$menu_display) {
  $active_item = strlen($menu_display->root_path) > 0 ? array('href' => $menu_display->root_path) : menu_get_item();
  $menu_display->active_trail = array();
  $menu_display->root_path = $active_item['href'];

  // Handle 404. We want to display the menu from "admin" root item point
  // of view.
  if (drupal_get_http_header('status') == '404 Not Found' && !empty($menu_display->page_not_found_root_path)) {
    $menu_display->root_path = $menu_display->page_not_found_root_path;
    // Add the page_not_found_root_path in the active trail.
    $menu_display->active_trail[] = _paddle_menu_display_get_mlid_by_href($menu_display->page_not_found_root_path);
  }

  // Handle 403. We want to display the menu from "admin" root item point
  // of view.
  if (drupal_get_http_header('status') == '403 Forbidden' && !empty($menu_display->access_denied_root_path)) {
    $menu_display->root_path = $menu_display->access_denied_root_path;
    // Add the page_not_found_root_path in the active trail.
    $menu_display->active_trail[] = _paddle_menu_display_get_mlid_by_href($menu_display->access_denied_root_path);
  }

  // Active trail.
  if ($menu_link = menu_link_get_preferred($active_item['href'], $menu_display->menu_name)) {
    $menu_display->active_trail = paddle_menu_display_get_active_trail($menu_link);
  }

  // @todo - optimize this part - use the $menu_link var from above and use
  // router_path to determine of the current menu item is node.
  $current_item = menu_get_item();
  $current_item = $current_item['href'];

  if (isset($menu_display->cont_types_roots) && count($menu_display->cont_types_roots) && strpos($current_item, 'node') === 0) {
    $parts = explode('/', $current_item);
    if (isset($parts[1]) && is_int($parts[1])) {
      $node = node_load($parts[1]);
      if ($node && isset($menu_display->cont_types_roots[$node->type])) {
        $menu_display->active_trail[] = _paddle_menu_display_get_mlid_by_href($menu_display->cont_types_roots[$node->type]);
      }
    }
  }
  // @todo - this can be optimized a lot - remove the db_query.
  if (isset($menu_display->path_roots)) {
    foreach ($menu_display->path_roots as $path_root) {
      $path = $path_root['path'];
      $path = str_replace('/', '\/', $path);
      $path = str_replace('?', '\?', $path);
      $path = str_replace('.', '\.', $path);
      $path = str_replace('&', '\&', $path);
      $path = str_replace('+', '\+', $path);
      $path = str_replace('{', '\{', $path);
      $path = str_replace('}', '\}', $path);
      $path = str_replace('[', '\[', $path);
      $path = str_replace(']', '\]', $path);
      $pattern = '/' . str_replace('%', '(.+)', $path) . '/i';
      preg_match($pattern, $current_item, $matches);
      if ($matches && isset($matches[0]) && $current_item == $matches[0]) {
        if (isset($path_root['root_item'])) {
          $menu_display->active_trail = array();
          // Get the mlid of the root item path.
          $mlid = _paddle_menu_display_get_mlid_by_href($path_root['root_item']);
          // Get the parent mlids to add them to the top of the active trail.
          $record = db_query("SELECT * FROM {menu_links} WHERE mlid = :mlid", array(':mlid' => $mlid))->fetchAssoc();
          for ($i = 1; $i <= MENU_MAX_DEPTH; $i++) {
            if ($record["p$i"]) {
              $menu_display->active_trail[] = $record["p$i"];
            }
          }
          $menu_display->active_trail[] = $mlid;
          break;
        }
      }
    }
  }
}

/**
 * Builds the menu items tree which will be rendered.
 *
 * @param object $menu_display
 *   The menu display object for which the options will be set.
 * @param array $menu_tree
 *   The part of menu tree to build.
 * @param int $plid
 *   The parent menu item of the menu part to build.
 * @param int $depth
 *   The depth of the menu items which should be in the menu.
 * @param int $reset
 *   Indicates if the cache should be reset or not.
 */
function paddle_menu_display_build_menu($menu_display, &$menu_tree, $plid, $depth, $reset = FALSE) {
  // Hoist a flag if we are recursing.
  $recursing = $plid != -1;

  // Display nothing if we are following the active trail and the current menu
  // item is not in the active trail.
  if ($menu_display->isFollowingActiveTrail() && !$menu_display->isInActiveTrail()) {
    return;
  }

  // Display nothing if we have descended too deep.
  if ($menu_display->toLevel() < $depth) {
    return;
  }

  // Try to retrieve the menu display from the cache. This is only needed if we
  // are not recursing, since if we are recursing we are already dedicated to
  // building a new cache entry.
  $cid = paddle_menu_display_generate_cid($menu_display);
  $menu_tree = !$recursing ? paddle_menu_display_cache_retrieve($cid) : array();

  if (!$menu_tree || $reset) {
    $menu_tree = array();
    $query = db_select('menu_links', 'ml');
    $query->leftjoin('menu_router', 'mr', 'mr.path = ml.router_path');
    $query->fields('ml', array('mlid'))
      ->condition('menu_name', $menu_display->menu_name);
    if ($plid >= 0) {
      $query->condition('plid', $plid);
    }
    if ($menu_display->isFollowingActiveTrail()) {
      // Limit the menu items on active trail.
      foreach ($menu_display->active_trail as $at_depth => $mlid) {
        $at_depth++;
        if ($at_depth < $depth) {
          $query->condition('p' . $at_depth, $mlid);
        }
      }
    }
    $result = $query->condition('depth', $depth)
      ->condition('hidden', 1, '<>')
      ->orderBy('ml.weight, ml.link_title', 'ASC')
      ->execute()
      ->fetchCol();
    foreach ($result as $mlid) {
      $menu_item = menu_link_load($mlid);
      if (!$menu_item['access']) {
        continue;
      }
      $menu_tree[$mlid] = $menu_item;
      $menu_tree[$mlid]['below'] = array();

      if ($menu_item['has_children']) {
        paddle_menu_display_build_menu($menu_display, $menu_tree[$mlid]['below'], $mlid, $depth + 1);
      }
    }
    // Store the menu tree in the cache when we're done recursing.
    if (!$recursing && !empty($menu_tree)) {
      if ($menu_display->isFollowingActiveTrail()) {
        // Since we have generated the entire tree, we can loop over all parents
        // in the active trail and generate cache entries for all of them.
        $menu_display_clone = clone $menu_display;
        $active_trail = $menu_display_clone->active_trail;

        do {
          $current_mlid = array_pop($active_trail);
          $current_depth = count($active_trail) + 1;

          // Don't cache if the current depth is below the minimum level. The
          // minimum level depends on whether we are using the dynamic "current
          // level plus one" type.
          $minimum_level = $menu_display_clone->getDynamicFromLevel() == 'curr_lvl_plus_1' ? $current_depth + 1 : $current_depth;
          if ($menu_display_clone->fromLevel() <= $minimum_level) {
            $mlid = $menu_display_clone->getCacheMlid();
            $cid = paddle_menu_display_generate_cid($menu_display_clone, $mlid);

            // Ancestors in the active trail might already be cached. Skip the
            // generation of new cache entries if this is the case. If we are at
            // maximum depth of the original menu display we do not need to
            // check if the cache exists.
            $sub_menu_tree = $current_depth < $menu_display->toLevel() ? paddle_menu_display_cache_retrieve($cid) : array();
            if (!$sub_menu_tree || $reset) {
              // Limit the $menu_tree working on a copy.
              $sub_menu_tree = $menu_tree;
              switch ($menu_display_clone->getDynamicToLevel()) {
                case 'curr_lvl':
                  // If the maximum level is limited on the current menu item,
                  // cut everything below.
                  paddle_menu_display_limit_menu_tree($sub_menu_tree, $current_mlid);
                  break;

                case 'curr_lvl_plus_1':
                  // If the maximum level is limited on the current level + 1,
                  // cut everything below the current depth.
                  paddle_menu_display_trim_menu_tree($sub_menu_tree, $current_depth);
                  break;
              }
              paddle_menu_display_cache_store($cid, $sub_menu_tree);
            }
          }
          $menu_display_clone->active_trail = $active_trail;

          // Continue processing the parent items if:
          // - There are items left in the active trail.
          // - The 'to level' is dynamic. If it is fixed and we move up the
          //   trail this would mean that we would have to include the
          //   descendants of all the children of the parent item, and we did
          //   not load these.
          // - The 'from level' is fixed. If the from level changes, the entire
          //   tree changes.
        } while ($active_trail && $menu_display_clone->getDynamicToLevel() && !$menu_display_clone->getDynamicFromLevel());
      }
      else {
        // Means that we always generate the full menu, without respecting the
        // active trail so we save the full menu.
        $cid = paddle_menu_display_generate_cid($menu_display, 0);
        paddle_menu_display_cache_store($cid, $menu_tree);
      }
    }
  }
}

/**
 * Determines the mlid from the passed href.
 */
function _paddle_menu_display_get_mlid_by_href($href) {
  return db_query('SELECT mlid FROM {menu_links} WHERE link_path = :path', array(':path' => $href))->fetchColumn(0);
}

/**
 * Determines if the link is active or if one of their children is active.
 *
 * @param array $menu_item
 *   The Drupal menu link array.
 * @param array $active_trail
 *   Array with mlid of the active trail.
 *
 * @return bool
 *   The result of the check.
 */
function _paddle_menu_display_me_or_children_in_active_trail($menu_item, $active_trail) {
  if (in_array($menu_item['mlid'], $active_trail)) {
    return TRUE;
  }
  else {
    foreach ($menu_item['below'] as $item) {
      if (_paddle_menu_display_me_or_children_in_active_trail($item, $active_trail)) {
        return TRUE;
      }
    }
  }

  return FALSE;
}

/**
 * Add the class to the passed variable.
 *
 * @param string $var
 *   The variable to add the class to.
 * @param string $class
 *   The class to add.
 */
function paddle_menu_display_add_class(&$var, $class) {
  $classes = explode(' ', $var);
  if (in_array($class, $classes)) {
    return;
  }
  if (strlen($var) > 0) {
    $var .= ' ';
  }
  $var .= $class;
}

/**
 * Retrieves cached menu tree from the cache.
 *
 * @param string $cid
 *   The cache id for which to get the data.
 *
 * @return array
 *   The array representing the menu tree.
 */
function paddle_menu_display_cache_retrieve($cid) {
  $cache = cache_get($cid, 'cache_menu_displays');

  return !empty($cache->data) ? $cache->data : array();
}

/**
 * Clears the cached menu tree from the cache based on cid.
 *
 * Allows also to clear a cache record by any part of the cid - mdid, role id
 * or mlid.
 *
 * @param string $cid
 *   The cache id for which to clear the data. If $strict is FALSE this must be
 *   a string compatible with MySQL like - exp.: '%' . db_like($cid) . '%'.
 * @param bool $strict
 *   Indicates whether the condition should be "equal" or "like".
 */
function paddle_menu_display_cache_clear_by_cid($cid, $strict = TRUE) {
  $operator = $strict ? '=' : 'LIKE';
  db_delete('cache_menu_displays')
    ->condition('cid', $cid, $operator)
    ->execute();
}

/**
 * Clears the cached menu tree from the cache based on mlid.
 *
 * @param string $mlid
 *   The mlid for which we clear cached menu tree.
 */
function paddle_menu_display_cache_clear_by_mlid($mlid) {
  db_delete('cache_menu_displays')
    ->condition('mlid_list', '%' . db_like("|$mlid|") . '%', 'LIKE')
    ->execute();
}

/**
 * Generates a cache id.
 *
 * A cache id contains 4 elements separated by double colons:
 * - The menu display id.
 * - A SHA1 hash of the menu display object. This allows to store separate cache
 *   entries if the menu display has been altered or is displayed in a different
 *   language.
 * - A pipe separated list of role ids that have access to the menu display.
 * - The mlid that was used to generate the menu display. If this is -1 the
 *   active trail was used.
 *
 * @param PaddleMenuDisplay $menu_display
 *   The menu_display for which the cid is generated.
 * @param int $mlid
 *   Optional. Mlid to use for the cid. If == -1 the active trail will be used
 *   to determine the correct mlid.
 *
 * @return string
 *   The generated cid in the format "mdid:sha1:rids:mlid".
 */
function paddle_menu_display_generate_cid($menu_display, $mlid = -1) {
  global $user;

  // Get the role ids of the user, and sort them to avoid duplicate entries.
  $rids = array_keys($user->roles);
  sort($rids);

  // Convert the role ids to a pipe-separated string. If the user is UID 1 then
  // make sure it has separate cache entries, since this user has full access.
  $rids = $user->uid == 1 ? 0 : implode('|', $rids);

  // If the root_path is empty the entire menu should be returned.
  if ($menu_display->root_path == '') {
    $cid = implode(':', array(
      $menu_display->mdid,
      $menu_display->getCacheHash(),
      $rids,
      0,
    ));
  }
  else {
    // If no specific mlid is passed, retrieve the mlid from the active trail.
    if ($mlid == -1) {
      $mlid = $menu_display->getCacheMlid();
    }
    $cid = implode(':', array(
      $menu_display->mdid,
      $menu_display->getCacheHash(),
      $rids,
      $mlid,
    ));
  }

  return $cid;
}

/**
 * Stores cached menu tree into the cache.
 *
 * If a record for this cid already exists it will be overwritten.
 *
 * @param int $cid
 *   The cid of the cache record.
 * @param array $menu_tree
 *   Hierarchical array representing the structure of the menu.
 */
function paddle_menu_display_cache_store($cid, array $menu_tree) {
  db_merge('cache_menu_displays')
    ->key(array('cid' => $cid))
    ->fields(array(
      'cid' => $cid,
      'mlid_list' => '|' . implode('|', paddle_menu_display_get_mlid_list($menu_tree)) . '|',
      'data' => serialize($menu_tree),
      'created' => REQUEST_TIME,
      'expire' => REQUEST_TIME + variable_get('paddle_menu_display_cache_expire_time', 86400),
      'serialized' => 1,
    ))
    ->execute();
}

/**
 * Generates a flat list of all mlid in the passed menu tree.
 *
 * @param array $menu_tree
 *   The menu tree for which we need the mlid list.
 *
 * @return array
 *   The mlid of all menu items in the menu tree.
 */
function paddle_menu_display_get_mlid_list(array $menu_tree) {
  $mlids = array();

  foreach ($menu_tree as $mlid => $menu_item) {
    $mlids[] = $mlid;

    if (count($menu_item['below'])) {
      $mlids = array_merge($mlids, paddle_menu_display_get_mlid_list($menu_item['below']));
    }
  }
  sort($mlids);

  return $mlids;
}

/**
 * Limits a menu tree so that it has nothing below the passed $mlid.
 *
 * @param array $menu_tree
 *   The menu tree which we want to limit.
 * @param int $searched_mlid
 *   The item down to which we want to limit the menu tree. This will be the
 *   last menu item and it will not have children.
 */
function paddle_menu_display_limit_menu_tree(&$menu_tree, $searched_mlid) {
  foreach ($menu_tree as $mlid => $menu_item) {
    if ($searched_mlid == $mlid) {
      $menu_tree[$mlid]['below'] = array();
    }
    elseif (!empty($menu_tree[$mlid]['below'])) {
      paddle_menu_display_limit_menu_tree($menu_tree[$mlid]['below'], $searched_mlid);
    }
  }
}

/**
 * Trims a menu tree so that it has nothing below the passed depth.
 *
 * @param array $menu_tree
 *   The menu tree which we want to limit.
 * @param int $depth
 *   The depth below which the menu tree should be cut off.
 */
function paddle_menu_display_trim_menu_tree(&$menu_tree, $depth) {
  foreach ($menu_tree as &$menu_item) {
    if ($depth == 0) {
      $menu_item['below'] = array();
    }
    elseif (!empty($menu_item['below'])) {
      paddle_menu_display_trim_menu_tree($menu_item['below'], $depth - 1);
    }
  }
}

/**
 * Clears the menu display cache taking into account specific menu item.
 *
 * This function will be called when menu item has been inserted, updated or
 * deleted.
 *
 * @param array $menu_item
 *   The menu item which has been changed.
 * @param string $operation
 *   The operation performed on the menu item - insert, update or delete.
 */
function paddle_menu_display_clear_cache_for_menu_item($menu_item, $operation) {
  // Start by clearing all caches in which this menu item is included.
  paddle_menu_display_cache_clear_by_mlid($menu_item['mlid']);

  // Check to which menu displays the item belongs.
  $result = db_select('menu_displays', 'md')
    ->fields('md', array('mdid', 'name'))
    ->condition('menu_name', $menu_item['menu_name'])
    ->execute()
    ->fetchAll();

  if ($result) {
    $menu_displays = paddle_menu_display_get_displays();
  }
  foreach ($result as $record) {
    $menu_display = $menu_displays[$record->name];
    $menu_display->active_trail = paddle_menu_display_get_active_trail($menu_item);

    if (!$menu_display->isFollowingActiveTrail() || $menu_display->getDynamicFromLevel() || $menu_display->getDynamicToLevel()) {
      // If the menu display doesn't respect the active trail (displays all
      // items) we just clear all the cache records for it as any operation will
      // affect the tree. If the from_level or to_level are variables the menu
      // display can display any level of the menu so we clear all.
      paddle_menu_display_cache_clear_by_cid($menu_display->mdid . ':%', FALSE);
      continue;
    }

    // If the menu item is outside of what we display don't clear the cache.
    if ($menu_item['depth'] >= $menu_display->fromLevel() && $menu_item['depth'] <= $menu_display->toLevel()) {
      switch ($operation) {
        case 'delete':
          // If the menu item is deleted we cannot land on it so we clear all
          // cache records for it.
          paddle_menu_display_cache_clear_by_cid($menu_display->mdid . ':%:' . $menu_item['mlid']);
          break;

        case 'insert':
          // The item has been inserted on a depth which we display. If it is
          // inserted at the root level we need to clear the entire cache.
          if ($menu_item['depth'] == 1) {
            paddle_menu_display_cache_clear_by_cid($menu_display->mdid . ':%', FALSE);
          }
          else {
            // If the item is inserted at a deeper level we need to clear the
            // cache for its parent and all its descendants. As the parent will
            // always be present in the mlid_list we can clear cache records
            // containing the plid.
            paddle_menu_display_cache_clear_by_mlid($menu_item['plid']);
          }
          break;
      }
    }
  }
}

# Permissions

## User roles
The Paddle distribution comes with 4 user roles:

- Read Only: can log in and look at unpublished content, but not much more
- Editor: can work with apps. can create, edit, delete but not publish content
- Chief Editor: same as Editor plus publish content and install and configure 
apps
- Site Manager: can create, edit and delete some content (taxonomy, menu  
items), adapt the theme and install and configure apps

## Assign permissions
Implement `hook_enable()` in the app install file to assign permissions to 
roles.

    /**
     * Implements hook_enable().
     */
    function module_name_enable() {
      $permissions = array(
        'permission 1',
        'permission 2',
      );
    
      foreach (array('Chief Editor', 'Editor') as $role_name) {
        $role = user_role_load_by_name($role_name);
        user_role_grant_permissions($role->rid, $permissions);
      }
    }

Clear some caches, if the permission is not yet known at that point in code. 
Have a look at other Paddle apps to see how they handle this. It depends on 
whether the permission is pane or content type related.

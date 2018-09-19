# Paddle common page type features
Page types in the Paddle distribution share features. They can all be 
scheduled, go through a workflow, have multiple versions, be translated, ...

Here is how you can make a new page type fit in with the other Paddle page 
types.

## Common settings
Set and export the following settings with [Features](https://www.drupal.org/project/features):
- Scheduler settings
- Publishing options
- Compare revisions
- Multilingual settings
- Panelizer settings, with the correct renderer:
    1. Go to /admin/structure/types/manage/paddle-product/panelizer/page_manager
    2. Choose Paddle Content Region for a normal page type and save.
    3. Go to /admin/structure/types/manage/paddle_product/panelizer/page_manager/layout
    4. In the select box choose Paddle Content Region, select Kappa and save.
    5. Go to /admin/structure/types/manage/paddle_product/panelizer/page_manager/content
    6. Make sure that following regions have the following content to start with:
        - Right region: "Region content: inherit"
        - Bottom region: "Region content: inherit"
        - Left region: "Rendered Node revision using view mode "Full content""

## Common fields
The [Field Instance Sync](https://www.drupal.org/project/field_instance_sync) 
module is included in the Paddle distribution and replicates all common field 
instances from the master configuration, mostly Basic Page, to any other page 
type that is exported with [Features](https://www.drupal.org/project/features).

1. Surf to /admin/config/system/field-instance-sync 'Field Instance Sync 
configuration' to select which entity types should automatically get synced 
with master fields when a new bundle is created.
2. Mark a field as master field by editing the field and checking the checkbox 
'Sync field settings across different bundles'. 
3. Create a new bundle and enjoy synced fields getting created automatically.

## Node Content pane support
If your page type needs to show up in Node Content panes:
1. Enable the Field UI module.
2. Go to Structure > Content Types > {content type} > Manage display 
(admin/structure/types/manage/my-content-type/display).
3. Under Custom Display Settings enable the following 2 view modes and save:
    - Node content pane summary
    - Node content pane full
4. Change the view modes to your liking, but make sure that the summary view 
mode outputs the body text in the format "Summary or trimmed, in plain text", 
and the full view mode uses the "Default" format.
5. Export the settings in your feature, making sure you export the "field 
bundle settings" variable as well as the field instance settings.

## Revision comparison support
1. Enable the Field UI module.
2. Go to Structure > Content Types > {content type} > Manage display 
(admin/structure/types/manage/my-content-type/display).
3. Under Custom Display Settings enable the following two view modes and save 
"Revision comparison".
4. Change the view mode to your liking.
5. Export the settings in your feature, making sure you export the "field 
bundle settings" variable as well as the field instance settings.

## Test coverage
Don't forget to add test coverage for the common page type features. You need 
to copy the Common page type Selenium tests to the test folder of your page 
type. See for example the files in Kanooh/Paddle/Core/ContentType/BasicPage/Common.

And also:
- Add a checkbox in Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\ConfigurePage\ConfigurePage
- Add a Checkbox in Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRate\ConfigurePage\ConfigurePage
- Add a Checkbox in Kanooh\Paddle\Pages\Element\PanelsContentType\ListingPanelsContentType

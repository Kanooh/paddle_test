/**
 * @file
 * Contains breadcrumb specific scripts.
 */
(function ($) {
  // Ensure the WidgetApi.Event.Handlers namespace is available.
  WidgetApi.Event = WidgetApi.Event || {};
  WidgetApi.Event.Handlers = WidgetApi.Event.Handlers || [];

  // Register for "WidgetCreated" event.
  WidgetApi.Event.Handlers.push({
    event: 'WidgetCreated',
    data: null,
    handler: function (event) {
      // Get the widget which triggered the event.
      var widget = event.getEventArgs().getSource();
      // Check whether the widget being processed is a Global Header and supports breadcrumb API.
      if (widget.getClassName() === 'FlemishAuthorities.InfolijnWidget.GlobalHeader' && typeof widget.getBreadcrumb === 'function') {
        // Get the breadcrumb component from the Global Header.
        var breadcrumb = widget.getBreadcrumb();
        // Retrieve the breadcrumb trail from the Web Universe theme.
        var breadcrumb_trail = Drupal.settings.web_universe_theme.breadcrumb_trail;

        breadcrumb_trail.forEach(function(element) {
          breadcrumb.getLevels().add(
              {
                type: 'link',
                url: element.url,
                label: element.text
              }
          );
        });
      }
    }
  });
})(jQuery);

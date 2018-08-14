<?php
/**
 * @file
 * Template for the detail of an event.
 */
?>
<?php drupal_add_library('system', 'drupal.collapse'); ?>
<section class="event col-sm-8 col-md-9" id="page-content">
  <?php if (!empty($tickets)) : ?>
    <div class="buy-online"><?php print implode(', ', $tickets) ?></div>
  <?php endif; ?>

  <?php if (!empty($types_links) || !empty($themes_links)): ?>
    <div class="tags">
      <?php if (!empty($types_links)): ?>
        <?php print implode(' ', $types_links); ?>
      <?php endif; ?>
      <?php if (!empty($themes_links)): ?>
        <?php print implode(' ', $themes_links); ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($shortdescription)): ?>
    <div class="short-description">
      <?php print $shortdescription; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($longdescription)): ?>
    <div class="description">
      <?php
      $read_more['fieldset'] = array(
        '#type' => 'fieldset',
        '#title' => t('Read more'),
        'example content' => array(
          '#theme' => 'item_list',
          '#items' => array(
            $longdescription,
          ),
        ),
        '#attributes' => array(
          'class' => array(
            'collapsible',
            'collapsed',
          ),
        ),
      );
      print drupal_render($read_more);
      ?>
    </div>
  <?php endif; ?>

  <?php if ($location): ?>
    <div class="location" style="position: relative;">
      <h3><?php print t('Where'); ?></h3>

      <?php if (!empty($location['title'])): ?>
        <?php print $location['title']; ?><br/>
      <?php endif; ?>
      <?php if (!empty($location['street'])): ?>
        <?php print $location['street'] ?><br/>
      <?php endif; ?>
      <?php if (!empty($location['zip'])): ?>
        <?php print $location['zip']; ?>
      <?php endif; ?>
      <?php if (!empty($location['city'])): ?>
        <?php print $location['city']; ?>
      <?php endif; ?>
      <?php if (!empty($coordinates)): ?>
        <?php
        $latitude = $coordinates['lat'];
        $longitude = $coordinates['lng'];

        $markers[] = array(
          'latitude' => $latitude,
          'longitude' => $longitude,
          'text' => $location['title'],
        );

        $settings = array(
          'latitude' => $latitude, // center the map
          'longitude' => $longitude, // on the marker
          'zoom' => 10,
          'markers' => $markers,
          'width' => '100%',
          'height' => '400px',
          'type' => 'Satellite',
        );

        $element = array(
          '#type' => 'gmap',
          '#gmap_settings' => $settings,
        );

        $google_map = drupal_render($element);

        $google_map_render['fieldset'] = array(
          '#type' => 'fieldset',
          '#title' => t('Road map'),
          'example content' => array(
            '#theme' => 'item_list',
            '#items' => array(
              $google_map,
            ),
          ),
          '#attributes' => array(
            'class' => array(
              'collapsible',
              'collapsed',
            ),
          ),
        );
        print drupal_render($google_map_render);
        ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <?php if ($when_lg): ?>
    <div class="when">
      <h3><?php print t('When'); ?></h3>
      <?php print $when_lg; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($age)): ?>
    <div class="age">
      <h3><?php print t('Age'); ?></h3>
      <?php print $age; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($price)): ?>
    <div class="price">
      <h3><?php print t('Price'); ?></h3>
      <?php print $price; ?><br/>
      <?php print $price_description; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($reservation) || !empty($tickets)) : ?>
    <div class="reservation">
      <h3><?php print t('Reservation'); ?></h3>
      <?php if (!empty($reservation['mail'])) : ?>
        <?php print $reservation['mail'] ?><br/>
      <?php endif; ?>
      <?php if (!empty($reservation['url'])) : ?>
        <?php print $reservation['url'] ?><br/>
      <?php endif; ?>
      <?php if (!empty($reservation['phone'])) : ?>
        <?php print t('Phone'); ?>: <?php print $reservation['phone'] ?><br/>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <?php if ($organiser): ?>
    <div class="organization">
      <h3><?php print t('Organization'); ?></h3>
      <?php if (!empty($organiser['title'])): ?>
        <?php print $organiser['title']; ?>
      <?php else: ?>
        <?php print $organiser['link'] ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</section>

<aside class="col-sm-4 col-md-3 event">
  <?php if (!empty($main_picture)): ?>
    <img src="<?php print $main_picture; ?>"/>

    <?php foreach ($pictures as $picture): ?>
      <img src="<?php print $picture; ?>?width=160&height=120&crop=auto"/>
    <?php endforeach; ?>
  <?php endif; ?>
</aside>

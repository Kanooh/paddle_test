<?php

/**
 * @file
 * UitDataBankPane Class.
 */
namespace Drupal\paddle_cultuurnet;

use CultureFeed_Cdb_Data_Calendar_Period;
use CultureFeed_Cdb_Data_Calendar_Timestamp;
use CultureFeed_Cdb_Data_Calendar_TimestampList;
use CultureFeed_Cdb_Data_File;
use CultuurNet\Search\Parameter\FilterQuery;
use CultuurNet\Search\Parameter\Group;
use CultuurNet\Search\Parameter\Parameter;
use CultuurNet\Search\Parameter\Query;
use CultuurNet\Search\Parameter\Rows;
use Exception;

/**
 * The UitDatabank helper class.
 *
 * @package Drupal\paddle_cultuurnet
 */
class UitDataBank {
  /**
   * Receives suggestions based on a search string.
   *
   * @param string $search_string
   *   The string to look for.
   *
   * @return array
   *   The suggestions found.
   */
  public function paddle_cultuurnet_get_autocomplete_suggestions($search_string) {
    $matches = array();

    try {
      $suggestions = culturefeed_get_search_service()->searchSuggestions($search_string,
        array('event'));

      if ($suggestions->hasSuggestions()) {
        foreach ($suggestions as $suggestion) {
          $matches[$suggestion->getTitle()] = check_plain($suggestion->getTitle());
        }

      }
    }
    catch (Exception $e) {
      watchdog_exception('culturefeed_search', $e);
    }

    return $matches;
  }

  /**
   * Search a cdb item by title and type.
   *
   * @param string $title
   *   Title to search.
   *
   * @return mixed
   *   FALSE if not found, otherwise
   *   CultuurNet\Search\ActivityStatsExtendedEntity.
   */
  public function paddle_cultuurnet_load_event_by_title($title) {
    $search_string = trim($title);
    $parameters = array();
    $parameters[] = new Query('title:"' . $search_string . '"');
    $parameters[] = new FilterQuery('type:event');
    $parameters[] = new Group();
    $result = culturefeed_get_search_service()->search($parameters);

    if ($result->getTotalCount() > 0) {
      $items = $result->getItems();

      return $items[0]->getEntity();
    }

    return FALSE;
  }

  /**
   * Prepares the event to be rendered in a template.
   *
   * @param string $title
   *   The title of the event.
   * @param Object $event
   *   The event which needs to be prepared.
   *
   * @return array
   *   An array which can be be used in a template.
   */
  public function paddle_cultuurnet_prepare_event_for_spotlight($title, $event) {
    $prepared_event = array();

    // Add the event title.
    if (!empty($title)) {
      $prepared_event["title"] = $title;
    }

    // Add the event period.
    $event_calendar = $event->getCalendar();
    $parsed_calendar = $this->paddle_cultuurnet_parse_event_calender($event_calendar);

    if (!empty($parsed_calendar)) {
      $prepared_event["period"] = $parsed_calendar;
    }

    // Add the event URL.
    $prepared_event["url"] = culturefeed_search_detail_url('event', $event->getCdbId(), $title);

    // Add the main image.
    $prepared_event["image_url"] = $this->paddle_cultuurnet_retrieve_image_URL($event);

    return $prepared_event;
  }

  /**
   * Parses the Calender object into a string.
   *
   * @param CultureFeed_Cdb_Data_Calendar_TimestampList $calendar
   *   The calender object retrieved from the event.
   *
   * @return string
   *   A string containing the period in which the event takes place.
   */
  public function paddle_cultuurnet_parse_event_calender($calendar) {
    $parsed_calender = "";

    if (!empty($calendar)) {
      foreach ($calendar as $calendaritem) {
        if ($calendaritem instanceof CultureFeed_Cdb_Data_Calendar_Timestamp) {
          $time = $this->paddle_cultuurnet_convert_cultuurnet_timestamp_to_timestamp($calendaritem);
          if (!isset($min_time) || $time < $min_time) {
            $min_time = $time;
          }

          if (!isset($max_time) || $time > $max_time) {
            $max_time = $time;
          }
        }
        elseif ($calendaritem instanceof CultureFeed_Cdb_Data_Calendar_Period) {
          $min_time = strtotime($calendaritem->getDateFrom());
          $max_time = strtotime($calendaritem->getDateTo());
        }

        $parsed_calender = format_date($min_time, 'custom', 'j F');

        if ($min_time != $max_time) {
          $parsed_calender .= ' ' . t('until @date', array('@date' => format_date($max_time, 'custom', 'j F')));
        }
      }
    }

    return $parsed_calender;
  }

  /**
   * Converts the date from the Timestamp object into a PHP timestamp.
   *
   * @param CultureFeed_Cdb_Data_Calendar_Timestamp $timestamp_object
   *   The Timestamp object which is retrieved from the calendar.
   *
   * @return int
   *   The PHP timestamp.
   */
  private function paddle_cultuurnet_convert_cultuurnet_timestamp_to_timestamp($timestamp_object) {
    return strtotime($timestamp_object->getDate());
  }

  /**
   * Retrieves the URL of the main image of the event.
   *
   * @param object $event
   *   The event from which we obtain the image URL.
   *
   * @return string
   *   The respective image URL.
   */
  private function paddle_cultuurnet_retrieve_image_URL($event) {
    $main_image = "";
    $media_types = array(
      CultureFeed_Cdb_Data_File::MEDIA_TYPE_PHOTO,
      CultureFeed_Cdb_Data_File::MEDIA_TYPE_IMAGEWEB
    );
    $event_details = $event->getDetails();

    // Retrieve the images from the Dutch (default) details.
    $images = $event_details->getDetailByLanguage('nl')
      ->getMedia()
      ->byMediaTypes($media_types);

    foreach ($images as $image) {
      if ($image->isMain()) {
        $main_image = culturefeed_get_relative_image($image->getHLink());
        break;
      }
    }

    // If no fixed main image is found, take the first available image.
    if (empty($main_image) && !empty($images->count())) {
      // $images implements the Iterator pattern, so we use those functions.
      $images->rewind();
      $image = $images->current();
      $main_image = culturefeed_get_relative_image($image->getHLink());
    }

    return $main_image;
  }

  /**
   * Sends a query to the UiTdatabank to retrieve events by tag.
   *
   * @param string $tag
   *   The tag of the events which are searched for.
   * @param int $limit
   *   The amount of events which will be returned.
   *
   * @return array
   *   The list of events which belong to the given tag.
   */
  public function paddle_cultuurnet_get_events_by_tag($tag, $limit = 5) {
    $parameters = array();

    $search_string = trim($tag);
    if (empty($search_string)) {
      // If no search terms have been given, match on everything.
      $parameters[] = new Query('*:*');
    }
    else {
      $parameters[] = new Query($search_string);
    }
    $parameters[] = new FilterQuery('type:event');

    // Use the same default sorting as the activities search page.
    $search_page = culturefeed_agenda_culturefeed_search_page_info();
    $search_page_sort_options = culturefeed_search_ui_get_sort_options_for_page($search_page['activiteiten']);
    $default_search_page_sort_options = $search_page_sort_options[$search_page_sort_options['default']]['query'];
    $parameters[] = new Parameter('sort', $default_search_page_sort_options);

    $parameters[] = new Rows($limit);
    $parameters[] = new Group();
    $result = culturefeed_get_search_service()->search($parameters);

    return $result->getItems();
  }

  /**
   * Prepares the list to be loaded into the template.
   *
   * @param string $events
   *   The events which will be shown in the list.
   * @param string $view_mode
   *   The view mode of the list.
   *
   * @return array
   *   The formatted list, ready to be loaded into the template.
   */
  public function paddle_cultuurnet_prepare_events_for_list($events, $view_mode) {
    $list = array();

    foreach ($events as $event) {
      $title = $event->getTitle('nl');
      $list_item["title"] = $title;
      $entity = $event->getEntity();
      $list_item["url"] = culturefeed_search_detail_url('event', $event->getId(), $title);

      if ($view_mode == 'summaries') {
        $list_item['description'] = $entity->getDetails()->getDetailByLanguage('nl')->getShortDescription();
        $list_item["image_url"] = $this->paddle_cultuurnet_retrieve_image_URL($entity);
        $event_calendar = $entity->getCalendar();
        $parsed_calendar = $this->paddle_cultuurnet_parse_event_calender($event_calendar);

        if (!empty($parsed_calendar)) {
          $list_item["period"] = $parsed_calendar;
        }
      }
      $list[] = $list_item;
    }

    return $list;
  }

}

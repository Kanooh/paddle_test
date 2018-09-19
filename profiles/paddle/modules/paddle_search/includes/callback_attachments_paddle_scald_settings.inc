<?php

/**
 * @file
 * Search API data alteration callback.
 */

require_once drupal_get_path('module', 'search_api_attachments') . '/includes/callback_attachments_settings.inc';

class SearchApiAttachmentsPaddleScaldAlterSettings extends SearchApiAttachmentsAlterSettings {

  /**
   * {@inheritdoc}
   */
  public function alterItems(array &$items) {
    foreach ($items as $id => &$item) {
      $references = reference_tracker_get_outbound_references('node', $id);

      if (!empty($references['scald_atom'])) {
        foreach ($references['scald_atom'] as $aid) {
          $atom = scald_atom_load($aid);

          if ($atom->provider == 'paddle_scald_file') {
            $field_item = field_get_items('scald_atom', $atom, 'paddle_scald_file');
            $file = file_load($field_item[0]['fid']);

            if ($this->isFileIndexable($field_item[0], $field_item, 'paddle_scald_file')) {
              $attachments = 'attachments_paddle_scald_file';
              if (isset($item->{$attachments})) {
                $item->{$attachments} .= ' ' . $this->getFileContent($file);
              }
              else {
                $item->{$attachments} = $this->getFileContent($file);
              }
            }
          }
        }
      }
    }
  }

  public function propertyInfo() {
    $ret = array();

    $ret['attachments_paddle_scald_file'] = array(
      'label' => 'Attachment content: paddle_scald_file',
      'description' => 'paddle_scald_file',
      'type' => 'text',
    );

    return $ret;
  }
}

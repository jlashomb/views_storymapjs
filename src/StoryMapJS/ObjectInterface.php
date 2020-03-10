<?php

namespace Drupal\views_storymapjs\StoryMapJS;

/**
 * Provides an interface for defining StoryMap objects.
 */
interface ObjectInterface {

  /**
   * Creates an array representing the StoryMap javascript object.
   *
   * @return mixed[]
   *   The formatted array.
   */
  public function buildArray();

}

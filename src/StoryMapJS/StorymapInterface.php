<?php

namespace Drupal\views_storymapjs\StoryMapJS;

/**
 * Provides an interface for defining StoryMapJS data.
 */
interface StorymapInterface extends ObjectInterface {

  /**
   * Adds a new slide to the map's slides array.
   *
   * @return \Drupal\views_storymapjs\StoryMapJS\SlideInterface[]
   *   The new slide.
   */
  public function addSlide(SlideInterface $location);

  /**
   * Returns the map's array of slides.
   *
   * @return \Drupal\views_storymapjs\StoryMapJS\SlideInterface[]
   *   An array of slides.
   */
  public function getSlides();

}

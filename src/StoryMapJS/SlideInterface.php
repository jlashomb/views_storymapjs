<?php

namespace Drupal\views_storymapjs\StoryMapJS;

/**
 * Provides an interface for defining StoryMap slide data.
 */
interface SlideInterface extends ObjectInterface {

  /**
   * Sets the display date for this slide.
   *
   * @param string $location_lat
   *   The location latitude.
   * @param string $location_lon
   *   The location longitude.
   */
  public function setLocation($location_lat, $location_lon);

  /**
   * Sets the display date for this slide.
   *
   * @param string $headline
   *   The slide headline.
   */
  public function setHeadline($headline);

  /**
   * Sets the display date for this slide.
   *
   * @param string $text
   *   The slide text.
   */
  public function setText($text);

  /**
   * Sets the display date for this slide.
   *
   * @param string $media_url
   *   The media url.
   * @param string $media_url
   *   The media credit.
   * @param string $media_url
   *   The media caption.
   */
  public function setMedia($media_url, $media_credit, $media_caption);

}

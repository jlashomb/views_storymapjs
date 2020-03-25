<?php

namespace Drupal\views_storymapjs\StoryMapJS;

/**
 * Defines a StoryMap slide.
 */
class Slide implements SlideInterface {

  /**
   * The slide type.
   *
   * @var string
   */
  protected $type;

  /**
   * The location latitude.
   *
   * @var string
   */
  protected $location_lat;

  /**
   * The location longitude.
   *
   * @var string
   */
  protected $location_lon;

  /**
   * The location zoom.
   *
   * @var string
   */
  protected $location_zoom;

  /**
   * The slide date.
   *
   * @var string
   */
  protected $date;

  /**
   * The slide headline.
   *
   * @var string
   */
  protected $headline;

  /**
   * The slide text.
   *
   * @var string
   */
  protected $text;

  /**
   * The slide media url.
   *
   * @var string
   */
  protected $media_url;

  /**
   * The slide media caption.
   *
   * @var string
   */
  protected $media_caption;

  /**
   * The slide media credit.
   *
   * @var string
   */
  protected $media_credit;

  /**
   * Constructs a new Slide object.
   *
   * @param string $type
   *   The type of the slide.
   */
  public function __construct($type = NULL) {
    if (!empty($type)) {
      $this->type = $type;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setLocation($location_lat, $location_lon, $location_zoom = NULL) {
    $this->location_lat = $location_lat;
    $this->location_lon = $location_lon;
    if (!empty($location_zoom)) {
      $this->location_zoom = $location_zoom;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setDate($date) {
    $this->date = $date;
  }

  /**
   * {@inheritdoc}
   */
  public function setHeadline($headline) {
    $this->headline = $headline;
  }

  /**
   * {@inheritdoc}
   */
  public function setText($text) {
    $this->text = $text;
  }

  /**
   * {@inheritdoc}
   */
  public function setMedia($media_url, $media_credit, $media_caption) {
    $this->media_url = $media_url;
    if (!empty($media_credit)) {
      $this->media_credit = $media_credit;
    }
    if (!empty($media_caption)) {
      $this->media_caption = $media_caption;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildArray() {
    $slide = [];

    if (!empty($this->type)) {
      $location['type'] = $this->type;
    }
    if (!empty($this->date)) {
      $location['date'] = $this->date;
    }
    if (!empty($this->headline)) {
      $location['text']['headline'] = $this->headline;
    }
    if (!empty($this->text)) {
      $location['text']['text'] = $this->text;
    } else {
      $location['text']['text'] = "";
    }
    $location['location']['line'] = "true";
    if (!empty($this->location_lat)) {
      $location['location']['lat'] = $this->location_lat;
    }
    if (!empty($this->location_lon)) {
      $location['location']['lon'] = $this->location_lon;
    }
    if (!empty($this->location_zoom)) {
      $location['location']['zoom'] = $this->location_zoom;
    }
    if (!empty($this->media_url)) {
      $location['media']['url'] = $this->media_url;
    }
    if (!empty($this->media_caption)) {
      $location['media']['caption'] = $this->media_caption;
    }
    if (!empty($this->media_credit)) {
      $location['media']['credit'] = $this->media_credit;
    }

    // Filter any empty values before returning.
    return array_filter($location);
  }
}


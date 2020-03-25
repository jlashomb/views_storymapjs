<?php

namespace Drupal\views_storymapjs\StoryMapJS;

/**
 * Defines a StoryMap.
 */
class Storymap implements StorymapInterface {

  /**
   * The default width.
   *
   * @var string
   */
  protected $width = '1300px';

  /**
   * The default height.
   *
   * @var string
   */
  protected $height = '1000px';

  /**
   * The storymap language.
   *
   * @var string
   */
  protected $language = 'EN';

  /**
   * The storymap type.
   *
   * @var string
   */
  protected $map_type = "stamen:toner-lines";

  /**
   * The storymap type.
   *
   * @var string
   */
  protected $map_subdomains;

  /**
   * The storymap map background color.
   *
   * @var string
   */
  protected $map_background_color;

  /**
   * The storymap map_as_image mode.
   *
   * @var boolean
   */
  protected $map_as_image = false;

  /**
   * The storymap map_as_image mode.
   *
   * @var boolean
   */
  protected $call_to_action;

  /**
   * The map's array of slides.
   *
   * @var \Drupal\views_storymapjs\StoryMapJS\SlideInterface[]
   */
  protected $slides = [];

  /**
   * {@inheritdoc}
   */
  public function setType($map_type) {
    $this->map_type = $map_type;
  }

  /**
   * {@inheritdoc}
   */
  public function setMapSubdomains($map_subdomains) {
    $this->map_subdomains = $map_subdomains;
  }

  /**
   * {@inheritdoc}
   */
  public function setHeight($height) {
    $this->height = $height;
  }

  /**
   * {@inheritdoc}
   */
  public function setWidth($width) {
    $this->width = $width;
  }

  /**
   * {@inheritdoc}
   */
  public function setCallToAction($call_to_action) {
    $this->call_to_action = $call_to_action;
  }

  /**
   * {@inheritdoc}
   */
  public function addSlide(SlideInterface $slide) {
    $this->slides[] = $slide;
  }

  /**
   * {@inheritdoc}
   */
  public function getSlides() {
    return $this->slides;
  }

  /**
   * {@inheritdoc}
   */
  public function getStoryMapOptions() {
    $options = [];
    if($this->map_type){
      $options["map_type"] = $this->map_type;
    }
    if($this->map_subdomains){
      $options["map_subdomains"] = $this->map_subdomains;
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildArray() {
    $map["width"] = $this->width;
    $map["height"] = $this->height;
    $map["storymap"] = [];
    $map["storymap"]["language"] = $this->language;
    if($this->call_to_action){
      $map["storymap"]["call_to_action"] = true;
      $map["storymap"]["call_to_action_text"] = $this->call_to_action;
    }
    if($this->map_type){
      $map["storymap"]["map_type"] = $this->map_type;
    }
    if($this->map_subdomains){
      $map["storymap"]["map_subdomains"] = $this->map_subdomains;
    }
    if($this->map_as_image){
      $map["storymap"]["map_as_image"] = $this->map_as_image;
    }

    $slides = [];
    foreach ($this->slides as $slide) {
      $slides[] = $slide->buildArray();
    }
    $map["storymap"]['slides'] = $slides;

    return $map;
  }

}


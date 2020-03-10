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
   * The storymap map_as_image mode.
   *
   * @var boolean
   */
  protected $map_as_image = false;

  /**
   * The map's array of slides.
   *
   * @var \Drupal\views_storymapjs\StoryMapJS\SlideInterface[]
   */
  protected $slides = [];

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
  public function buildArray() {
    $map["width"] = $this->width;
    $map["height"] = $this->height;
    $map["storymap"] = [];
    $map["storymap"]["language"] = $this->language;
    if($this->map_type){
      $map["storymap"]["map_type"] = $this->map_type;
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


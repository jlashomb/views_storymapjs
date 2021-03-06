<?php
namespace Drupal\views_storymapjs\Plugin\views\style;

use DateTime;
use DOMDocument;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\views_storymapjs\StoryMapJS\Storymap;
use Drupal\views_storymapjs\StoryMapJS\Slide;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Style plugin for the storymap view.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "storymapjs",
 *   title = @Translation("StoryMapJS"),
 *   help = @Translation("Display the results in StoryMap."),
 *   theme = "views_storymapjs_view_storymapjs",
 *   display_types = {"normal"}
 * )
 */
class StoryMapJS extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  protected $usesRowPlugin = FALSE;

  /**
   * {@inheritdoc}
   */
  protected $usesGrouping = FALSE;

  /**
   * {@inheritdoc}
   */
  protected $usesFields = TRUE;

  /**
   * Constructs a StoryMapJS object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ImmutableConfig $module_configuration
   *   The Views StoryMapJS module's configuration.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    //$this->configuration['library_location'] = $module_configuration->get('library_location');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['storymapjs_config'] = [
      'contains' => [
        'width' => ['default' => '100%'],
        'height' => ['default' => '40em'],
        'map_type' => [],
        'map_subdomains' => [],
      ],
    ];

    $options['storymapjs_cover'] = [
      'contains' => [
        'headline' => [],
        'text' => [],
        'media' => [],
        'media_caption' => [],
        'media_credit' => [],
        'call_to_action' => [],
      ],
    ];

    $options['storymapjs_fields'] = [
      'contains' => [
        'latlon' => ['default' => ''],
        'lat' => ['default' => ''],
        'lon' => ['default' => ''],
        'date' => ['default' => ''],
        'headline' => ['default' => ''],
        'text' => ['default' => ''],
        'media' => ['default' => ''],
        'media_credit' => ['default' => ''],
        'media_caption' => ['default' => ''],
      ],
    ];

    return $options;
  }

  /**
   * Builds the configuration form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {

    //parent::buildOptionsForm($form, $form_state);

    $initial_labels = ['' => $this->t('- None -')];
    $view_fields_labels = $this->displayHandler->getFieldLabels();
    $view_fields_labels = array_merge($initial_labels, $view_fields_labels);

    $form['storymapjs_config'] = [
      '#type' => 'details',
      '#title' => $this->t('StoryMapJS Options'),
      '#description' => $this->t('Each of these settings maps directly to one of the StoryMapJS presentation options.'),
      '#open' => TRUE,
    ];
    $form['storymapjs_config']['width'] = [
      '#type' => 'textfield',
      '#title' => $this->t('StoryMapJS width'),
      '#description' => $this->t('The width of the map, e.g. "100%" or "650px".'),
      '#default_value' => $this->options['storymapjs_config']['width'],
      '#size' => 10,
      '#maxlength' => 10,
    ];
    $form['storymapjs_config']['height'] = [
      '#type' => 'textfield',
      '#title' => $this->t('StoryMapJS height'),
      '#description' => $this->t('The height of the map, e.g. "40em" or "650px".  Percent values are not recommended for the height.'),
      '#default_value' => $this->options['storymapjs_config']['height'],
      '#size' => 10,
      '#maxlength' => 10,
    ];
    $form['storymapjs_config']['map_type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Map type or Map URL tile template'),
      '#description' => $this->t('<strong>Map URL tile template:</strong> "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"<br><strong>Presets:</strong> stamen:toner-lite, stamen:toner, stamen:toner-lines, stamen:toner-labels, stamen:watercolor, osm:standard, mapbox:<em>map-id</em>'),
      '#default_value' => $this->options['storymapjs_config']['map_type'],
      '#size' => 100,
      '#maxlength' => 256,
    ];
    $form['storymapjs_config']['map_subdomains'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Map Subdomains'),
      '#description' => $this->t('Is only needed when using a custom URL tile template. It should be specified as a single string, where each character is a valid subdomain substitution (e.g. "abc"). These a substitutions for the "s" wildcard, {s}.'),
      '#default_value' => $this->options['storymapjs_config']['map_subdomains'],
      '#size' => 50,
      '#maxlength' => 50,
    ];

    $form['storymapjs_cover'] = [
      '#type' => 'details',
      '#title' => $this->t('StoryMapJS Cover'),
      '#description' => $this->t('The first slide is an overview slide and you can set the Headline and Text.'),
      '#open' => TRUE,
    ];
    $form['storymapjs_cover']['headline'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Cover Headline'),
      '#description' => $this->t('The selected field may contain any text. (Supports replacement patterns from the first row.)'),
      '#default_value' => $this->options['storymapjs_cover']['headline'],
      '#size' => 50,
      '#maxlength' => 50,
    ];
    $form['storymapjs_cover']['text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Cover Text'),
      '#description' => $this->t('The selected field may contain any text, including HTML markup. (Supports replacement patterns from the first row.)'),
      '#default_value' => $this->options['storymapjs_cover']['text'],
    ];
    $form['storymapjs_cover']['media'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Cover Media (URL or Rendered Field)'),
      '#description' => $this->t('The selected field may contain any text, including HTML markup. (Supports replacement patterns from the first row.)'),
      '#default_value' => $this->options['storymapjs_cover']['media'],
    ];
    $form['storymapjs_cover']['media_caption'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Cover Media Caption'),
      '#description' => $this->t('The selected field may contain any text, including HTML markup. (Supports replacement patterns from the first row.)'),
      '#default_value' => $this->options['storymapjs_cover']['media_caption'],
    ];
    $form['storymapjs_cover']['media_credit'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Cover Media Credit'),
      '#description' => $this->t('The selected field may contain any text, including HTML markup. (Supports replacement patterns from the first row.)'),
      '#default_value' => $this->options['storymapjs_cover']['media_credit'],
    ];
    $form['storymapjs_cover']['call_to_action'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Call to Action Button Text'),
      '#description' => $this->t('The selected field may contain any text. Leave blank to hide. (Supports replacement patterns from the first row.)'),
      '#default_value' => $this->options['storymapjs_cover']['call_to_action'],
    ];

    // Field mapping.
    $form['storymapjs_fields'] = [
      '#type' => 'details',
      '#title' => $this->t('Field mappings'),
      '#description' => $this->t('Map your Views data fields to StoryMapJS slide properties.'),
      '#open' => TRUE,
    ];
    $form['storymapjs_fields']['date'] = [
      '#type' => 'select',
      '#options' => $view_fields_labels,
      '#title' => $this->t('Date'),
      '#description' => $this->t('The selected field may contain any year.'),
      '#default_value' => $this->options['storymapjs_fields']['date'],
    ];
    $form['storymapjs_fields']['headline'] = [
      '#type' => 'select',
      '#options' => $view_fields_labels,
      '#title' => $this->t('Headline'),
      '#description' => $this->t('The selected field may contain any text.'),
      '#default_value' => $this->options['storymapjs_fields']['headline'],
    ];
    $form['storymapjs_fields']['text'] = [
      '#type' => 'select',
      '#options' => $view_fields_labels,
      '#title' => $this->t('Text'),
      '#description' => $this->t('The selected field may contain any text, including HTML markup.'),
      '#default_value' => $this->options['storymapjs_fields']['text'],
    ];
    $form['storymapjs_fields']['latlon'] = [
      '#type' => 'select',
      '#options' => $view_fields_labels,
      '#title' => $this->t('Location Latitude and Longitude'),
      '#description' => $this->t('The latitude and longitude in decimal form separated by a comma. This field takes precidence over the individual fields.'),
      '#default_value' => $this->options['storymapjs_fields']['latlon'],
    ];
    $form['storymapjs_fields']['lat'] = [
      '#type' => 'select',
      '#options' => $view_fields_labels,
      '#title' => $this->t('Location Latitude'),
      '#description' => $this->t('The latitude in decimal form. This field will be ignored if a value is found in the "Location Latitude and Longitude" field.'),
      '#default_value' => $this->options['storymapjs_fields']['lat'],
    ];
    $form['storymapjs_fields']['lon'] = [
      '#type' => 'select',
      '#options' => $view_fields_labels,
      '#title' => $this->t('Location Longitude'),
      '#description' => $this->t('The longitude in decimal form. This field will be ignored if a value is found in the "Location Latitude and Longitude" field.'),
      '#default_value' => $this->options['storymapjs_fields']['lon'],
    ];
    $form['storymapjs_fields']['zoom'] = [
      '#type' => 'select',
      '#options' => $view_fields_labels,
      '#title' => $this->t('Location Zoom'),
      '#description' => $this->t('The zoom level to use from 0 - 18. Leave blank for auto.'),
      '#default_value' => $this->options['storymapjs_fields']['zoom'],
    ];
    $form['storymapjs_fields']['media'] = [
      '#type' => 'select',
      '#options' => $view_fields_labels,
      '#title' => $this->t('Media URL'),
      '#description' => $this->t('The URL to the media.'),
      '#default_value' => $this->options['storymapjs_fields']['media'],
    ];
    $form['storymapjs_fields']['media_caption'] = [
      '#type' => 'select',
      '#options' => $view_fields_labels,
      '#title' => $this->t('Media Caption'),
      '#description' => $this->t('The selected field may contain any text, including HTML markup.'),
      '#default_value' => $this->options['storymapjs_fields']['media_caption'],
    ];
    $form['storymapjs_fields']['media_credit'] = [
      '#type' => 'select',
      '#options' => $view_fields_labels,
      '#title' => $this->t('Media Credit'),
      '#description' => $this->t('The selected field may contain any text, including HTML markup.'),
      '#default_value' => $this->options['storymapjs_fields']['media_credit'],
    ];
  }
  /**
   * {@inheritdoc}
   */
  public function render() {
    $map = new Storymap();

    // Render the fields.  If it isn't done now then the row_index will be unset
    // the first time that getField() is called, resulting in an undefined
    // property exception.
    $this->renderFields($this->view->result);

    if ($map_type = $this->options['storymapjs_config']['map_type']) {
      $map->setType($this->tokenizeValue($map_type, 0));
    }
    if ($map_subdomains = $this->options['storymapjs_config']['map_subdomains']) {
      $map->setMapSubdomains($this->tokenizeValue($map_subdomains, 0));
    }
    if ($cta = $this->options['storymapjs_cover']['call_to_action']) {
      $map->setCallToAction($this->tokenizeValue($cta, 0));
    }
    if ($height = $this->options['storymapjs_config']['height']) {
      $map->setHeight($this->tokenizeValue($height, 0));
    }
    if ($width = $this->options['storymapjs_config']['width']) {
      $map->setWidth($this->tokenizeValue($width, 0));
    }

    $slide = new Slide("overview");
    $headline = $this->tokenizeValue($this->options['storymapjs_cover']['headline']?:"", 0);
    $slide->setHeadline($headline);
    $text = $this->tokenizeValue($this->options['storymapjs_cover']['text']?:"", 0);
    $slide->setText($text);
    $media = $this->tokenizeValue($this->options['storymapjs_cover']['media']?:"", 0);
    $media = $this->extractUrl($media);
    $caption = $this->tokenizeValue($this->options['storymapjs_cover']['media_caption']?:"", 0);
    $credit = $this->tokenizeValue($this->options['storymapjs_cover']['media_credit']?:"", 0);
    $slide->setMedia($media, $credit, $caption);
    $map->addSlide($slide);

    // Render slide arrays from the views data.
    foreach ($this->view->result as $row_index => $row) {
      $this->view->row_index = $row_index;
      $slide = $this->buildSlide();
      // Ensure the slide was built.
      if (!empty($slide)) {
        $map->addSlide($slide);
      }
    }
    unset($this->view->row_index);

    // Skip theming if the view is being edited or previewed.
    if ($this->view->preview) {
      return '<pre>' . print_r($map->buildArray(), 1) . '</pre>';
    }

    return [
      '#theme' => $this->themeFunctions(),
      '#view' => $this->view,
      '#options' => [
        'storymap_options' => $map->getStoryMapOptions(),
      ],
      '#rows' => $map->buildArray(),
    ];
  }

  /**
   * Get the value of the field on the row as a string.
   *
   * @param int $row_index
   *   The row index
   * @param string $field_name
   *   The machine name of the field
   *
   * @return string
   *   The value of the field for a given row as a string.
   */
  protected function getRowFieldValue($row_index, $field_name) {
    $field = $this->getField($row_index, $field_name);
    return $field ? $field->__toString() : '';
  }

   /**
   * Searches a string for HTML attributes that contain URLs and returns them.
   *
   * This will search a string which is presumed to contain HTML for anchor or
   * image tags.  It will return the href or src attribute of the first one it
   * finds.
   *
   * This is basically special handling for core Image fields.  There is no
   * built-in field formatter for outputting a raw URL from an image.  This
   * method allows image fields to "just work" as sources for TimelineJS media
   * and background image URLs.  Anchor tag handling was added for people who
   * forget to output link fields as plain text URLs.
   *
   * @param string $html
   *   A string that contains HTML.
   *
   * @return string
   *   A URL if one was found in the input string, the original string if not.
   */
  protected function extractUrl($html) {
    if (!empty($html)) {
      // Disable libxml errors.
      $previous_use_errors = libxml_use_internal_errors(TRUE);

      $document = new DOMDocument();
      $document->loadHTML($html);

      // Handle XML errors.
      foreach (libxml_get_errors() as $error) {
        //$this->handleXmlErrors($error, $html);
      }

      // Restore the previous error setting.
      libxml_use_internal_errors($previous_use_errors);

      // Check for anchor tags.
      $anchor_tags = $document->getElementsByTagName('a');
      if ($anchor_tags->length) {
        return $anchor_tags->item(0)->getAttribute('href');
      }

      // Check for image tags.
      $image_tags = $document->getElementsByTagName('img');
      if ($image_tags->length) {
        return $image_tags->item(0)->getAttribute('src');
      }
    }
    return $html;
  }

  /**
   * Builds a map location from the current views data row.
   *
   * @return \Drupal\views_storymapjs\StoryMapJS\Slide|null
   *   A slide object or NULL.
   */
  protected function buildSlide() {
    $row_index = $this->view->row_index;
    $slide = new Slide();

    $date = $this->getRowFieldValue($row_index, $this->options['storymapjs_fields']['date']);
    $slide->setDate($date);

    $headline = $this->getRowFieldValue($row_index, $this->options['storymapjs_fields']['headline']);
    $slide->setHeadline($headline);

    $text = $this->getRowFieldValue($row_index, $this->options['storymapjs_fields']['text']);
    $slide->setText($text);

    $latlon = $this->getRowFieldValue($row_index, $this->options['storymapjs_fields']['latlon']);
    $latlon = explode(',', preg_replace('/[^0-9\.\,\-]/', '', strip_tags($latlon)));
    $lat = $latlon[0];
    $lon = $latlon[1];
    // If either lat or lon is empty, try to get the value from the individual field
    $lat = $lat?:$this->getRowFieldValue($row_index, $this->options['storymapjs_fields']['lat']);
    $lon = $lon?:$this->getRowFieldValue($row_index, $this->options['storymapjs_fields']['lon']);
    $zoom = $this->getRowFieldValue($row_index, $this->options['storymapjs_fields']['zoom']);
    $slide->setLocation($lat, $lon, $zoom?:NULL);

    $media = $this->fetchMediaURL($row_index);
    $caption = $this->getRowFieldValue($row_index, $this->options['storymapjs_fields']['media_caption']);
    $credit = $this->getRowFieldValue($row_index, $this->options['storymapjs_fields']['media_credit']);
    $slide->setMedia($media, $credit, $caption);

    return $slide;
  }

  /**
   * Fetch media url from the current data row.
   */
  protected function fetchMediaURL($row_index) {
    $url = '';
    if ($this->options['storymapjs_fields']['media']) {
      $url_markup = $this->getField($row_index, $this->options['storymapjs_fields']['media']);
      $url = $url_markup ? $url_markup->__toString() : '';

      // Special handling because core Image fields have no raw URL formatter.
      // Check to see if we don't have a raw URL.
      if (!filter_var($url, FILTER_VALIDATE_URL)) {
        // Attempt to extract a URL from an img or anchor tag in the string.
        $url = $this->extractUrl($url);
      }
    }
    // Return NULL if the URL is empty.
    if (empty($url)) {
      return NULL;
    }
    return $url;
  }

}

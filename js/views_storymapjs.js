/* Landing page scripts */
(function($){
  $(document).ready(function() {

    var data;
    drupalSettings.StoryMapJS.forEach(function(map, key) {
      if (map['processed'] != true) {
        data = map['source'];
      }
      map['processed'] = true;
    });

    // storymap_data can be an URL or a Javascript object
    var storymap_data = data;

    // certain settings must be passed within a separate options object
    var storymap_options = {};

    var storymap = new VCO.StoryMap('mapdiv', storymap_data, storymap_options);
    window.onresize = function(event) {
      storymap.updateDisplay(); // this isn't automatic
    }

  });
})(jQuery);

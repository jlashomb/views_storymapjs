View StoryMapJS
===============
This module adds a new style plugin for Views which renders result rows as
StoryMapJS slides. For more information about StoryMapJS vist 
https://storymap.knightlab.com/ or the GitHub repository 
https://github.com/NUKnightLab/StoryMapJS

Installation
------------
Download the module from https://github.com/jlashomb/views_storymapjs and enable
it.

Add Library: Currently you'll need to serve the library files from your own site
by downloading the library files from the knighlab github and putting the
StoryMapJS library in the /libraries directory inside your Drupal installation.
Alternate library locations such as those checked by the Libraries API module
will not work.

You can download or clone the entire StoryMapJS GitHub repository.
```
git clone --branch master https://github.com/NUKnightLab/StoryMapJS.git
```

If you don't want to download the entire repository, then you can download the
Javascript and CSS files selectively.  The storymap.js and storymap.css files
are required to use StoryMapJS.
In the end, you need to have the following files in these directories:

1. /libraries/TimelineJS3/compiled/js/timeline.js
2. /libraries/TimelineJS3/compiled/css/timeline.css

In the future, no library files will be needed by default, because we should be
able to get them from the NU Knight Lab CDN.

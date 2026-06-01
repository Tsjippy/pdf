This module adds the possibility to have a print button on pages or posts.<br>
This will print the webpage to a PDF document.<br>
It also adds the option for full screen PDF display.<br>
Both options can be turned on or off below.<br>

== Hooks ==
# FILTERS
- apply_filters('sim_before_pdf_text', $cellText, $this);

# Actions
- do_action('sim-pdf-before-fullscreen', $postId);
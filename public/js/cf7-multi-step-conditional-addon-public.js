(function($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  $(window).load(function() {
    // $("ul.cmsca-multistep-progressbar").each(function() {
    //   var liWidth = 100;
    //   if ($(this).children("li").length > 0) {
    // 		liWidth /= $(this).children("li").length;
    //     $(this).children("li").each(function(i, e) {
    // 			$(this).width(liWidth + '%');
    //     });
    //   }
    // });

    $(document).ready(function() {
      $(".cmsca_next_button").click(function() {
        
        var steps = $(".cmsca-multistep-form .cmsca-step");
        steps.each(function(k, v) {
          console.log(v);
          if ($(this).hasClass('cmsca-step-active') && k == 0) {
            $(".cmsca_previous_button").hide();
            $(".cmsca_next_button").show();
          } else if ($(this).hasClass('cmsca-step-active') && k != 0) {
            $(".cmsca_next_button").hide();
            $(".cmsca_previous_button").show();
          }
        });
        // Function Runs On NEXT Button Click
        $(".cmsca-step-active")
          .hide()
          .next()
          .fadeIn("slow");
        $(".cmsca-step-active")
          .removeClass("cmsca-step-active")
          .next()
          .addClass("cmsca-step-active");
      });
      $(".cmsca_previous_button").click(function() {
        // Function Runs On PREVIOUS Button Click
        $(".cmsca-step-active")
          .prev()
          .fadeIn("slow");
        $(".cmsca-step-active").hide();
        $(".cmsca-step-active")
          .removeClass("cmsca-step-active")
          .prev()
          .addClass("cmsca-step-active");
      });
    });
  });
})(jQuery);

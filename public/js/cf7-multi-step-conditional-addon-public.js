(function($) {
  "use strict";

  $(document).ready(function() {
    $(".cmsca_next_button, .cmsca_previous_button").click(function() {
      var clickedButton = $(this).hasClass("cmsca_next_button")
        ? "next"
        : "previous";
      var steps = $(".cmsca-multistep-form .cmsca-step");
      steps.each(function(k, v) {
        if ($(this).hasClass("cmsca-step-active")) {
          $(this).hide();
          $(this).removeClass("cmsca-step-active");
          if (clickedButton == "next") {
            k++;
            $("ul.cmsca-multistep-progressbar .step-" + k).addClass('active');
            if ($(this).next().hasClass("last-step")) {
              $(".wpcf7-submit").show();
              $(".cmsca_next_button").hide();
              $(".cmsca_previous_button").show();
            } else {
              $(".cmsca_next_button").show();
              $(".cmsca_previous_button").show();
              $(".wpcf7-submit").hide();
            }
            $(this)
              .next()
              .fadeIn("slow");
            $(this)
              .next()
              .addClass("cmsca-step-active");
          } else if (clickedButton == "previous") {
            $("ul.cmsca-multistep-progressbar .step-" + k, "ul.cmsca-multistep-progressbar .step-" + k-1).removeClass('active');
            $(".wpcf7-submit").hide();
            if ($(this).prev().hasClass("first-step")) {
              $(".cmsca_previous_button").hide();
              $(".cmsca_next_button").show();
            } else {
              $(".cmsca_previous_button").show();
              $(".cmsca_next_button").show();
            }
            $(this)
              .prev()
              .fadeIn("slow");
            $(this)
              .prev()
              .addClass("cmsca-step-active");
          }
          return false;
        }
      });
    });
  });
})(jQuery);

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
          if (clickedButton == "next") {
            var inputs = $(this).find('input');
            var validationRequired = false;
            var inputsToValidate = [];
            inputs.each(function(k, v){
              if ($(this).hasClass('wpcf7-validates-as-required')) {
                $(this).addClass( 'wpcf7-not-valid' );
                $(this).attr( 'aria-invalid', 'true' );
                validationRequired = true;
                inputsToValidate.push({
                    type: $(this).attr('type') + '*',
                    basetype: $(this).attr('type'),
                    value:  $(this).val(),
                    name: $(this).attr('name')
                });
                // console.log(v);
              }
            });
            if (validationRequired == true) {
              // console.log(inputsToValidate);
                var data = {
                  action: 'cmsca_public_ajax',
                  security: cmsca_public_ajax_object.security,
                  validate: inputsToValidate
                };
                jQuery.post(cmsca_public_ajax_object.ajaxurl, data, function(response) {
                  console.log(response);
                });
                return false;
            }
            $(this).hide();
            $(this).removeClass("cmsca-step-active");
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
            $(this).hide();
            $(this).removeClass("cmsca-step-active");
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

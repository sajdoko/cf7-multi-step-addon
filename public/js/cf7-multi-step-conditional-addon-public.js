(function ($) {
  "use strict";

  $(document).ready(function () {
    $(".cmsca_next_button, .cmsca_previous_button").click(function () {
      var clickedButton = $(this).hasClass("cmsca_next_button") ?
        "next" :
        "previous";
      var steps = $(".cmsca-multistep-form .cmsca-step");
      var CF7Id = steps.parent().parent().find("input[name='_wpcf7']").val();
      var $form = steps.parent().parent().parent().find("form");
      var $message = $( '.wpcf7-response-output', $form );
      steps.each(function (k, v) {
        var step = $(this);
        if ($(this).hasClass("cmsca-step-active")) {
          if (clickedButton == "next") {
            var inputs = $(this).find('input');
            var inputsToValidate = [];
            inputs.each(function (k, v) {
              if ($(this).hasClass('wpcf7-validates-as-required')) {
                $(this).addClass('wpcf7-not-valid');
                $(this).attr('aria-invalid', 'true');
                inputsToValidate.push({
                  formId: CF7Id,
                  type: $(this).attr('type'),
                  value: $(this).val(),
                  name: $(this).attr('name')
                });
              }
            });
            if (inputsToValidate.length > 0) {
              var data = {
                action: 'cmsca_public_ajax',
                security: cmsca_public_ajax_object.security,
                validate: inputsToValidate
              };
              $.ajax({
                url: cmsca_public_ajax_object.ajaxurl,
                type: 'POST',
                data: data,
                success: function (response) {
                  if (response.success == false) {
                    $.each(response.data, function (i, n) {
                      $(n.into, $form).each(function () {
                        wpcf7.notValidTip(i, n);
                        $('.wpcf7-form-control', i).addClass('wpcf7-not-valid');
                        $('[aria-invalid]', i).attr('aria-invalid', 'true');
                      });
                    });

                    $message.addClass('wpcf7-validation-errors');
                    $form.addClass('invalid');

                    // wpcf7.triggerEvent(data.into, 'invalid', detail);
                    return false;
                  } else {
                    step.hide();
                    step.removeClass("cmsca-step-active");
                    k++;
                    $("ul.cmsca-multistep-progressbar .step-" + k).addClass('active');
                    if (step.next().hasClass("last-step")) {
                      $(".wpcf7-submit").show();
                      $(".cmsca_next_button").hide();
                      $(".cmsca_previous_button").show();
                    } else {
                      $(".cmsca_next_button").show();
                      $(".cmsca_previous_button").show();
                      $(".wpcf7-submit").hide();
                    }
                    step
                      .next()
                      .fadeIn("slow");
                    step
                      .next()
                      .addClass("cmsca-step-active");
                  }
                  console.log(response.success);
                }
              });
            } else {
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
            }
          } else if (clickedButton == "previous") {
            $(this).hide();
            $(this).removeClass("cmsca-step-active");
            $("ul.cmsca-multistep-progressbar .step-" + k, "ul.cmsca-multistep-progressbar .step-" + k - 1).removeClass('active');
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

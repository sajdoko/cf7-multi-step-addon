(function ($) {
  "use strict";
  $(document).ready(function () {
    function notValidWarning(target, message) {
      var $target = $(target);
      $('.wpcf7-not-valid-tip', $target).remove();
      $('<span role="alert" class="wpcf7-not-valid-tip"></span>')
        .text(message).appendTo($target);
    };
    function clearNotValidWarning(target) {
      var $target = $(target);
      $( '.wpcf7-not-valid-tip', $target ).remove();
      $( '[aria-invalid]', $target ).attr( 'aria-invalid', 'false' );
      $( '.wpcf7-form-control', $target ).removeClass( 'wpcf7-not-valid' );
      $( '.wpcf7-response-output', $target )
        .hide().empty().removeAttr( 'role' )
        .removeClass( 'wpcf7-mail-sent-ok wpcf7-mail-sent-ng wpcf7-validation-errors wpcf7-spam-blocked' );
    };
    function groupObjectBy(array, property) {
      var hash = {};
      for (var i = 0; i < array.length; i++) {
          if (!hash[array[i][property]]) hash[array[i][property]] = [];
          hash[array[i][property]].push(array[i]);
      }
      return hash;
    }
    $(".cmsca_next_button, .cmsca_previous_button").click(function () {
      var clickedButton = $(this).hasClass("cmsca_next_button") ?
        "next" :
        "previous";
      var steps = $(".cmsca-multistep-form .cmsca-step");
      var CF7Id = steps.parent().parent().find("input[name='_wpcf7']").val();
      var $cmscaForm = steps.parent().parent().parent().find("form");
      var $message = $('.wpcf7-response-output', $cmscaForm);
      steps.each(function (k, _v) {
        var step = $(this);
        if ($(this).hasClass("cmsca-step-active")) {
          if (clickedButton == "next") {
//TODO - File validation
            var inputs = $(this).find('input, select');
            var inputsToValidate = new Array();
            var checkboxToValidate = new Array();
            var valClassString = 'wpcf7-validates-as-required';
            inputs.each(function (_k, v) {
              if ($(this).hasClass(valClassString) || $(this)[0].type == 'checkbox') {
                if ($(this)[0].type == 'checkbox') {
                  if ($(this).parent().parent().hasClass(valClassString) || $(this).parent().parent().parent().hasClass(valClassString)) {
                    checkboxToValidate.push({
                          formId: CF7Id,
                          type: $(this)[0].type,
                          value: $(this).val(),
                          name: $(this)[0].name.replace(/(\[|\])/g, ''),
                          checked: $(this)[0].checked
                        });
                  } else if (!$(this).parent().parent().hasClass('optional')) {
                    if(!$(this)[0].checked) {
                      inputsToValidate.push({
                        formId: CF7Id,
                        type: $(this)[0].type,
                        value: $(this).val(),
                        name: $(this)[0].name.replace(/(\[|\])/g, '')
                      });
                    }
                  }
                } else {
                  inputsToValidate.push({
                    formId: CF7Id,
                    type: $(this)[0].type,
                    value: $(this).val(),
                    name: $(this)[0].name.replace(/(\[|\])/g, '')
                  });
                }
                clearNotValidWarning('.' + $(this)[0].name.replace(/(\[|\])/g, ''));
              }
            });
            if (checkboxToValidate.length > 0) {
              var groupedObj = groupObjectBy(checkboxToValidate, 'name');
              $.each(groupedObj, function (key, val) {
                var isChecked = false;
                $.each(val, function (key, val2) {
                  if (val2['checked'] == true) isChecked = true ;
                });
                if (isChecked == false) {
                  inputsToValidate.push(val[0]);
                }
              });
            }
            if (inputsToValidate.length > 0) {
              if (!$(this).hasClass("last-step")) {
                $('.cmsca-multistep-form-footer', $cmscaForm).append('<span class="ajax-loader cmsca-loader"></span>');
              }
              $('.cmsca-loader', $cmscaForm).addClass('is-active');
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
                  $('.cmsca-loader', $cmscaForm).remove();
                  if (response.success == false) {
                    $.each(response.data, function (i, n) {
                      notValidWarning("." + i, n);
                    });
                    $message.addClass('wpcf7-validation-errors');
                    $cmscaForm.addClass('invalid');
                    return false;
                  } else {
                    step.hide();
                    step.removeClass("cmsca-step-active");
                    k++;
                    $("ul.cmsca-multistep-progressbar .step-" + k).addClass('active');
                    if (step.next().hasClass("last-step")) {
                      $(".wpcf7-submit", $cmscaForm).show();
                      $(".cmsca_next_button").hide();
                      $(".cmsca_previous_button").show();
                    } else {
                      $(".cmsca_next_button").show();
                      $(".cmsca_previous_button").show();
                      $(".wpcf7-submit", $cmscaForm).hide();
                    }
                    step
                      .next()
                      .fadeIn("slow");
                    step
                      .next()
                      .addClass("cmsca-step-active");
                  }
                }
              });
            } else {
              $(this).hide();
              $(this).removeClass("cmsca-step-active");
              k++;
              $("ul.cmsca-multistep-progressbar .step-" + k).addClass('active');
              if ($(this).next().hasClass("last-step")) {
                $(".wpcf7-submit", $cmscaForm).show();
                $(".cmsca_next_button").hide();
                $(".cmsca_previous_button").show();
              } else {
                $(".cmsca_next_button").show();
                $(".cmsca_previous_button").show();
                $(".wpcf7-submit", $cmscaForm).hide();
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
            $(".wpcf7-submit", $cmscaForm).hide();
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

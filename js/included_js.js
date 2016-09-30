(function ($) {
  "use strict";

  $(function () {

    var dialog, form, wpqpd_submit_info, objectToPost, ajaxWPRESTAPI, new_wpqpd_submit_info, ajaxLoader;

    new_wpqpd_submit_info = $(window.wpqpd_submit_info);
    new_wpqpd_submit_info = (new_wpqpd_submit_info["0"]);

    ajaxLoader = $('.ajax-loader');

    ajaxWPRESTAPI = function (wpqpd_submit_info, objectToPost) {
      $.ajax({
        method: "POST",
        url: wpqpd_submit_info.root + 'wp/v2/posts',
        data: objectToPost,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-WP-Nonce', wpqpd_submit_info.nonce);
        },
        success: function (response) {

          ajaxLoader.children().hide();
          ajaxLoader.append('<div class="wpqpd-success"><small><b>Success!</b> You can close this window, or it will close in 5 seconds.</small></div>');
          setTimeout(function() {
            dialog.dialog("close");
          }, 50000);

        },
        fail: function (response) {
          alert(wpqpd_submit_info.failure);
        }

      });
    };


    dialog = $("#wp-quick-post-draft-form").dialog({
      autoOpen: false,
      height: 600,
      width: 600,
      modal: true,
      close: function () {
        form[0].reset();
        $('.modal-backdrop').remove();
      }
    });

    form = dialog.find( "form" );

    var getPostInfo = function (status) {
      var postTitle = $("#wpqpd-post-title").val();
      var postContent = $("#wpqpd-post-content").val();
      var postCategory = $("#wpqpd-post-category").val();

      objectToPost = {
        title: postTitle,
        content: postContent,
        categories: [postCategory],
        status: status
      };

      return objectToPost;

    };

    ajaxLoader.hide();

    $("#wp-quick-post-draft-button-draft").button().on("click", function (event) {
      event.preventDefault();
      $('#wpqpd-post-content-html').click();
      $('#wpqpd-post-content-tmce').click();
      $('.ajax-loader').show();
      getPostInfo('draft');
      console.log(objectToPost);

      ajaxWPRESTAPI(new_wpqpd_submit_info, objectToPost);
    });

    $("#wp-quick-post-draft-button-post").button().on("click", function (event) {
      event.preventDefault();
      $('#wpqpd-post-content-html').click();
      $('#wpqpd-post-content-tmce').click();
      $('.ajax-loader').show();
      getPostInfo('publish');
      console.log(objectToPost);

      ajaxWPRESTAPI(new_wpqpd_submit_info, objectToPost);
    });

    $("#wp-quick-post-draft-button").on("click", function () {
      $('body').append('<div class="modal-backdrop fade in"></div>');
      dialog.dialog("open");
    });
  });


})(jQuery);
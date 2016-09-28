(function ($) {
  "use strict";

  $(function () {

    var dialog, form, wpqpd_submit_info, objectToPost, ajaxWPRESTAPI, new_wpqpd_submit_info;

    new_wpqpd_submit_info = $(window.wpqpd_submit_info);
    new_wpqpd_submit_info = (new_wpqpd_submit_info["0"]);


    ajaxWPRESTAPI = function (wpqpd_submit_info, objectToPost) {
      $.ajax({
        method: "POST",
        url: wpqpd_submit_info.root + 'wp/v2/posts',
        data: objectToPost,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-WP-Nonce', wpqpd_submit_info.nonce);
        },
        success: function (response) {
          console.log(response);
          alert(wpqpd_submit_info.success);
        },
        fail: function (response) {
          console.log(response);
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
        category: postCategory,
        status: status
      };

      return objectToPost;

    };

    $("#wp-quick-post-draft-button-draft").button().on("click", function (event) {
      event.preventDefault();
      getPostInfo('draft');
      console.log('Object to Post: ', objectToPost);

      ajaxWPRESTAPI(new_wpqpd_submit_info, objectToPost);




    });

    $("#wp-quick-post-draft-button-post").button().on("click", function (event) {
      event.preventDefault();
      getPostInfo('publish');
      console.log('Object to Post: ', objectToPost);

      ajaxWPRESTAPI(new_wpqpd_submit_info, objectToPost);
    });

    $("#wp-quick-post-draft-button").button().on("click", function () {
      $('body').append('<div class="modal-backdrop fade in"></div>');
      dialog.dialog("open");
    });
  });


})(jQuery);
(function ($) {
  "use strict";

  $(function () {

    var dialog, form, objectToPost;

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
    });

    $("#wp-quick-post-draft-button-post").button().on("click", function (event) {
      event.preventDefault();
      getPostInfo('published');
      console.log('Object to Post: ', objectToPost);
    });

    $("#wp-quick-post-draft-button").button().on("click", function () {
      $('body').append('<div class="modal-backdrop fade in"></div>');
      dialog.dialog("open");
    });
  });


})(jQuery);
jQuery(document).ready(function($) {

    $(".wplp-add-category-image").click(function(event) {
        upload_button = $(this);
        var frame;

        event.preventDefault();
        if (frame) {
            frame.open();
            return;
        }
        frame = wp.media();
        frame.on( "select", function() {
            // Grab the selected attachment.
            var attachment = frame.state().get("selection").first();
            frame.close();
            if (upload_button.parent().prev().children().hasClass("tax_list")) {
                upload_button.parent().prev().children().val(attachment.attributes.url);
                upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
            }
            else{
                $(".wplp-category-image").attr("src", attachment.attributes.url);
                $(".wplp-category-image").attr("style", "max-width:300px;max-height: 300px;");
                $("#wplp-category-image").val(attachment.attributes.url);
            }

        });
        frame.open();
    });

    $(".wplp-remove-category-image").click(function() {
        $(".wplp-category-image").attr("src", default_image.image);
        $(".wplp-category-image").attr("style", "width:150px;height: 150px;");
        $("#wplp-category-image").val("");
        $(this).parent().siblings(".title").children("img").attr("src",default_image.image);
        $(".inline-edit-col :input[name='wplp-category-image']").val("");
        return false;
    });

    $(".editinline").on("click", function() {
        var tax_id = $(this).parents("tr").attr("id").substr(4);
        var thumb = $("#tag-"+tax_id+" .wplp_thumb img").attr("src");

        if (thumb !== default_image.image) {
            $(".inline-edit-col :input[name='wplp-category-image']").val(thumb);
        } else {
            $(".inline-edit-col :input[name='wplp-category-image']").val("");
        }
    });

});
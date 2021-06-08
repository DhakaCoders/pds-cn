jQuery(function ($) {
    $('.load-more-btn').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var current = $(this).data("current");
        var post_per_page = $(this).data("post-per-page");
        var post_type = $(this).data("post-type");
        var category = $(this).data("category");

        var this_button = $(this);
        this_button.parent().addClass('loading');
        $.post(
            ajaxurl,
            {
                'action': 'load_more_action',
                'data': {current_page: current, posts_per_page: post_per_page, post_type: post_type, category: category}
            },
            function (response) {
                var load_more_container = $("." + post_type + "-list[data-load-more-container]");
                load_more_container.append($(response));
                if ($("." + post_type + "-list[data-load-more-container] .next-no-post").length > 0) {
                    this_button.parent().hide();
                } else {
                    this_button.data("current", this_button.data("current") + 1);
                }
                TWC.dataLink();
                TWC.productShowingEffect();

                // scroll down 1px to trigger show product
                this_button.parent().removeClass('loading');
                $('html,body').animate({
                    scrollTop: $(window).scrollTop() + 1
                }, 700);
            }
        );
    });
});
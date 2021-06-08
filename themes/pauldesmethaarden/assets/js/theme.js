/*
 * Table of Contents:
 *
 * 1 - Replace Email
 * 2 - Wait for element load
 * 3 - Data-link Click Function
 * 4 - Mobile Menu
 * 5 - Init Functions
 * ----------------------------------------------------------------------------
 */

TWC = {};

/**
 * Check if element is visible after scrolling
 * https://stackoverflow.com/questions/487073/check-if-element-is-visible-after-scrolling
 * @constructor
 */
function Utils() {
}

Utils.prototype = {
    constructor: Utils,
    isElementInView: function (element, fullyInView) {
        var pageTop = jQuery(window).scrollTop();
        var pageBottom = pageTop + jQuery(window).height();
        var elementTop = jQuery(element).offset().top;
        var elementBottom = elementTop + jQuery(element).height();

        if (fullyInView === true) {
            return ((pageTop < elementTop) && (pageBottom > elementBottom));
        } else {
            return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
        }
    }
};

var checkVisible = new Utils();

jQuery(document).ready(function ($) {

    /*
     * REPLACE EMAIL
     */

    String.prototype.replaceAll = function (find, replace) {
        var str = this;
        return str.replace(new RegExp(find.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g'), replace);
    };

    $.fn.ignore = function (sel) {
        return this.clone().find(sel).remove().end();
    };

    function get_mail_from_website() {
        var regEx = /[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+/g;
        var email = new Array();
        var elements = ["footer", "#primary", "header"];
        for (var i = 0; i < elements.length; i++) {
            var temp = new Array();
            $(elements[i]).filter(function () {
                email = $(this).ignore("input,a").html().match(regEx);
                $(email).each(function (index, val) {
                    var flag = true;
                    $(temp).each(function (indexT, val) {
                        if (temp[indexT] == email[index])
                            flag = false;
                    });
                    if (flag) {
                        var arr = val.split('@');
                        $(elements[i]).html($(elements[i]).html().replaceAll(val, "<a class=\"link_email\" href=\"javascript:mailto(['" + arr[0] + "','" + arr[1] + "'].join('@'))\">" + val + "</a>"));
                        temp.push(email[index]);
                    }
                });
            });
        }
    }

    get_mail_from_website();

    /*
     * WAIT FOR ELEMENT LOAD FUNCTION
     */

    TWC.waitForEl = function (selector, callback) {
        if (jQuery(selector).length) {
            callback();
        } else {
            setTimeout(function () {
                TWC.waitForEl(selector, callback);
            }, 100);
        }
    };

    /*
     * MOBILE MENU
     */

    TWC.mobileMenu = function () {
        $('.btn_menu').on('click', function () {
            $(this).toggleClass('btn_opened');
            $('body').toggleClass('mobile-menu-open');
        });
        $(window).on('load resize', function () {
            if ($(window).width() > 1023) {
                $('body').removeClass('mobile-menu-open');
            }
        });
    };

    /*
     * INIT SLICK
     */
    var clickable = true;
    TWC.initSlick = function () {
        var slickInit = $("[data-slick]");
        if (slickInit.length > 0) {
            slickInit.slick();
        }
        $('.product-gallery-inner').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.product-thumbs-inner'
        });
        $('.product-thumbs-inner').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.product-gallery-inner',
            dots: false,
            arrows: true,
            focusOnSelect: true
        });
        $('.related-slider-inner').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            infinite: true,
            responsive: [
                {
                    breakpoint: 640,
                    settings: {slidesToShow: 2}
                },
                {
                    breakpoint: 420,
                    settings: {slidesToShow: 1}
                }
            ]
        });
        $('.related-slider-inner').on('beforeChange', function () {
            clickable = false;
        });
        $('.related-slider-inner').on('afterChange', function () {
            clickable = true;
        });
    };

    /*
     * DATA-LINK CLICK FUNCTION
     */

    TWC.dataLink = function () {
        var data_link = $('[data-link]');
        data_link.css('cursor', 'pointer');
        data_link.on('click', function (e) {
            var url = $(this).attr('data-link');
            if ($(this).closest('.related-slider-inner').length > 0) {
                if (clickable === true) {
                    if (url != null) {
                        window.location.href = url;
                    }
                }
            } else {
                if (url != null) {
                    window.location.href = url;
                }
            }
        });
    };

    /**
     * Check if element is in viewport
     * @param $el
     * @returns {*}
     */
    TWC.isVisible = function ($el) {
        return checkVisible.isElementInView($el, false);
    };

    /**
     * Showing effect for product item in archive page
     */
    TWC.productShowingEffect = function () {
        // only run in archive page
        if (!$('body').hasClass('archive')) return;

        // loop
        $(".product-list .item:not(.twc-animated)").each(function () {
            var _this = $(this);
            _this.addClass('twc-invisible');
            $(window).on('scroll load', function () {
                if (TWC.isVisible(_this)) {
                    // in viewport
                    _this.removeClass('twc-invisible');
                    _this.addClass('twc-animated');
                }
            });
        });
    };

    /**
     * Handle loading state
     */
    TWC.handleLoadingState = function () {
        $(window).on('load', function () {
            if ($('body').hasClass('twc-page-loading')) {

                // Remove loading class after page loaded
                $('body').removeClass('twc-page-loading');

                // Add loading class when user leave page
                window.onbeforeunload = on_leaving_page;

                // When a mailto link clicked
                $('a[href*=mailto]').click(function () {
                    mailtoClicked = true;
                });
            }
        });
    };

    /**
     * Insta feed slick arrow button position
     */
    TWC.instaFeedButton = function () {
        var $btn_prev = $('.main_content .insta-content .slick-prev'),
            $btn_next = $('.main_content .insta-content .slick-next'),
            containerSideOffset = $('#content > .container').offset().left + 20; // 20 is container padding

        // set position
        $btn_next.css('right', containerSideOffset + 'px');
        $btn_prev.css('right', containerSideOffset + $btn_next.width() + 20 + 'px'); // 20 is space between two buttons
    };

    /*
     * Instagram feed
     */
    TWC.waitForEl("#sb_instagram .sbi_item", function () {
        $(".sbi_item").wrapAll("<div class='sbi_images_slider'></div>");
        $(".sbi_item").wrap("<div class='sbi_item_wrapper'></div>");
        $('.sbi_images_slider').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            dots: false,
            arrows: true,
            focusOnSelect: true,
            variableWidth: true
        });

        // set slick arrow position
        TWC.instaFeedButton();
        $(window).on('load resize', function () {
            TWC.instaFeedButton();
        });
    });

    /**
     * Scroll down button
     */
    TWC.scrollDownTopButton = function () {
        $('.home-banner .scroll-down').click(function () {
            $('html,body').animate({
                scrollTop: $(window).height()
            }, 700);
        });

        $('.scroll-top').click(function () {
            $('html,body').animate({
                scrollTop: 0
            }, 700);
        });
    };

    /**
     * Product category effect
     */
    TWC.productCategoryInit = function () {
        // Calc position
        $('.twc-product-categories .category-item').each(function () {
            var _this = $(this),
                info = _this.find('.category-item-info'),
                imageWrapper = _this.find('.category-item-images'),
                mainImage = _this.find('.category-main-image'),
                subImage = _this.find('.category-sub-image');

            // run on load, resize
            $(window).on('load resize', function () {
                if ($(window).width() > 768) {
                    var sub_offsetTop = info.height() + 40;
                    // set value
                    subImage.css('top', sub_offsetTop + 'px');
                    imageWrapper.css('padding-bottom', (sub_offsetTop + subImage.height()) - mainImage.height());
                } else {
                    // reset value
                    subImage.css('top', '');
                    imageWrapper.css('padding-bottom', '');
                }
            });
        });

        // Visible control
        var $el = $('.category-item-images > div');
        $el.addClass('twc-invisible');
        $el.each(function () {
            var _this = $(this);
            $(window).on('load scroll', function () {
                if (TWC.isVisible(_this)) {
                    // in viewport
                    _this.removeClass('twc-invisible');
                    _this.addClass('twc-animated');
                }
            });
        });
    };

    /**
     * Parallax using background image
     */
    TWC.backgroundParallax = function (el, scrollRatio, backgroundSize) {
        // set default value
        scrollRatio = typeof scrollRatio !== 'undefined' ? scrollRatio : 0.1;
        backgroundSize = typeof backgroundSize !== 'undefined' ? backgroundSize : '120%';

        $(el).each(function () {
            var _this = $(this);

            // set background size
            _this.css('background-size', backgroundSize);

            // process
            $(window).on('load resize', function () {
                var offset = _this.offset().top, wScroll = 0, scroll_window;

                if ($(window).width() > 768) {
                    // set background position on scroll
                    $(window).on("scroll", function () {
                        scroll_window = $(window).scrollTop();
                        wScroll = (scroll_window - offset) * scrollRatio;
                        _this.css('background-position', '50% ' + wScroll + 'px');
                    });
                } else {
                    _this.css('background-position', '');
                    _this.css('background-size', '');
                }
            });
        });
    };

    TWC.readMoreElement = function () {
        var $begin = $('.read-more-begin'),
            $end = $('.read-more-end');

        // Wrap content to be hidden
        $begin.nextUntil($end).wrapAll("<section class='read-more-wrapper'></section>");

        // Hide content
        var $content = $('.read-more-wrapper');
        $content.slideUp();

        // Add events to button
        var $button = $end.find('.read-more-button');
        $button.click(function (e) {
            e.preventDefault();
            $content.slideDown();
            $button.hide();
            $end.addClass('active');
        });
    };

    TWC.ctaCallOnMobile = function () {
        $(window).on('load resize', function () {
            var cta = $('.cta-button a');
            if ($(window).width() <= 768) {
                cta.attr('href', cta.attr('title'));
            } else {
                cta.attr('href', cta.attr('data-href'));
            }
        });
    };

    $('.product-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: false,
        arrows: true,
        responsive: [
            {
                breakpoint: 640,
                settings: {slidesToShow: 2}
            },
            {
                breakpoint: 420,
                settings: {slidesToShow: 1}
            }
        ]
    });

    /*
     * INIT FUNCTIONS
     */

    TWC.loadFunctions = function () {
        TWC.dataLink();
        TWC.mobileMenu();
        TWC.initSlick();
        TWC.productShowingEffect();
        TWC.handleLoadingState();
        TWC.scrollDownTopButton();
        TWC.productCategoryInit();
        TWC.backgroundParallax('.category-main-image > div', 0.1);
        TWC.backgroundParallax('.category-sub-image > div', 0.03);
        TWC.readMoreElement();
        TWC.ctaCallOnMobile();

        /*$(window).on('load', function () {
            revapi1.bind("revolution.slide.onvideoplay", function (e, data) {
                var player = data.video;
                //player.unMute();
                //player.setVolume(50);
                //player.pauseVideo();
                console.log(data);
            });
        });*/

        $('select').niceSelect();

        if (navigator.userAgent.match(/iPad/i) != null) {
            $('body').addClass('ipad-browser');
        }
    };

    TWC.loadFunctions();


});

function mailto(address) {
    document.location.href = 'mail' + 'to:' + address;
}

/**
 * Add loading class
 * This function placed outside on page loaded
 */
var mailtoClicked = false;

function on_leaving_page() {
    if (!mailtoClicked) {
        jQuery('body').addClass('twc-page-leaving');
    } else {
        mailtoClicked = false;
    }
}
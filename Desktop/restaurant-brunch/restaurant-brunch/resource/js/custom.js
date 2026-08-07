jQuery(function($) {

    /* -----------------------------------------
    Preloader
    ----------------------------------------- */
    $('#preloader').delay(1000).fadeOut();
    $('#loader').delay(1000).fadeOut("slow");

    /* -----------------------------------------
    Navigation
    ----------------------------------------- */
    $('.menu-toggle').click(function() {
        $(this).toggleClass('open');
    });

    /* -----------------------------------------
    Keyboard Navigation
    ----------------------------------------- */
    $(window).on('load resize', restaurant_brunch_navigation)

    function restaurant_brunch_navigation(event) {
        if ($(window).width() < 1200) {
            $('.main-navigation').find("li").last().bind('keydown', function(e) {
                if (e.shiftKey && e.which === 9) {
                    if ($(this).hasClass('focus')) {
                    }

                } else if (e.which === 9) {
                    e.preventDefault();
                    $('#masthead').find('.menu-toggle').focus();
                }                
            })
        } else {
            $('.main-navigation').find("li").unbind('keydown')
        }
    }

    restaurant_brunch_navigation()

    var restaurant_brunch_primary_menu_toggle = $('#masthead .menu-toggle');
    restaurant_brunch_primary_menu_toggle.on('keydown', function(e) {
        var tabKey = e.keyCode === 9;
        var shiftKey = e.shiftKey;

        if (restaurant_brunch_primary_menu_toggle.hasClass('open')) {
            if (shiftKey && tabKey) {
                e.preventDefault();
                const $the_last_li = $('.main-navigation').find("li").last()
                $the_last_li.find('a').focus()
                if (!$the_last_li.hasClass('focus')) {

                    const $is_parent_on_top = true
                    let $the_parent_ul = $the_last_li.closest('ul.sub-menu')

                    let count = 0

                    while (!!$the_parent_ul.length) {
                        ++count

                        const $the_parent_li = $the_parent_ul.closest('li')

                        if (!!$the_parent_li.length) {
                            $the_parent_li.addClass('focus')
                            $the_parent_ul = $the_parent_li.closest('ul.sub-menu')

                            // Blur the cross
                            $(this).blur()
                            $the_last_li.addClass('focus')
                        }

                        if (!$the_parent_ul.length) {
                            break;
                        }
                    }

                }

            };
        }
    })

    /* -----------------------------------------
    Main Slider
    ----------------------------------------- */

    // Determine if the document is RTL
    var isRtl = $('html').attr('dir') === 'rtl';
    
    $('.banner-slider').slick({
        autoplaySpeed: 3000,
        dots: false,
        arrows: false,
        nextArrow: '<button class="fas fa-angle-right slick-next"></button>',
        prevArrow: '<button class="fas fa-angle-left slick-prev"></button>',
        rtl: isRtl, // Set the rtl option
        responsive: [{
            
            breakpoint: 1025,
            settings: {
                dots: false,
                arrows: false,
            }
        },
        {
            breakpoint: 480,
            settings: {
                dots: false,
                arrows: false,
            }
        }],
        autoplayHoverPause: false,
        mouseDrag: true
    });

    /* -----------------------------------------
    Category Section
    ----------------------------------------- */

    $('.category-section').slick({
        slidesToShow: 4, 
        autoplaySpeed: 3000,
        dots: false,
        arrows: false,
        autoplay: true,
        nextArrow: '<button class="fas fa-angle-right slick-next"></button>',
        prevArrow: '<button class="fas fa-angle-left slick-prev"></button>',
        rtl: isRtl,
        responsive: [
            {
                breakpoint: 992, 
                settings: {
                    slidesToShow: 3, 
                    dots: false,
                    arrows: false
                }
            },
            {
                breakpoint: 768, 
                settings: {
                    slidesToShow: 2, 
                    dots: false,
                    arrows: false
                }
            },
            {
                breakpoint: 601, 
                settings: {
                    slidesToShow: 1, 
                    dots: false,
                    arrows: false
                }
            }
        ],
        autoplayHoverPause: false,
        mouseDrag: true
    });
    

    /* -----------------------------------------
    Scroll Top
    ----------------------------------------- */
    var restaurant_brunch_scrollToTopBtn = $('.restaurant-brunch-scroll-to-top');

    $(window).scroll(function() {
        if ($(window).scrollTop() > 400) {
            restaurant_brunch_scrollToTopBtn.addClass('show');
        } else {
            restaurant_brunch_scrollToTopBtn.removeClass('show');
        }
    });

    restaurant_brunch_scrollToTopBtn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, '300');
    });

    //search js

    $(".input").focus(function() {
        $(".form").addClass("move");
    });
    $(".input").focusout(function() {
        $(".form").removeClass("move");
        $(".input").val("");
    });

    $(".search-main .btn").click(function() {
        $(".input").toggleClass("active");
        $(".form").toggleClass("active");
    });  
    
});

document.addEventListener('DOMContentLoaded', function() {
  const header = document.querySelector('.sticky-header');
  if (header) { // Check if header exists
    window.addEventListener('scroll', function() {
      if (window.scrollY > 0) {
        header.classList.add('is-sticky');
      } else {
        header.classList.remove('is-sticky');
      }
    });
  }
});
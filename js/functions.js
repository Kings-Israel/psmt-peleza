(function ($) {

	"use strict";

	// Preload
    $(window).on("load", function (){  // makes sure the whole site is loaded
		'use strict';
		$('[data-loader="circle-side"]').fadeOut(); // will first fade out the loading animation
		$('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
		var $hero= $('.hero_home .content');
		var $hero_v= $('#hero_video .content ');
		$hero.find('h3, p, form').addClass('fadeInUp animated');
		$hero.find('.btn_1').addClass('fadeIn animated');
		$hero_v.find('.h3, p, form').addClass('fadeInUp animated');
		$(window).scroll();
	})

	// Sticky nav + scroll to top
	var $headerStick = $('.header_sticky');
	var $toTop = $('#toTop');
	$(window).on("scroll", function () {
		if ($(this).scrollTop() > 1) {
			$headerStick.addClass("sticky");
		} else {
			$headerStick.removeClass("sticky");
		};
		if ($(this).scrollTop() != 0) {
			$toTop.fadeIn();
		} else {
			$toTop.fadeOut();
		}
	});
	$toTop.on("click", function () {
		$('body,html').animate({
			scrollTop: 0
		}, 500);
	});
	
	// Selectbox
	$(".selectbox").selectbox();

	// Sticky horizontal results list page
	$("#results").stick_in_parent({
		offset_top: 0
	});

	// Sticky sidebar
	$('#sidebar').theiaStickySidebar({
		additionalMarginTop: 95
	});

	// Mobile Mmenu
	var $menu = $("nav#menu").mmenu({
		"extensions": ["pagedim-black"],
		counters: false,
		keyboardNavigation: {
			enable: true,
			enhance: true
		},
		navbar: {
			title: 'MENU'
		},
		navbars: [{position:'bottom',content: ['<a href="#0">© 2018 PSMT</a>']}]}, 
		{
		// configuration
		clone: true
	});
	var $icon = $("#hamburger");
	var API = $menu.data("mmenu");
	$icon.on("click", function () {
		API.open();
	});
	API.bind("open:finish", function () {
		setTimeout(function () {
			$icon.addClass("is-active");
		}, 100);
	});
	API.bind("close:finish", function () {
		setTimeout(function () {
			$icon.removeClass("is-active");
		}, 100);
	});

	// WoW - animation on scroll
	var wow = new WOW({
		boxClass: 'wow', // animated element css class (default is wow)
		animateClass: 'animated', // animation css class (default is animated)
		offset: 0, // distance to the element when triggering the animation (default is 0)
		mobile: true, // trigger animations on mobile devices (default is true)
		live: true, // act on asynchronously loaded content (default is true)
		callback: function (box) {
			// the callback is fired every time an animation is started
			// the argument that is passed in is the DOM node being animated
		},
		scrollContainer: null // optional scroll container selector, otherwise use window
	});
	wow.init();

	// Carousel
	$('#reccomended').owlCarousel({
		center: true,
		items: 2,
		loop: true,
		margin: 10,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1000: {
				items: 4
			}
		}
	});

	// Tooltips
	$('[data-toggle="tooltip"]').tooltip();
	
	// Detail page func
	$('#booking_date').dateDropper();
		$('#booking_time').timeDropper({
			setCurrentTime: false,
			meridians: true,
			primaryColor: "#929e36",
			borderColor: "#929e36",
			minutesInterval: '15'
		});
		
		var $sticky_nav= $('#secondary_nav');
		$sticky_nav.stick_in_parent();
		$sticky_nav.find('ul li a').on('click', function(e) {
			e.preventDefault();
			var target = this.hash;
			var $target = $(target);
			$('html, body').animate({
				'scrollTop': $target.offset().top - 95
			}, 800, 'swing');
		});
		$sticky_nav.find('ul li a').on('click', function() {
		$sticky_nav.find('.active').removeClass('active');
		$(this).addClass('active');
		});
	
	// Faq section
	$('#faq_box a[href^="#"]').on('click', function () {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
			|| location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			   if (target.length) {
				 $('html,body').animate({
					 scrollTop: target.offset().top -185
				}, 800);
				return false;
			}
		}
	});
	$('ul#cat_nav li a').on('click', function () {
		$('ul#cat_nav li a.active').removeClass('active');
		$(this).addClass('active');
	});
	// Accordion
	function toggleChevron(e) {
		$(e.target)
			.prev('.card-header')
			.find("i.indicator")
			.toggleClass('icon_minus_alt2 icon_plus_alt2');
	}
	$('.accordion').on('hidden.bs.collapse shown.bs.collapse', toggleChevron);
		function toggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".indicator")
            .toggleClass('icon_minus_alt2 icon_plus_alt2');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);


})(window.jQuery);
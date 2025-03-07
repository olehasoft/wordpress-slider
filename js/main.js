jQuery(document).ready(function ($) {

    new Swiper('.swiper-slider-gallery', {
        centeredSlides: true,
        slidesPerView: 'auto',
        spaceBetween: 16,
        breakpoints: {
            768: {
                centeredSlides: false,
                slidesPerView: 4,
                spaceBetween: 36,
            },
        },
        autoplay: {
            delay: 5000,
            pauseOnMouseEnter: true,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    $('.slider-popup-ajax').magnificPopup({
		type: 'ajax',
        fixedBgPos: true,
        fixedContentPos: true,
	});

});

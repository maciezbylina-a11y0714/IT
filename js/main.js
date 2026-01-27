$(document).ready(function(){

     $('.fa-bars').click(function(){
        $(this).toggleClass('fa-times');
        $('.navbar').toggleClass('nav-toggle');
    });

    $(window).on('load scroll',function(){
        $('.fa-bars').removeClass('fa-times');
        $('.navbar').removeClass('nav-toggle');

        if($(window).scrollTop()>35)
        {
            $('.header').css({'background':'#002e5f','box-shadow':'0 .2rem .5rem rgba(0,0,0,.4)'});
        }
        else
        {
            $('.header').css({'background':'none','box-shadow':'none'});
        }
    });

    const counters = document.querySelectorAll('.counter');
    const speed = 120;
    counters.forEach(counter => {
	const updateCount = () => {
		const target = +counter.getAttribute('data-target');
		const count = +counter.innerText;
		const inc = target / speed;
		if (count < target) {
			counter.innerText = count + inc;
			setTimeout(updateCount, 1);
		} else {
			counter.innerText = target;
		}
	};
	  updateCount();
   });

   (function ($) {
    "use strict";
    
    $(".clients-carousel").owlCarousel({
        autoplay: true,
        dots: true,
        loop: true,
        responsive: { 0: {items: 2}, 768: {items: 4}, 900: {items: 6} }
    });

    
})(jQuery);

$(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
        $('.back-to-top').fadeIn('slow');
    } else {
        $('.back-to-top').fadeOut('slow');
    }
});
$('.back-to-top').click(function () {
    $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
    return false;
});

$('.accordion-header').click(function(){
    $('.accordion .accordion-body').slideUp(500);
    $(this).next('.accordion-body').slideDown(500);
    $('.accordion .accordion-header span').text('+');
    $(this).children('span').text('-');
});

    // Portfolio pagination (6 per page)
    if ($('.portfolio-pagination').length) {
        var $items = $('.portfolio-item[data-page]');
        var totalPages = 2;

        function showPage(page) {
            page = Math.max(1, Math.min(page, totalPages));
            $items.removeClass('visible').filter('[data-page="' + page + '"]').addClass('visible');
            $('.portfolio-pagination .page-item').removeClass('active disabled');
            $('.portfolio-pagination .page-link[data-goto="' + page + '"]').parent().addClass('active');
            $('.portfolio-pagination .page-link[data-goto="prev"]').parent().toggleClass('disabled', page === 1);
            $('.portfolio-pagination .page-link[data-goto="next"]').parent().toggleClass('disabled', page === totalPages);
        }

        $('.portfolio-pagination').on('click', '.page-link', function(e) {
            e.preventDefault();
            if ($(this).parent().hasClass('disabled')) return;
            var goto = $(this).data('goto');
            var $active = $('.portfolio-pagination .page-item.active .page-link[data-goto="1"], .portfolio-pagination .page-item.active .page-link[data-goto="2"]');
            var current = parseInt($active.data('goto'), 10) || 1;
            if (goto === 'prev') showPage(current - 1);
            else if (goto === 'next') showPage(current + 1);
            else if (goto === 1 || goto === 2) showPage(goto);
        });

        showPage(1);
    }

});
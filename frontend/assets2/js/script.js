

// Slider func
$(document).ready(function(){
    $('.owl-carousel').owlCarousel();
});
$('.owl-carousel').owlCarousel({
    loop: true,
    margin: 0,
    nav: true,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 3
        }
    }
})

// On Hover Dropdown
$(document).ready(function () {
  $('.dropdown-toggle').mouseover(function() {
      $('.dropdown-menu').show();
  })

  $('.dropdown-toggle').mouseout(function() {
      t = setTimeout(function() {
          $('.dropdown-menu').hide();
      }, 100);

      $('.dropdown-menu').on('mouseenter', function() {
          $('.dropdown-menu').show();
          clearTimeout(t);
      }).on('mouseleave', function() {
          $('.dropdown-menu').hide();
      }) 
  })
});

// On Phone Auto open Header Dropdown
var isMobile = Math.min(window.screen.width, window.screen.height) < 768 || navigator.userAgent.indexOf("Mobi") > -1;
if(isMobile){
    $('.dropdown-menu').show();
}
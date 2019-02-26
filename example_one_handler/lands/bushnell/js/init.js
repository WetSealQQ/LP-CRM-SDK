$(function(){
    $('a[href^="#"]').click(function (){
        var elementClick = $(this).attr("href");
        var destination = $(elementClick).offset().top;
        jQuery("html:not(:animated), body:not(:animated)").animate({scrollTop: destination}, 800);
        return false;
    }); 

      $(".slider-rev").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: $(".arrow-left1"),
        nextArrow: $(".arrow-right1")
      });

      $(".slider-top").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: $(".arrow-left"),
        nextArrow: $(".arrow-right")
      });
});
function buildCarousel(dir)
{
    $.get( "/images.php?dir="+dir, function( data ) {
        if(data.status=='ok')
        {
            $(".carousel").html("");
            for(i in data.images)
            {
                $(".carousel").append('<div><a target="_blank" href="'+data.images[i]+'"><img height="300px" src="'+data.images[i]+'" /></a></div>')
            }

            $('.carousel').slick({
                dots: true,
                infinite: false,
                speed: 300,
                slidesToShow: 1,
                centerMode: false,
                variableWidth: true
                });
        }
      },"json");
}
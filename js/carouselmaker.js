function buildCarousel(dir)
{
    $.get("/images.php?dir=" + dir, function (data) {
        if (data.status == 'ok') {

            if (data.images.length > 0) {
                $(".carousel").html("");
                for (i in data.images) {
                    $(".carousel").append('<div class="text-center"><a target="_blank" href="' + data.images[i].file + '"><img height="300px" src="' + data.images[i].file + '/300" /></a>'+(data.images[i].text?'<h5>'+data.images[i].text+'</h5>':'')+'</div>')
                }

                $('.carousel').slick({
                    dots: true,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 1,
                    centerMode: false,
                    variableWidth: true
                });
            }

            if (data.videos.length > 0) {
                $(".videos").html("");
                for (i in data.videos) {
                    $(".videos").append('<div class="text-center"> \
                            <video class="img-fluid poster="'+data.videos[i]+'/preview" controls loop="loop" webkit-playsinline>   \
                                <source src="'+data.videos[i]+'/raw" type="video/mp4">\
                            </video>                   \
                    </div>')
                }
            }
        }
    }, "json");
}
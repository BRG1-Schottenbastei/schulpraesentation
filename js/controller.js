$(document).ready(function () {
    loadFloorplan(3)
    if (location.hash != '') {
        var page = location.hash.replace(/^#/, '');

        loadHBS('views/' + page + '.hbs', 'main', {})
    }
});

$(window).on('hashchange', function (e) {
    e.preventDefault();
    var page = location.hash.replace(/^#/, '');
    loadHBS('views/' + page + '.hbs', 'main', {})
});


function loadHBS(url, rendertoelement, viewdata) {
    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            template = Handlebars.compile(data);
            $(rendertoelement).html(template(viewdata));
            $(window).scrollTop($(rendertoelement).offset().top);
        }
    });
}

function loadFloorplan(floor)
{
    $.ajax({
        url: floor+'.stock.svg',
        cache: false,
        success: function (data) {
            $("#floorplan").html(new XMLSerializer().serializeToString(data.documentElement));
            $("#floorplan").children('svg').addClass("img-fluid");
        }
    });
}
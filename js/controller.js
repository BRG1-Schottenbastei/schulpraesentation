$(document).ready(function () {
    //loadFloorplan(3)
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
            var converter = new showdown.Converter(),
            text      = template(viewdata),
            html      = converter.makeHtml(text);
            $(rendertoelement).html(html);
            $(window).scrollTop($(rendertoelement).offset().top);
        }
    });
}

function loadFloorplan(floor)
{
    $.ajax({
        url: 'plans/'+floor+'.stock.svg',
        cache: false,
        success: function (data) {
            $("#floorplan").html(new XMLSerializer().serializeToString(data.documentElement));
            $("#floorplan").children('svg').addClass("img-fluid");
        }
    });
}
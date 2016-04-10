$(".scrollTo").click(function() {
    $('html, body').animate({
        scrollTop: $(".panel[device=" + $(this).html() + "]").offset().top - 70
    }, 1000);
});


var deviceTemplate = '<div class="panel panel-default" device="{ $DEVICE }"><div class="panel-heading">{ $DEVICE }</div><table class="table"><thead><tr><th>#</th><th>Version</th><th>Region</th><th>Release date</th><th>Download link</th></tr></thead><tbody></tbody></table></div>';
var firmwareTemplate = '<tr><th scope="row">{ $INDEX }</th><td>{ $VERSION }</td><td>{ $REGION }</td><td>{ $RELEASE_DATE }</td><td><a href="{ $URL }">{ $URL }</a></td>';

$.getJSON('./api.php', function(data) {
    $.each(data, function(device, firmwares) {
        $(".container").append(deviceTemplate.replace(/{ \$DEVICE }/g, device));

        $.each(firmwares, function(index, firmware) {
$(".container > div[device=" + device + "] tbody").append(
        firmwareTemplate.replace(/{ \$INDEX }/g, index + 1)
    .replace(/{ \$VERSION }/g, firmware['version'])
    .replace(/{ \$RELEASE_DATE }/g, firmware['release_date'])
    .replace(/{ \$REGION }/g, firmware['region'])
    .replace(/{ \$URL }/g, firmware['url'])
);
        });
    });

    $("#spinner").remove();
});

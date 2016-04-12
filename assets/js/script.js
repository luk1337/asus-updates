var deviceMenuTemplate = '<li><a href="javascript:;" class="scrollTo">{ $DEVICE }</a></li>';
var deviceTemplate = '<div class="panel panel-default" device="{ $DEVICE }"><div class="panel-heading">{ $DEVICE }</div><table class="table"><thead><tr><th>#</th><th>Version</th><th>Region</th><th>Release date</th><th>Changelog</th><th>Download link</th></tr></thead><tbody></tbody></table></div>';
var firmwareTemplate = '<tr><th scope="row">{ $INDEX }</th><td>{ $VERSION }</td><td>{ $REGION }</td><td>{ $RELEASE_DATE }</td><td><a href="javascript:;" device="{ $DEVICE }" changelogID="{ $CHANGELOG_ID }" class="showChangelog">Show changelog</a></td><td><a href="{ $URL }">Download</a></td>';
var changelogs = [];

$.getJSON('./api.php', function(data) {
    $.each(data, function(device, firmwares) {
        $(".container").append(deviceTemplate.replace(/{ \$DEVICE }/g, device));
        $("#devices").append(deviceMenuTemplate.replace(/{ \$DEVICE }/g, device));
        changelogs[device] = [];

        $.each(firmwares, function(index, firmware) {
            changelogs[device][index] = firmware['description'];

            $(".container > div[device=" + device + "] tbody").append(
                firmwareTemplate.replace(/{ \$INDEX }/g, index + 1)
                        .replace(/{ \$VERSION }/g, firmware['version'])
                        .replace(/{ \$RELEASE_DATE }/g, firmware['release_date'])
                        .replace(/{ \$DEVICE }/g, device)
                        .replace(/{ \$CHANGELOG_ID }/g, index)
                        .replace(/{ \$REGION }/g, firmware['region'])
                        .replace(/{ \$URL }/g, firmware['url'])
               );
        });
    });

    $("#spinner").remove();

    $(".showChangelog").click(function() {
        var changelogID = parseInt($(this).attr('changelogID'));
        var device = $(this).attr('device');

        $("#changelog .modal-body").html(changelogs[device][changelogID]);
        $("#changelog").modal('show');
    });

    $(".scrollTo").click(function() {
        $('html, body').animate({
            scrollTop: $(".panel[device=" + $(this).html() + "]").offset().top - 70
        }, 1000);
    });
});

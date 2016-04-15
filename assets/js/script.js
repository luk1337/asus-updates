var deviceMenuTemplate = '<li><a href="javascript:;" class="scrollTo">{ $DEVICE }</a></li>';
var deviceTemplate = '<div class="panel panel-default" device="{ $DEVICE }"><div class="panel-heading">{ $DEVICE }<div class="btn-group pull-right"> <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Firmware <span class="caret"></span> </button> <ul class="dropdown-menu"> <li><a href="javascript:;" type="firmware">Firmware</a></li><li><a href="javascript:;" type="source_code">Kernel</a></li></ul></div></div><table class="table"><thead><tr><th>#</th><th>Version</th><th>Region</th><th>Release date</th><th>Changelog</th><th>Download link</th></tr></thead><tbody></tbody></table></div>';
var firmwareTemplate = '<tr class="type_{ $TYPE }"><th scope="row">{ $INDEX }</th><td>{ $VERSION }</td><td>{ $REGION }</td><td>{ $RELEASE_DATE }</td><td><a href="javascript:;" device="{ $DEVICE }" changelogID="{ $CHANGELOG_ID }" type="{ $TYPE }" class="showChangelog">Show changelog</a></td><td><a href="{ $URL }">Download</a></td>';
var changelogs = [];

$.getJSON('./api.php', function(data) {
    $.each(data, function(device, types) {
        $(".container").append(deviceTemplate.replace(/{ \$DEVICE }/g, device));
        $("#devices").append(deviceMenuTemplate.replace(/{ \$DEVICE }/g, device));
        changelogs[device] = [];

        $.each(types, function(index, type) {
            var typeName = index;
            changelogs[device][typeName] = [];

            $.each(type, function(index, value) {
                changelogs[device][typeName][index] = value['description'];
                $(".container > div[device=" + device + "] tbody").append(
                    firmwareTemplate.replace(/{ \$TYPE }/g, typeName)
                            .replace(/{ \$INDEX }/g, index + 1)
                            .replace(/{ \$VERSION }/g, value['version'])
                            .replace(/{ \$RELEASE_DATE }/g, value['release_date'])
                            .replace(/{ \$DEVICE }/g, device)
                            .replace(/{ \$CHANGELOG_ID }/g, index)
                            .replace(/{ \$REGION }/g, value['region'])
                            .replace(/{ \$URL }/g, value['url'])
                );
            });
        });
    });

    $("#spinner").remove();

    $(".btn-group a").click(function() {
        var tbody = $(this).parent().parent().parent().parent().parent().children("table").children("tbody");

        $(".dropdown-menu a").parent().parent().parent().children("button").html($(this).html() + " <span class=\"caret\"></span>");
        tbody.children("tr").css('display', 'none');
        tbody.children(".type_" + $(this).attr('type')).css('display', 'table-row');
    });

    $(".showChangelog").click(function() {
        var changelogID = parseInt($(this).attr('changelogID'));
        var type = $(this).attr('type');
        var device = $(this).attr('device');

        $("#changelog .modal-body").html(changelogs[device][type][changelogID]);
        $("#changelog").modal('show');
    });

    $(".scrollTo").click(function() {
        $('html, body').animate({
            scrollTop: $(".panel[device=" + $(this).html() + "]").offset().top - 70
        }, 1000);
    });
});

var deviceMenuTemplate = '<li><a href="javascript:;" class="scrollTo">{ $DEVICE }</a></li>';
var deviceTemplate = '<div class="panel panel-default" device="{ $DEVICE }"><div class="panel-heading">{ $DEVICE }<div class="btn-group pull-right"> <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Firmware <span class="caret"></span> </button> <ul class="dropdown-menu"><li><a href="javascript:;" category="emi_and_safety">EMI and Safety</a></li><li><a href="javascript:;" category="firmware">Firmware</a></li><li><a href="javascript:;" category="usb">USB</a></li><li><a href="javascript:;" category="source_code">Source Code</a></li><li><a href="javascript:;" category="manual">Manual</a></li></ul></div></div><table class="table"><thead><tr><th>#</th><th>Version</th><th>Release date</th><th>Changelog</th><th>Download link</th></tr></thead><tbody></tbody></table></div>';
var firmwareTemplate = '<tr class="category_{ $CATEGORY }"><th scope="row">{ $INDEX }</th><td>{ $VERSION }</td><td>{ $RELEASE_DATE }</td><td><a href="javascript:;" device="{ $DEVICE }" changelogID="{ $CHANGELOG_ID }" category="{ $CATEGORY }" class="showChangelog">Show changelog</a></td><td><a href="{ $URL }">Download</a></td>';
var changelogs = [];

$.getJSON('./api.php', function(data) {
    $.each(data, function(device, categories) {
        changelogs[device] = [];

        $(".container").append(deviceTemplate.replace(/{ \$DEVICE }/g, device));
        $("#devices").append(deviceMenuTemplate.replace(/{ \$DEVICE }/g, device));

        $.each(categories, function(categoryName, categoryValues) {
            changelogs[device][categoryName] = [];

            $.each(categoryValues, function(index, value) {
                changelogs[device][categoryName][index] = value['description'];

                $(".container > div[device=" + device + "] tbody").append(
                    firmwareTemplate.replace(/{ \$CATEGORY }/g, categoryName)
                            .replace(/{ \$INDEX }/g, index + 1)
                            .replace(/{ \$VERSION }/g, value['version'])
                            .replace(/{ \$RELEASE_DATE }/g, value['release_date'])
                            .replace(/{ \$DEVICE }/g, device)
                            .replace(/{ \$CHANGELOG_ID }/g, index)
                            .replace(/{ \$URL }/g, value['url'])
                );
            });
        });
    });

    $("#spinner").remove();

    $(".btn-group a").click(function() {
        var tbody = $(this).parents(".panel").find("tbody");
        var button = $(this).parents(".btn-group").children("button");

        button.html($(this).html() + " <span class=\"caret\"></span>");
        tbody.children("tr").css('display', 'none');
        tbody.children(".category_" + $(this).attr('category')).css('display', 'table-row');
    });

    $(".showChangelog").click(function() {
        var changelogID = parseInt($(this).attr('changelogID'));
        var category = $(this).attr('category');
        var device = $(this).attr('device');

        $("#changelog .modal-body").html(changelogs[device][category][changelogID]);
        $("#changelog").modal('show');
    });

    $(".scrollTo").click(function() {
        $('html, body').animate({
            scrollTop: $(".panel[device=" + $(this).html() + "]").offset().top - 70
        }, 1000);
    });
});

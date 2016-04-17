var deviceMenuTemplate = `<li>
    <a href="javascript:;" class="scrollTo">{ $DEVICE }</a>
</li>`;

var deviceTemplate = `<div class="panel panel-default" device="{ $DEVICE }">
    <div class="panel-heading">{ $DEVICE }
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Firmware <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="javascript:;" category="emi_and_safety">EMI and Safety</a></li>
                <li><a href="javascript:;" class="active" category="firmware">Firmware</a></li>
                <li><a href="javascript:;" category="usb">USB</a></li>
                <li><a href="javascript:;" category="utilities">Utilities</a></li>
                <li><a href="javascript:;" category="source_code">Source Code</a></li>
                <li><a href="javascript:;" category="manual">Manual</a></li>
            </ul>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Version</th>
                    <th>Release date</th>
                    <th>Description</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>`;

var firmwareTemplate = `<tr class="category_{ $CATEGORY }" device="{ $DEVICE }" descriptionID="{ $DESCRIPTION_ID }" category="{ $CATEGORY }">
    <th scope="row">{ $INDEX }</th>
    <td>{ $VERSION }</td>
    <td>{ $RELEASE_DATE }</td>
    <td><a href="javascript:;" class="showDescription">Show description</a></td>
    <td><a href="{ $URL }">Download</a></td>
</tr>`;

var descriptions = [];

$.getJSON('./api.php', function(data) {
    $.each(data, function(device, categories) {
        descriptions[device] = [];

        $(".container").append(deviceTemplate.replace(/{ \$DEVICE }/g, device));
        $("#devices").append(deviceMenuTemplate.replace(/{ \$DEVICE }/g, device));

        $.each(categories, function(categoryName, categoryValues) {
            descriptions[device][categoryName] = [];

            if (categoryValues.length == 0) {
                $(".panel[device=" + device +"] a[category=" + categoryName + "]").remove();
            }

            $.each(categoryValues, function(index, value) {
                descriptions[device][categoryName][index] = value['description'];

                $(".container > div[device=" + device + "] tbody").append(
                    firmwareTemplate.replace(/{ \$CATEGORY }/g, categoryName)
                            .replace(/{ \$INDEX }/g, index + 1)
                            .replace(/{ \$VERSION }/g, value['version'])
                            .replace(/{ \$RELEASE_DATE }/g, value['release_date'])
                            .replace(/{ \$DEVICE }/g, device)
                            .replace(/{ \$DESCRIPTION_ID }/g, index)
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

        $(this).parents('.dropdown-menu').find('a').attr('class', '');
        $(this).attr('class', 'active');
    });

    $(".showDescription").click(function() {
        var tr = $(this).parents('tr');
        var descriptionID = parseInt(tr.attr('descriptionID'));
        var category = tr.attr('category');
        var device = tr.attr('device');

        $("#description .modal-body").html(descriptions[device][category][descriptionID]);
        $("#description").modal('show');
    });

    $(".scrollTo").click(function() {
        if ($('.navbar-toggle').attr('aria-expanded') == 'true') {
            $('.navbar-toggle').click();
        }

        $('html, body').animate({
            scrollTop: $(".panel[device=" + $(this).html() + "]").offset().top - 70
        }, 1000);
    });
});

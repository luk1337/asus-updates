var deviceMenuTemplate = `<li>
    <a href="javascript:;" class="scrollTo">{ $DEVICE }</a>
</li>`;

var deviceTemplate = `<div class="panel panel-default" device="{ $DEVICE }">
    <div class="panel-heading">{ $DEVICE }
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
            <ul class="dropdown-menu"></ul>
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

            if (categoryValues.length > 1) {
                $(".panel[device=" + device +"] .dropdown-menu").append("<li><a href='javascript:;'>" + categoryName +"</a></li>");
                $(".panel[device=" + device +"] a:contains('Firmware')").parent().attr('class', 'active');
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

        tbody.children("tr").css('display', 'none');
        tbody.children("[category='" + $(this).html() + "']").css('display', 'table-row');

        $(this).parents('.dropdown-menu').find('li').attr('class', '');
        $(this).parent().attr('class', 'active');
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

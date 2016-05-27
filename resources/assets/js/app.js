function showDescription(data) {
    $("#description .modal-body").html(window.atob(data));
    $("#description").modal('show');
}
$(document).ready(function(){
    $("#form").validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
            controller_name: {
                required: true,
                minlength: 2
            }
        }
    });

    $('#selectall').on('click', function() {
        var checked = $(this).prop('checked');
        $('input.case').each(function() {
            $(this).prop('checked', checked);
        });
    });
});
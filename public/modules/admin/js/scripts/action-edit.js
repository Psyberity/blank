$(document).ready(function(){
    $("#form").validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
            action_name: {
                required: true,
                minlength: 2
            }
        }
    });

});
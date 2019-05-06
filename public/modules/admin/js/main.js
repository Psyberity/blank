$(document).ready(function() {
	$('body').on('click', '.delete-button', function() {
		var link = $(this).attr('link');
		if(confirm('Уверены, что хотите удалить запись?')) {
			document.location.href = link;
		}
	});

	$('body').on('click', '.checkbox_label', function() {
		var check_id = $(this).attr('check_id');
		var checkbox = $('#' + check_id);
		$(checkbox).prop('checked', !$(checkbox).prop('checked'));
	});

    $('body').on('click', '.group_check', function() {
        var check_group_id = $(this).attr('check_group_id');
        var checked = $(this).prop('checked');
        $('input.check_group').each(function() {
            if($(this).attr('check_group_id') == check_group_id) {
                $(this).prop('checked', checked);
            }
        });
    });
	
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd'
	});
});
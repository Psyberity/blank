<script type="text/javascript">
    var flashMessages = [];
    flashMessages['error'] = [];
    flashMessages['success'] = [];
    {% set flashMessages = flashSession.getMessages() %}
    {% if flashMessages is not empty %}
    {% for type, messages in flashMessages %}
    {% for message in messages %}
    flashMessages['{{ type }}'][flashMessages['{{ type }}'].length] = '{{ message }}';
    {% endfor %}
    {% endfor %}
    {% endif %}
</script>
<script type="text/javascript">
    $(document).ready(function() {
        toastr.options = {
            showDuration: '400',
            hideDuration: '1000',
            timeOut: '0',
            //timeOut: 7000,
            extendedTimeOut: '0',
            //extendedTimeOut: '1000',
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut',
            closeButton: true,
            debug: false,
            progressBar: true,
            preventDuplicates: false,
            positionClass: 'toast-top-right',
            onclick: null
        };

        var i, msg;
        if(flashMessages['error'].length > 0) {
            for(i = 0; i < flashMessages['error'].length; i++) {
                msg = flashMessages['error'][i];
                errorMessage(msg);
            }
        }
        if(flashMessages['success'].length > 0) {
            for(i = 0; i < flashMessages['success'].length; i++) {
                msg = flashMessages['success'][i];
                successMessage(msg);
            }
        }
    });

    function successMessage(msg) {
        toastr.options.timeOut = '7000';
        toastr.options.extendedTimeOut = '1000';
        toastr['success'](msg, '');
    }

    function errorMessage(msg) {
        toastr.options.timeOut = '0';
        toastr.options.extendedTimeOut = '0';
        toastr['error'](msg, 'Ошибка');
    }
</script>
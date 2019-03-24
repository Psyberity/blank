{% extends "partial/ce_container.volt" %}
{% block other %}
    <script type="text/javascript">
        var user_id = {{ user_id }};
        apiController = 'user';
        apiAction = 'select';
    </script>
{% endblock %}
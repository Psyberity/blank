{% extends "partial/index_container.volt" %}
{% block content %}
    <tr>
        <th><input type="checkbox" class="group_check" check_group_id="list_check"/></th>
        <th>Имя</th>
        <th>Роль</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
{% endblock %}
{% block other %}
    <script type="text/javascript">
        datatablesColumns = [
            {"sWidth": "10px", "aTargets": [0, 3, 4, 5]},
            {"bSortable": false, 'aTargets': [0, 3, 4, 5]},
            {"sClass": "text-center", "aTargets": [1, 2]}
        ];
        apiController = '{{ controllerName }}';
        apiAction = 'list';
        datatablesDeleteUrl = '/{{ controllerName }}/delete/';
    </script>
{% endblock %}
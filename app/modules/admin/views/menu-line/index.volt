{% extends "partial/index_container.volt" %}
{% block content %}
    <tr>
        <th><input type="checkbox" class="group_check" check_group_id="list_check"/></th>
        <th>Прародитель</th>
        <th>Родитель</th>
        <th>Название</th>
        <th>Контроллер</th>
        <th>Экшен</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
{% endblock %}
{% block other %}
    <script type="text/javascript">
        datatablesColumns = [
            {"sWidth": "10px", "aTargets": [0, 6, 7, 8]},
            {"bSortable": false, 'aTargets': [0, 1, 6, 7, 8]},
            {"sClass": "text-center", "aTargets": [1, 2, 3, 4, 5]}
        ];
        apiController = '{{ controllerName }}';
        apiAction = 'list';
        datatablesDeleteUrl = '/{{ controllerName }}/delete/';
    </script>
{% endblock %}
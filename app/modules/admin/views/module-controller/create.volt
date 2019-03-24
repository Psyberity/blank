{% extends "partial/ce_container.volt" %}
{% block tabs %}
    <li class="{% if tab == 'tab-actions' %}active{% endif %}"><a data-toggle="tab" href="#tab-actions">Экшены</a></li>
{% endblock %}
{% block content %}
    <div id="tab-actions" class="tab-pane {% if tab == 'tab-actions' %}active{% endif %}">
        <div class="panel-body">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px;"><input type="checkbox" class="group_check" check_group_id="action_list"/></th>
                        <th>Название экшена</th>
                        <th>Имя экшена</th>
                    </tr>
                </thead>
                <tbody>
                {% for action in actions %}
                    {% set check_id = 'a' ~ action.action_id %}
                    <tr>
                        <td><input type="checkbox" class="check_group" check_group_id="action_list" id="{{ check_id }}" name="action_ids[]" value="{{ action.action_id }}" /></td>
                        <td class="checkbox_label" check_id="{{ check_id }}">{{ action.name }}</td>
                        <td class="checkbox_label" check_id="{{ check_id }}">{{ action.action_name }}</td>
                    </tr>
                {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
{% endblock %}
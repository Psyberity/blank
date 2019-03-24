{% extends "partial/ce_container.volt" %}
{% block tabs %}
    <li class="{% if tab == 'tab-rights' %}active{% endif %}"><a data-toggle="tab" href="#tab-rights">Права</a></li>
{% endblock %}
{% block content %}
    <div id="tab-rights" class="tab-pane {% if tab == 'tab-rights' %}active{% endif %}">
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
                    {% for controller in controllers %}
                        <tr>
                            <td colspan="3">
                                <b>{{ controller.name }} ({{ controller.controller_name }})</b>
                            </td>
                        </tr>
                        {% for action in controller.actions %}
                            {% set check_id = 'mc' ~ controller.module_controller_id ~ 'a' ~ action.action_id %}
                            <tr>
                                <td><input type="checkbox" class="check_group" check_group_id="action_list" id="{{ check_id }}" name="action_ids[{{ controller.module_controller_id }}][]" value="{{ action.action_id }}"{{ acl is not empty and acl.isAllowed(item_id, controller.module_controller_id, action.action_id) ? ' checked' : '' }}/></td>
                                <td class="checkbox_label" check_id="{{ check_id }}">{{ action.name }}</td>
                                <td class="checkbox_label" check_id="{{ check_id }}">{{ action.action_name }}</td>
                            </tr>
                        {% endfor %}
                    {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
{% endblock %}
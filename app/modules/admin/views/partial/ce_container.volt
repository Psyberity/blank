<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ h2 }}</h2>
        {{ partial('partial/breadcrumbs') }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li class="{% if tab == 'tab-info' %}active{% endif %}"><a data-toggle="tab" href="#tab-info">Основное</a></li>
                    {% block tabs %}
                    {% endblock %}
                </ul>
                {% set form_action = controllerName ~ '/' ~ actionName %}
                {% if actionName == 'edit' and item_id is not empty %}
                    {% set form_action = form_action ~ '/' ~ item_id %}
                {% endif %}
                {% if item is empty %}
                    {% set item = null %}
                {% endif %}
                {{ form('action': form_action, 'method': 'post', 'enctype': 'multipart/form-data', 'id': 'form') }}
                    <div class="tab-content">
                        <div id="tab-info" class="tab-pane {% if tab == 'tab-info' %}active{% endif %}">
                            <div class="panel-body">
                                <fieldset class="form-horizontal">
                                    {% if fields|length > 0 %}
                                        {% for field in fields %}
                                            {{ field.render(render_action, item, ['module_id': moduleId]) }}
                                        {% endfor %}
                                    {% endif %}
                                </fieldset>
                            </div>
                        </div>
                        {% block content %}
                        {% endblock %}
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <input type="submit" value="{{ submit_label }}" class="btn btn-primary">
                                {{ link_to(controllerName, 'Отменить', 'class': 'btn btn-white') }}
                            </div>
                        </div>
                    </div>
                {{ endForm() }}
            </div>
        </div>
    </div>
</div>
{% block other %}
{% endblock %}
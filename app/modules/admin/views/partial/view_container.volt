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
                </div>
            </div>
        </div>
    </div>
</div>
{% block other %}
{% endblock %}
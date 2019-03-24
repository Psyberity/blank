<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ h2 }}</h2>
        {{ partial('partial/breadcrumbs') }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                                {% block content %}
                                {% endblock %}
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    {% block tfoot %}
                                    {% endblock %}
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% block other %}
{% endblock %}
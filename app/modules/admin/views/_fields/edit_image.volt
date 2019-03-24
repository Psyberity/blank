<div class="form-group">
    <label for="{{ field.getName() }}" class="col-sm-2 control-label">{{ field.getLabel() }}</label>
    <div class="col-sm-4">
        {{ field.render() }}
    </div>
    <div class="col-sm-5">
        {% if value is empty %}
            {% if value is defined %}
                <i>Отсутствует</i>
            {% endif %}
        {% else %}
            <img src="/resize.php?data={{ value }};300;150" />
        {% endif %}
    </div>
</div>
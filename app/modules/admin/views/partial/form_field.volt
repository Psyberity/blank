{% if field.getLabel() %}
    <div class="form-group">{{ form.label(field.getName(), ['class': 'col-sm-2 control-label']) }}
        {% if field.getAttribute('field_type') == 'checkbox' %}
            <div class="col-sm-10">
                <div class="onoffswitch">
                    {{ field.render() }}
                    <label class="onoffswitch-label" for="{{ field.getAttribute('id') }}">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        {% elseif field.getAttribute('field_type') == 'image' %}
            <div class="col-sm-4">
                {{ field.render() }}
            </div>
            <div class="col-sm-5">
                {% if item is empty %}
                    &nbsp;
                {% else %}
                    {% set field_val = item.getVal(field.getName()) %}
                    {% if field_val is empty %}
                        <i>Отсутствует</i>
                    {% else %}
                        <img src="/resize.php?data={{ field_val }};300;150" />
                    {% endif %}
                {% endif %}
            </div>
        {% else %}
            <div class="col-sm-10">
                {{ field.render() }}
            </div>
        {% endif %}
    </div>
{% else %}
    {% if field.getAttribute('field_type') == 'hidden' %}
        {{ field.render() }}
    {% endif %}
{% endif %}
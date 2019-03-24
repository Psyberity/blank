<ol class="breadcrumb">
    {% if menu is defined  %}
        {% for level1 in menu %}
            {% if level1['active'] %}
                {% if level1['children'] is empty %}
                    <li class="active">
                        <strong>{{ level1['label'] }}</strong>
                    </li>
                {% else %}
                    <li>
                        {{ level1['label'] }}
                    </li>
                    {% for level2 in level1['children'] %}
                        {% if level2['active'] %}
                            {% if level2['children'] is empty %}
                                <li class="active">
                                    <strong>{{ level2['label'] }}</strong>
                                </li>
                            {% else %}
                                <li>
                                    {{ level2['label'] }}
                                </li>
                                {% for level3 in level2['children'] %}
                                    {% if level3['active'] %}
                                        <li class="active">
                                            <strong>{{ level3['label'] }}</strong>
                                        </li>
                                    {% endif %}
                                {% endfor %}
                            {% endif %}
                        {% endif %}
                    {% endfor %}
                {% endif %}
            {% endif %}
        {% endfor %}
    {% endif %}
</ol>
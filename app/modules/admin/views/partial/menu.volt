{% if menu is defined  %}
    {% for level1 in menu %}
        <li{% if level1['active'] %} class="active"{% endif %}>
            <a href="{% if level1['url'] is not empty %}{{ level1['url'] }}{% else %}#{% endif %}"><i class="fa fa-sitemap"></i> <span class="nav-label">{{ level1['label'] }} </span>{% if level1['children'] is not empty %}<span class="fa arrow"></span>{% endif %}</a>
            {% if level1['children'] is not empty %}
                <ul class="nav nav-second-level collapse">
                    {% for level2 in level1['children'] %}
                        <li{% if level2['active'] %} class="active"{% endif %}>
                            <a href="{% if level2['url'] is not empty %}{{ level2['url'] }}{% else %}#{% endif %}">{{ level2['label'] }}{% if level2['children'] is not empty %}<span class="fa arrow"></span>{% endif %}</a>
                            {% if level2['children'] is not empty %}
                                <ul class="nav nav-third-level">
                                    {% for level3 in level2['children'] %}
                                        <li{% if level3['active'] %} class="active"{% endif %}>
                                            <a href="{{ level3['url'] }}">{{ level3['label'] }}</a>
                                        </li>
                                    {% endfor %}
                                </ul>
                            {% endif %}
                        </li>
                    {% endfor %}
                </ul>
            {% endif %}
        </li>
    {% endfor %}
{% endif %}
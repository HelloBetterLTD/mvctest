<div class="row">

    <div class="large-9 columns" role="content">
        <article>
            <h3>{{ Title }}</h3>
            {{ Contents }}
            {{ Content }}
        </article>
    </div>


    <aside class="large-3 columns">

        {% if Children %}
            <div class="panel">
                <ul>
                    {% for child in Children %}
                        <li><a href="{{ child.Link }}">{{ child.MenuTitle }}</a></li>
                    {% endfor %}
                </ul>
            </div>
        {% endif %}

        <div class="panel">
            <h5>{{ Settings.SidebarTitle }}</h5>
            <p>{{ Settings.SidebarContent }}</p>
        </div>
    </aside>

</div>
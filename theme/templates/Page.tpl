
<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ MetaTitle }}</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.3/css/normalize.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.3/css/foundation.min.css">
        <link href='http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css' rel='stylesheet' type='text/css'>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    </head>
    <body>

    <div class="row">
        <div class="large-12 columns">
            {% if Menu %}
            <div class="nav-bar right">
                <ul class="button-group">
                    {% for page in Menu %}
                        <li><a href="{{ page.Link }}" class="button">{{ page.MenuTitle }}</a></li>
                    {% endfor %}
                </ul>
            </div>
            {% endif %}
            <h1>{{ Settings.SiteTitle }}</h1>
            <hr/>
        </div>
    </div>


    {{ Layout }}


    <footer class="row">
        <div class="large-12 columns">
            <hr/>
            <div class="row">
                <div class="large-6 columns">
                    <p>&copy; Copyright {{ Year }} <a href="http://silverstripers.com/" target="_blank">SilverStripers PVT LTD</a>. </p>
                </div>
                <div class="large-6 columns">
                    <ul class="inline-list right">
                        {% for page in Menu %}
                            <li><a href="{{ page.Link }}">{{ page.MenuTitle }}</a></li>
                        {% endfor %}
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.3/js/foundation.min.js"></script>
    <script>
        $(document).foundation();
    </script>
    </body>
</html>


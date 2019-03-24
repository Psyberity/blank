<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Blank</title>
        {{ assets.outputCss() }}
    </head>
    <body>
		{{ content() }}

        {{ assets.outputJs('footer') }}
        {{ partial('partial/flash') }}
    </body>
</html>
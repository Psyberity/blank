<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Blank - панель управления</title>

    <link href="/modules/admin/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="/modules/admin/plugins/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/modules/admin/css/animate.css" rel="stylesheet">
    <link href="/modules/admin/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/modules/admin/css/style.css" rel="stylesheet">

</head>

<body>

<div id="wrapper" style="width: 500px; margin: 200px auto;">
    <div class="gray-bg">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Авторизация</h2>
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <form method="post" id="auth-form">
                                        <fieldset class="form-horizontal">
                                            <div class="form-group">
                                                <label for="email" class="col-sm-2 control-label">E-mail</label>
                                                <div class="col-sm-10">
                                                    <input type="text" id="email" name="email" class="form-control" placeholder="Логин">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="password" class="col-sm-2 control-label">Пароль</label>
                                                <div class="col-sm-10">
                                                    <input type="password" id="password" name="password" class="form-control" placeholder="Пароль">
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="form-group">
                                            <div class="col-sm-4 col-sm-offset-2">
                                                <input type="submit" value="Войти" class="btn btn-primary">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="/modules/admin/plugins/jquery/jquery-3.1.1.min.js"></script>
<script src="/modules/admin/plugins/validate/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#auth-form").validate({
            rules: {
                email: {
                    required: true,
                    minlength: 3
                },
                password: {
                    required: true,
                    minlength: 3
                }
            }
        });

    });
</script>

{{ partial('partial/flash') }}

</body>
</html>

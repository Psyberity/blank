<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Blank CMS - панель управления</title>
        {{ assets.outputCss() }}
    </head>
    <body>
        <script type="text/javascript">
            var datatablesColumns = [];
            var apiController = '';
            var apiAction = '';
            var datatablesDeleteUrl = '';
            var datatablesFilters = [];
            var apiUrl = '{{ api_url }}';
        </script>
        <div id="wrapper">
            <nav class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav metismenu" id="side-menu">
                        <li class="nav-header">
                            <div class="dropdown profile-element">
                                <span>
                                    <img alt="image" class="img-circle" width="48" height="48" src="{{ auth.user.getAvatar() }}" />
                                </span>
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <span class="clear">
                                        <span class="block m-t-xs">
                                            <strong class="font-bold">{{ auth.user.name }}</strong>
                                        </span>
                                        <span class="text-muted text-xs block">{{ auth.moduleUser.role.name }} <b class="caret"></b></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                    <li><a href="/profile">Профиль</a></li>
                                    <li class="divider"></li>
                                    <li><a href="/auth/logout">Выйти</a></li>
                                </ul>
                            </div>
                            <div class="logo-element">
                                IN+
                            </div>
                        </li>
                        {{ partial('partial/menu') }}
                    </ul>
                </div>
            </nav>
            <div id="page-wrapper" class="gray-bg">
                <div class="row border-bottom">
                    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                        <div class="navbar-header">
                            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                            <!--<form role="search" class="navbar-form-custom" action="search_results.html">
                                <div class="form-group">
                                    <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                                </div>
                            </form>-->
                        </div>
                        <ul class="nav navbar-top-links navbar-right">
                            <li>
                                <span class="m-r-sm text-muted welcome-message">Blank - панель управления</span>
                            </li>
                            <li>
                                <a href="/auth/logout">
                                    <i class="fa fa-sign-out"></i> Выйти
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                {{ content() }}

                <div class="modal inmodal fade" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                                <button id="appDeleteId" type="button" class="btn btn-primary" data-dismiss="modal">Удалить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <!--<div class="pull-right">
                        10GB of <strong>250GB</strong> Free.
                    </div>-->
                    <div>
                        <strong>Copyright</strong> Blank CMS &copy; 2019
                    </div>
                </div>
            </div>
        </div>
        {{ assets.outputJs('footer') }}
        {{ partial('partial/flash') }}
    </body>
</html>
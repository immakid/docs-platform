<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Docs platform</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <link href="/css/app.css" rel="stylesheet" type="text/css">

        <link href="/codemirror/lib/codemirror.css" rel="stylesheet" type="text/css">
        <script type="application/javascript" src="/codemirror/lib/codemirror.js"></script>

        <script src="/codemirror/mode/javascript/javascript.js"></script>
        <script src="/codemirror/mode/css/css.js"></script>

        <script type="application/javascript" src="/js/app.js"></script>
        <script type="application/javascript" src="/js/all.js"></script>


    </head>
    <body id="app">

        <div id="wrapper" class="toggled">

            <!-- Sidebar -->
            <div id="sidebar-wrapper">

                <ul class="sidebar-nav">
                    <li class="sidebar-brand">
                        <input id="tags-search-input" type="text" class="form-control">
                    </li>
                </ul>

                <ul id="tags-list" class="sidebar-nav mt-5">
                </ul>

            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">


                <div id="view-article" class="container invisible">

                    <h1 class="">
                        <span>Group</span>
                        <span>SubGroup</span>
                        <span>Title</span>
                    </h1>


                    <div class="row">
                        <a href="#menu-toggle" class="btn btn-sm btn-secondary col-md-1 delete-article-btn mb-1 ml-3" id="">Delete</a>
                        <a href="#menu-toggle" class="btn btn-sm btn-primary col-md-1 update-article-btn mb-1 ml-1" id="">Update</a>
                    </div>

                    <div id="editors" class="mt-2">
                    </div>

                    <a href="#menu-toggle" class="btn btn-secondary mt-3" id="add-editor-btn">Add editor</a>
                </div>


                <div id="new-article-form" class="container invisible">
                    <h1>Создать статью</h1>

                    <div class="row">
                        <input type="text" class="form-control ml-3 col-md-3" id="tag" name="tag">
                        <input type="text" class="form-control ml-1 col-md-3" id="subgroup" name="subgroup">
                        <input type="text" class="form-control ml-1 col-md-3" id="title" name="title">

                        <a href="#menu-toggle" class="btn btn-primary col-md-1 ml-2" id="save-article-btn">Save</a>
                        <a href="#menu-toggle" class="btn btn-dark col-md-1 ml-2" id="reset-article-btn">Reset</a>
                    </div>

                    <div id="editors" class="mt-2">
                    </div>

                    <a href="#menu-toggle" class="btn btn-secondary mt-3" id="add-editor-btn">Add editor</a>
                </div>

                <a href="#menu-toggle" class="btn btn-secondary" id="add-new-article-btn">+</a>
            </div>

            <!-- /#page-content-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Menu Toggle Script -->
        <script>


        </script>
    </body>
</html>

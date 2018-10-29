<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <link href="/css/app.css" rel="stylesheet" type="text/css">

        <link href="/codemirror/lib/codemirror.css" rel="stylesheet" type="text/css">
        <script type="application/javascript" src="/codemirror/lib/codemirror.js"></script>

        <script src="/codemirror/mode/javascript/javascript.js"></script>
        <script src="/codemirror/mode/css/css.js"></script>

        <script type="application/javascript" src="/js/app.js"></script>

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

                <ul class="sidebar-nav mt-5">


                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li>
                        <a href="#">Shortcuts</a>
                    </li>
                    <li>
                        <a href="#">Overview</a>
                    </li>
                    <li>
                        <a href="#">Events</a>
                    </li>
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Services</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">

                <div id="view-article" class="container">
                    <h1>Title</h1>

                    <div id="editors" class="mt-2">
                    </div>

                    <a href="#menu-toggle" class="btn btn-secondary mt-3" id="add-editor-btn">Add editor</a>
                </div>


                <div id="new-article-form" class="container invisible">
                    <h1>Создать статью</h1>

                    <div class="row">
                        <input type="text" class="form-control ml-3 col-md-7" id="tag" name="tag">
                        <a href="#menu-toggle" class="btn btn-primary col-md-2 ml-2" id="save-article-btn">Save</a>
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

            editors = {};
            editorId = 1;


            function api_get(url, params, callb) {
                axios.get(url, params)
                    .then(function (response) {
                        callb(response);
                    })
            }

            function api_post(url, params, callb) {
                axios.post(url, params)
                    .then(function (response) {
                        callb(response);
                    })
            }

            function addEditor($box, id) {

                var html = '<div data-id="'+ id +'" class="editor-holder">\n' +
                    '                <a href="#menu-toggle" class="btn btn-secondary btn-sm" id="del-editor-btn">Del</a>\n' +
                    '                <textarea class="mt-2" name="editor" id="editor-'+ id+ '" cols="30" rows="10"></textarea>\n' +
                    '            </div>';


                $('#editors', $box).append(html);

                var textArea = $('#editor-'+ id, $box);

                var editor = CodeMirror.fromTextArea(textArea.get(0), {
                    lineNumbers: true
                });

                editors[id] = editor;
            }


            function globalPageService()
            {

                // add article window
                $("#add-new-article-btn").click(function(e) {
                    $('#new-article-form').toggleClass('invisible');
                    $('#view-article').toggleClass('hidden');
                });

            }

            function tagsListService()
            {
                $('#tags-search-input').keyup(function() {

                });
            }


            function createNewArticleService()
            {
                var $box = $('#new-article-form');

                addEditor($box, editorId++);



                $('#save-article-btn', $box).click(function() {

                });


                $('#add-editor-btn', $box).click(function() {
                    addEditor($box, editorId++);
                });
            }


            function viewArticleService()
            {
                var $box = $('#view-article');
                addEditor($box, editorId++);


                $('#add-editor-btn', $box).click(function() {
                    debugger;
                    addEditor($box, editorId++);
                });

            }


            $(document).ready(function() {

                // api();

                createNewArticleService();

                viewArticleService();

                tagsListService();

                globalPageService();

            });

        </script>
    </body>
</html>

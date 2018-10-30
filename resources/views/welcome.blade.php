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

                    <h1 class="">Title</h1>

                    <div class="row">
                        <a href="#menu-toggle" class="btn btn-secondary col-md-1 delete-article-btn mb-1 ml-3" id="">Delete</a>
                        <a href="#menu-toggle" class="btn btn-primary col-md-1 update-article-btn mb-1 ml-1" id="">Update</a>
                    </div>

                    <div id="editors" class="mt-2">
                    </div>

                    <a href="#menu-toggle" class="btn btn-secondary mt-3" id="add-editor-btn">Add editor</a>
                </div>


                <div id="new-article-form" class="container invisible">
                    <h1>Создать статью</h1>

                    <div class="row">
                        <input type="text" class="form-control ml-3 col-md-7" id="tag" name="tag">
                        <a href="#menu-toggle" class="btn btn-primary col-md-2 ml-2" id="save-article-btn">Save</a>
                        <a href="#menu-toggle" class="btn btn-dark col-md-2 ml-2" id="reset-article-btn">Reset</a>
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

            function api_delete(url, params, callb, callb_error) {
                axios.delete('/api'+url, params)
                    .then(function (response) {
                        callb(response);
                    })
                    .catch(error => {
                        console.log(error.response);
                        callb_error && callb_error(error);
                    });
            }

            function api_post(url, params, callb, callb_error) {
                axios.post('/api'+url, params)
                    .then(function (response) {
                        callb(response);
                    })
                    .catch(error => {
                        console.log(error.response);
                        callb_error && callb_error(error);
                    });
            }

            function resetNewArticleWindow() {

                var $box = $('#new-article-form');

                $('#tag', $box).val('');
                $('#editors', $box).html('');
                editors = {};

                addEditor($box, editorId++);

            }

            function addEditor($box, id) {

                var html = '<div id="editor-holder-'+id+'" data-id="'+ id +'" class="editor-holder mt-2">\n' +
                    '                <a href="#menu-toggle" class="btn btn-secondary btn-sm del-editor-btn mb-1" id="">Del</a>\n' +
                    '                <textarea class="mt-2" name="editor" id="editor-'+ id+ '" cols="30" rows="10"></textarea>\n' +
                    '            </div>';


                $('#editors', $box).append(html);

                var textArea = $('#editor-'+ id, $box);

                var editor = CodeMirror.fromTextArea(textArea.get(0), {
                    lineNumbers: true
                });

                editors[id] = editor;

                var $holder = '#editor-holder-'+ id;


                $('.del-editor-btn', $holder).click(function() {

                    if(confirm('Delete this editor?')) {
                        var eh = $(this).parents('.editor-holder');
                        eh.remove();
                    }

                });

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


                $('#reset-article-btn', $box).click(function() {
                    resetNewArticleWindow();
                });


                $('#save-article-btn', $box).click(function() {

                    var $box = $('#new-article-form');
                    var content = [];
                    var tag = $('#tag', $box).val();


                    $('.editor-holder', $box).each(function(i, item) {

                        var editorId = $(item).data('id');

                        var value = editors[editorId].getValue();

                        if (value.length)
                        {
                            content.push({
                                value: value
                            });
                        }
                    });


                    if (content.length) {

                        api_post('/articles', {
                            content:    content,
                            tag:        tag
                        }, function(resp) {

                            resetNewArticleWindow();


                        }, function(error) {



                            error.request.response

                        });
                    }


                });


                $('#add-editor-btn', $box).click(function() {

                    var id = $('#view-article').attr('data-id');


                    api_delete('/article/'+ id, {}, function() {
                        debugger;
                    });

                });
            }


            function viewArticleService()
            {
                var $box = $('#view-article');
                addEditor($box, editorId++);


                $('#add-editor-btn', $box).click(function() {
                    addEditor($box, editorId++);
                });

                $('#delete-article-btn', $box).click(function() {
                    if(confirm('Delete article?')) {

                    }
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

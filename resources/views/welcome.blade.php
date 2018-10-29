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
                        <a href="#">
                            Start Bootstrap
                        </a>
                    </li>
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

                <div class="container">
                    <h1>Simple Sidebar</h1>
                    <p>This template has a responsive menu toggling system. The menu will appear collapsed on smaller screens, and will appear non-collapsed on larger screens. When toggled using the button below, the menu will appear/disappear. On small screens, the page content will be pushed off canvas.</p>
                    <p>Make sure to keep all page content within the <code>#page-content-wrapper</code>.</p>


                    <a href="#menu-toggle" class="btn btn-secondary" id="add-new-article-btn">+</a>
                </div>

                <div id="new-article-form" class="container hidden">

                    <input type="text" class="form-control" id="tag" name="tag">



                    <textarea class="mt-2" name="editor" id="editor-1" cols="30" rows="10"></textarea>

                    <a href="#menu-toggle" class="btn btn-secondary" id="add-editor-btn">Add</a>


                </div>

            </div>
            <!-- /#page-content-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Menu Toggle Script -->
        <script>


            $(document).ready(function() {


                // add article window
                $("#add-new-article-btn").click(function(e) {
                    $('#new-article-form').toggleClass('hidden');
                });

            });


        </script>
    </body>
</html>

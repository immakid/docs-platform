(function () {

    editors = {};
    editorId = 1;


    function api_get(url, params, callb) {
        axios.get('/api'+ url, params)
            .then(function (response) {
                callb(response);
            })
    }

    function api_put(url, params, callb, callb_error) {
        axios.put('/api'+ url, params)
            .then(function (response) {
                callb(response);
            })
            .catch(error => {
                console.log(error.response);
                callb_error && callb_error(error);
            });
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
            lineNumbers: true,
            viewportMargin: Infinity
        });

        editors[id] = editor;

        var $holder = '#editor-holder-'+ id;


        $('.del-editor-btn', $holder).click(function() {

            if(confirm('Delete this editor?')) {
                var eh = $(this).parents('.editor-holder');
                eh.remove();
            }

        });

        return editor;
    }

    function resetTagsList()
    {
        $('#tags-search-input')
            .val('')
            .focus();
        
        
        
    }
    
    function loadMainTagsList()
    {
        api_get('/tags', {}, function(r) {

            $('#tags-list').html(r.data.html);
            tagsListBindings();

        });
    }

    function findTagsList(needle)
    {
        api_get('/tags/find', {needle: needle}, function(r) {

            $('#tags-list').html(r.data.html);
            tagsListBindings();

        });
    }


    function globalPageService()
    {
        function toggleNewArticleForm()
        {
            $('#new-article-form').toggleClass('invisible');
            $('#view-article').toggleClass('hidden');
            $('#app').toggleClass('opened-new-article');
        }

        // add article window
        $("#add-new-article-btn").click(function(e) {
            toggleNewArticleForm();
        });


        $(window).keyup(function(e) {

            if(e.key == 'Escape') {

                if($('#app').hasClass('opened-new-article')) {

                    toggleNewArticleForm();
                    return;

                }
                //=-=-=-=-=-=-=-=-=-=

                if($('#tags-search-input').val().length) {

                    resetTagsList();

                    return;
                }
                //=-=-=-=-=-=-=-=-=-=
            }

        });



        $('#tags-search-input').keyup(function() {
            var val = $(this).val();

            if(val.length < 3) return;
            //=-=-=-=-=-=-=-=-=-=

            findTagsList(val);

        });


        loadMainTagsList();
    }

    function tagsListBindings()
    {

        function showArticle(id)
        {
            api_get('/articles/'+ id, {}, function(r) {

                var $box = $('#view-article');

                $('#editors', $box).html('');

                r.data.content.map(function(item) {
                    var editor = addEditor($box, editorId++);
                    editor.setValue(item.value)
                });

                $box.attr('data-id', r.data.id);
                $box.removeClass('invisible');
            });
        }

        function showArticleRoute($item, id)
        {
            var $box = $('#new-article-form');
            $('#tag', $box).val($item.text());


        }


        $('#tags-list a').click(function() {

            var id = $(this).attr('data-id');

            if($('#app').hasClass('opened-new-article')) {

                showArticleRoute($(this), id);

                return;
            }

            showArticle(id);
        });

    }

    function getBoxEditorsContent($box)
    {
        var content = [];
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

        return content;
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
            var content = getBoxEditorsContent($box);

            var tag = $('#tag', $box).val();


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
            addEditor($box, editorId++);
        });

    }


    function viewArticleService()
    {
        var $box = $('#view-article');
        addEditor($box, editorId++);


        $('#add-editor-btn', $box).click(function() {
            addEditor($box, editorId++);
        });


        $('.update-article-btn', $box).click(function() {
            var id = $('#view-article').attr('data-id');

            var $box = $('#view-article');

            var content = getBoxEditorsContent($box);
            if(!content.length) return;
            //=-=-=-=-=-=-=-=-=-=

            api_put('/articles/'+id, {
                content: content,
                tag: 'tag_test'
            }, function(r) {
                debugger;
            });

        });

        // delete article
        $('.delete-article-btn', $box).click(function() {
            if(confirm('Delete article?')) {
                var id = $('#view-article').attr('data-id');

                api_delete('/articles/'+ id, {}, function() {

                    var $box = $('#view-article');
                    $box.addClass('invisible');


                    id;


                });
            }
        });
    }


    $(document).ready(function() {

        createNewArticleService();

        viewArticleService();

        globalPageService();

    });
}());
(function () {

    editors = {};
    editorId = 1;
    showedParentGroupId = 0;

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
        $box.removeClass('editor-changed');

        $('#group', $box).val('');
        $('#sub_group', $box).val('');
        $('#title', $box).val('');

        $('#editors', $box).html('');
        editors = {};

        addEditor($box, editorId++);

    }



    function addEditor($box, id, params) {
        !params && (params={});

        var html = '<div id="editor-holder-'+id+'" data-id="'+ id +'" class="editor-holder mt-2">\n' +
            '                <a href="#menu-toggle" class="btn btn-secondary btn-sm del-editor-btn mb-1" id="">Del</a>\n' +
            '                <textarea class="mt-2" name="editor" id="editor-'+ id+ '" cols="30" rows="10"></textarea>\n' +
            '            </div>';


        $('#editors', $box).append(html);

        var textArea = $('#editor-'+ id, $box);

        var final = $.extend(params, {
            lineNumbers: true,
            viewportMargin: Infinity,
        });

        var editor = CodeMirror.fromTextArea(textArea.get(0), final);

        editors[id] = editor;

        var $holder = '#editor-holder-'+ id;


        $('.del-editor-btn', $holder).click(function() {

            if(confirm('Delete this editor?')) {
                var eh = $(this).parents('.editor-holder');
                eh.remove();
            }

        });

        editor.on('inputRead', function() {
            $box.addClass('editor-changed');
        });

        return editor;
    }

    function resetTagsList()
    {
        $('#tags-search-input')
            .val('')
            .focus();
    }

    function showSubgroups(id)
    {
        showedParentGroupId = id;

        api_get('/groups/subgroups/'+ id, {}, function(r) {

            $('#tags-list').html(r.data.html);
            tagsListBindings();

        });
    }

    function showArticle(id)
    {
        api_get('/articles/'+ id, {}, function(r) {

            var $box = $('#view-article');

            // set title
            $('#group', $box).text(r.data.group_name);
            $('#sub_group', $box).text(r.data.sub_group_name);
            $('#title', $box).text(r.data.title);

            // set block attr
            $box.attr('data-id', r.data.id);
            $box
                .removeClass('invisible')
                .removeClass('editor-changed');


            // install editors
            $('#editors', $box).html('');
            r.data.content.map(function(item) {
                var editor = addEditor($box, editorId++, {});
                editor.setValue(item.value);
            });

        });
    }

    function loadMainTagsList()
    {
        showedParentGroupId = 0;

        api_get('/groups', {}, function(r) {

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

    function toggleNewArticleForm()
    {
        $('#new-article-form').toggleClass('invisible');
        $('#view-article').toggleClass('hidden');
        $('#app').toggleClass('opened-new-article');
    }


    function globalPageService()
    {
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

    function tagsListBindings() {

        function showArticleRoute($item, id)
        {
            var $box = $('#new-article-form');

            $('#group', $box).val($item.attr('data-group'));
            $('#sub_group', $box).val($item.attr('data-subgroup'));
        }


        var $box = $('#tags-list');

        $('.main', $box).click(function() {
            var $link = $(this);
            var id = $link.attr('data-id');

            showSubgroups(id);
        });

        $('.article', $box).click(function() {

            $('#tags-list .active').removeClass('active');


            var $link = $(this);
            var id = $link.attr('data-id');

            if($('#app').hasClass('opened-new-article')) {

                showArticleRoute($(this), id);

                return;
            }
            //=-=-=-=-=-=-=-=-=-=

            var $box = $('#view-article');

            if (!$box.hasClass('invisible')
                && !$box.hasClass('hidden')
                && $box.hasClass('editor-changed')) {

                if (confirm('Last article has changes, continue?')) {
                    $link.addClass('active');
                    showArticle(id);
                }

            } else {
                $link.addClass('active');
                showArticle(id);
            }
        });


        $('.back-link').click(function() {

            loadMainTagsList();

            var $box = $('#view-article');
            $box.addClass('invisible');
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
            if (!content.length) return;
            //=-=-=-=-=-=-=-=-=-=

            var group = $('#group', $box).val();
            var subGroup = $('#sub_group', $box).val();
            var title = $('#title', $box).val();
            if (!title.length || !group.length || !subGroup.length) return;
            //=-=-=-=-=-=-=-=-=-=


            api_post('/articles', {
                content:    content,
                title:      title,
                group:      group,
                sub_group:  subGroup,

            }, function(resp) {

                resetNewArticleWindow();

                showSubgroups(showedParentGroupId);
                showArticle(resp.data.article.id);

                toggleNewArticleForm();

            }, function(error) {


            });

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

            var $box = $('#view-article');
            var id = $box.attr('data-id');

            var group = $('#group', $box).text();
            var subGroup = $('#sub_group', $box).text();
            var title = $('#title', $box).text();
            if (!title.length || !group.length || !subGroup.length) return;
            //=-=-=-=-=-=-=-=-=-=

            var content = getBoxEditorsContent($box);
            if(!content.length) return;
            //=-=-=-=-=-=-=-=-=-=

            api_put('/articles/'+id, {
                content: content,
                title: title,

            }, function(r) {

                $box.removeClass('editor-changed');

            });
        });

        // delete article
        $('.delete-article-btn', $box).click(function() {
            if(confirm('Delete article?')) {
                var id = $('#view-article').attr('data-id');

                api_delete('/articles/'+ id, {}, function() {

                    var $box = $('#view-article');
                    $box.addClass('invisible');

                    showSubgroups(showedParentGroupId);
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
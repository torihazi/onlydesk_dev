$(function() {
    let textarea = $('.comment_area');
    let titlearea = $('.title_area');

    //textareaをクリックしたときの処理
    textarea.focus(function(e){
        e.stopPropagation();
        textarea.addClass('form_focus');
        textarea.removeAttr("placeholder");
    });

    // textarea内のクリックイベントが親要素に伝播しないように設定
    textarea.click(function(e){
        e.stopPropagation();
    });

    // textarea内のクリックイベントが親要素に伝播しないように設定
    titlearea.click(function(e){
        e.stopPropagation();
    });

    //titleareaをクリックしたときの処理
    titlearea.focus(function(e){
        e.stopPropagation();
        titlearea.addClass('form_focus');
        titlearea.removeAttr("placeholder");
    });

    //ドキュメントのどこかをクリックしたときの処理
    $(document).click(function() {
        textarea.removeClass('form_focus');
        textarea.attr("placeholder", "説明を入力する");
        titlearea.removeClass('form_focus');
        titlearea.attr("placeholder", "タイトルを入力する");
    });


})
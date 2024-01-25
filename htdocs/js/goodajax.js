$(function() {

    $('.like-btn').on('click', function() {

        //$(this)でクリックした要素のみを取得
        var _this = $(this);
        var span = _this.next();
        var article_id = _this.data('article_id');

        $.ajax({
            url: "https://ida-1.onlydesk.jp/api/goodAjax", // URLを指定
            type: "POST", // GET,POSTなどを指定
            dataType: "json",
            data: { // データを指定
                article_id : article_id,
            }
        }).done(function (data) {
            // 通信成功時のコールバック処理
            console.log('Ajax Successe');

            if (data['is_login'] === false) {

                alert("いいね は ログインユーザのみ可能です!!");

            } else {

                //レスポンスがtrue(いいねしていた)である時、ハートのアイコンを白地にする。
                if (data['is_good']) {

                    _this.removeClass('active fas');
                    _this.addClass('far');
                    span.removeClass('active');

                } else {
                    _this.removeClass('far');
                    _this.addClass('active fas');
                    span.addClass('active');
                }

                //同時に取得してきたいいね数をviewに反映する。
                span.text(data['good_count']);

            }

        }).fail(function (data) {
            
            // 通信失敗時のコールバック処理
            console.log('fail');
            alert("通信が失敗しました。");
            console.log(data);

        
        }).always(function (data) {
            
            // 常に実行する処理
            console.log('always');
            console.log(data);
        
        });

    });

});
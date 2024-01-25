$(function() {

    $('.follow_label').on('click', function() {

        //$(this)でクリックしたもののみのクラス？を取得
        var $this = $(this);
        var follow_id = $this.data('followid')

        $.ajax({
            url: "https://ida-1.onlydesk.jp/api/followajax", // URLを指定
            type: "POST", // GET,POSTなどを指定
            dataType: "json",
            data: { // データを指定
                follow_id : follow_id
            }
        }).done(function (data) {
            // 通信成功時のコールバック処理
            console.log('Ajax Successe');
            console.log($this);

            //レスポンスがtrue(フォローした)である時、フォローlabelを黒背景にする。
            if(data['isfollow']){
                $this.removeClass('follow');
                $this.addClass('unfollow');
                $this.text("フォローする");
            } else {
                $this.removeClass('unfollow');
                $this.addClass('follow');
                $this.text("フォロー解除");               
            }
            
            $('.follow_num').text(data['follow_num']);
            $('.follower_num').text(data['follower_num']);
        
        }).fail(function (data) {
            // 通信失敗時のコールバック処理
            console.log('fail');
            console.log(data);
        
        }).always(function (data) {
            // 常に実行する処理
            console.log('always');
            console.log(data);
            // alert(json);
        
        });

    });

});
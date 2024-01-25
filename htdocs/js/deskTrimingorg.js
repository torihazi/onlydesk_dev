$(function () {
    let cropper;
    
    // ファイル選択時の処理
    $('#file_input').change(function () {
 

        // 前回の Cropper インスタンスが存在する場合、破棄する
        if (cropper) {
            cropper.destroy();
        }

        let file = this.files[0];

        if (file) {

            //フォームからアップロードした画像を非同期で読み込むためにFileReaderオブジェクトを作成
            let reader = new FileReader();

            reader.onload = function (e) {
                // 画像をプレビューとして表示
                $('#up_image_preview').attr('src', e.target.result);
                $('#modal').css('display', 'block');
                
                // Cropperインスタンスを初期化
                cropper = new Cropper(document.getElementById('up_image_preview'), {
                    aspectRatio: 4 / 3,
                    viewMode: 2,
                    guides: false,
                    autoCropArea: 1,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    // トリミングボタンのクリックイベント
    $('#trim_fin_button').click(function (e) {

        // ボタンクリック時のデフォルト動作（フォーム送信）を防ぐ
        e.preventDefault(); 
        
        // 現在の切り抜き画像の情報を取得
        let croppedCanvas = cropper.getCroppedCanvas();

        //切り抜き画像のDataURLを取得
        let croppedData = croppedCanvas.toDataURL('image/jpeg');

        //切り抜き画像からBlobを生成し、
        croppedCanvas.toBlob(function (imgBlob){

            //jsにおいてファイルを扱うためにFileオブジェクトを作成
            const croppedImgFile = new File([imgBlob], 'trimimage.jpeg', {type: "image/jpeg"});

            //フォームに新しいファイルデータを設定するためにDataTransferオブジェクトを作成
            const dt = new DataTransfer();

            dt.items.add(croppedImgFile);
            document.querySelector('input[name="up_image"]').files = dt.files;
        });

        //ファイルを選択する、というboxを非表示
        $('#input_file_box').css('display', 'none');

        //トリミング結果が格納されたdivを表示
        $('#cropped_image_container').css('display', 'block');

        // トリミング結果を表示
        $('#up_cropped_image').attr('src', croppedData);
        $('#up_cropped_image').show();

        //×ボタン表示
        $('#remove_image_button').css('display', 'block');
        
        // モーダルウィンドウを閉じる
        $('#modal').css('display', 'none');

    });

    //キャンセルボタンのクリックイベント
    //前回トリミングしたデータをそのまま再使用する
    $('#trim_cancel_button').click(function (e){

        //ボタンクリック時のデフォルト動作(フォーム送信)を防ぐ
        e.preventDefault();

        // トリミングを破棄
        cropper.destroy();
        
        // フォームのファイルフィールドをクリア
        $('#file_input').val('');

        //モーダルウィンドウを閉じる
        $('#modal').css('display', 'none');

        //ファイルを選択する、というboxを表示
        $('#input_file_box').css('display', 'flex');

    });

    //トリミング領域外を選択してもキャンセルボタンと同じ挙動を示す
    $('.modal_close').click(function (e){

        //ボタンクリック時のデフォルト動作(フォーム送信)を防ぐ
        e.preventDefault();

        // トリミングを破棄
        cropper.destroy();
        
        // フォームのファイルフィールドをクリア
        $('#file_input').val('');

        //モーダルウィンドウを閉じる
        $('#modal').css('display', 'none');

        //ファイルを選択する、というboxを表示
        $('#input_file_box').css('display', 'flex');

    });

    // プレビュー画像の × ボタンをクリックしたときの処理
    $('#remove_image_button').click(function (e) {

        //ボタンクリック時のデフォルト動作(フォーム送信)を防ぐ
        e.preventDefault();        
        
        //トリミング結果が格納されたdivを非表示
        $('#cropped_image_container').css('display', 'none');
        
        //×ボタン非表示
        $('#remove_image_button').css('display', 'none');
        
        // フォームのファイルフィールドをクリア
        $('#file_input').val('');

        //ファイルを選択する、というboxを表示
        $('#input_file_box').css('display', 'flex');


    });

});

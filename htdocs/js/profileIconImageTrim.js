"use strict";

const modal = document.getElementById("js_modal");
const modal_content = document.getElementById("modal_content");
const file_input = document.getElementById("input_file");
const form_input = document.getElementById("form_input_box");
const now_image = document.getElementById("now_image_container");
const crop_container = document.getElementById("cropped_image_container");
const cropping_image = document.getElementById("cropping_image");
const cropped_icon = document.getElementById("cropped_icon");
const trim_button = document.getElementById("crop_fin_button");
const cancel_button = document.getElementById("crop_cancel_button");
const remove_now_button = document.getElementById("remove_now_image_button");
const remove_trim_button = document.getElementById("remove_cropped_image_button");

let cropper;

// formからファイルを選択し終えると発火
file_input.addEventListener("change", (event) => {
    
    //選択したファイルを取得(Fileオブジェクト)
    let file = event.target.files[0];
    
    // 選択されたファイルの内容を確認
    if (! fileCheck(event)) {

        //ファイルのvalueを削除し、処理を中止
        file.value = "";
        return ;

    }

    if (cropper) {

        cropper.destroy();

    }

    //モーダルウィンドウを可視化するclass(is_open)を追加
    modal.classList.add("is_open");

    //ファイルを読み込むために FileReaderオブジェクト生成
    let reader = new FileReader();

    // ファイル読み込み完了したら cropperに投入
    reader.addEventListener("load", () => {
            
            //画像ファイルを base64文字列に変換し、プレビュー画像のsrcに代入
            cropping_image.src = reader.result;

            // Cropper オブジェクト化
            cropper = new Cropper(cropping_image,{
                aspectRatio: 1 / 1,
                viewMode: 2,
                guides: false,
                center: false,
                autoCropArea: 1
            });

            // トリミングボタン押下時
            trim_button.addEventListener("click", () => {

                //cropperインスタンスから 現在の切り抜き範囲の画像を canvas要素として取得
                const cropped_canvas = cropper.getCroppedCanvas();

                cropped_canvas.toBlob((imgBlob) => {

                    //ここにinput のファイルに装填する処理も追加しないといけない
                    const cropped_file = new File([imgBlob], 'triming.png', {type: "image/png"});

                    // DataTransfer インスタンスを介して、input filesに ファイルを渡す
                    const dt = new DataTransfer();
                    dt.items.add(cropped_file);
                    file_input.files = dt.files;

                });

                //取得したcanvas要素 のデータを トリミング結果 の img srcに装填
                cropped_icon.src = cropped_canvas.toDataURL();

                //ファイルを選択する というdivを降下させる
                form_input.classList.remove("is_open");

                //トリミング結果のdivを 浮上させる
                crop_container.classList.add("is_open");

                //現在画像を含むdivを降下

                //モーダルウィンドウを閉じる
                modal.classList.remove("is_open");

            });

            // キャンセルボタン押下時
            cancel_button.addEventListener("click", () => {

                //モーダルウィンドウを可視化するclass("is_open")を削除
                modal.classList.remove("is_open");

                //input した valueを削除する
                file_input.value = '';

            });
    });

    // input した画像ファイルを読み込む->その後、上のイベントリスナが発火
    reader.readAsDataURL(file);

});

//モーダルウィンドウの画面外をクリックした際、閉じる処理
modal.addEventListener("click", (event) => {

    //クリックした対象要素がモーダルコンテンツの内か外か判別
    if (event.target.closest("#modal_content") === null) {

        //モーダルウィンドウを可視化するclass("is_open")を削除
        modal.classList.remove("is_open");

        //input した valueを削除する
        file_input.value = '';

    }
});

// 現在画像結果を削除する ボタン 押下時
remove_now_button.addEventListener("click", (event) => {

    //現在画像をふくむdivを降下
    now_image.classList.remove("is_open");

    // ファイルを選択する div を浮上させる 
    form_input.classList.add("is_open");


});

// トリミング結果の表示を消す ボタン 押下時
remove_trim_button.addEventListener("click", (event) => {

    // トリミング結果 を示す divを 下に隠す
    crop_container.classList.remove("is_open");

    //現在画像

    // ファイルを選択する div を浮上させる 
    form_input.classList.add("is_open");

    // input した valueを削除する 
    file_input.value = '';

});


//inputされたファイルの形式をフロント側で確認する
function fileCheck(e) {

    let target = e.target;
    let file = target.files[0];
    let type = file.type; // MIMEタイプ
    let size = file.size; // ファイル容量（byte）
    let limit = 3000000; // byte, 5MB

    //ファイルの選択判定
    if (!file) {
        alert("ファイルが選択されていません");
        return false;
    }

    // MIMEタイプの判定
    if (type !== 'image/jpeg' && type !== 'image/png') {

        alert('選択できるファイルはJPEGもしくはPNG画像だけです。');
        return false;
    
    }
    // サイズの判定
    if (limit < size) {
        
        alert('3MBを超えています。適切なファイルを選択してください。');
        
        return false;
    }

    return true;
}


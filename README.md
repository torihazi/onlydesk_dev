# onlydesk_dev
URL:https://ida-1.onlydesk.jp/

フレームワークとして"パーフェクトPHP"のものを使用しています

(MIT License)

# はじめに
こちらは私が作成した"ONLYDESK"というWebアプリケーションです。
このアプリは「自分の机周りをどんなふうにおしゃれにすればいいのかわからない」
という人たちのためのアプリです。
- 新生活がこれから始まるけど、どんな机を買えばいいかな。
- 在宅ワークをするうえで、気分が上がる机周りにしたいな。
- 私のお気に入りの机をみんなに見てほしい!!
このような方たちのための
**机** X **おしゃれ** X **SNS**です!!

まだまだ至らない点等ありますが、
これからもっともっと良くしていきたいと考えていますので
よろしくお願いします!!

# 技術構成について
- PHP 8.2.9
- JavaScript
- Jquery 3.7.1
- HTML5
- CSS 3
- MYSQL 5.7.36
- Apache 
- Log4php
- Cropper.js
- GD
- ロリポップレンタルサーバ

## 選定理由
はじめてのWebアプリ製作、レンタルサーバを利用しました。
そのため選定した上記技術に関してはレンタルサーバ準拠のものとなります。

## 実装ページ
- ログインページ
- 新規会員登録ページ
- パスワードリマインダー
- お問い合わせページ
- ホーム画面(投稿一覧)
- マイページ
- 他ユーザのマイページ
- 投稿ページ
- 投稿詳細ページ
- 投稿編集ページ

このうち未ログインであっても閲覧が可能なページは
- ログインページ
- 新規会員登録ページ
- お問い合わせページ
- ホーム画面(投稿一覧)
- 投稿詳細ページ

## データベースについて
テーブルについては下記を作成しました。
- user テーブル
- article テーブル
- article_image テーブル
- good テーブル
- follow テーブル
- pwreset テーブル
- contact テーブル

### user テーブル
一般ユーザのテーブルです。
ユーザ情報を管理します。

|名前|タイプ|Null|その他|
|:---|:---|:---|:---|
|id|bigint(20)|no|auto_increment|
|name|varchar(32)|no||
|email|varchar(64)|no||
|password|varchar(40)|no||
|sex_type|tinyint(4)|no||
|age_type|tinyint(4)|no||
|icon_image_file|varchar(200)|no||
|is_delete|tinyint(4)|no||
|create_at|datetime|yes||
|update_at|datetime|yes||

sex_typeは0~2、age_typeは0~9を使用します。

### article テーブル
投稿情報のテーブルです。
画像に関してはarticle_imageテーブルに保存します
1人のuserに対し複数の投稿が紐づきます。

|名前|タイプ|Null|その他|
|:---|:---|:---|:---|
|id|bigint(20)|no|auto_increment|
|user_id|bigint(20)|no||
|title|varchar(64)|no||
|detail|varchar(40)|no||
|is_delete|tinyint(4)|no||
|create_at|datetime|yes||
|update_at|datetime|yes||

### article_image テーブル
1つの投稿に対し、1つの画像が結びつきます。
articleテーブルのidを使用し、画像周りの情報を管理します。

|名前|タイプ|Null|その他|
|:---|:---|:---|:---|
|article_id|bigint(20)|no||
|article_image_file|varchar(200)|no||
|create_at|datetime|yes||
|update_at|datetime|yes||

### good テーブル
いいねしたユーザのid(user_id）といいねされた記事のID(article_id)等を管理します。

|名前|タイプ|Null|その他|
|:---|:---|:---|:---|
|id|bigint(20)|no||
|user_id|bigint(20)|no||
|article_id|bigint(20)|no||
|create_at|datetime|yes||
|update_at|datetime|yes||


### follow テーブル
フォローしたユーザのid(user_id)とフォローされたユーザのid等を管理します。

|名前|タイプ|Null|その他|
|:---|:---|:---|:---|
|id|bigint(20)|no||
|user_id|bigint(20)|no||
|follow_id|bigint(20)|no||
|create_at|datetime|yes||
|update_at|datetime|yes||

### pwreset テーブル
既に登録されてあるuserのemailに対して生成される特定時間有効なトークンデータを保持します。

|名前|タイプ|Null|その他|
|:---|:---|:---|:---|
|id|bigint(20)|no||
|email|varchar(64)|no||
|pwresetToken|varchar(64)|no||
|create_at|datetime|yes||
|update_at|datetime|yes||

### contact テーブル
お問い合わせに関するデータを管理します。

|名前|タイプ|Null|その他|
|:---|:---|:---|:---|
|id|bigint(20)|no|auto_increment|
|email|varchar(64)|no||
|title|varchar(30)|no||
|type|tinyint(4)|no||
|content|varchar(500)|no||
|is_answer|tinyint(4)|no||
|create_at|datetime|yes||
|update_at|datetime|yes||


### 新規会員登録機能
### ログイン・ログアウト機能
`$_SESSION`を利用しています。
ログイン画面においてID(メールアドレス)とパスワードを入力して送信することにより
あらかじめDBに登録しているデータと照合し、その結果に応じてログイン可否を判断します。

実際は`$_SESSION`の中身を調べ、既に対応するkeyとvalueが存在すれば
ホーム画面にリダイレクトします。
またワンタイムトークンを生成することによってCSRF対策もしています。

### 投稿、編集、削除機能
投稿画面において
- 投稿タイトル(必須)
- 画像(必須1枚)
- 説明(任意)
を指定して投稿することができます。
画像投稿時はフロント側でCropper.jsを使用しており、
指定サイズにトリミングを行ってから投稿することができます。
トリミングエリアはモーダルウィンドウ表示をしますが、画面外クリックでキャンセルも可能です。(こちらについてはRoomClipのイメージを参考にしました)

投稿時には投稿タイトル、写真容量サイズでバリデーションをしています。
その後、処理がパスしたものに関してDBへインサート処理をしています。
一連の処理はトランザクションとなっています。
また画像のデータはDBに画像パスとして保存しています。

編集時においては投稿時と同様のバリデーションをしています。
しかしRoomClipのように投稿を削除しなくても画像の切り替えが可能です。

削除機能について
※こちらについては実装をスルーしていました。
出来次第アップロードいたします。

### いいね機能(非同期通信)
いいね機能はAjaxを使用して、実装しています。
フロント側でHTMLのカスタム属性からユーザIDを取得し、生成されたデータを
PHP側で受け取ります。
PHP側ではいいねを押したユーザIDといいねをされた投稿主のユーザIDを受け取り、
その値を元にテーブルへ検索をかけ既にあればDELETEし、なければINSERTするという処理を行っています。最後にその投稿に対するいいね数をカウントし、いいね結果(true, false)をフロント側へ投げ返して反映をしています。いいね数のカウントについては投稿主のIDでテーブル検索をかけそのCOUNT結果を使用しています。

### フォロー機能(非同期通信)
処理内容としてはいいね機能と同じです。

### 画像トリミング機能
投稿機能の欄で少しふれましたが、javascriptのトリミング用のライブラリとして"Cropper.js"を使用しています。デフォルトでは四角型のトリミングしかできませんが、CSSを調整することにより丸形のトリミングを可能としました。

### 画像圧縮機能
投稿機能の欄で少しふれましたが、投稿のバリデーションがパスした際にサーバへアップロードする前にGDライブラリを使用して画像の縮小圧縮を行っています。
トリミングした画像をサイズダウンすることによってサーバ容量がひっ迫することを抑えました。

### ページネーション機能
投稿数に応じて画面上部と下部にページネーションのリンクを生成しています。
1ページには9枚の投稿を上限としているので10枚を超えると2ページ目のリンクが生成されます。

### パスワードリマインダー機能
ログイン画面などから"パスワードをお忘れの方へ"リンクをクリックすると遷移することができます。入力情報はメールアドレスですが、正誤結果に関わらず送信完了ページを表示しています。正常な処理が行われた際は登録されたメールアドレスにランダム文字列が付与されたページリンクが記載されたメールが届き、そのランダム文字列をpwresetテーブルへインサートします。リンクの有効期限は24時間としています。リンクをクリックした際はGETメソッドでURLを取得し、テーブルへ検索をかけています。その後正常にパスワード変更が終わった際は更新をすることができます。

### お問い合わせ機能
問い合わせページから管理者に問い合わせメールを送信することが可能です。
ログインユーザに関わらずだれでも送信することが可能です。
必須項目を入力したら管理者に通知メールが届き、入力者には送信完了メールが届くように実装しています。

### 退会機能
退会ページより退会すると、userテーブルにおいて削除フラグが立ちます。
そうすると投稿一覧画面では削除フラグが立っていないユーザのみ表示するので
実質削除することができます。論理削除としています。

## 今後追加したい機能、予定
まず仕様を大幅に変更したいと思っています。バックエンドはphpフレームワークのLaravelを使用し、フロントエンドはReactを使用したいと思っています。laravelはフロント側にAPIを提供するだけにとどめReactで主にフロント部分を構築していきたいと考えています。現状ではReactはおろか、Laravelも学習中であるため今年度には完了させたいと考えています。見た目がまだまだ殺風景であるのでフロント側の技術もさらに磨いていきたいと考えています。
機能面では投稿者とのコミュニケーションを図るためにコメント機能やより便利に使用できるようにするためにタグ検索機能も実装したいと考えています。
ゆくゆくはRoomClipの机部門としてデビューをして見せます。

## 本アプリの製作を通じて

このアプリ制作を通じてよかったことと反省点は下記になります。
- 良かった点
    - 自身のアイデアを形にして世にデプロイしたという達成感を得られた
    - エラーを解決するうえで忍耐力と推測力が身についた。
    - インターネット上の文献を読み込み、自身のプロダクトへ反映させる応用力が身についた
    - 1つずつ積み上げていくことで大きなことを成し遂げられるということに気づいた。
- 反省点
    - 期限や絶対に完成させないといけない強制的なものがなかったのでだらけることがあった。
    - LaravelやReactなどもっとモダンな技術を使って実装するべきだった。
    - 画像アップロードに時間がかかりすぎてしまっている。
    - GithubやDockerなどのツールやterraformなどのIac関連周りの知識も活用して製作を進めていくべきだった。

まずはよかった点からです。
私自身これまで大学時代にプログラミングスクールに通ってwebサイトを製作しデプロイまでした経験はあるのですが静的なサイトでクオリティも高くありませんでした。しかし今回はPHPというプログラミング言語を使用して、自分が触ったことや見たことある機能である"いいね"や"フォロー"を実装するなど前回よりはレベルアップしたプロダクトを作成することができました。また今までの人生においてここまで長期間で1つのものを"0から作る"という経験はしたことが無かったのでとてつもない苦労と同時に達成感を得ることができました。
一方で反省すべき点としてはプロダクトとしての技術レベルがまだまだであったことです。私は当初、Laravelというようなモダンなフレームワークを使用せずに書籍に記載のあるフレームワークを使用しました。使用した理由はフレームワークの内部処理を実際にコーディングをして動きを知ることでフレームワークに対する知見を深めるためでした。(もちろん講師の方から推薦されたということも理由の1つです)この経験はもちろんタメになったと思います。なぜルーティングをこのように書けば指定されたコントローラへ処理をするようになるのかなどその処理の推移を知ることができました。しかし、現場で活躍するためにはそのさらに1歩進んだ状態でいることが重要であったのではないかと考えています。使用経験のあるフレームワークとして書籍のフレームワークと書くことができないためです。やはり説得力があるためにはLaravelというものが必要であったのではないかと思っています。この境地まで到達できなかった原因は私の慢心にあります。井の中の蛙でした。そのため今後はこれまでの1年間の学習を踏まえさらに飛躍してLaraevl X Reactの開発ができる技術レベルになるまで精進していきたいと考えております。
以上でこのREADMEを締めさせていただきます。最後までご覧いただきありがとうございました。X(旧ツイッター)ではtorihaziというユーザ名で活動しています。よろしければフォロー等していただけますと幸いです。 by torihazi..(2024/01/25)

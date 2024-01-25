<?php

class Pager {

	public static function pager($c, $t) {
    	
    	$current_page = $c;     //現在のページ
    	$total_rec = $t;    //総レコード数->合計データ数->$記事テーブルのCOUNT(*)を取ればいいのか。それを渡せばいい。
    	$page_rec   = ARTICLE_LIMIT;   //１ページに表示するレコード
    	$total_page = ceil($total_rec / $page_rec); //総ページ数
    	$show_nav = 5;  //表示するナビゲーションの数
    	$pg = '?pg=';   //パーマリンク
    	$path = ''; //GETパラメータの値、pg以外取り出す予定

    	 
    	//全てのページ数が表示するページ数より小さい場合、総ページを表示する数にする
    	if ($total_page < $show_nav) {
    	
    	    $show_nav = $total_page;
    	
    	}
    	
    	//トータルページ数が2以下か、現在のページが総ページより大きい場合表示しない
    	if ($total_page <= 1 || $total_page < $current_page ) return;
    	
    	//総ページの半分
    	$show_navh = floor($show_nav / 2);
    	
    	//現在のページをナビゲーションの中心にする
    	$loop_start = $current_page - $show_navh;
    	$loop_end = $current_page + $show_navh;
    	
    	//現在のページが両端だったら端にくるようにする
    	if ($loop_start <= 0) {
    	
    	    $loop_start  = 1;
    	    $loop_end = $show_nav;
    	
    	}
    	
    	if ($loop_end > $total_page) {
    	
    	    $loop_start  = $total_page - $show_nav +1;
    	    $loop_end =  $total_page;
    	
    	}

    	//?pg=1[[ &freeword='hogehoge'&・・・]] の部分をここで作成
    	if(!empty($_GET)){

    		$path .= '&';

    		foreach($_GET as $key => $val){

    			if($key !== 'pg'){

    				$path .= $key . "=" . $val;

    				if(array_key_last($_GET) !== $key){

    					$path .= "&";

    				}
    			}
    		}
    	}



    	return(array(
    			'current_page' => $current_page,
    			'pg' => $pg,
    			'path' => $path,
				'loop_start' => $loop_start,
				'total_page' => $total_page,
				'loop_end' => $loop_end,
				));
	}

}

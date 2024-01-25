<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="ONLYDESKであなただけの理想のデスク環境を見つけましょう。多くのユーザがお気に入りのデスク周りを投稿しています。">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($title)): echo $this->escape($title) . ' - '; endif;?>ONLYDESK</title>

    <link rel="stylesheet" type="text/css" href="/css/ress.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/cropper.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/css/style.css" >
    <script src="https://kit.fontawesome.com/96a28a998b.js" crossorigin="anonymous"></script>

</head>

<body>

    <header id="header" class="header">

        <div class="logo">
            <a class="opacity_animation" href="<?php echo $base_url; ?>/post/list"><span class="logo">ONLYDESK</span></a>
        </div>


        <div class="header_menu_container">
            <?php if (preg_match("/\/post\/list/", $_SERVER['REQUEST_URI']) || preg_match("/\/post\/search.*/", $_SERVER['REQUEST_URI'])): ?>
                <div class="search_form_container desktop">
                    <form action="<?php echo $base_url; ?>/post/search" method="get">
                        <div class="search_form_box">
                            <button type="submit" name="submit" value="">
                                <div class="search_button">
                                    <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                                </div>
                            </button>
                            <div class="search_form">
                                <input type="text" name="freeword" placeholder="机を探す">
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; ?> 

            <div class="hamburger_wrapper">
                    <div class="openbutton" id="openbutton">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <nav id="g_nav" class="g_nav">
                    <div id="g_nav_list" class="g_nav_list">
                        <ul class="nav_list" id="nav_list">
                            <li>
                                <?php if (preg_match("/\/post\/list/", $_SERVER['REQUEST_URI']) || preg_match("/\/post\/search.*/", $_SERVER['REQUEST_URI'])): ?>
                                    <div class="search_form_container mobile">
                                        <form action="<?php echo $base_url; ?>/post/search" method="get">
                                            <div class="search_form_box">
                                                <button type="submit" name="submit" value="">
                                                    <div class="search_button">
                                                        <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                                                    </div>
                                                </button>
                                                <div class="search_form">
                                                    <input type="text" name="freeword" placeholder="机を探す">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </li> 
                            <li><a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/post/list">ホーム</a></li>
    
                            <?php if ($session->isAuthenticated()): ?>
                                <li><a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/login/signout">ログアウト</a></li>
                                <li><a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/mypage/profile?user_id=<?php echo $_SESSION['user']['id']; ?>">マイページ</a></li>
                                <li><a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/mypage/profileUpdateForm">マイページ更新</a></li>
                                <li><a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/post/uploadForm">投稿</a></li>
                                <li><a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/mypage/pwChangeForm">PW変更</a></li>
                                <li><a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/mypage/quitForm">退会</a></li>
                            <?php elseif (! $session->isAuthenticated()): ?>
                                <li><a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/login/signin">ログイン</a></li>
                            <?php endif; ?>
    
                            <li><a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/contact/qaForm">お問い合わせ</a></li>
    
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    
    <main>
        <?php echo $_content; ?>
    </main>

    <footer id="footer">
        <div>@copy right</div>
    </footer>
    
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="/js/cropper.min.js"></script>

    <script src="/js/goodajax.js"></script>
    <script src="/js/followajax.js"></script>
    <script src="/js/hamburger.js"></script>

</body>
</html>
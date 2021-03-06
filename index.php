<?php
    // 初始化
    if(!file_exists("config")){
        header("location:./install.php");
        exit();
    }

    include "./config";
    include "./func.php";

    try{
        $conn = connect();
    }catch(Exception $error){
        display_message($error->getMessage(),"","error");
        exit();
    }

    $nickname = get_nickname($conn);
    //获取页面参数
    if( isset($_GET['page']) && is_num($_GET['page'])){
        $page = $_GET['page'];
    }else{
        $page = 1;
    }

    $PAGESIZE = 5; 
    $pages = ceil(get_rows($conn) / $PAGESIZE) ;
    $offset = $PAGESIZE*($page-1);
    $select = "select * from article order by id desc limit $offset,$PAGESIZE";
    $result = $conn->query($select);

?>
<!DOCTYPE html>
<html>
<head>
    <title>monogatari</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-combined.min.css">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style type="text/css">
        body {
            background-image:url("image/theme.png");
            background-size: cover;
            background-repeat:no-repeat;
            background-attachment:fixed;
        }
        .hero-unit{
            font-weight: normal;
        }
        .jumbotron {
            background:url("image/banner.jpg");
            background-position: 50% 60%;
            background-size: cover;
            box-shadow: 0 0px 30px rgba(0, 0, 0, 0.36) inset;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2"></div>
            <div class="span8">
                <div class="header">
                    <ul class="nav nav-pills pull-right">
                        <li>
                            <a href="./index.php">主页</a>
                        </li>
                        <li>
                            <a href="./archives.php">归档</a>
                        </li>
                        <li>
                            <a href="./about.php">about me</a>
                        </li>
                    </ul>
                    <h4 class="text-muted">
                        <small><?php echo $nickname;?></small>
                    </h4>
                </div>
                <div class="jumbotron">
                    <p class="lead pull-middle"> 
                        我們所度過的每個平凡の日常
                    </p>
                    <div class="span12"></div>
                    <div class="span1"></div>
                    <h2>
                        也許就是連續發生的奇跡 
                    </h2>
                </div>
            </div>
        </div>
        <!-- 文章内容-->
        <?php
            while ( $array = mysqli_fetch_row($result) ) {
        ?>
        <div class="row-fluid">
            <div class="span2"></div>
            <div class="span8">
                <div class="hero-unit">
                    <h3><?php echo $array[1];?></h3>
                    <p>    
                        <?php echo mb_substr($array[2],0,140,'utf8');?>
                    </p>
                    <p>
                         <a class="btn btn-primary pull-right" href=<?php echo './article.php?id='.$array[0];?>>Read More »</a>
                    </p>
                </div>

            </div>
        </div>
        <?php
            }
            ?>
        <!-- 文章部分 结束 -->

        <!-- 分页 -->
        <div class="row-fluid">
            <div class="span2"></div> 
            <div class="span8">
                <div class="pagination pull-left">
                    <ul>
                        <li>
                            <a href=<?php echo './index.php?page='.($page-1<=0 ? $page : $page-1);?>>上一页</a>
                        </li>
                    </ul>
                </div>
                <div class="pagination pull-right">
                    <ul>
                        <li>
                            <a href=<?php echo './index.php?page='.($page+1 > $pages ? $page : $page+1 );?>>下一页</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav nav-list ">
            <li class="divider"></li>
            <p class="pull-right">powered by Qsaka</p>
        </ul>
    </div>
</body>
</html>
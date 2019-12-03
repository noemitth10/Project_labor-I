<?php require_once "pages/head.php";?>
<!--
<style>
.mySlides {display:none}
.w3-left, .w3-right, .w3-badge {cursor:pointer}
.w3-badge {height:13px;width:13px;padding:0}
</style>-->

<?php 
$sql = new SQL();

//newest products
$newest = $sql->execute("SELECT * FROM `products` WHERE (`adpic` <> ?) AND (`adpic` IS NOT NULL) ORDER BY `id` DESC LIMIT 6","none");

//best products
$best_scored = $sql->execute("SELECT * FROM `products` WHERE (`adpic` <> ?) AND (`adpic` IS NOT NULL) ORDER BY `score` DESC LIMIT 6 ", "none");

?>
<div class="page-home">
    <div class="main-column">
        <!--<img src="menogif.gif" alt="So 2004">-->
        <section class="home-big-ad">
        <div class="home-title">
        <h1 class="home-titles">TOP TRENDING</h1>  
        </div>
       
        <div class="slideshow-container">
            <?php
            for ($i=0; $i < sizeof($newest); $i++):
            ?>
                <div class="mySlides fade">
                    <a href="<?=url('product')?>&id=<?=$newest[$i]['id']?>">
                        <div class="box-hover">
                            <div class="home-coverimg" style="float: left;">
                                <img src="<?=$newest[$i]['cover']?>" style="width: 100%;">
                            </div>
                            <div>
                                <div style="font-size: 20pt">
                                    <?=$newest[$i]['title']?>
                                </div>
                                <div style="max-height: 275px; padding: 4%; line-height:22px !important; text-align: justify;">
                                    <?=$newest[$i]['description']?>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="numbertext">1 / <?=sizeof($newest)?></div>
                    <img src="<?=$newest[$i]['adpic']?>" style="width:100%; height: 600px">
                    <div class="text"></div>
                </div>
            <?php
            endfor;
            ?>

            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>

        </div>
        <br>

        <div style="text-align:center">
            <?php for ($i=0; $i < sizeof($newest); $i++):?>
                <span class="dot" onclick="currentSlide(<?=($i+1)?>)"></span> 
            <?php endfor; ?>
        </div>
        </section>
        <div></div>
        <div class="home-title">
            <h1 class="home-titles">BEST REVIEWED</h1>  
            <div class="slideshow-container">
            <?php
            for ($i=0; $i < sizeof($best_scored); $i++):
            ?>
                <div class="mySlides2 fade">
                    <a href="<?=url('product')?>&id=<?=$best_scored[$i]['id']?>">
                        <div class="box-hover">
                            <div class="home-coverimg" style="float: left;">
                                <img src="<?=$best_scored[$i]['cover']?>" style="width: 100%;">
                            </div>
                            <div>
                                <div style="font-size: 20pt">
                                    <?=$best_scored[$i]['title']?>
                                </div>
                                <div style="max-height: 275px; padding: 4%; line-height:22px !important; text-align: justify;">
                                    <?=$best_scored[$i]['description']?>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="numbertext">1 / <?=sizeof($best_scored)?></div>
                    <img src="<?=$best_scored[$i]['adpic']?>" style="width:100%; height: 600px">
                    <div class="text"></div>
                </div>
            <?php
            endfor;
            ?>

            <a class="prev" onclick="plusSlides2(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides2(1)">&#10095;</a>

        </div>
        <br>

        <div style="text-align:center">
            <?php for ($j=0; $j < sizeof($best_scored); $j++):?>
                <span class="dot2" onclick="currentSlide2(<?=($j+1)?>)"></span> 
            <?php endfor; ?>
        </div>
        </div>
        <div class="home-title">
            <h1 class="home-titles">coming soon...</h1>  
        </div>
        <div class="com-soon-img"> 
            <img src="images/comingsoonad.jpg" alt="Coming soon..." class="home-cs-image"> 
            <div class="h-csimg-middle">
                <div class="h-csimg-text">Coming soon...</div>
            </div>
        </div>
        <div class="home-title">
        <h1 class="home-titles">People said about us:</h1>  
        </div>
        <p style="text-align: justify"></p><br>
        <div class="home-opinion-box">
        <?php 
        $reviews = $sql->execute("SELECT * FROM `reviews` WHERE `product_id` = 1 AND `score` >4");
        $count = $sql->execute("SELECT Count(*) as `count` FROM `reviews` WHERE `product_id` = ? ",1);
        foreach ($reviews as $key => $value) {
            $profilepic = $sql->execute("SELECT `profile_pic` FROM `users` WHERE id = ?",$value['user_id']);
            $username = $sql->execute("SELECT `user_name` FROM `users` WHERE id = ?",$value['user_id']);
            $message = $value['msg'];
            $score = $value['score'];
            $generatedid=GenerateID(4);    
            echo "<div class='h-o-box-peruser'><div class='h-opinion-left-side'><img src=".$profilepic[0]['profile_pic']." class=p-review-profile-pic>";
            echo "<p>".$username[0]['user_name']."</p></div>";
            echo "<span class=rating>";
            if($score == 5) echo "<input type=radio class=rating-input id=rating-input-1-5 name=rating-input$generatedid value =5 checked>";
            else  echo "<input type=radio class=rating-input id=rating-input-1-5 name=rating-input$generatedid value =5>";
            echo "<label for=rating-input-1-5 class=rating-star></label>";
            if($score ==4) echo "<input type=radio class=rating-input id=rating-input-1-4 name=rating-input$generatedid value =4 checked>";
            else  echo "<input type=radio class=rating-input id=rating-input-1-4 name=rating-input$generatedid value =4>";
            echo "<label for=rating-input-1-4 class=rating-star></label>";
            if($score == 3)echo "<input type=radio class=rating-input id=rating-input-1-3 name=rating-input$generatedid value =3 checked>";
            else echo "<input type=radio class=rating-input id=rating-input-1-3 name=rating-input$generatedid value =3>";
            echo "<label for=rating-input-1-3 class=rating-star></label>";
            if($score ==2)echo "<input type=radio class=rating-input id=rating-input-1-2 name=rating-input$generatedid value =2 checked>";
            else"<input type=radio class=rating-input id=rating-input-1-2 name=rating-input$generatedid value =2>";
            echo "<label for=rating-input-1-2 class=rating-star></label>";
            if($score == 1) echo "<input type=radio class=rating-input id=rating-input-1-1 name=rating-input$generatedid value =1 checked>";
            else echo "<input type=radio class=rating-input id=rating-input-1-1 name=rating-input$generatedid value =1>";
            echo "<label for=rating-input-1-1 class=rating-star></label>";
            echo "</span><br><br><br>";
            echo "<div class='message-box'><p>$message</p></div></div>";  
        }
        ?>
        </div>

</div>
    </div>
</div> 
<?php require_once "pages/footer.php"; ?>
<script>
    var intervalID = setInterval(function(){plusSlides(1)}, 10000);
    var slideIndex = 1;
        showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        if (n > slides.length) {
            slideIndex = 1;
        }    
        if (n < 1) {
            slideIndex = slides.length;
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";  
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "block";  
        dots[slideIndex-1].className += " active";
    }
</script>
<script>
    var intervalID2 = setInterval(function(){plusSlides2(1)}, 8000);
    var slideIndex2 = 1;
        showSlides2(slideIndex2);

    function plusSlides2(n) {
        showSlides2(slideIndex2 += n);
    }

    function currentSlide2(n) {
        showSlides2(slideIndex2 = n);
    }

    function showSlides2(n) {
        var j;
        var slides2 = document.getElementsByClassName("mySlides2");
        var dots2 = document.getElementsByClassName("dot2");
        if (n > slides2.length) {
            slideIndex2 = 1;
        }    
        if (n < 1) {
            slideIndex2 = slides2.length;
        }
        for (j = 0; j < slides2.length; j++) {
            slides2[j].style.display = "none";  
        }
        for (j = 0; j < dots2.length; j++) {
            dots2[j].className = dots2[j].className.replace(" active", "");
        }
        slides2[slideIndex2-1].style.display = "block";  
        dots2[slideIndex2-1].className += " active";
    }
</script>
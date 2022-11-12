<?php

    include "includes/navbar.php";
    include "includes/dbconn.php";
    include "includes/login_form.php";

?>

<?php 
    $product_id=$_GET['product_id'];
    //echo $product_id;
    $query="SELECT * FROM products WHERE product_id=$product_id";
    $result=mysqli_query($conn,$query);
    $result=mysqli_fetch_assoc($result);
    //print_r($result);
    $img_path=substr(explode(',',$result['img'])[0],1);
    //print_r($img_path);
    //$img=$img_path[0];

?>
<?php 
    if(!empty($_SESSION)){
        $user_id=$_SESSION['user_id'];
        $query1="SELECT * FROM wishlist WHERE user_id=$user_id AND product_id=$product_id";
        $result1=mysqli_query($conn,$query1);
        $num_rows=mysqli_num_rows($result1);
        $query2="SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id";
        $result2=mysqli_query($conn,$query2);
        $num_cart=mysqli_num_rows($result2);
        $query4="SELECT * FROM reviews WHERE product_id=$product_id";
        $result4=mysqli_query($conn,$query4);
        $counter=0;
        $total=0;
        while($row=mysqli_fetch_assoc($result4)){
            $counter++;
            $total+=$row['rating'];
        }
        if($counter==0){
            $avg_rating="No";
        }else{
            $avg_rating=$total=$total/$counter;
        }
        
    }
    else{
        $num_rows=0;
        $num_cart=0;
        $query4="SELECT * FROM reviews WHERE product_id=$product_id";
        $result4=mysqli_query($conn,$query4);
        $counter=0;
        $total=0;
        while($row=mysqli_fetch_assoc($result4)){
            $counter++;
            $total+=$row['rating'];
        }
        if($counter==0){
            $avg_rating="No";
        }else{
            $avg_rating=$total=$total/$counter;
        }
    }
    
?>
<script type="text/javascript">

    $(document).ready(function(){
        $('#wishlist-btn').click(function(){
            var product_id='<?php echo $_GET['product_id'];?>';
            //alert(product_id),
            var val='<?php echo empty($_SESSION);?>';
            console.log(val);
            if(val==1){
                alert('Log In to Continue')
            }
            else{
                $.ajax({
                url:'add_to_wishlist.php',
                method:'POST',
                data:{'product_id':product_id},
                success:function(data){
                    //alert(data)
                    if(data.trim()==='success'){
                        $('#wishlist-btn').hide()
                        $('#button-container').append('<button class="btn bg-success btn-lg ml-3">Wishlisted</button>')
                    }
                },
                error:function(error){
                    alert(error);
                },
            })
            }    
        })
        $('#cart-btn').click(function(){
            var product_id='<?php echo $_GET['product_id'];?>';
            var val='<?php echo empty($_SESSION);?>';
            if(val==1){
                alert('Log in to continue');
            }
            else{
                $.ajax({
                    url:'cart.php',
                    method:'POST',
                    data:{'product_id':product_id},
                    success:function(data){
                        if(data.trim()==="success"){
                            $('#cart-btn').hide()
                            $('#button-container').prepend('<button class="btn bg-success btn-lg mt-2">Added to Cart</button>')
                            //$('#carted-btn').show()
                        }
                    },
                    error:function(error){
                        alert('error');
                    },
                })
            }
        })
        $("#st1").click(function() {
              $("#st1").css("color","#ffcc00");
              $("#st2,#st3,#st4,#st5").css("color","black");
              val=1
            });
            $("#st2").click(function() {  
              $("#st1,#st2").css("color","#ffcc00"); 
              $("#st3,#st4,#st5").css("color","black")
              val=2
            });
            $("#st3").click(function() {  
              $("#st1,#st2,#st3").css("color","#ffcc00");
              $("#st4,#st5").css("color","black")
              val=3
            });
            $("#st4").click(function() {  
              $("#st1,#st2,#st3,#st4").css("color","#ffcc00"); 
              $("#st5").css("color","black")
              val=4
            });
            $("#st5").click(function() {  
              $("#st1,#st2,#st3,#st4,#st5").css("color","#ffcc00");
              val=5 
            });  
            $('#review_submit').click(function(){
                var title=$('#title').val()
                //alert(title)
                var body=$('#body').val()
                var product_id='<?php echo $_GET['product_id'];?>'
                
                //alert(body)
                $.ajax({
                    url:"submit_review.php",
                    method:'POST',
                    data:{'product_id':product_id,'rating':val,'title':title,'body':body},
                    success:function(data){
                        window.location.href="description.php?product_id="+data.trim()
                    },
                    error:function(error){
                        alert('error');
                    },
                })
    
    })})
    </script>
    <div class="container mt-5">

    <div class="row">

    <div class="col-md-12">
        <h5>Home--><a href="category.php?category=<?php echo $result["category"]?>"><?php echo $result["category"]?></a></h5>
        

    </div>
    <div class="row mt-5 mb-5">
     <div class="col-6 col-md-6">

     <img src=<?php echo $img_path?> alt="" style="width:100%;height:auto;">
     </div>
    <div class="col-6 col-md-6">
    <h2><?php echo $result['name'] ?></h2>
    <p><?php echo $counter ?> Reviews | <?php echo $avg_rating ?> Average Rating</p>
    <h3>₹ <?php echo $result['price'] ?></h3>
    <div class="mt-3 mb-5" id="button-container">
    
    <?php
    if($num_cart==0){
        echo '<button class="btn bg-amazon btn-lg text-white mr-3 mt-2" id="cart-btn">Add to Cart</button>';
    }
    else{
        echo '<button class="btn bg-success btn-lg mt-2">Added to Cart</button>';
    }
    if($num_rows==0){
        echo '<button class="btn bg-warning btn-lg mt-2" id="wishlist-btn">Wishlist</button>';
    }
    else{
        echo '<button class="btn bg-success btn-lg  mt-2">Wishlisted</button>';
    }
     ?>
    </div>
    
    
    <p><?php echo $result['details'] ?></p>
    
    </div>
    </div>
    
    </div>
    <div class="row">
        <div class="col-md-7">
        <?php 
            $query3="SELECT * FROM reviews r JOIN user u ON r.user_id=u.user_id WHERE product_id=$product_id";
            
            $result3=mysqli_query($conn,$query3);
            echo '<hr>';
            while($row=mysqli_fetch_assoc($result3)){
                $user_id6=$row['user_id'];
                $query6="SELECT * FROM pictures WHERE user_id=$user_id6";
                $result6=mysqli_query($conn,$query6);
                $result6=mysqli_fetch_assoc($result6);
                $num_star=$row['rating'];
                echo '<span class="ml-3"></span>';
                for($i=0;$i<$num_star;$i++){
                    echo '<i class="fa-sharp fa-solid fa-star fa-1x" style="color:#FFD700" ></i>';
                }
                echo'
                <div class="col-md-7 mt-3">
                <h4>'.$row['title'].'</h4>
                <p><span class="float:right">Reviewed by <b>'.$row['name'].'</b></span><img src="img_list/'.$result6['filename'].'" style="height:50px;width:50px;border-radius:100%;" class="ml-3" alt="NO IMAGE"></p>
                <p>'.$row['review_text'].'</p>
                <p>Reviewed On - <b>'.$row['review_date'].'</b></p>
                <hr>
    
            </div>';
            }
            
        ?>
        </div>
        
        
            <div class="col-md-5">
            <h3>Add Review</h3>
                
                    <!-- <label>Rate the product ( 1 to 5 )</label><br>
                    <input type="number" name="rating" min="1" max= "5" class="form-control"><br> -->
                    <i class="fa-sharp fa-solid fa-star fa-2x" id="st1"></i>
                    <i class="fa-sharp fa-solid fa-star fa-2x" id="st2"></i>
                    <i class="fa-sharp fa-solid fa-star fa-2x" id="st3"></i>
                    <i class="fa-sharp fa-solid fa-star fa-2x" id="st4"></i>
                    <i class="fa-sharp fa-solid fa-star fa-2x" id="st5"></i><br><br>
                    <label>Review Title</label><br>
                    <input type="text" id="title" class="form-control"><br>
                    <label>Review Text</label><br>
                    <textarea  id="body" class="form-control" cols="30" rows="10"></textarea><br>
                    <button class="btn btn-lg btn-warning mb-2" id="review_submit">Submit Review</button><br>
                
            </div>     
    </div>
    </div>
    </div>

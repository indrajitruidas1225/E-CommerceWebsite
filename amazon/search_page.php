<?php 
    include "includes/dbconn.php";
    include "includes/navbar.php";
    $text=$_GET['search_key'];
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-5">Search Results for <?php echo "'$text'" ?></h2>
        </div>
        <?php 
        $query="SELECT * FROM products WHERE name LIKE '%$text%'";
        $result=mysqli_query($conn,$query);
        $counter=0;
        while($row=mysqli_fetch_assoc($result)){
            $img=substr(explode(',',$row['img'])[0],1);
            echo '<div class="col-md-4 mt-5">
            <div class="card" >
                <img src='.$img.' alt="" >
                    <div class="card_body">
                    <h4>'.$row['name'].'</h4>
                    <p>Rs '.$row['price'].'</p>
                    <a href="description.php?product_id='.$row['product_id'].'" class="btn btn-warning">View</a>
                    </div>
                </div>
            </div>';
            $counter++;
        }
        if($counter==0){
            echo '<h4 class="mt-5">No Such Product Found !</h4>';
        }
        
        
        ?>
        
    </div>
</div>
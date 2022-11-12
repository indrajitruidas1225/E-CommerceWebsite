<?php 
    include "includes/navbar.php";
    $user_id=$_SESSION['user_id'];
    $order_id=$_GET['order_id'];
?>
<div class="container">
    <div class="row mt-5">
        <div class="col-12 col-md-8">
            <div class="row">
                
                <div class="col-12 col-md-12">
                    <h4>Select Address</h4>
                </div>
                <?php 
                    $query="SELECT * FROM address WHERE user_id=$user_id";
                    $result=mysqli_query($conn,$query);
                    $counter=0;
                    while($row=mysqli_fetch_assoc($result)){

                        echo '
                        <div class="col-12 col-md-12 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <p>
                                    <b>Address</b> - '.$row['details'].'<br>
                                    <b>Mobile</b> - '.$row['phone'].'<br>
                                    <b>Pin Code</b> - '.$row['pincode'].'<br>
                                </p>
                                <a href="update_address.php?address_id='.$row['address_id'].'&order_id='.$order_id.'" class="btn btn-sm btn-warning">Select this address</a>
                            </div>
                        </div>
                    </div>
                        ';
                    $counter++;
                    }
                    if($counter==0){
                        echo '<div class="col-12 col-md-12 mt-3">
                        <h4>You have no address</h4>
                        </div>';
                    }
                ?>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="row">
                <div class="col-12 col-md-12">
                    <h4>Add New Address</h4>
                </div>
                <div class="col-12">
                <form action="add_new_address.php" method='POST'>
                    <label>Address Details</label><br>
                    <textarea class="form-control" name='details'></textarea><br>
                    <label>Phone</label><br>
                    <input type="phone" name="phone" class="form-control"><br>
                    <label>Pincode</label><br>
                    <input type="text" name="pincode" class="form-control"><br>
                    <input type="hidden" name="order_id" value=<?php echo $_GET['order_id'] ?>>
                    <input type="submit" class="btn btn-warning" value="Add Address">
                </form>
                </div>
                
            </div>
        </div>
    </div>
</div>
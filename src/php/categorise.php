<?php
    
    require_once ("connectDB.php");

    $catList = '<div class="col-lg-9  order-1 order-lg-2 mb-5 mb-lg-0">
    <div class="row">';

    if(!empty($_GET['category']) && !empty($_GET['gender'])) {

        $category = $_GET['category'];
        $gender = $_GET['gender'];

        if($gender == 'all') {
            $result = pg_query($link,"select * from product where category='".$category."' order by added DESC"); 
        } else if($gender == 'women') {
            if($category == 'all') {
            $result = pg_query($link,"select * from product where gender='".$gender."' order by added DESC");
            } else {
                $result = pg_query($link,"select * from product where gender='".$gender."' and category='".$category."' order by added DESC");
            }
        } else if($gender == 'men') {
            if($category == 'all') {
            $result = pg_query($link,"select * from product where gender='".$gender."' order by added DESC");
            } else {
                $result = pg_query($link,"select * from product where gender='".$gender."' and category='".$category."' order by added DESC");
            }
        }
    if(pg_num_rows($result)>0) {

        while($row = pg_fetch_array($result)) {
            $id = $row['id'];
            $pname = $row['pname'];
            $image = $row['image'];
            $price = $row['price'];
            $catList = $catList.'<div class="col-lg-4 col-sm-6">
                                    <div class="product-item">
                                        <div class="pi-pic">
                                        <img src="'.$image.'1.jpg" alt="">
                                        <div class="pi-links">
                                            <a href="#" ><form class="site-btn sb-white" id="form1" name="form1" method="post" action="cart.php">
                                            <input type="hidden" name="pid" id="pid" value="'.$id.'" /><i class="flaticon-bag"></i>
                                            <input type="submit" name="button" class="btn btn-light" value="Add to cart" />
                                            </form></a>
                                        </div>
                                    </div>
                                    <div class="pi-text">
                                        <h6>'.$price.'</h6>
                                        <a href="product.php/?id='.$id.'">'.$pname.' </a>
                                    </div>
                                </div>
                            </div>';
        }
        $catList .= '</div></div>';

    } else {
        $catList = '<div class="col-lg-9  order-1 order-lg-2 mb-5 mb-lg-0">No product available</div>';
    }


    }


?>
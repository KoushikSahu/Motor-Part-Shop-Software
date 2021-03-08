<?php

session_start();
if (empty($_SESSION['manager'])) {
    header("location: ../login.php");
    exit();
}
// $manager = preg_replace('#[^A-Za-z0-9@.]#i', '', $_SESSION['manager']);
$manager = htmlspecialchars($_SESSION['manager']);
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION['password']);

require_once("../php/connectDB.php");
$query = "SELECT id from admin where \"Username\"='" . $manager . "' and password='" . $password . "'";
// echo $query;
$sql = pg_query($link, $query);
$exists = pg_num_rows($sql);
if ($exists) {
    while ($row = pg_fetch_array($sql)) {
        $id = $row['id'];
    }
    $query = 'SELECT * from admininfo where id='.$id;
    // echo $query;
    $sql = pg_query($link, $query);
    while ($row = pg_fetch_array($sql)) {
        $name = $row['firstName'];
    }
    $_SESSION['id'] = $id;
    $_SESSION['manager'] = $manager;
    $_SESSION['name'] = $name;
    $_SESSION['password'] = $password;
    if (!empty($_POST['stayin']) && $_POST['stayin'] == '1') {
        setcookie("id", $_SESSION['id'], time() + 3600 * 24);
        setcookie("manager", $_SESSION['manager'], time() + 3600 * 24);
        setcookie("name", $_SESSION['name'], time() + 3600 * 24);
        setcookie("password", $_SESSION['password'], time() + 3600 * 24);
    }
    // header("Location: backend.php");

} else {
    // print_r($_SESSION);
    echo "SESSION DATA DOES NOT EXIST IN DATABASE";
    exit();
}

?>

<?php

if (isset($_POST['product-name']) && $_POST['product-name'] != "") {
    $product_name = pg_escape_string($link, $_POST['product-name']);
    $price = pg_escape_string($link, $_POST['product-price']);
    $category = pg_escape_string($link, $_POST['category']);
    $for = pg_escape_string($link, $_POST['for']);
    $quantity = pg_escape_string($link, $_POST['quantity']);


    $sql = pg_query($link, "SELECT id from product where pname='" . $product_name . "'");
    if (pg_num_rows($sql)) {
        printf("Product name already exists");
        exit();
    }
    // print_r($_POST);

    $sql = pg_query($link, 'insert into product(category,gender,pname,quantity,price,added) values("' . $category . '","' . $for . '","' . $product_name . '",' . $quantity . ',' . $price . ',now()) returning id') or die(pg_last_error($link));
    $insert_row = pg_fetch_row($res);
    $pid = $insert_row[0];
    $sql1 = pg_query($link, 'UPDATE product SET image = "img/single-product/' . $pid . '/" WHERE product.id = ' . $pid);
    echo $sql1;
    $error = array();
    $extension = array("jpeg", "jpg", "png");
    $i = 1;
    mkdir("../img/single-product/$pid");
    $foldername = "../img/single-product/$pid/";
    foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {

        $file_name = $_FILES["image"]["name"][$key];
        $file_tmp = $_FILES["image"]["tmp_name"][$key];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name = $i . ".jpg";
        if (in_array($ext, $extension)) {

            if (!file_exists($foldername . $file_name)) {
                move_uploaded_file($file_tmp = $_FILES["image"]["tmp_name"][$key], $foldername . $file_name);
            } else {
                $i++;
                $file_name = $i . ".jpg";
                move_uploaded_file($file_tmp = $_FILES["image"]["tmp_name"][$key], $foldername . $file_name);
            }
        }
        $i++;
    }
    header("location:backend.php");
    exit();
}

?>

<?php

$product_list = '</br></br><table class="table ">
<thead>
  <tr>
    <th scope="col">#id</th>
    <th scope="col">Product Name</th>
    <th scope="col">Date Added</th>    
    <th scope="col">Remaining stock</th>
    <th scope="col">Action</th>
  </tr>
</thead>
<tbody>';
$sql = pg_query($link, "select * from product order by added DESC");
if (pg_num_rows($sql)) {
    while ($row = pg_fetch_array($sql)) {
        $id = $row['id'];
        $product_name = $row['pname'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $date_added = strftime("%b %d,%Y", strtotime($row['added']));
        $product_list .= '<tr>
        <th scope="row">' . $id . '</th>
        <td>' . $product_name . '</td>
        <td>' . $date_added . '</td>
        <td>' . $quantity . '</td>
        <td><a href="edit_item.php?pid=' . $id . '">edit</a>&bull;<a href="backend.php?deleteid=' . $id . '">delete</a></td>
      </tr>';
    }
    $product_list .= '</tbody>
    </table>';
} else {
    $product_list = "Your inventory is empty";
}

?>

<?php

if (isset($_GET['deleteid'])) {
    echo "Are you sure you want to delete the product with ID= " . $_GET['deleteid'] . "?<a href='backend.php?yesdelete=" . $_GET['deleteid'] . "'> Yes </a> | <a href='backend.php'> No </a> ";
    exit();
}

function delete_files($target)
{
    if (is_dir($target)) {
        $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

        foreach ($files as $file) {
            delete_files($file);
        }

        rmdir($target);
    } elseif (is_file($target)) {
        unlink($target);
    }
}

if (isset($_GET['yesdelete'])) {
    $id_to_delete = $_GET['yesdelete'];
    $sql = pg_query($link, 'Delete from product where id=' . $id_to_delete) or die(pg_last_error($link));
    delete_files('../img/single-product/' . $id_to_delete . '/');
    /* 
        * php delete function that deals with directories recursively
        */
    header("location:backend.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin UI</title>

    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="http://placehold.it/64.png/000/fff">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">

    <!-- Styles -->
    <link href="assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="assets/css/lib/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/lib/helper.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">



    <style>
        .jumbotron {
            position: relative;
            width: 60vw;
            top: 5vw;
            left: 18vw;
        }

        #disp {
            width: 60vw;
            position: relative;
            top: 10vw;
            left: 18vw;
        }

        #add-to-inventory {
            width: 60vw;
            position: relative;
            top: 15vw;
            left: 18vw;

        }

        #button {
            margin-left: 25vw;
            margin-bottom: 5vw;
        }
    </style>

</head>

<body>

    <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
        <div class="nano">
            <div class="nano-content">
                <div class="logo"><a href="#">
                        <!-- <img src="assets/images/logo.png" alt="" /> --><span>Fachione</span></a></div>
                <ul>
                    <li class="label">Main</li>
                    <li class="active"><a class="sidebar-sub-toggle"><i class="ti-home"></i> Dashboard <span class="badge badge-primary">1</span> <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="#">Dashboard 1</a></li>
                        </ul>
                    </li>

                    <li class="label">Apps</li>
                    <li><a class="sidebar-sub-toggle"><i class="ti-bar-chart-alt"></i> Statistics <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="#">Sales Chart</a></li>
                            <li><a href="#">Profits</a></li>
                            <li><a href="#">Returns</a></li>
                            <li><a href="#">New Users Stats</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="ti-calendar"></i> Calender </a></li>
                    <li><a href="#"><i class="ti-email"></i> Email</a></li>
                    <li><a href="#"><i class="ti-user"></i> Profile</a></li>
                    <li class="label">Features</li>
                    <li><a class="sidebar-sub-toggle"><i class="ti-layout-grid4-alt"></i> Table <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="#">Basic</a></li>

                            <li><a href="#">Datatable Export</a></li>
                            <li><a href="#">Datatable Row Select</a></li>
                            <li><a href="#">Editable </a></li>
                        </ul>
                    </li>
                    <li><a class="sidebar-sub-toggle"><i class="ti-map"></i> Maps <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="#">Basic</a></li>
                            <li><a href="#">Vector Map</a></li>
                        </ul>
                    </li>
                    <li class="label">Form</li>
                    <li><a href="#"><i class="ti-view-list-alt"></i> Basic Form </a></li>
                    <li class="label">Extra</li>
                    <li><a class="sidebar-sub-toggle"><i class="ti-files"></i> Invoice <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="#">Basic</a></li>
                            <li><a href="#">Editable</a></li>
                        </ul>
                    </li>
                    <li><a class="sidebar-sub-toggle"><i class="ti-target"></i> Pages <span class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="../login.php" target="_blank">Login / Sign Up</a></li>
                            <li><a href="#">Forgot password</a></li>
                        </ul>
                    </li>
                    <li><a href="../php/logout.php"><i class="ti-close"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /# sidebar -->







    <div class="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="float-left">
                        <div class="hamburger sidebar-toggle">
                            <span class="line"></span>
                            <span class="line"></span>
                            <span class="line"></span>
                        </div>
                    </div>
                    <div class="float-right">
                        <ul>

                            <li class="header-icon dib"><i class="ti-bell"></i>
                                <div class="drop-down">
                                    <div class="dropdown-content-heading">
                                        <span class="text-left">Recent Notifications</span>
                                    </div>
                                    <div class="dropdown-content-body">
                                        <ul>
                                            <li>
                                                <a href="#">
                                                    <img class="pull-left m-r-10 avatar-img" src="assets/images/avatar/3.jpg" alt="" />
                                                    <div class="notification-content">
                                                        <small class="notification-timestamp pull-right">02:34 PM</small>
                                                        <div class="notification-heading">Mr. John</div>
                                                        <div class="notification-text">5 members joined today </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img class="pull-left m-r-10 avatar-img" src="assets/images/avatar/3.jpg" alt="" />
                                                    <div class="notification-content">
                                                        <small class="notification-timestamp pull-right">02:34 PM</small>
                                                        <div class="notification-heading">Mariam</div>
                                                        <div class="notification-text">likes a photo of you</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img class="pull-left m-r-10 avatar-img" src="assets/images/avatar/3.jpg" alt="" />
                                                    <div class="notification-content">
                                                        <small class="notification-timestamp pull-right">02:34 PM</small>
                                                        <div class="notification-heading">Tasnim</div>
                                                        <div class="notification-text">Hi Teddy, Just wanted to let you ...</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img class="pull-left m-r-10 avatar-img" src="assets/images/avatar/3.jpg" alt="" />
                                                    <div class="notification-content">
                                                        <small class="notification-timestamp pull-right">02:34 PM</small>
                                                        <div class="notification-heading">Mr. John</div>
                                                        <div class="notification-text">Hi Teddy, Just wanted to let you ...</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="text-center">
                                                <a href="#" class="more-link">See All</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="header-icon dib"><i class="ti-email"></i>
                                <div class="drop-down">
                                    <div class="dropdown-content-heading">
                                        <span class="text-left">2 New Messages</span>
                                        <a href="email.html"><i class="ti-pencil-alt pull-right"></i></a>
                                    </div>
                                    <div class="dropdown-content-body">
                                        <ul>
                                            <li class="notification-unread">
                                                <a href="#">
                                                    <img class="pull-left m-r-10 avatar-img" src="assets/images/avatar/1.jpg" alt="" />
                                                    <div class="notification-content">
                                                        <small class="notification-timestamp pull-right">02:34 PM</small>
                                                        <div class="notification-heading">Michael Qin</div>
                                                        <div class="notification-text">Hi Teddy, Just wanted to let you ...</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="notification-unread">
                                                <a href="#">
                                                    <img class="pull-left m-r-10 avatar-img" src="assets/images/avatar/2.jpg" alt="" />
                                                    <div class="notification-content">
                                                        <small class="notification-timestamp pull-right">02:34 PM</small>
                                                        <div class="notification-heading">Mr. John</div>
                                                        <div class="notification-text">Hi Teddy, Just wanted to let you ...</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img class="pull-left m-r-10 avatar-img" src="assets/images/avatar/3.jpg" alt="" />
                                                    <div class="notification-content">
                                                        <small class="notification-timestamp pull-right">02:34 PM</small>
                                                        <div class="notification-heading">Michael Qin</div>
                                                        <div class="notification-text">Hi Teddy, Just wanted to let you ...</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img class="pull-left m-r-10 avatar-img" src="assets/images/avatar/2.jpg" alt="" />
                                                    <div class="notification-content">
                                                        <small class="notification-timestamp pull-right">02:34 PM</small>
                                                        <div class="notification-heading">Mr. John</div>
                                                        <div class="notification-text">Hi Teddy, Just wanted to let you ...</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="text-center">
                                                <a href="#" class="more-link">See All</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="header-icon dib"><span class="user-avatar"><?php echo $_SESSION['name']; ?><i class="ti-angle-down f-s-10"></i></span>
                                <div class="drop-down dropdown-profile">

                                    <div class="dropdown-content-body">
                                        <ul>
                                            <li><a href="#"><i class="ti-user"></i> <span>Profile</span></a></li>

                                            <li><a href="#"><i class="ti-email"></i> <span>Inbox</span></a></li>
                                            <li><a href="#"><i class="ti-settings"></i> <span>Setting</span></a></li>

                                            <li><a href="#"><i class="ti-lock"></i> <span>Lock Screen</span></a></li>
                                            <li><a href="../php/logout.php"><i class="ti-power-off"></i> <span>Logout</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="jumbotron">
        <h1 class="display-4">Hello, <?php echo $_SESSION['name']; ?>!</h1>
        <hr class="my-4">
        <p class="lead">Hope you had a good day. Enjoy your work!</p>
    </div>

    <div id="disp">
        <h2>Items currently in Inventory</h2>
        <?php echo $product_list; ?>
    </div>

    <!-- inventory Input -->
    <form id="add-to-inventory" method="post" enctype="multipart/form-data" action="backend.php" class="needs-validation" novalidate>
        <h2>Add To Inventory</br></br></h2>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-default">Product Name</span>
            </div>
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="product-name" required>
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                You must write the product name
            </div>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-default">Product Price</span>
                <span class="input-group-text">Rs.</span>
            </div>
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="product-price" required>
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                You must write price of the product.
            </div>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">Category</label>
            </div>
            <select class="custom-select" id="inputGroupSelect01" name="category" required>
                <!-- <option selected>Choose...</option> -->
                <option value="clothing" selected>clothing</option>
                <option value="footwear">footwear</option>
                <option value="jewellery">jewellery</option>
            </select>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">For</label>
            </div>
            <select class="custom-select" id="inputGroupSelect01" name="for" required>
                <!-- <option selected>Choose...</option> -->
                <option value="men">Men</option>
                <option value="women" selected>Women</option>
            </select>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-default">Quantity</span>
            </div>
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="quantity" required>
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                You must enter quantity.
            </div>
        </div>

        <div class="custom-file mb-3">

            <input type="file" class="custom-file-input" id="image" name="image[]" multiple required />

            <label class="custom-file-label" for="customFile">Choose file</label>
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                You must upload a product picture.
            </div>
        </div>

        <div class="mb-3">
            <input type="submit" name="Add" id="button" value="Add To Inventory" />
        </div>
    </form>





    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
    <!-- jquery vendor -->
    <script src="assets/js/lib/jquery.min.js"></script>
    <script src="assets/js/lib/jquery.nanoscroller.min.js"></script>
    <!-- nano scroller -->
    <script src="assets/js/lib/menubar/sidebar.js"></script>
    <script src="assets/js/lib/preloader/pace.min.js"></script>
    <!-- sidebar -->
    <script src="assets/js/lib/bootstrap.min.js"></script>

    <!-- bootstrap -->

    <script src="assets/js/lib/circle-progress/circle-progress.min.js"></script>
    <script src="assets/js/lib/circle-progress/circle-progress-init.js"></script>

    <script src="assets/js/scripts.js"></script>


</body>

</html>
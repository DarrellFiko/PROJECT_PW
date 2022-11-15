<?php
require("functions.php");

if (!isset($_SESSION["masukDetail"])) {
    $_SESSION["masukDetail"] = false;
}
if (!isset($_SESSION["tempIdDetail"])) {
    $_SESSION["tempIdDetail"] = -1;
}
// ARRAY UNTUK CHECKBOX BRAND DAN CATEGORY
if (!isset($_SESSION["cbBrand"])) {
    $_SESSION["cbBrand"] = [];

    $brands = query("SELECT * FROM brand ORDER BY name_brand");
    foreach ($brands as $brand) {
        $pageBaru = [
            "brand" => $brand["name_brand"]
        ];
        array_push($_SESSION["cbBrand"], $pageBaru);
    }
}
if (!isset($_SESSION["cbCategories"])) {
    $_SESSION["cbCategories"] = [];

    $categories = query("SELECT * FROM kategori ORDER BY name_kategori");
    foreach ($categories as $kategori) {
        $pageBaru = [
            "categories" => $kategori["name_kategori"]
        ];
        array_push($_SESSION["cbCategories"], $pageBaru);
    }
}

if (!isset($_SESSION["productCount"])) {
    $_SESSION["productCount"] = 0;
}
if (!isset($_SESSION["listProduk"])) {
    $_SESSION["listProduk"] = [];
    $_SESSION["listProduk"] = query("SELECT * FROM produk");
    $_SESSION["productCount"] = count($_SESSION["listProduk"]);
}
// alert($tempCount);

if (!isset($_SESSION["brand"])) {
    $_SESSION["brand"] = "";
}
if (!isset($_SESSION["category"])) {
    $_SESSION["category"] = "";
}
if (!isset($_SESSION["input"])) {
    $_SESSION["input"] = "";
}
if (!isset($_SESSION["paging"])) {
    resetPaging();
}

//SEARCH
if (isset($_POST["search"])) {
    //Munculkan semua
    $_SESSION["masukDetail"] = false;

    $_SESSION["input"] = $_POST["input"];
    alert($_SESSION["input"]);
    $_SESSION["listProduk"] = query("SELECT * FROM produk,brand,kategori WHERE produk.id_brand = brand.id_brand AND produk.id_kategori = kategori.id_kategori AND produk.name_produk LiKE '%" . $_SESSION["input"] . "%'");
    $_SESSION["productCount"] = count($_SESSION["listProduk"]);
    resetPaging();
    header("Location: #collections");
}


//PAGING
//GANTI MAKS PAGE ARRAY
$maks = ($_SESSION["productCount"] / 30) + 1;
if (isset($_POST["page0"])) {
    $_SESSION["pageSekarang"] = $_SESSION["paging"][0]["page"];
    if ($_SESSION["paging"][0]["page"] > 2) {
        $_SESSION["paging"][0]["page"] -= 2;
        $_SESSION["paging"][1]["page"] -= 2;
        $_SESSION["paging"][2]["page"] -= 2;
        $_SESSION["paging"][3]["page"] -= 2;
        $_SESSION["paging"][4]["page"] -= 2;
    }
    // alert($_SESSION["pageSekarang"]);
    header("Location: #collections");
}
if (isset($_POST["page1"])) {
    $_SESSION["pageSekarang"] = $_SESSION["paging"][1]["page"];
    if ($_SESSION["paging"][0]["page"] > 1) {
        $_SESSION["paging"][0]["page"]--;
        $_SESSION["paging"][1]["page"]--;
        $_SESSION["paging"][2]["page"]--;
        $_SESSION["paging"][3]["page"]--;
        $_SESSION["paging"][4]["page"]--;
    }
    // alert($_SESSION["pageSekarang"]);
    header("Location: #collections");
}
if (isset($_POST["page2"])) {
    $_SESSION["pageSekarang"] = $_SESSION["paging"][2]["page"];
    // alert($_SESSION["pageSekarang"]);
    header("Location: #collections");
}
if (isset($_POST["page3"])) {
    $_SESSION["pageSekarang"] = $_SESSION["paging"][3]["page"];
    if ($_SESSION["paging"][4]["page"] < $maks - 1) {
        $_SESSION["paging"][0]["page"]++;
        $_SESSION["paging"][1]["page"]++;
        $_SESSION["paging"][2]["page"]++;
        $_SESSION["paging"][3]["page"]++;
        $_SESSION["paging"][4]["page"]++;
    }
    // alert($_SESSION["pageSekarang"]);
    header("Location: #collections");
}
if (isset($_POST["page4"])) {
    $_SESSION["pageSekarang"] = $_SESSION["paging"][4]["page"];
    if ($_SESSION["paging"][4]["page"] < $maks - 2) {
        $_SESSION["paging"][0]["page"] += 2;
        $_SESSION["paging"][1]["page"] += 2;
        $_SESSION["paging"][2]["page"] += 2;
        $_SESSION["paging"][3]["page"] += 2;
        $_SESSION["paging"][4]["page"] += 2;
    } else if ($_SESSION["paging"][4]["page"] < $maks - 1) {
        $_SESSION["paging"][0]["page"] += 1;
        $_SESSION["paging"][1]["page"] += 1;
        $_SESSION["paging"][2]["page"] += 1;
        $_SESSION["paging"][3]["page"] += 1;
        $_SESSION["paging"][4]["page"] += 1;
    }
    // alert($_SESSION["pageSekarang"]);
    header("Location: #collections");
}
if (isset($_POST["pageSekarangMin1"])) {
    if ($_SESSION["paging"][0]["page"] > 1 && $_SESSION["pageSekarang"] != 1) {
        $_SESSION["pageSekarang"]--;
        $_SESSION["paging"][0]["page"]--;
        $_SESSION["paging"][1]["page"]--;
        $_SESSION["paging"][2]["page"]--;
        $_SESSION["paging"][3]["page"]--;
        $_SESSION["paging"][4]["page"]--;
    } else if ($_SESSION["pageSekarang"] > 1) {
        $_SESSION["pageSekarang"]--;
    }
    // alert($_SESSION["pageSekarang"]);
    header("Location: #collections");
}
if (isset($_POST["pageSekarangPlus1"])) {
    if ($maks <= 4) {
        if ($_SESSION["paging"][$maks - 2]["page"] <= $maks && $_SESSION["pageSekarang"] < $maks - 1) {
            $_SESSION["pageSekarang"]++;
        }
    } else {
        if ($_SESSION["paging"][4]["page"] < $maks - 1 && $_SESSION["pageSekarang"] != $maks) {
            $_SESSION["pageSekarang"]++;
            $_SESSION["paging"][0]["page"]++;
            $_SESSION["paging"][1]["page"]++;
            $_SESSION["paging"][2]["page"]++;
            $_SESSION["paging"][3]["page"]++;
            $_SESSION["paging"][4]["page"]++;
        } else if ($_SESSION["pageSekarang"] < $maks - 1) {
            $_SESSION["pageSekarang"]++;
        }
    }
    // alert($_SESSION["pageSekarang"]);
    header("Location: #collections");
}
if (isset($_POST["pagePertama"])) {
    $_SESSION["pageSekarang"] = 1;
    $_SESSION["paging"][0]["page"] = 1;
    $_SESSION["paging"][1]["page"] = 2;
    $_SESSION["paging"][2]["page"] = 3;
    $_SESSION["paging"][3]["page"] = 4;
    $_SESSION["paging"][4]["page"] = 5;
}
if (isset($_POST["pageTerakhir"])) {
    $maks = (int)$maks;
    $_SESSION["pageSekarang"] = $maks;
    $_SESSION["paging"][0]["page"] = $maks - 4;
    $_SESSION["paging"][1]["page"] = $maks - 3;
    $_SESSION["paging"][2]["page"] = $maks - 2;
    $_SESSION["paging"][3]["page"] = $maks - 1;
    $_SESSION["paging"][4]["page"] = $maks;
}

if (isset($_POST["back"])) {
    $_SESSION["masukDetail"] = false;
    header("Location: #collections");
}

if (isset($_POST["detail"])) {
    //GANTIII
    if (isset($_POST["idDetail"])) {
        $_SESSION["masukDetail"] = true;
        $id = $_POST["idDetail"];
        $curID = $id;
        $produkDetail = query("SELECT * FROM produk WHERE id_produk = '$id'");
    }

    // if ($_SESSION["tempIdDetail"] != -1) {
    //     //Munculkan detail
    //     $_SESSION["masukDetail"] = true;
    //     $tempIdQuery = $_SESSION["tempIdDetail"];
    //     alert($_SESSION["tempIdDetail"]);

    //     $produkDetail = query("SELECT * FROM produk WHERE id_produk = '$tempIdQuery'");
    //     // alert($produkDetail[0]["id_produk"]);
    // }
}

if (isset($_POST["btnFilter"])) {

    // alert("filter");

    $brandFilter = [];
    if (!empty($_POST["filterBrand"])) {
        foreach ($_POST["filterBrand"] as $value) {
            // alert("brand : " . $value);
            $brands = query("SELECT * FROM brand WHERE name_brand = '$value'");
            array_push($brandFilter, $brands[0]["id_brand"]);
        }
    }

    // var_dump($brandFilter);

    $kategoriFilter = [];
    if (!empty($_POST["filterKategori"])) {

        foreach ($_POST["filterKategori"] as $value) {
            // alert("kategori : " . $value);
            $kategories = query("SELECT * FROM kategori WHERE name_kategori = '$value'");
            array_push($kategoriFilter, $kategories[0]["id_kategori"]);
        }
    }

    // var_dump($kategoriFilter);

    if (count($brandFilter) > 0 || count($kategoriFilter) > 0) {

        $query = 'SELECT * FROM produk WHERE';

        for ($i = 0; $i < count($brandFilter); $i++) {
            $filter = $brandFilter[$i];
            if ($i == 0) {
                $temp = ' (id_brand LIKE "%' . $filter . '%"';
            } else {
                $temp = ' OR id_brand LIKE "%' . $filter . '%"';
            }
            $query .= $temp;
        }
        if (count($brandFilter) > 0) {
            $query .= ')';
        }

        for ($i = 0; $i < count($kategoriFilter); $i++) {
            $filter = $kategoriFilter[$i];
            if ($i == 0 && count($brandFilter) > 0) {
                $temp = ' AND (id_kategori LIKE "%' . $filter . '%"';
            } else if ($i == 0) {
                $temp = ' (id_kategori LIKE "%' . $filter . '%"';
            } else {
                $temp = ' OR id_kategori LIKE "%' . $filter . '%"';
            }
            $query .= $temp;
        }
        if (count($kategoriFilter) > 0) {
            $query .= ')';
        }

        // echo "<script>console.log('$query')</script>";

        $_SESSION["listProduk"] = query($query);
        $_SESSION["productCount"] = count($_SESSION["listProduk"]);
        resetPaging();
        header("Location: #collections");
    } else {
        $_SESSION["listProduk"] = query("SELECT * FROM produk");
        $_SESSION["productCount"] = count($_SESSION["listProduk"]);
        resetPaging();
        header("Location: #collections");
    }
}

if (isset($_POST["addToCart"])) {
    $_SESSION["masukDetail"] = false;
    $tempId = $_POST["cartPassID"];
    $tempQuantity = $_POST["quantity"];
    alert("Berhasil menambahkan ke keranjang!");
    //header("Location: cart.php");
    $tempProduk = query("SELECT * FROM produk WHERE id_produk = '$tempId'");
    
    $tempKeranjang = [
        "id_produk" => $tempProduk[0]["id_produk"],
        "image_produk" => $tempProduk[0]["image_produk"],
        "name_produk" => $tempProduk[0]["name_produk"],
        "price_produk" => $tempProduk[0]["price_produk"],
        "quantity_produk" => $tempQuantity
    ];
    if (isset($_SESSION["keranjang"])){
        array_push($_SESSION["keranjang"],$tempKeranjang);
    }else{
        $_SESSION["keranjang"] = []; 
        array_push($_SESSION["keranjang"],$tempKeranjang);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sport Station</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- jQuery -->
    <script src="jquery-3.6.1.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<script>
    function submit() {
        nama = document.getElementsByName("name")[0].value;
        email = document.getElementsByName("email")[0].value;
        teks = document.getElementsByName("textarea")[0].value;
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            document.getElementsByName("name")[0].value = "";
            document.getElementsByName("email")[0].value = "";
            document.getElementsByName("textarea")[0].value = "";
        }
        xhttp.open("GET", "Mailer/Mailer/emailku.php?nama=" + nama + "&email=" + email + "&teks=" + teks);
        xhttp.send();
    }

    function clearForm() {
        document.getElementsByName("name")[0].value = "";
        document.getElementsByName("email")[0].value = "";
        document.getElementsByName("textarea")[0].value = "";
    }
</script>

<body>
    <!-- NAVBAR -->
    <nav class="bg-dark text-white fixed-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-2 col-lg-2 d-flex align-items-center">
                    <a class="navbar-brand" href="#">
                        <img src="asset/logo_toko.png" alt="Logo" width="50" height="50" class="d-inline-block">
                    </a>
                    <div class="navbar-brand d-xl-inline d-none fs-5">
                        Sport Station
                    </div>
                </div>
                <div class="col-7 col-lg-8 py-3">
                    <form class="d-flex" role="search" method="POST">
                        <input class="form-control me-2" type="Search" placeholder="Search" aria-label="Search" name="input">
                        <button class="btn btn-outline-light" type="submit" name="search">Search</button>
                    </form>
                </div>
                <div class="col-3 col-lg-2 d-flex justify-content-end py-3">
                    <a class="navbar-brand mx-2" href="cart.php">
                        <img src="asset/keranjang.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                    </a>
                    <a class="navbar-brand mx-2" href="login.php">
                        <img src="asset/login.png" alt="Logo" width="35" height="35" class="d-inline-block align-text-top">
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- SCROLL -->
    <div style="margin-top:70px">
        <!-- Div kiri buat scroll -->
        <div class="d-flex align-items-center justify-content-center">
            <button class="buttonCategories d-flex align-items-center justify-content-center text-white ps-2" onclick="openNav()">
                <h2><img src="asset/arrow_kanan.png" alt="" width="25" height="25"></h2>
            </button>
        </div>

        <!-- kategori -->
        <div id="mySidenav" class="sidenav">
            <form action="" method="post">

                <button type="button" class="closebtn bg-transparent text-white border border-0" onclick="closeNav()" class="btn btn-link">&times;</button>

                <div class="d-flex justify-content-between align-items-center klikBrand">
                    <span class="fs-3 text-white ms-4">
                        Brand
                    </span>
                    <div id="iconBrand" name="collapse">
                        <i class="fa-sharp fa-solid fa-arrow-up text-light me-4"></i>
                    </div>
                </div>
                <div class="toogleBrand">
                    <table class="text-light ms-5">
                        <?php
                        // GANTI ARRAY BRAND
                        foreach ($_SESSION["cbBrand"] as $key => $value) {
                        ?>
                            <tr class="text-light">
                                <td class="text-light py-2"><input type="checkbox" style="width: 17px; height: 17px;" name="filterBrand[]" id="" value='<?= $value["brand"] ?>'> <?= $value["brand"] ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>

                <div class="mt-2 d-flex justify-content-between align-items-center klikCategories">
                    <span class="fs-3 text-white ms-4">
                        Categories
                    </span>
                    <div id="iconCategory" name="collapse">
                        <i class="fa-sharp fa-solid fa-arrow-up text-light me-4"></i>
                    </div>
                </div>
                <div class="toogleCategories">
                    <table class="text-light ms-5">
                        <?php
                        // GANTI ARRAY BRAND
                        foreach ($_SESSION["cbCategories"] as $key => $value) {
                        ?>
                            <tr class="text-light">
                                <td class="text-light py-2"><input type="checkbox" style="width: 17px; height: 17px;" name="filterKategori[]" id="" value='<?= $value["categories"] ?>'> <?= $value["categories"] ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <button type="submit" class="btn btn-outline-light text-light" name="btnFilter">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    if ($_SESSION["masukDetail"] == false) {
    ?>
        <div class="container-fluid bgGradient">
            <div class="" style="margin-left: 60px;">
                <!-- carousel -->
                <div class="d-flex justify-content-center">
                    <div class="col-1"></div>
                    <div class="col-10 my-5 ">
                        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner shadow ">
                                <div class="carousel-item active" data-bs-interval="2000">
                                    <img src="asset/sepatu1.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="asset/sepatu2.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="asset/sepatu3.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="asset/ball1.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="asset/gloves1.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="asset/gloves2.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="asset/gloves3.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="asset/jersey1.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="asset/brand1.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="asset/brand2.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="asset/brand3.jpg" class="d-block w-100 rounded-4" alt="...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-1"></div>
                    <!-- tutup carousel -->
                </div>
            </div>

            <!-- untuk paging -->
            <div id="collections" class="pt-5"></div>

            <div class="" style="margin-left: 60px;">
                <div class="d-flex justify-content-center">
                    <div class="container text-center mt-4 pb-5">
                        <!-- CARD -->
                        <div class="row d-flex justify-content-center glass pt-4">
                            <div class="col-12">
                                <h1>New Collections</h1>
                            </div>
                            <div class="col-12">
                                <?php
                                if (($_SESSION["pageSekarang"]) * 30 < $_SESSION["productCount"]) {
                                ?>
                                    <h5>Result <?= ($_SESSION["pageSekarang"] - 1) * 30 + 1 ?> - <?= ($_SESSION["pageSekarang"]) * 30 ?> of <?= $_SESSION["productCount"] ?></h5>
                                <?php
                                } else {
                                ?>
                                    <h5>Result <?= ($_SESSION["pageSekarang"] - 1) * 30 + 1 ?> - <?= $_SESSION["productCount"] ?> of <?= $_SESSION["productCount"] ?></h5>

                                <?php
                                }
                                ?>
                            </div>
                            <div class="col-12 text-dark">
                                <hr style="font-weight: bold; color: black;">
                            </div>
                            <?php
                            $temp = 0;
                            // GANTI MAKS ARRAY
                            foreach ($_SESSION["listProduk"] as $product) {
                                $id = $product["id_produk"];
                                $name = $product["name_produk"];
                                $price = $product["price_produk"];
                                $image = $product["image_produk"];
                                $image = base64_decode($image);
                                //PASSINGG
                                $temp++;
                                if (($temp / 30) > $_SESSION["pageSekarang"] - 1 && ($temp / 30) <= $_SESSION["pageSekarang"]) {
                            ?>
                                    <div class="col-5 col-md-4 col-lg-3 col-xl-2 mx-3 my-3 d-flex justify-content-center ">
                                        <form action="" method="post">
                                            <button class="bg-transparent border border-0" name="detail">
                                                <input type="hidden" name="idDetail" value="<?= $id ?>">
                                                <div class="img-fluid">
                                                    <div class="card btn btn-outline-dark shadow border-0" style="width: 13rem; height: 21rem;">
                                                        <?php
                                                        echo '<img src = "data:assets/jpg;base64,' . base64_encode($image) . '" class="card-img-top border-0 img-size" alt="..."/>';
                                                        ?>
                                                        <div class="card-body ">
                                                            <h6 class="card-title mb-2"><?= $name ?></h6>
                                                            <p class="card-text">$ <?= $price ?></p>
                                                            <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <!-- PAGING -->
                            <div class="row py-3">
                                <form action="" method="post">
                                    <div class="col-12 d-flex justify-content-center">
                                        <ul class="pagination d-flex align-items-center justify-content-center img-fluid">
                                            <div class="row d-flex justify-content-center rounded-pill bg-dark px-2">
                                                <div class="col-1 col-xl-1 d-flex justify-content-center">
                                                    <li class="page-item">
                                                        <button type="submit" class="btn text-light border border-0" name="pageSekarangMin1" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </button>
                                                    </li>
                                                </div>
                                                <?php
                                                $adaSatu = false;
                                                if ($_SESSION["pageSekarang"] > 3 && $_SESSION["paging"][0]["page"] != 1) {
                                                    $adaSatu = true;
                                                ?>
                                                    <div class="col-5 col-xl-2 d-flex justify-content-center">
                                                        <li class="page-item text-light"><button type="submit" name="pagePertama" class="btn text-light border border-0">1</button><span class="text-light"> . . . </span></li>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <div class="col-1 col-xl-1 d-flex justify-content-center me-2">
                                                    <li class="page-item"><button type="submit" name="page0" class="btn text-light border border-0"><?= $_SESSION["paging"][0]["page"] ?></button></li>
                                                </div>
                                                <?php
                                                if (($_SESSION["productCount"] / 30) > 1) {
                                                ?>
                                                    <div class="col-1 col-xl-1 d-flex justify-content-center me-2">
                                                        <li class="page-item"><button type="submit" name="page1" class="btn text-light border border-0"><?= $_SESSION["paging"][1]["page"] ?></button></li>
                                                    </div>
                                                <?php
                                                }
                                                if (($_SESSION["productCount"] / 30) > 2) {
                                                ?>
                                                    <div class="col-1 col-xl-1 d-flex justify-content-center me-2">
                                                        <li class="page-item"><button type="submit" name="page2" class="btn text-light border border-0"><?= $_SESSION["paging"][2]["page"] ?></button></li>
                                                    </div>
                                                <?php
                                                }
                                                if (($_SESSION["productCount"] / 30) > 3) {
                                                ?>
                                                    <div class="col-1 col-xl-1 d-flex justify-content-center me-2">
                                                        <li class="page-item"><button type="submit" name="page3" class="btn text-light border border-0"><?= $_SESSION["paging"][3]["page"] ?></button></li>
                                                    </div>
                                                <?php
                                                }
                                                if (($_SESSION["productCount"] / 30) > 4) {
                                                ?>
                                                    <div class="col-1 col-xl-1 d-flex justify-content-center">
                                                        <li class="page-item"><button type="submit" name="page4" class="btn text-light border border-0"><?= $_SESSION["paging"][4]["page"] ?></button></li>
                                                    </div>
                                                    <?php
                                                }
                                                if ($_SESSION["pageSekarang"] < $maks - 3 && $_SESSION["paging"][4]["page"] != (int)$maks) {
                                                    if ($adaSatu) {
                                                    ?>
                                                        <div class="col-5 col-xl-2">
                                                            <li class="page-item text-light"><span class="text-light"> . . . </span><button type="submit" name="pageTerakhir" class="btn text-light border border-0"><?= (int)$maks ?></button></li>
                                                        </div>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="col-5 col-xl-3">
                                                            <li class="page-item text-light"><span class="text-light"> . . . </span><button type="submit" name="pageTerakhir" class="btn text-light border border-0"><?= (int)$maks ?></button></li>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                <div class="col-1 col-xl-1 d-flex justify-content-center">
                                                    <button type="submit" class="btn text-light border border-0" name="pageSekarangPlus1" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </ul>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center">
                                        <h5 class="text-dark">Page <?= $_SESSION["pageSekarang"] ?> of <?= (int)$maks ?></h5>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <!-- MAILER -->
                        <div id="mailer" class="container bgContactGradient rounded-5 pt-3" style="margin-top: 5vh">
                            <div class="row my-5 d-flex justify-content-center">
                                <div class="col-10 px-5 pt-2 pb-3 d-flex bg-transparent justify-content-start rounded-top">
                                    <h1 class="text-light">Contact Us</h1>
                                </div>
                                <div class="col-10 px-5 d-flex bg-transparent justify-content-start">
                                    <div class="form-floating mb-3 w-100">
                                        <input type="text" class="form-control" id="floatingInput" placeholder="Name" name="name">
                                        <label for="floatingInput">Name</label>
                                    </div>
                                </div>
                                <div class="col-10 px-5 d-flex bg-transparent justify-content-start">
                                    <div class="form-floating mb-3 w-100">
                                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
                                        <label for="floatingInput">Email address</label>
                                    </div>
                                </div>
                                <div class="col-10 px-5 d-flex bg-transparent justify-content-start">
                                    <div class="form-floating mb-3 w-100">
                                        <textarea class="form-control" id="floatingInput" placeholder="Message" style="height: 20vh" aria-label="With textarea" name="textarea"></textarea>
                                        <label for="floatingInput">Message</label>
                                    </div>
                                </div>
                                <div class="col-10 pt-3 pb-5 px-5 bg-transparent d-flex justify-content-start rounded-bottom">
                                    <button type="submit" class="btn btn-outline-light me-3" name="submit" onclick="submit();">Submit</button>
                                    <button type="submit" class="btn btn-outline-light" name="clear" onclick="clearForm();">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else {
        $_SESSION["masukDetail"] = false;
    ?>
        <form action="" method="post">
            <input type="hidden" name="cartPassID" value="<?= $curID ?>">
            <div class="container-fluid bgGradient">
                <div class="" style="margin-left: 60px;">
                    <div class="d-flex justify-content-center">
                        <div class="container text-center mt-4 py-5">
                            <div class="row d-flex justify-content-center glass p-5" style="background-color: white;">
                                <div class="col-12 text-center">
                                    <h1>Details</h1>
                                </div>
                                <div class="col-12 col-xl-6 d-flex justify-content-start align-items-center">
                                    <?php
                                    $image = $produkDetail[0]["image_produk"];
                                    $image = base64_decode($image);
                                    echo '<img src = "data:assets/jpg;base64,' . base64_encode($image) . '"style="height: auto;" class="card-img-top border-0 img-size" alt="..."/>';
                                    ?>
                                </div>
                                <div class="col-12 col-xl-6 px-5 py-5 d-flex align-items-center">
                                    <div class="row">
                                        <div class="col-12 text-dark text-start">
                                            <h1><?= $produkDetail[0]["name_produk"] ?></h1>
                                        </div>
                                        <div class=" py-3 col-12 pb-4 text-danger text-start">
                                            <div class="row">
                                                <div class="col-1">
                                                    <h3>$</h3>
                                                </div>
                                                <div class="col-9">
                                                    <h3 id="hargaProduk"><?= $produkDetail[0]["price_produk"] ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 pb-4 text-start">
                                            <hr>
                                        </div>
                                        <div class="col-12 pb-4">
                                            <div class="row">
                                                <div class="col-5 col-xl-1">
                                                    <h5>Quantity:</h5>
                                                </div>
                                                <div class="col-5 col-xl-9">
                                                    <input type="number" onclick="updateTotalHarga();" class="mx-3" style="width: 60px" name="quantity" id="quantity" min="0" value="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 pb-4 text-start">
                                            <div class="row">
                                                <div class="col-7 col-xl-3">
                                                    <h3>Total: $</h3>
                                                </div>
                                                <div class="col-4 col-xl-7 text-danger">
                                                    <h3 id="totalHarga">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 pb-4 text-start">
                                            <div class="row">
                                                <div class="col-6">
                                                    <button type="submit" class="btn btn-success d-flex justify-content-center align-items-center" name="addToCart">ADD TO CART <img src="asset/keranjang.png" style="width: 30px; height:auto; margin-left:15px;" alt=""></button>
                                                </div>
                                                <div class="col-6">
                                                    <form action="" method="post">
                                                        <button type="submit" class="btn btn-danger d-flex justify-content-center align-items-center" name="back">BACK</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 py-5 text-start d-flex align-items-center">
                                    <div class="row">
                                        <div class="col-12">
                                            <h3>Description : </h3>
                                            <hr>
                                        </div>
                                        <div class="col-12">
                                            <p style="font-size: 18px"><?= $produkDetail[0]["description_produk"] ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- MAILER -->
                                <div id="mailer" class="container bgContactGradient rounded-5 pt-3" style="margin-top: 5vh">
                                    <div class="row my-5 d-flex justify-content-center">
                                        <div class="col-10 px-5 pt-2 pb-3 d-flex bg-transparent justify-content-start rounded-top">
                                            <h1 class="text-light">Review</h1>
                                        </div>
                                        <div class="col-10 px-5 d-flex bg-transparent justify-content-start">
                                            <div class="form-floating mb-3 w-100">
                                                <input type="text" class="form-control" id="floatingInput" placeholder="Name" name="name">
                                                <label for="floatingInput">Name</label>
                                            </div>
                                        </div>
                                        <div class="col-10 px-5 d-flex bg-transparent justify-content-start">
                                            <div class="form-floating mb-3 w-100">
                                                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
                                                <label for="floatingInput">Email address</label>
                                            </div>
                                        </div>
                                        <div class="col-10 px-5 d-flex bg-transparent justify-content-start">
                                            <div class="form-floating mb-3 w-100">
                                                <textarea class="form-control" id="floatingInput" placeholder="Your Review" style="height: 20vh" aria-label="With textarea" name="textarea"></textarea>
                                                <label for="floatingInput">Your Review</label>
                                            </div>
                                        </div>
                                        <div class="col-10 pt-3 pb-5 px-5 bg-transparent d-flex justify-content-start rounded-bottom">
                                            <button type="submit" class="btn btn-outline-light me-3" name="submit" onclick="submit();">Submit</button>
                                            <button type="submit" class="btn btn-outline-light" name="clear" onclick="clearForm();">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php
    }
    ?>
    <!-- test -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <!-- script buat open scroll yang dikiri -->
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

        $('.klikBrand').click(function() {
            $('.toogleBrand').slideToggle();
            if ($('#iconBrand').attr('name') == "expand") {
                $('#iconBrand').html("<i class='fa-sharp fa-solid fa-arrow-up text-light me-4'></i>");
                $('#iconBrand').attr('name', 'collapse');
            } else if ($('#iconBrand').attr('name') == "collapse") {
                $('#iconBrand').html("<i class='fa-sharp fa-solid fa-arrow-down text-light me-4'></i>");
                $('#iconBrand').attr('name', 'expand');
            }
        });
        $('.klikCategories').click(function() {
            $('.toogleCategories').slideToggle();
            if ($('#iconCategory').attr('name') == "expand") {
                $('#iconCategory').html("<i class='fa-sharp fa-solid fa-arrow-up text-light me-4'></i>");
                $('#iconCategory').attr('name', 'collapse');
            } else if ($('#iconCategory').attr('name') == "collapse") {
                $('#iconCategory').html("<i class='fa-sharp fa-solid fa-arrow-down text-light me-4'></i>");
                $('#iconCategory').attr('name', 'expand');
            }
        });

        function updateTotalHarga() {
            tempJumlah = document.getElementById("quantity").value;
            jumlah = parseFloat(tempJumlah);
            tempHarga = document.getElementById("hargaProduk").innerText;
            harga = parseFloat(tempHarga);
            document.getElementById("totalHarga").innerText = tempJumlah * harga;
        }
    </script>
</body>

</html>
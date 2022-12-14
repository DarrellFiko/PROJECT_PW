<?php
require("functions.php");

$idUser = $_SESSION['idUser'];
$stmt = $conn->query("SELECT * FROM user WHERE id_user='$idUser'");
$user = $stmt->fetch_assoc();

$nota = $_SESSION["invoiceNota"];

$htrans = query("SELECT * FROM htrans WHERE nota_jual = '$nota'");
foreach ($htrans as $key => $value) {
    $tempTanggal = $value["tanggal"];
    $time = strtotime($tempTanggal);
    $tanggal = date('d/m/Y', $time);
    $subtotal = $value["subtotal"] + 5;
}

$dtrans = query("SELECT * FROM dtrans WHERE nota_jual = '$nota'");

if (isset($_POST["btnBack"])) {
    unset($_SESSION["invoiceNota"]);
    echo "<script>document.location.href = 'index.php'</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="bgGradient min-vh-100">
        <div class="row d-flex justify-content-center py-5">
            <div class="col-12 col-xl-8 glass" style="background-color: white">
                <div id="invoice">
                    <div class="toolbar d-print-none">
                        <div class="text-right d-flex justify-content-end">
                            <button id="printInvoice" class="btn btn-dark"><i class="fa fa-print"></i> Print</button>
                            <form class="m-0" action="" method="post">
                                <button id="btnBack" name="btnBack" class="btn btn-dark ms-2">Back</button>
                            </form>
                        </div>
                        <hr>
                    </div>
                    <div class="invoice overflow-auto">
                        <div style="min-width: 600px">
                            <header>
                                <div class="row">
                                    <div class="col pb-3">
                                        <a target="_blank" href="destroy.php">
                                            <img src="asset/logo_toko.png" data-holder-rendered="true" style="width: 120px;" />
                                        </a>
                                    </div>
                                    <div class="col company-details">
                                        <h2 class="name">
                                            <a class="text-decoration-none text-dark" target="_blank" href="destroy.php">
                                                Soccer Champ
                                            </a>
                                        </h2>
                                        <div>soccerchamp.com</div>
                                        <div>(888) 8888888</div>
                                        <div>soccerchampstore@gmail.com</div>
                                    </div>
                                </div>
                            </header>
                            <main>
                                <div class="row contacts">
                                    <div class="col invoice-to">
                                        <div class="text-gray-light">INVOICE TO:</div>
                                        <h2 class="to"><?= $user["full_name"] ?></h2>
                                        <div class="address mt-1"><?= $user["alamat"] ?></div>
                                        <div class="email mt-1"><a class="text-decoration-none text-dark" href="mailto:<?= $user["email"] ?>"><?= $user["email"] ?></a></div>
                                    </div>
                                    <div class="col invoice-details">
                                        <h6 class="invoice-id">INVOICE <?= $nota ?></h6>
                                        <div class="date">Date of Invoice: <?= $tanggal ?></div>
                                    </div>
                                </div>
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold">No</th>
                                            <th class="text-left fw-bold">Product</th>
                                            <th class="text-right fw-bold">Price</th>
                                            <th class="text-right fw-bold">Quantity</th>
                                            <th class="text-right fw-bold">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $subtotal = 0;
                                        $no = 0;
                                        foreach ($dtrans as $i => $valueDtrans) {
                                            if ($valueDtrans["quantity"] > 0) {
                                                $no += 1;
                                            }
                                            $idProduk = $valueDtrans["id_produk"];
                                            $produk = query("SELECT * FROM produk WHERE id_produk = '$idProduk'");
                                            foreach ($produk as $key => $value) {
                                                $name = $value["name_produk"];
                                                $price = $value["price_produk"];
                                            }
                                            $quantity = $valueDtrans["quantity"];
                                            $totalHarga = $price * $quantity;
                                            $subtotal += $totalHarga;

                                        ?>
                                            <tr>
                                                <td class="no"><?= $no ?></td>
                                                <td class="text-left"><?= $name ?></td>
                                                <td class="unit">$ <?= $price ?></td>
                                                <td class="qty"><?= $quantity ?></td>
                                                <td class="total">$ <?= $totalHarga ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2">Shipping Fee</td>
                                            <td colspan="2"></td>
                                            <td>$5</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Subtotal</td>
                                            <td colspan="2"></td>
                                            <td>$ <?= ceil($subtotal)+5 ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="thanks mt-5">
                                    Appreciation To You!
                                </div>
                            </main>
                            <footer>
                                Invoice was created on a computer and is valid without the signature and seal.
                            </footer>
                        </div>
                        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<script src="jquery-3.6.1.min.js"></script>
<script>
    $('#printInvoice').click(function() {
        Popup($('.invoice')[0].outerHTML);

        function Popup(data) {
            window.print();
            return true;
        }
    });
</script>

</html>
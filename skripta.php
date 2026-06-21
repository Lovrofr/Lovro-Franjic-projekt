<?php
include 'connect.php';

if (isset($_POST['naslov'])) {
    $naslov = $_POST['naslov'];
    $sazetak = $_POST['sazetak'];
    $tekst = $_POST['tekst'];
    $kategorija = $_POST['kategorija'];
    $datum = date('d.m.Y.');

    if (isset($_POST['arhiva'])) {
        $arhiva = 1;
    } else {
        $arhiva = 0;
    }

    $slika = $_FILES['slika']['name'];
    if (!empty($slika)) {
        $target_dir = 'img/' . $slika;
        move_uploaded_file($_FILES['slika']['tmp_name'], $target_dir);
    } else {
        $slika = 'default.jpg';
    }

    // Prepared statement (zaštita od SQL injection)
    $sql = "INSERT INTO vijesti (datum, naslov, sazetak, tekst, slika, kategorija, arhiva) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ssssssi', $datum, $naslov, $sazetak, $tekst, $slika, $kategorija, $arhiva);
        mysqli_stmt_execute($stmt);
    }

    mysqli_close($dbc);
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Vijest objavljena - SportPlus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1 class="logo">SportPlus</h1>
        <p class="podnaslov">Vijesti iz svijeta sporta i zabave</p>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">HOME</a></li>
            <li><a href="sport.php">SPORT</a></li>
            <li><a href="zabava.php">ZABAVA</a></li>
            <li><a href="unos.html">UNOS</a></li>
            <li><a href="administracija.php">ADMINISTRACIJA</a></li>
        </ul>
    </nav>

    <main>
        <article class="pojedinacni-clanak">
            <p class="kategorija-tag <?php echo $kategorija; ?>">
                <span></span> <?php echo strtoupper($kategorija); ?>
            </p>

            <h1 class="naslov-clanka"><?php echo $naslov; ?></h1>
            <p class="sazetak"><?php echo $sazetak; ?></p>

            <img src="img/<?php echo $slika; ?>" alt="<?php echo $naslov; ?>" class="slika-clanka">

            <p class="datum">Objavljeno: <?php echo $datum; ?></p>

            <?php if ($arhiva == 1) { ?>
                <p style="color: orange; font-weight: bold;">⚠ Ova vijest je spremljena u arhivu i neće se prikazivati na naslovnici.</p>
            <?php } ?>

            <div class="tekst-clanka">
                <p><?php echo $tekst; ?></p>
            </div>

            <p style="margin-top: 30px; color: green; font-weight: bold;">✅ Vijest je uspješno spremljena u bazu!</p>
        </article>
    </main>

    <footer>
<p>© 2025 SportPlus | Lovro Franjić </p>    </footer>

</body>
</html>

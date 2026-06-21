<?php
include 'connect.php';

$id = $_GET['id'];
$query = "SELECT * FROM vijesti WHERE id=$id";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $row['naslov']; ?> - SportPlus</title>
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
            <p class="kategorija-tag <?php echo $row['kategorija']; ?>">
                <span></span> <?php echo strtoupper($row['kategorija']); ?>
            </p>

            <h1 class="naslov-clanka"><?php echo $row['naslov']; ?></h1>
            <p class="sazetak"><?php echo $row['sazetak']; ?></p>

            <img src="img/<?php echo $row['slika']; ?>" alt="<?php echo $row['naslov']; ?>" class="slika-clanka">

            <p class="datum">Objavljeno: <?php echo $row['datum']; ?></p>

            <div class="tekst-clanka">
                <p><?php echo $row['tekst']; ?></p>
            </div>
        </article>
    </main>

    <footer>
<p>© 2025 SportPlus | Lovro Franjić </p>    </footer>

</body>
</html>

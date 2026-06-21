<?php include 'connect.php'; ?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportPlus - Najnovije vijesti</title>
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

        <section class="kategorija">
            <h2 class="naslov-kategorije sport"><span></span> SPORT</h2>
            <div class="clanci">
                <?php
                $query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='sport' ORDER BY id DESC LIMIT 3";
                $result = mysqli_query($dbc, $query);

                while ($row = mysqli_fetch_array($result)) {
                    echo '<article>';
                    echo '<img src="img/' . $row['slika'] . '" alt="' . $row['naslov'] . '">';
                    echo '<h3><a href="clanak.php?id=' . $row['id'] . '">' . $row['naslov'] . '</a></h3>';
                    echo '<span class="vrijeme">' . $row['datum'] . '</span>';
                    echo '</article>';
                }
                ?>
            </div>
        </section>

        <section class="kategorija">
            <h2 class="naslov-kategorije zabava"><span></span> ZABAVA</h2>
            <div class="clanci">
                <?php
                $query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='zabava' ORDER BY id DESC LIMIT 3";
                $result = mysqli_query($dbc, $query);

                while ($row = mysqli_fetch_array($result)) {
                    echo '<article>';
                    echo '<img src="img/' . $row['slika'] . '" alt="' . $row['naslov'] . '">';
                    echo '<h3><a href="clanak.php?id=' . $row['id'] . '">' . $row['naslov'] . '</a></h3>';
                    echo '<span class="vrijeme">' . $row['datum'] . '</span>';
                    echo '</article>';
                }
                ?>
            </div>
        </section>

    </main>

    <footer>
        <p>© 2025 SportPlus | Lovro Franjić </p>
    </footer>

</body>
</html>

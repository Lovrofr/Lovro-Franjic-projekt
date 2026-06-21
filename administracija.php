<?php
session_start();
include 'connect.php';

$uspjesnaPrijava = false;
$admin = false;
$porukaPrijava = '';

// PRIJAVA
if (isset($_POST['prijava'])) {
    $username = $_POST['username'];
    $lozinka = $_POST['lozinka'];

    $sql = "SELECT korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime = ?";
    $stmt = mysqli_stmt_init($dbc);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $razinaKorisnika);
    mysqli_stmt_fetch($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0 && password_verify($lozinka, $lozinkaKorisnika)) {
        $_SESSION['username'] = $imeKorisnika;
        $_SESSION['razina'] = $razinaKorisnika;
        $uspjesnaPrijava = true;
        if ($razinaKorisnika == 1) {
            $admin = true;
        }
    } else {
        $porukaPrijava = '❌ Pogrešno korisničko ime ili lozinka! Niste registrirani? <a href="registracija.php">Registriraj se</a>';
    }
}

// ODJAVA
if (isset($_GET['odjava'])) {
    session_destroy();
    header("Location: administracija.php");
    exit;
}

// BRISANJE
if (isset($_POST['delete']) && isset($_SESSION['razina']) && $_SESSION['razina'] == 1) {
    $id = $_POST['id'];
    $query = "DELETE FROM vijesti WHERE id=$id";
    mysqli_query($dbc, $query);
}

// UPDATE
if (isset($_POST['update']) && isset($_SESSION['razina']) && $_SESSION['razina'] == 1) {
    $id = $_POST['id'];
    $naslov = $_POST['naslov'];
    $sazetak = $_POST['sazetak'];
    $tekst = $_POST['tekst'];
    $kategorija = $_POST['kategorija'];

    if (isset($_POST['arhiva'])) {
        $arhiva = 1;
    } else {
        $arhiva = 0;
    }

    $sql = "UPDATE vijesti SET naslov=?, sazetak=?, tekst=?, kategorija=?, arhiva=? WHERE id=?";
    $stmt = mysqli_stmt_init($dbc);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssii', $naslov, $sazetak, $tekst, $kategorija, $arhiva, $id);
    mysqli_stmt_execute($stmt);
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Administracija - SportPlus</title>
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

        <?php if (isset($_SESSION['username']) && $_SESSION['razina'] == 1) { ?>
            
            <!-- ADMIN PANEL -->
            <section class="forma-section" style="max-width: 900px;">
                <h2 class="naslov-forme">Administracija vijesti</h2>
                <p style="margin-bottom: 20px;">Prijavljen kao: <strong><?php echo $_SESSION['username']; ?></strong> | <a href="?odjava=1">Odjava</a></p>

                <?php
                $query = "SELECT * FROM vijesti ORDER BY id DESC";
                $result = mysqli_query($dbc, $query);

                while ($row = mysqli_fetch_array($result)) {
                    echo '<form action="" method="POST" class="forma" style="border-bottom: 2px solid #ccc; padding-bottom: 20px; margin-bottom: 30px;">';

                    echo '<div class="form-item"><label>Naslov:</label>';
                    echo '<input type="text" name="naslov" value="' . $row['naslov'] . '"></div>';

                    echo '<div class="form-item"><label>Sažetak:</label>';
                    echo '<textarea name="sazetak" rows="2">' . $row['sazetak'] . '</textarea></div>';

                    echo '<div class="form-item"><label>Tekst:</label>';
                    echo '<textarea name="tekst" rows="5">' . $row['tekst'] . '</textarea></div>';

                    echo '<div class="form-item"><label>Kategorija:</label>';
                    echo '<select name="kategorija">';
                    $selSport = ($row['kategorija'] == 'sport') ? 'selected' : '';
                    $selZabava = ($row['kategorija'] == 'zabava') ? 'selected' : '';
                    echo '<option value="sport" ' . $selSport . '>Sport</option>';
                    echo '<option value="zabava" ' . $selZabava . '>Zabava</option>';
                    echo '</select></div>';

                    echo '<div class="form-item checkbox-item">';
                    $checked = ($row['arhiva'] == 1) ? 'checked' : '';
                    echo '<label><input type="checkbox" name="arhiva" ' . $checked . '> Arhivirano</label></div>';

                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';

                    echo '<div class="gumbi">';
                    echo '<button type="submit" name="update">Izmijeni</button>';
                    echo '<button type="submit" name="delete" style="background:#999;" onclick="return confirm(\'Sigurno obrisati?\')">Izbriši</button>';
                    echo '</div>';

                    echo '</form>';
                }
                ?>
            </section>

        <?php } else if (isset($_SESSION['username']) && $_SESSION['razina'] == 0) { ?>
            
            <!-- KORISNIK NIJE ADMIN -->
            <section class="forma-section">
                <h2 class="naslov-forme">Pristup zabranjen</h2>
                <p>Bok <strong><?php echo $_SESSION['username']; ?></strong>! Uspješno ste prijavljeni, ali nemate administratorska prava za pristup ovoj stranici.</p>
                <p style="margin-top:20px;"><a href="?odjava=1">Odjavi se</a></p>
            </section>

        <?php } else { ?>
            
            <!-- LOGIN FORMA -->
            <section class="forma-section">
                <h2 class="naslov-forme">Prijava</h2>

                <?php if ($porukaPrijava != '') { ?>
                    <p style="text-align:center; color:red; margin-bottom:20px;"><?php echo $porukaPrijava; ?></p>
                <?php } ?>

                <form action="" method="POST" class="forma">
                    <div class="form-item">
                        <label>Korisničko ime:</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="form-item">
                        <label>Lozinka:</label>
                        <input type="password" name="lozinka" required>
                    </div>

                    <div class="form-item gumbi">
                        <button type="submit" name="prijava">Prijavi se</button>
                    </div>
                </form>

                <p style="text-align:center; margin-top:20px;">Nemaš račun? <a href="registracija.php">Registriraj se</a></p>
            </section>

        <?php } ?>

    </main>

    <footer>
<p>© 2025 SportPlus | Lovro Franjić </p>    </footer>

</body>
</html>

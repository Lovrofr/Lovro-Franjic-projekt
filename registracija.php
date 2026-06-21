<?php
include 'connect.php';

$poruka = '';

if (isset($_POST['register'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $username = $_POST['username'];
    $lozinka = $_POST['lozinka'];
    $lozinkaRep = $_POST['lozinkaRep'];

    if ($lozinka != $lozinkaRep) {
        $poruka = '❌ Lozinke se ne podudaraju!';
    } else {
        // Provjera postoji li već korisničko ime
        $sql = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime = ?";
        $stmt = mysqli_stmt_init($dbc);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $poruka = '❌ Korisničko ime se već koristi!';
        } else {
            $hashLozinka = password_hash($lozinka, PASSWORD_DEFAULT);
            $razina = 0;

            $sql = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($dbc);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, 'ssssi', $ime, $prezime, $username, $hashLozinka, $razina);
            mysqli_stmt_execute($stmt);

            $poruka = '✅ Registracija je uspješna! <a href="administracija.php">Prijavi se</a>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Registracija - SportPlus</title>
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
        <section class="forma-section">
            <h2 class="naslov-forme">Registracija korisnika</h2>

            <?php if ($poruka != '') { ?>
                <p style="text-align:center; font-weight:bold; margin-bottom:20px;"><?php echo $poruka; ?></p>
            <?php } ?>

            <form action="" method="POST" class="forma">
                <div class="form-item">
                    <label>Ime:</label>
                    <input type="text" name="ime" required>
                </div>

                <div class="form-item">
                    <label>Prezime:</label>
                    <input type="text" name="prezime" required>
                </div>

                <div class="form-item">
                    <label>Korisničko ime:</label>
                    <input type="text" name="username" required>
                </div>

                <div class="form-item">
                    <label>Lozinka:</label>
                    <input type="password" name="lozinka" required>
                </div>

                <div class="form-item">
                    <label>Ponovi lozinku:</label>
                    <input type="password" name="lozinkaRep" required>
                </div>

                <div class="form-item gumbi">
                    <button type="submit" name="register">Registriraj se</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>© 2025 SportPlus | Ime Prezime | email@primjer.com</p>
    </footer>

</body>
</html>

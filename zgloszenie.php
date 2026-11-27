<?php
require_once 'funkcje.php';
/**
 * zgloszenie.php — Formularz zgłoszenia naprawy sprzętu
 * 
 * Ten plik pełni dwie role:
 * 1. Wyświetla formularz HTML do zgłoszenia uszkodzenia sprzętu
 * 2. Przetwarza dane wysłane przez formularz (POST)
 * 
 * Architektura: Pojedynczy plik (all-in-one) zawierający PHP i HTML,
 * gdzie sama strona obsługuje zarówno GET (wyświetlenie), jak i POST (przetworzenie)
 */

// Sprawdza, czy żądanie przyszło metodą POST (formularz został wysłany)
// Jeśli $_SERVER["REQUEST_METHOD"] === "POST", znaczy że dane formularza zostały przesłane
$wyslano = $_SERVER["REQUEST_METHOD"] === "POST";

// Przetwarzanie danych tylko jeśli formularz został wysłany
if ($wyslano) {
    $bledy = [];
    // Pobiera wartość "imie" z $_POST, lub pusty string "" jeśli nie istnieje (operator ?? "nullish coalescing")
    // trim() usuwa spacje na począsku i końcu stringa (np. " Jan " → "Jan")
    $imie   = trim($_POST["imie"] ?? "");
    if($imie === "") {
        // Ktoś podał puste imię
        $bledy['imie'] = "Imię jest wymagane.";
    }
    if(mb_strlen($imie) > 50) {
        $bledy['imie'] = "Imię nie może być dłuższe niż 50 znaków.";
    }

    // Pobiera email z formularza i usuwa spacje na brzegach
    $email  = trim($_POST["email"] ?? "");
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $bledy['email'] = "Nieprawidłowy adres email.";
    }
    // Pobiera typ sprzętu (wartość z <select>), nie używamy trim() bo wybór z <select> nigdy nie ma dodatkowych spacji
    $sprzet = $_POST["sprzet"] ?? "";
    $dopuszczalne = ["komputer", "laptop", "monitor"];
    if(!in_array($sprzet, $dopuszczalne)) {
        $bledy['sprzet'] = "Nieprawidłowy typ sprzętu.";
    }

    // Pobiera opis usterki z textarea i usuwa spacje z brzegów
    $opis   = trim($_POST["opis"] ?? "");
    if($opis === "") {
        $bledy['opis'] = "Opis usterki jest wymagany.";
    }
    else if(mb_strlen($opis) < 10) {
        $bledy['opis'] = "Opis usterki musi mieć co najmniej 10 znaków.";
    }

    // Checkbox "pilne" — jeśli nie jest zaznaczony, to w ogóle NIE JEST wysyłany w $_POST
    // Dlatego używamy isset() zamiast ?? , aby sprawdzić czy pole istnieje w $_POST
    // Jeśli checkbox jest zaznaczony → $pilne = true, jeśli nie → $pilne = false
    $pilne  = isset($_POST["pilne"]);
    //pobieramy z formularza maksymalny koszt naprawy i usuwamy spacje z brzegów
    $koszt = trim($_POST["maksymalnyKoszt"] ?? "");
    if($koszt !== "") {
        $kosztInt = filter_var($koszt, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]]);
        if($kosztInt === false) {
            $bledy['koszt'] = "Maksymalny koszt naprawy musi być liczbą całkowitą nieujemną.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <!-- Deklaracja standardu HTML5 i języka polskiego dla strony -->
    <meta charset="UTF-8"><!-- Kodowanie znaków UTF-8, umożliwia wyświetlanie polskich liter -->
    <title>Zgłoszenie — ElektroSerwis</title><!-- Tytuł strony widoczny na karcie przeglądarki -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MKn5NWVBEuQXJE7TSKSgPUmMiSCmVTfCsR+VSSeQpHnoyJQZZFn/kBn+Ht5jZDPQ" crossorigin="anonymous">
    <script type="module" src="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-+PW0IDVGGwmpaBQvcXY+W0BcpHpITFB3dSfSoGvDdHtLbrLpSbpGcvtQRM/BJpz+" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
    <!-- Nagłówek strony -->
    <h1>Zgłoś naprawę</h1>



    <!-- Formularz do zgłoszenia naprawy -->
    <form method="post" action="">
        <!-- method="post" — wysyła dane bezpiecznie (ukryte w ciele żądania, nie w URL-u) -->
        <!-- action="" — formularz wysyła dane do tego samego pliku (pusta wartość = URL bieżącej strony) -->
        <div class="form-field mb-3">
            <!-- Pole na imię użytkownika -->
            <label for="imie" class="form-label">Imię:</label>
            <input type="text" class="form-control" name="imie" id="imie" required>
            <!-- type="text" — zwykłe pole tekstowe -->
            <!-- name="imie" — nazwa zmiennej w $_POST["imie"] -->
            <!-- id="imie" — powiązanie z etykietą <label> (klikając na tekst, focus przechodzi na input) -->
        </div>

        <!-- Pole na adres email do kontaktu -->
        <div class="form-field mb-3">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" class="form-control" name="email" id="email" required>
            <!-- type="email" — przeglądarki sprawdzają prawidłowość adresu email -->
        </div>  
        <div class="form-field mb-3">
            <!-- Pole na typ sprzętu -->
            <label for="sprzet" class="form-label">Sprzęt:</label>
            <select class="form-control" name="sprzet" id="sprzet">
                <!-- <select> — lista rozwijana (dropdown), umożliwia wybór jednej wartości z listy -->
                <option value="laptop">laptop</option>
                <option value="komputer">komputer stacjonarny</option>
                <option value="monitor">monitor</option>
                <!-- value — wartość wysyłana w $_POST, tekst wewnątrz — to co widzi użytkownik -->
            </select>
        </div>

        <!-- Pole na szczegółowy opis problemu -->
        <div class="form-field mb-3">
            <label for="opis" class="form-label">Opis usterki:</label>
            <textarea class="form-control" name="opis" id="opis"></textarea>
            <!-- <textarea> — wielowierszowe pole tekstowe, idealne na dłuższe teksty -->
        </div>

        <div class="form-field mb-3">
            <!-- Checkbox — pole do zaznaczenia, czy naprawa jest pilna -->
            
            <input class="check" type="checkbox" name="pilne" value="1">
            <label for="pilne">Naprawa pilna </label>
            <!-- type="checkbox" — użytkownik może zaznaczać/odznaczać -->
            <!-- value="1" — zawsze wysyła wartość "1" (wartość konkretna nie jest ważna, ważne jest isset($_POST["pilne"])) -->
        </div>


        <div class="form-field mb-3">
            <label for="maksymalnyKoszt" class="form-label">Maksymalny koszt naprawy:</label>
            <input type="number" class="form-control" name="maksymalnyKoszt" id="maksymalnyKoszt">
        </div>

        <!-- Przycisk wysyłający formularz -->
        <button type="submit" class="btn btn-primary w-100">Wyślij</button>
        <!-- type="submit" — kliknięcie wysyła dane formularza metodą POST -->
    </form>
        <!-- Warunkowo wyświetla komunikat potwierdzenia tylko jeśli formularz został wysłany -->
        <?php if ($wyslano) { 
            if(count($bledy) > 0) {
                echo "<p>Wystąpiły błędy w formularzu.</p>";
                echo "<ul>";
                foreach($bledy as $blad) {
                    echo "<li>" . e($blad) . "</li>";
                }
                echo "</ul>";
            } else {
            ?>
            
            <!-- Podziękowanie z danymi wysłanymi przez użytkownika -->
            <p>
                Dziękujemy,
                <!-- Wyświetla imię wysłane przez użytkownika -->
                <?php echo e($imie); ?>.
                Zgłoszenie:
                <!-- Wyświetla typ sprzętu -->
                <?php echo e($sprzet); ?>
                <!-- Jeśli naprawa jest pilna, dodaj napis "(pilne)" na koniec -->
                <?php echo $pilne ? " (pilne)" : ""; ?>.
            </p>
            <!-- Informacja do jakich kontaktów serwis się odezwie -->
            <p>Odpowiemy na adres:
                <!-- Wyświetla email wysłany przez użytkownika -->
                <?php echo e($email); ?>
            </p>
        <?php } } ?>
    </div>
</body>

</html>
<!-- Koniec dokumentu HTML -->
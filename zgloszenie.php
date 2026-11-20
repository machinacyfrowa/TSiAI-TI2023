<?php
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
    // Pobiera wartość "imie" z $_POST, lub pusty string "" jeśli nie istnieje (operator ?? "nullish coalescing")
    // trim() usuwa spacje na począsku i końcu stringa (np. " Jan " → "Jan")
    $imie   = trim($_POST["imie"] ?? "");
    
    // Pobiera email z formularza i usuwa spacje na brzegach
    $email  = trim($_POST["email"] ?? "");
    
    // Pobiera typ sprzętu (wartość z <select>), nie używamy trim() bo wybór z <select> nigdy nie ma dodatkowych spacji
    $sprzet = $_POST["sprzet"] ?? "";
    
    // Pobiera opis usterki z textarea i usuwa spacje z brzegów
    $opis   = trim($_POST["opis"] ?? "");
    
    // Checkbox "pilne" — jeśli nie jest zaznaczony, to w ogóle NIE JEST wysyłany w $_POST
    // Dlatego używamy isset() zamiast ?? , aby sprawdzić czy pole istnieje w $_POST
    // Jeśli checkbox jest zaznaczony → $pilne = true, jeśli nie → $pilne = false
    $pilne  = isset($_POST["pilne"]);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- Deklaracja standardu HTML5 i języka polskiego dla strony -->
    <meta charset="UTF-8"><!-- Kodowanie znaków UTF-8, umożliwia wyświetlanie polskich liter -->
    <title>Zgłoszenie — ElektroSerwis</title><!-- Tytuł strony widoczny na karcie przeglądarki -->
</head>
<body>
<!-- Nagłówek strony -->
<h1>Zgłoś naprawę</h1>

<!-- Warunkowo wyświetla komunikat potwierdzenia tylko jeśli formularz został wysłany -->
<?php if ($wyslano) { ?>
    <!-- Podziękowanie z danymi wysłanymi przez użytkownika -->
    <p>
        Dziękujemy, 
        <!-- Wyświetla imię wysłane przez użytkownika -->
        <?php echo $imie; ?>. 
        Zgłoszenie: 
        <!-- Wyświetla typ sprzętu -->
        <?php echo $sprzet; ?>
        <!-- Jeśli naprawa jest pilna, dodaj napis "(pilne)" na koniec -->
        <?php echo $pilne ? " (pilne)" : ""; ?>.
    </p>
    <!-- Informacja do jakich kontaktów serwis się odezwie -->
    <p>Odpowiemy na adres: 
        <!-- Wyświetla email wysłany przez użytkownika -->
        <?php echo $email; ?>
    </p>
<?php } ?>

<!-- Formularz do zgłoszenia naprawy -->
<form method="post" action="">
    <!-- method="post" — wysyła dane bezpiecznie (ukryte w ciele żądania, nie w URL-u) -->
    <!-- action="" — formularz wysyła dane do tego samego pliku (pusta wartość = URL bieżącej strony) -->
    
    <!-- Pole na imię użytkownika -->
    <label for="imie">Imię:</label>
    <input type="text" name="imie" id="imie">
    <!-- type="text" — zwykłe pole tekstowe -->
    <!-- name="imie" — nazwa zmiennej w $_POST["imie"] -->
    <!-- id="imie" — powiązanie z etykietą <label> (klikając na tekst, focus przechodzi na input) -->

    <!-- Pole na adres email do kontaktu -->
    <label for="email">E-mail:</label>
    <input type="email" name="email" id="email">
    <!-- type="email" — przeglądarki sprawdzają prawidłowość adresu email -->

    <!-- Pole na typ sprzętu -->
    <label for="sprzet">Sprzęt:</label>
    <select name="sprzet" id="sprzet">
        <!-- <select> — lista rozwijana (dropdown), umożliwia wybór jednej wartości z listy -->
        <option value="laptop">laptop</option>
        <option value="komputer">komputer stacjonarny</option>
        <option value="monitor">monitor</option>
        <!-- value — wartość wysyłana w $_POST, tekst wewnątrz — to co widzi użytkownik -->
    </select>

    <!-- Pole na szczegółowy opis problemu -->
    <label for="opis">Opis usterki:</label>
    <textarea name="opis" id="opis"></textarea>
    <!-- <textarea> — wielowierszowe pole tekstowe, idealne na dłuższe teksty -->

    <!-- Checkbox — pole do zaznaczenia, czy naprawa jest pilna -->
    <label>
        <input type="checkbox" name="pilne" value="1">
        <!-- type="checkbox" — użytkownik może zaznaczać/odznaczać -->
        <!-- value="1" — zawsze wysyła wartość "1" (wartość konkretna nie jest ważna, ważne jest isset($_POST["pilne"])) -->
        Naprawa pilna
    </label>

    <!-- Przycisk wysyłający formularz -->
    <button type="submit">Wyślij</button>
    <!-- type="submit" — kliknięcie wysyła dane formularza metodą POST -->
</form>
</body>
</html>
<!-- Koniec dokumentu HTML -->

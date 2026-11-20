<?php
//import danych
require 'dane-start.php';
//import funkcji
require 'funkcje.php';

//spodziewamy się, że dostaniemy tekst z <input name="filtr">, jeśli nie to ustawiamy pusty ciąg
//?? to nullcoalescing operator, zwraca wartość po lewej jeśli istnieje, w przeciwnym razie wartość po prawej
//czyli jeśli nie dostali wartości w $_GET['filtr'], to $filtr będzie pustym ciągiem
$filtr = $_GET['filtr'] ?? "";

//sprawdzamy czy dostaliśmy jakiś filtr
if ($filtr === "") 
{
    //jeżeli filtr jest pusty, to nie filtrujemy danych
    //$produkty bierze się z pliku dane_start.php
    $lista = $produkty;
}
else 
{
    //funkcja filtruje po kategorii
    $lista = filtrujPoKategorii($produkty, $filtr);

}

?>
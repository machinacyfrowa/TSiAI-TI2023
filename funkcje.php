<?php
//slowo kluczowe: function, nazwa funkcji: formatCena, 
//argumenty: float $cena, zwraca: string
function formatCena(float $cena): string 
{
    //funkcja zwraca nam sformatowaną cenę w postaci string z 
    //dwoma miejscami po przecinku i symbolem zł
    return number_format($cena, 2, ",", " ") . "zł";
}
//funkcja z domyślną stawką VAT 23%
function cenaBrutto(float $cenaNetto, float $stawkaVat = 0.23): float
{
    return $cenaNetto * (1 + $stawkaVat);
}
$rabat = 10;
//funkcja nie widzi tego co jest poza jej zakresem, np. zmiennej $rabat
function cenaPoRabacie(float $cena): float
{
    //możemy przekazać do funkcji coś przez global
    global $rabat; //znajdz zmienna globalną o nazwie $rabat i użyj jej
    //generalnie unikamy tego - ciężko jest śledzić skąd pochodzi zmienna 
    //i może prowadzić do błędów - zła praktyka
    return $cena * (1 - $rabat / 100);
}
function stanMagazynu(int $ilosc): string
{
    return match (true) {
        $ilosc === 0 => "brak na magazynie", //jeżeli nie ma towaru to zwróć informację o braku
        $ilosc < 5 => "ostatnie sztuki", //jeśli jest mało
        default => "dostępny" //w każdym innym przypadku towar jest dostępny
    };
}

function produktyNaMagazynie(array $produkty): array 
{
    //robie sobie pusta tablice
    $produktyNaMagazynie = [];
    //there be dragons
    foreach($produkty as $produkt) //przechodzimy przez wszystkie produkty
    {
        if($produkt[2] > 0) //sprawdzam czy ilość w magazynie jest większa od 0
            $produktyNaMagazynie[] = $produkt; //dodaję produkt do tablicy produktów na magazynie
    }
    //zwracam tablice produktów które mają niezerową ilość w magazynie
    return $produktyNaMagazynie;
}
//funkcja którą możemy uruchomić w pętli wewnątrz <table> żeby wypisać pozycje z magazynu
function wierszTabeliProduktow(array $produkt): string
{
    return "<tr>
                <td>{$produkt[0]}</td>
                <td>" . formatCena($produkt[1]) . "</td>
                <td>" . stanMagazynu($produkt[2]) . "</td>
            </tr>";
}
function filtrujPoKategorii(array $produkty, string $kategoria): array
{
    $wynik = [];
    foreach($produkty as $produkt)
    {
        if($produkt['kategoria'] === $kategoria) //zakładamy, że kategoria jest w indeksie 3
            $wynik[] = $produkt;
    }
    return $wynik;
}

?>
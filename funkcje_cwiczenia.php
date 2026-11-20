<?php
require_once 'funkcje.php';

$produkty = [
    //nazwa produktu, cena, ilość w magazynie
    ["Produkt 1", 1200.50, 3],
    ["Produkt 2", 800.00, 0],
    ["Produkt 3", 150.75, 10]
];

$cena = 1200.50;

// zamiast echo number_format($cena, 2, ",", " ") . "zł";
echo formatCena($cena);
echo "<br>";
//nie musimy podawać stawki VAT, bo domyślnie jest 23%
echo formatCena(cenaBrutto($cena));
//chyba że chcemy podać inną stawkę VAT
echo "<br>";
echo formatCena(cenaBrutto($cena, 0.08)); // przykładowo 8% VAT

echo cenaBrutto(120);
echo "<br>";
echo formatCena(cenaBrutto(120));

echo "<br>";
echo "Stan magazynu: " . stanMagazynu(3);
echo "<br>";
echo "Stan magazynu: " . stanMagazynu(0);
echo "<br>";
echo "Stan magazynu: " . stanMagazynu(10);

$produktyNaMagazynie = produktyNaMagazynie($produkty);
echo "<table>";
foreach($produktyNaMagazynie as $produkt) {
    echo wierszTabeliProduktow($produkt);
}
echo "</table>";

$nazwa = "  Mysz  ";
echo trim($nazwa); //obcina białe znaki z początku i końca stringa
echo "<br>";
echo strlen("Pamięć RAM");
echo "<br>";
echo mb_strlen("Pamięć RAM"); //używamy mb_strlen dla poprawnego liczenia znaków w UTF-8
echo "<br>";
echo mb_strtoupper("Pamięć RAM"); //zamiana na wielkie litery
echo "<br>";
echo str_contains("Pamięć RAM", "RAM"); //sprawdza czy string zawiera podany fragment
echo  "<br>";
echo date("H:i d.m.Y", time());

//filtruje produkty, które mają cenę mniejszą niż 1000
//ciąg fn($produkt) => $produkt[1] < 1000
//czytamy fukcja zwraca takie produkty dla których cena jest mniejsza niż 1000
$tanieProdukty = array_filter($produkty, fn($produkt) => $produkt[1] < 1000);
//możemy też sortować produkty po cenie rosnąco
$posortowaneProdukty = usort($produkty, fn($a, $b) => $a[1] <=> $b[1]);
?>
<?php
// init.php - Inicializace databáze SQLite

try {
    // Připojení k databázi (vytvoří se, pokud neexistuje)
    $db = new PDO("sqlite:profile.db");
    
    // Nastavení chybového režimu
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vytvoření tabulky interests, pokud neexistuje
    $db->exec("CREATE TABLE IF NOT EXISTS interests (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )");
    
    echo "Databáze a tabulka byly úspěšně vytvořeny.";
} catch (PDOException $e) {
    echo "Chyba při vytváření databáze: " . $e->getMessage();
}
?>
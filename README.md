# IT_profil_6.0

Webová aplikace pro správu zájmů uživatele s použitím SQLite databáze.

## Popis

Aplikace umožňuje:
- Přidání nového zájmu
- Úpravu existujícího zájmu
- Smazání zájmu
- Zobrazení seznamu všech zájmů

Data jsou uložena v SQLite databázi místo původního JSON souboru.

## Struktura projektu

- `index.php` - Hlavní stránka aplikace
- `init.php` - Inicializace databáze
- `profile.db` - SQLite databáze
- `style.css` - Styly aplikace

## Instalace a spuštění

1. Ujistěte se, že máte nainstalované PHP s podporou PDO a SQLite.

2. Spusťte inicializaci databáze:
   ```
   php init.php
   ```

3. Spusťte webový server:
   ```
   php -S localhost:8000
   ```

4. Otevřete prohlížeč a přejděte na `http://localhost:8000`

## Funkce

- **Přidání zájmu**: Zadejte název do formuláře a klikněte "Přidat"
- **Úprava zájmu**: Klikněte "Upravit" u zájmu, upravte název a uložte
- **Smazání zájmu**: Klikněte "Smazat" u zájmu (s potvrzením)
- **Zobrazení**: Všechny zájmy jsou zobrazeny v seznamu

## Technologie

- PHP 7+
- SQLite
- HTML/CSS/JavaScript
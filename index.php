<?php
// index.php - Hlavní stránka aplikace IT Profil 6.0

session_start();

// Připojení k databázi
try {
    $db = new PDO("sqlite:profile.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Chyba připojení k databázi: " . $e->getMessage());
}

// Funkce pro získání všech zájmů
function getInterests($db) {
    $stmt = $db->query("SELECT * FROM interests ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Funkce pro přidání zájmu
function addInterest($db, $name) {
    if (empty(trim($name))) {
        $_SESSION['message'] = "Pole nesmí být prázdné.";
        return false;
    }
    
    try {
        $stmt = $db->prepare("INSERT INTO interests (name) VALUES (?)");
        $stmt->execute([$name]);
        $_SESSION['message'] = "Zájem byl přidán.";
        return true;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // UNIQUE constraint failed
            $_SESSION['message'] = "Tento zájem už existuje.";
        } else {
            $_SESSION['message'] = "Chyba při přidávání zájmu.";
        }
        return false;
    }
}

// Funkce pro úpravu zájmu
function updateInterest($db, $id, $name) {
    if (empty(trim($name))) {
        $_SESSION['message'] = "Pole nesmí být prázdné.";
        return false;
    }
    
    try {
        $stmt = $db->prepare("UPDATE interests SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
        $_SESSION['message'] = "Zájem byl upraven.";
        return true;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['message'] = "Tento zájem už existuje.";
        } else {
            $_SESSION['message'] = "Chyba při úpravě zájmu.";
        }
        return false;
    }
}

// Funkce pro smazání zájmu
function deleteInterest($db, $id) {
    try {
        $stmt = $db->prepare("DELETE FROM interests WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['message'] = "Zájem byl odstraněn.";
        return true;
    } catch (PDOException $e) {
        $_SESSION['message'] = "Chyba při mazání zájmu.";
        return false;
    }
}

// Zpracování POST požadavků
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        addInterest($db, $_POST['name']);
        header("Location: index.php");
        exit;
    } elseif (isset($_POST['edit'])) {
        updateInterest($db, $_POST['id'], $_POST['name']);
        header("Location: index.php");
        exit;
    } elseif (isset($_POST['delete'])) {
        deleteInterest($db, $_POST['id']);
        header("Location: index.php");
        exit;
    }
}

// Získání zájmů pro zobrazení
$interests = getInterests($db);

// Zobrazení hlášky
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Profil 6.0</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>IT Profil 6.0</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <h2>Přidat nový zájem</h2>
        <form method="post" action="index.php">
            <input type="text" name="name" placeholder="Název zájmu" required>
            <button type="submit" name="add">Přidat</button>
        </form>
        
        <h2>Seznam zájmů</h2>
        <?php if (empty($interests)): ?>
            <p>Žádné zájmy nejsou uloženy.</p>
        <?php else: ?>
            <ul class="interests-list">
                <?php foreach ($interests as $interest): ?>
                    <li>
                        <span><?php echo htmlspecialchars($interest['name']); ?></span>
                        <div class="actions">
                            <button onclick="editInterest(<?php echo $interest['id']; ?>, '<?php echo htmlspecialchars($interest['name']); ?>')">Upravit</button>
                            <form method="post" action="index.php" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $interest['id']; ?>">
                                <button type="submit" name="delete" onclick="return confirm('Opravdu chcete smazat tento zájem?')">Smazat</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <!-- Skrytý formulář pro úpravu -->
        <div id="edit-form" style="display: none;">
            <h2>Upravit zájem</h2>
            <form method="post" action="index.php">
                <input type="hidden" name="id" id="edit-id">
                <input type="text" name="name" id="edit-name" placeholder="Název zájmu" required>
                <button type="submit" name="edit">Uložit</button>
                <button type="button" onclick="cancelEdit()">Zrušit</button>
            </form>
        </div>
    </div>

    <script>
        function editInterest(id, name) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-form').style.display = 'block';
        }
        
        function cancelEdit() {
            document.getElementById('edit-form').style.display = 'none';
        }
    </script>
</body>
</html>
<?php
if (!class_exists('Database')) {
    class Database {
        // Veritabanı sunucusunun adresi
        private $host = '127.0.0.1';
        // Veritabanı adı
        private $db_name = 'task_manager';
        // Veritabanı kullanıcı adı
        private $username = 'root';
        // Veritabanı kullanıcı şifresi
        private $password = '';
        // Veritabanı bağlantı nesnesi
        private $conn;

        // Veritabanı bağlantısını başlat
        public function connect() {
            $this->conn = null;

            try {
                // PDO kullanarak veritabanına bağlan
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
                // Hata modunu istisna olarak ayarla
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                // Bağlantı hatası durumunda hata mesajını yazdır
                echo 'Bağlantı Hatası: ' . $e->getMessage();
            }

            // Bağlantı nesnesini döndür
            return $this->conn;
        }
    }
}

// Görevleri getir
if (!function_exists('getTasks')) {
    function getTasks() {
        $database = new Database();
        $db = $database->connect();
        
        if ($db) {
            try {
                // Görevleri veritabanından çek
                $query = "SELECT id, title AS name, description, created_at AS status FROM tasks";
                $stmt = $db->prepare($query);
                $stmt->execute();
                $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $tasks;
            } catch (PDOException $e) {
                echo 'Sorgu Hatası: ' . $e->getMessage();
            }
        }
        return [];
    }
}

// Yeni görev ekle
if (!function_exists('insertTask')) {
    function insertTask($title, $description) {
        $database = new Database();
        $db = $database->connect();
        
        if ($db) {
            try {
                // Yeni görev ekle
                $query = "INSERT INTO tasks (title, description) VALUES (:title, :description)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo 'Ekleme Hatası: ' . $e->getMessage();
            }
        }
        return false;
    }
}

// Görev güncelle
if (!function_exists('updateTask')) {
    function updateTask($id, $title, $description) {
        $database = new Database();
        $db = $database->connect();
        
        if ($db) {
            try {
                // Görevi güncelle
                $query = "UPDATE tasks SET title = :title, description = :description WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo 'Güncelleme Hatası: ' . $e->getMessage();
            }
        }
        return false;
    }
}

// Görev sil
if (!function_exists('deleteTask')) {
    function deleteTask($id) {
        $database = new Database();
        $db = $database->connect();
        
        if ($db) {
            try {
                // Görevi sil
                $query = "DELETE FROM tasks WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo 'Silme Hatası: ' . $e->getMessage();
            }
        }
        return false;
    }
}

// Form gönderildiğinde işlemleri yönet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_task'])) {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';

        if (!empty($title)) {
            if (insertTask($title, $description)) {
                echo "<p>Görev başarıyla eklendi!</p>";
            } else {
                echo "<p>Görev eklenirken bir hata oluştu.</p>";
            }
        } else {
            echo "<p>Görev adı boş olamaz.</p>";
        }
    } elseif (isset($_POST['update_task'])) {
        $id = $_POST['id'] ?? '';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';

        if (!empty($id) && !empty($title)) {
            if (updateTask($id, $title, $description)) {
                echo "<p>Görev başarıyla güncellendi!</p>";
            } else {
                echo "<p>Görev güncellenirken bir hata oluştu.</p>";
            }
        } else {
            echo "<p>Görev adı boş olamaz.</p>";
        }
    } elseif (isset($_POST['delete_task'])) {
        $id = $_POST['id'] ?? '';

        if (!empty($id)) {
            if (deleteTask($id)) {
                echo "<p>Görev başarıyla silindi!</p>";
            } else {
                echo "<p>Görev silinirken bir hata oluştu.</p>";
            }
        } else {
            echo "<p>Geçersiz görev ID'si.</p>";
        }
    }
}

// Görevleri getir
$tasks = getTasks();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Görev Yöneticisi</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>
    <h1>Görev Yöneticisi</h1>

    <!-- Görev Ekle Butonu -->
    <button onclick="openModal('taskModal')">Görev Ekle</button>

    <!-- Görev Ekleme Formu -->
    <div id="taskModal" class="modal">
        <h2>Yeni Görev Ekle</h2>
        <form method="POST" action="">
            <label for="title">Görev Adı:</label>
            <input type="text" id="title" name="title" required>
            <br>
            <label for="description">Açıklama:</label>
            <textarea id="description" name="description"></textarea>
            <br>
            <button type="submit" name="add_task">Görev Ekle</button>
            <button type="button" onclick="closeModal('taskModal')">İptal</button>
        </form>
    </div>

    <!-- Görev Güncelleme Formu -->
    <div id="updateTaskModal" class="modal">
        <h2>Görev Güncelle</h2>
        <form method="POST" action="">
            <input type="hidden" id="update_id" name="id">
            <label for="update_title">Görev Adı:</label>
            <input type="text" id="update_title" name="title" required>
            <br>
            <label for="update_description">Açıklama:</label>
            <textarea id="update_description" name="description"></textarea>
            <br>
            <button type="submit" name="update_task">Görev Güncelle</button>
            <button type="button" onclick="closeModal('updateTaskModal')">İptal</button>
        </form>
    </div>

    <!-- Görev Listesi -->
    <?php if (!empty($tasks)): ?>
        <h2>Görev Listesi</h2>
        <table border="1">
            <tr><th>ID</th><th>Görev Adı</th><th>Açıklama</th><th>Durum</th><th>İşlemler</th></tr>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($task['name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($task['description'] ?? '') ?></td>
                    <td><?= htmlspecialchars($task['status'] ?? '') ?></td>
                    <td>
                        <button onclick="openUpdateModal(<?= $task['id'] ?>, '<?= htmlspecialchars($task['name']) ?>', '<?= htmlspecialchars($task['description']) ?>')">Güncelle</button>
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                            <button type="submit" name="delete_task">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Görev bulunamadı.</p>
    <?php endif; ?>

    <!-- Overlay -->
    <div id="overlay" class="overlay" onclick="closeAllModals()"></div>

    <script>
        // Modal açma ve kapama fonksiyonları
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function closeAllModals() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
            document.getElementById('overlay').style.display = 'none';
        }

        // Güncelleme modalını açma fonksiyonu
        function openUpdateModal(id, title, description) {
            document.getElementById('update_id').value = id;
            document.getElementById('update_title').value = title;
            document.getElementById('update_description').value = description;
            openModal('updateTaskModal');
        }
    </script>
</body>
</html>
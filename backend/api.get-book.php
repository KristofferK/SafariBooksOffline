<?PHP
require_once('func/DataManager.class.php');
$dataManager = new DataManager();
$book = ($_GET['id'] ?? '');

header('Content-Type: application/json');
try {
    $chapters = $dataManager->getBookDetails($book);
    echo json_encode(['success' => true, 'data' => $chapters]);
}
catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
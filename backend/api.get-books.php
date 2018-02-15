<?PHP
require_once('func/DataManager.class.php');
$dataManager = new DataManager();

header('Content-Type: application/json');
try {
    $books = $dataManager->getBookList();
    echo json_encode(['success' => true, 'data' => $books]);
}
catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
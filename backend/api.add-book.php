<?PHP
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once('func/DataManager.class.php');
$dataManager = new DataManager();
$bookTitle = trim($_POST['title'] ?? '');

try {
    $bookId = $dataManager->addBook($bookTitle);
    echo json_encode(['success' => true, 'message' => 'The book has been added.', 'bookId' => $bookId]);
}
catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
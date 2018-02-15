<?PHP
require_once('func/DataManager.class.php');
$dataManager = new DataManager();
$chapter = ($_GET['id'] ?? '');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
try {
    $source = $dataManager->getChapterSource($chapter);
    echo json_encode(['success' => true, 'data' => $source]);
}
catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
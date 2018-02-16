<?PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once('func/WebClient.class.php');
require_once('func/SafariBooksOnlineClient.class.php');
require_once('func/DataManager.class.php');

$chapterLink  = trim($_POST['link'] ?? '');
$chapterTitle = trim($_POST['title'] ?? '');
$bookId = $_POST['bookId'] ?? '';

$chapterReference = md5($chapterLink);
$dataManager = new DataManager();

try {
    $dataManager->getChapterSource($chapterReference);
    echo json_encode(['success' => false, 'message' => 'The chapter seems to already be downloaded']);
    exit;
}
catch (Exception $e) {
}

try {
    $book = $dataManager->getBookDetails($bookId);
}
catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'The specified bookId is invalid. '.$e->getMessage()]);
    exit;
}
if (strlen($chapterTitle) < 4 || strlen($chapterTitle) > 64) {
    echo json_encode(['success' => false, 'message' => 'Please specify a title between 4 and 64 characters.']);
    exit;
}
$pattern = '~^https://www.safaribooksonline.com/library/view/[a-zA-Z0-9/_-]+\.html$~';
if (!preg_match($pattern, $chapterLink)) {
    echo json_encode(['success' => false, 'message' => 'The link must follow the pattern: '.$pattern]);
    exit;
}

try {
    $details = '';
    $wc = new SafariBooksOnlineClient(new WebClient('sob'));
    $userDetails = $wc->getCredentials();
    if ($userDetails === null) {
        $details = 'Registering account.';
        $userDetails = $wc->register();
        if (!empty($userDetails)) {
            $wc->login($userDetails['email'], $userDetails['password']);
            $wc->selectTopics();
        }
        else {
            $details .= ' Never mind. Seems like we are already logged in.';
        }
    } else {
        $details = 'Signing into existing account.';
        $wc->login($userDetails['email'], $userDetails['password']);
    }
    $details .= " Used the account {$userDetails['email']} - {$userDetails['password']}.";

    $chapterSource = $wc->getPage($chapterLink);
    $dataManager->saveChapter($bookId, $chapterReference, $chapterTitle, $chapterLink, $chapterSource);
    echo json_encode(['success' => true, 'message' => 'The chapter has been saved', 'details' => $details]);
}
catch (Execption $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
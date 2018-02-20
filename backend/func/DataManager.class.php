<?PHP
class DataManager
{
    private $mapPath = 'local-storage/map.json';
    private $downloadsDirectory = 'local-storage/downloads/';
    
    public function createMappingFileIfNotExists() {
        $directory = dirname($this->mapPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        if (!file_exists($this->mapPath)) {
            file_put_contents($this->mapPath, '[]');
        }
    }
    
    public function getMap(): array {
        $this->createMappingFileIfNotExists();
        if (!file_exists($this->mapPath)) {
            throw new Exception('The mapping file couldn\'t be found, nor could it be created.');
        }
        
        return json_decode(file_get_contents($this->mapPath), true);
    }
    
    public function addBook(string $bookTitle): string {
        $bookTitle = trim($bookTitle);
        if (strlen($bookTitle) < 4 || strlen($bookTitle) > 64) {
            throw new Exception('Please specify a title between 4 and 64 characters.');
        }
        
        $bookId = md5($bookTitle);
        if ($this->isBookIdTaken($bookId)) {
            throw new Exception('The specified book title is taken.');
        }
        
        $map = $this->getMap();
        $map[] = ['id' => $bookId, 'title' => $bookTitle, 'chapters' => []];
        file_put_contents($this->mapPath, json_encode($map));
        return $bookId;
    }
    
    private function isBookIdTaken(string $bookId): bool {
        try {
            $book = $this->getBookDetails($bookId);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }
    
    public function saveChapter(string $bookId, string $chapterReference, string $chapterTitle, string $chapterLink, string $chapterSource) {
        $path = $this->downloadsDirectory.$chapterReference.'.html';
        file_put_contents($path, $chapterSource);
        $map = $this->getMap();
        $i = 0;
        foreach ($map as $book) {
            if ($book['id'] == $bookId) {
                $map[$i]['chapters'][] = ['title' => $chapterTitle, 'url' => $chapterLink, 'dateDownloaded' => time(), 'reference' => $chapterReference];
                file_put_contents($this->mapPath, json_encode($map));
                return true;
            }
            $i++;
        }
        throw new Exception('Unexpected error. The chapter couldn\'t be saved');
    }
    
    public function getBookList(): array {
        $map = $this->getMap();
        $bookList = [];
        foreach ($map as $book) {
            $bookList[] = ['id' => $book['id'], 'title' => $book['title']];
        }
        return $bookList;
    }
    
    public function getBookDetails(string $bookId): array {
        if (!preg_match('/^[a-z0-9_-]+$/i', $bookId)) {
            throw new Exception('A bookId should only concist of letters (a-z), numbers (0-9), dash (-) and underscore (_).');
        }
        
        $map = $this->getMap();
        foreach ($map as $book) {
            if ($book['id'] == $bookId) {
                return $book;
            }
        }
        
        throw new Exception('The requested book couldn\'t found.');
    }
    
    public function getChapterSource(string $chapterReference): string {
        if (!preg_match('/^[a-z0-9_-]+$/i', $chapterReference)) {
            throw new Exception('A chapterReference should only concist of letters (a-z), numbers (0-9), dash (-) and underscore (_).');
        }
        
        $path = $this->downloadsDirectory.$chapterReference.'.html';
        if (!file_exists($path)) {
            throw new Exception('The requested chapter couldn\'t be found.');
        }
        
        $source = file_get_contents($path);
        return $this->formatSafariHtmlFile($source);
    }
    
    private function formatSafariHtmlFile(string $source): string {
        $source = str_replace('&nbsp;', ' ', $source);
        
        if (stripos($source, '<div id="sbo-rt-content">') !== false)
            $source = explode('<div id="sbo-rt-content">', $source)[1];

        if (stripos($source, '<div id="sbo-rt-content" style="transform: none;">') !== false)
            $source = explode('<div id="sbo-rt-content" style="transform: none;">', $source)[1];
        
        if (stripos($source, '<div id="sbo-rt-content" class="calibre" style="transform: none;">') !== false)
            $source = explode('<div id="sbo-rt-content" class="calibre" style="transform: none;">', $source)[1];
        
        if (stripos($source, '<div id="sbo-rt-content" class="calibre">') !== false)
            $source = explode('<div id="sbo-rt-content" class="calibre">', $source)[1];

        if (stripos($source, '</div><div class="annotator-outer annotator-viewer viewer annotator-hide') !== false)
            $source = explode('</div><div class="annotator-outer annotator-viewer viewer annotator-hide', $source)[0];

        if (stripos($source, '<h2 class=') !== false)
            $source = explode('<h2 class=', $source)[0];
        
        if (stripos($source, '<div class="t-sbo-prev sbo-prev sbo-nav-bottom') !== false)
            $source = explode('<div class="t-sbo-prev sbo-prev sbo-nav-bottom', $source)[0];
        
        $source = preg_replace('~<img src="/library/view([^"]+)" alt="Image" width="[0-9]+" height="[0-9]+"~', '<img src="https://www.safaribooksonline.com/library/view$1" class="img-fluid"', $source);

        return $source;
    }
}
?>
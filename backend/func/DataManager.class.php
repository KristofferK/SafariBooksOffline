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

        return $source;
    }
}
?>
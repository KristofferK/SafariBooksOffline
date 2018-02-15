<?PHP
class DataManager
{
    private $mapPath = 'local-storage/map.json';
    
    public function createMappingFileIfNotExists() {
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
            $bookList[$book['id']] = ['id' => $book['id'], 'title' => $book['title']];
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
}
?>
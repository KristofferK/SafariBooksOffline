import { Component, OnInit, OnDestroy } from '@angular/core';
import { BookService } from '../services/book.service';
import { Book } from '../_models/book';
import { Subscription } from 'rxjs/Subscription';
import { Title } from '@angular/platform-browser';
import { Chapter } from '../_models/chapter';

@Component({
  selector: 'app-my-books',
  templateUrl: './my-books.component.html',
  styleUrls: ['./my-books.component.scss']
})
export class MyBooksComponent implements OnInit, OnDestroy {
  public books: Book[];
  private booksSubscription: Subscription;
  public selectedBook: Book;
  public selectedChapter: Chapter;
  public source: string;

  constructor(private bookService: BookService, private titleService: Title) { }

  async ngOnInit() {
    this.booksSubscription = this.bookService.books$.subscribe(books => {
      this.books = books;
    });
  }

  public async bookSelected(e: MouseEvent, book: Book) {
    e.preventDefault();
    this.selectedChapter = null;
    if (this.selectedBook != null) {
      this.selectedBook.chapters.forEach(chapter => chapter.active = false);
    }
    this.source = 'Loading ' + book.title;
    this.titleService.setTitle('Loading ' + book.title);
    this.selectedBook = await this.bookService.getBook(book.id);
    this.titleService.setTitle(book.title);
    this.source = 'Please select a chapter';
  }

  public async chapterSelected(e: MouseEvent, chapter: Chapter) {
    e.preventDefault();
    this.source = 'Loading ' + chapter.title;
    this.selectedBook.chapters.forEach(c => c.active = false);
    this.selectedChapter = chapter;
    this.selectedChapter.active = true;
    this.source = await this.bookService.getChapterSource(chapter.reference);
  }

  public ngOnDestroy() {
    this.booksSubscription.unsubscribe();
  }
}

import { Component, OnInit, OnDestroy } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { BookService } from '../services/book.service';
import { Subscription } from 'rxjs/Subscription';
import { Book } from '../_models/book';
import { MessageResponse } from '../_models/message-response';

@Component({
  selector: 'app-book-manager',
  templateUrl: './book-manager.component.html',
  styleUrls: ['./book-manager.component.scss']
})
export class BookManagerComponent implements OnInit, OnDestroy {
  private booksSubscription: Subscription;
  public books: Book[];
  public bookManagerResponse: MessageResponse;

  constructor(private bookService: BookService, private titleService: Title) { }

  ngOnInit() {
    this.titleService.setTitle('Book manager');

    this.booksSubscription = this.bookService.books$.subscribe(books => {
      this.books = books;
    });
  }

  public async addBook(e: Event, title: string) {
    e.preventDefault();
    const response = await this.bookService.addBook(title);
    this.bookManagerResponse = response;
  }

  public async addChapter(e: Event, bookId: string, title: string, link: string) {
    e.preventDefault();
    console.log(bookId, title, link);
    alert('Not implemented');
  }

  ngOnDestroy() {
    this.booksSubscription.unsubscribe();
  }
}

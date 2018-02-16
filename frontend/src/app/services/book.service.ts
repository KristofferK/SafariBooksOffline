import { ReplaySubject } from 'rxjs/ReplaySubject';
import { Http, Response } from '@angular/http';
import { Injectable } from '@angular/core';
import 'rxjs/add/operator/map';
import { Book } from '../_models/book';
import { environment } from '../../environments/environment';
import { MessageResponse } from '../_models/message-response';

@Injectable()
export class BookService {
  public books$ = new ReplaySubject<Book[]>();

  constructor(private http: Http) {
    this._getBooks().then(books => {
      this.books$.next(books);
    });
  }

  public async addBook(title: string): Promise<MessageResponse> {
    const payload = new FormData();
    payload.append('title', title);
    return this.http.post(environment.apiUrl + 'add-book/', payload)
    .map((res: Response) => (<MessageResponse>res.json()))
    .toPromise();
  }

  public async addChapter(bookId: string, title: string, link: string) {
    const payload = new FormData();
    payload.append('bookId', bookId);
    payload.append('title', title);
    payload.append('link', link);
    return this.http.post(environment.apiUrl + 'add-chapter/', payload)
    .map((res: Response) => (<MessageResponse>res.json()))
    .toPromise();
  }

  public async getBook(bookId: string): Promise<Book> {
    return this.http.get(environment.apiUrl + 'get-book/' + bookId)
    .map((res: Response) => (<Book>res.json()['data']))
    .toPromise();
  }

  public async getChapterSource(chapterReference: string): Promise<string> {
    return this.http.get(environment.apiUrl + 'get-chapter-source/' + chapterReference)
    .map((res: Response) => res.json()['data'])
    .toPromise();
  }

  private async _getBooks(): Promise<Book[]> {
    return this.http.get(environment.apiUrl + 'get-books/')
    .map((res: Response) => (<Book[]>res.json()['data']))
    .toPromise();
  }
}

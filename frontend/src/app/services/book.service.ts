import { Subject } from 'rxjs/subject';
import { Http, Response } from '@angular/http';
import { Injectable } from '@angular/core';
import 'rxjs/add/operator/map';
import { Book } from '../_models/book';
import { environment } from '../../environments/environment';

@Injectable()
export class BookService {
  public books$ = new Subject<Book[]>();

  constructor(private http: Http) {
    this._getBooks().then(books => {
      this.books$.next(books);
    });
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

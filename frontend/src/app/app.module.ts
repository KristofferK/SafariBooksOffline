import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';

import { AppRoutingModule } from './app-routing.module';


import { AppComponent } from './app.component';
import { NavbarComponent } from './navbar/navbar.component';
import { FrontPageComponent } from './front-page/front-page.component';
import { MyBooksComponent } from './my-books/my-books.component';
import { BookService } from './services/book.service';
import { BookManagerComponent } from './book-manager/book-manager.component';


@NgModule({
  declarations: [
    AppComponent,
    FrontPageComponent,
    NavbarComponent,
    MyBooksComponent,
    BookManagerComponent
  ],
  imports: [
    BrowserModule,
    HttpModule,
    AppRoutingModule,
  ],
  providers: [
    BookService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }

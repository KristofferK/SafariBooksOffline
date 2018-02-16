import { FrontPageComponent } from './front-page/front-page.component';

import { NgModule } from '@angular/core';
import { LoadChildren, PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { MyBooksComponent } from './my-books/my-books.component';
import { BookManagerComponent } from './book-manager/book-manager.component';

const routes: Routes = [
  {
    path: 'my-books',
    component: MyBooksComponent
  },
  {
    path: 'book-manager',
    component: BookManagerComponent
  },
  {
    path: '',
    component: FrontPageComponent
  },
  {
    path: '**',
    redirectTo: ''
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

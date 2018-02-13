# SafariBooksOffline
Taking SafariBooksOnline.com off the web.

## Abstract
Using PHP I'd like to be able to access some specified books from SafariBooksOnline.com without having internet access.

## Purpose
SafariBooksOnline.com offers a range of educational for a small monthly fee. It does however require you to have internet access.
Furthermore I find the paragraphs to be too wide, making it harder to read and drastically reducing my reading speed. I also find the page to be too bright.

I'd like to if it's possible to make a local website, where I can load these HTML files in a seamlessly way.
In later versions the local website, should be able to download chapters to local storage itself. This will of course only be possible, when having internet access.

This is for educational purposes only.

## Implemented
* Nothing

## To be implemented
* Load local HTML file in an asynchronous manner.
* Format the text paragraphs in a way that makes reading faster.
* Somehow show the user how far he is in the chapter, since we won't have page numbers.
* Download chapters to local storage through the website
  * This could be implemented in a way, where the website itself registers a new account.
    * For this the website has to be able to register an account, and
    * Log into the newly register account, and
    * Download the HTML source for the user-specified chapter.
  * Or it could be implemented in a way, where the user himself has to enter his credentials. In this case the website must be able to
    * Log into the users account, and
    * Download the HTML source for the user-specified chaper.
* Get a list of downloaded chapters
* Load a download chapter into the view.
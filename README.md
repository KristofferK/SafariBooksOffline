# SafariBooksOffline
Taking SafariBooksOnline.com off the web.

## Abstract
Using a (offline) website we'd like to be able to access some specified books from SafariBooksOnline.com. We don't want to be dependenet on having internet access.

## Purpose
SafariBooksOnline.com offers a range of educational for a small monthly fee. It does however require you to have internet access.
Furthermore we find the paragraphs to be too wide, making it harder to read and drastically reducing my reading speed. We also find the page to be too bright.

We'd like to examine whether or not it's possible to make a local website, where we can load these HTML files in a seamlessly way.
In later versions the local website, should be able to download chapters to local storage itself. This will of course only be possible, when having internet access.

This is for educational purposes only.

## The journey
This project is going to be all about the journey, and not so much about the destination.
We'll try to document the journey through a diary and of course through my Git commits. Maybe someday we'll make a blog, where the journey can me documented more thorough.

## Decisions, decisions
Language and framework. We've thought of four different langauges/frameworks, that we see suitable for this small project.
  * ASP.NET Core MVC (C#). We're really fond of this framework and language. It is strongly typed, and we have plenty of experience with it. Not so much with the Core part though.
  * Node.js (TypeScript). We've some experience with this. TypeScript is a great language, that introduces types to JavaScript.
  * Django (Python). Not strongly typed. We've however not been doing much Python lately, so could be a change of getting it brushed up. We've never used the Django framework. Maybe it's worth learning?
  * PHP. Not really much to say. Not strongly typed and over all a somewhat bad language. My teacher hates it.

ASP.NET Core MVC and Node.js with TypeScript seems like great candidates. We however suddenly remember how much my teacher dislikes PHP. Therefore we decide to use PHP for this project.

## Implemented
* Nothing

## To be implemented
* Load local HTML file in an asynchronous manner.
* Format the text paragraphs in a way that makes reading faster.
* Somehow show the user how far he is in the chapter, since we won't have page numbers.
* Download chapters to local storage through the website for offline use. We decide not to download the images, despite this resulting in us not being able to see them without internet access.
  * This could be implemented in a way, where the website itself registers a new account.
    * For this the website has to be able to register an account, and
    * Log into the newly register account, and
    * Download the HTML source for the user-specified chapter.
  * Or it could be implemented in a way, where the user himself has to enter his credentials. In this case the website must be able to
    * Log into the users account, and
    * Download the HTML source for the user-specified chaper.
* Get a list of downloaded chapters
* Load a download chapter into the view.
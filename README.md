Introduction:
  The Book Management System allows the KLab Cyscorpions employees to rent a book online with a pdf copy or to rent the book by a hardbound copy. The Book Management System will be an intrastate implementation. The books that will be lent are work related like 3D/2D books, game development books, web development books, and etc.
Book Management System is a web application for managing/organizing books to help the book lender in his/her task:

The System can:

    # add new books.
    # search for books.
    # search for books via tags.
    # record lend date and return date of books.
    # helps book borrowers choose from available books.
    # sort search results.
    # rate books. [x]
    # comment books. [x]
    
    
Platform:
PHP
jQuery


Framework:
Dietcake

## How to Install:
##### 1. Download https://github.com/sarahgosago/Book-Managing-by-Google-Account/archive/master.zip (book management system 1.0.0). 
##### 2. Create symlink to core.php at app/config from core_development.php or core_production.php depending on your environment. Edit the snippets according on how you use it like the host/url/db_dsn.
##### 3. Add your database (.sql files) on app/config/sql
##### 4. Put all book cover images on webroot/book_covers
##### 5. Follow the steps here to create Google APIs project, http://www.phonglo.com/documents/plo-blogger/create-google-apis-project-and-oauth-2-0-client-ids-to-config-plo-blogger , and register as a google developer

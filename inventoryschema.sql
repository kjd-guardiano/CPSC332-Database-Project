CREATE DATABASE tuffys_tomes;


CREATE TABLE book_authors(
    BookID int,
    AuthorID VARCHAR(400),
    CONSTRAINT pk_author PRIMARY KEY (authorID)
)

CREATE TABLE books(
    BookID INT,
    GenreID INT,
    Title VARCHAR(200),
    ISBN VARCHAR(20),
    PublicationYear year,
    Price DECIMAL(10, 2),
    PublisherID INT,
    CONSTRAINT pk_book PRIMARY KEY (BookID)
)

CREATE TABLE genres(
    GenreID int,
    GenreName VARCHAR(100),
    CONSTRAINT pk_genre PRIMARY KEY (GenreID)
)

CREATE TABLE publishers(
    PublisherID INT,
    PublisherName VARCHAR(150),
    CONSTRAINT pk_publisher PRIMARY KEY (PublisherID)
)

CREATE TABLE authors(
    AuthorID INT,
    FirstName VARCHAR(100),
    LastName VARCHAR(100),
    CONSTRAINT pk_author PRIMARY KEY (AuthorID)
)
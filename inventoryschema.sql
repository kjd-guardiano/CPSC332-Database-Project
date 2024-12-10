CREATE TABLE authors (
    AuthorID INT AUTO_INCREMENT,
    FirstName VARCHAR(100),
    LastName VARCHAR(100),
    PRIMARY KEY (AuthorID)
);

-- Create the publishers table
CREATE TABLE publishers (
    PublisherID INT AUTO_INCREMENT,
    PublisherName VARCHAR(150),
    PRIMARY KEY (PublisherID)
);

-- Create the genres table
CREATE TABLE genres (
    GenreID INT AUTO_INCREMENT,
    GenreName VARCHAR(100),
    PRIMARY KEY (GenreID)
);

-- Create the books table
CREATE TABLE books (
    BookID INT AUTO_INCREMENT,
    GenreID INT,
    Title VARCHAR(200),
    ISBN VARCHAR(20),
    PublicationYear INT, -- Can use YEAR data type if supported by your DBMS
    Price DECIMAL(10, 2),
    PublisherID INT,
    image VARCHAR(200),
    stock INT
    PRIMARY KEY (BookID),
    FOREIGN KEY (GenreID) REFERENCES genres(GenreID),
    FOREIGN KEY (PublisherID) REFERENCES publishers(PublisherID)
);

-- Create the book_authors table (many-to-many relationship between books and authors)
CREATE TABLE book_authors (
    BookID INT,
    AuthorID INT,
    PRIMARY KEY (BookID, AuthorID), -- Composite key
    FOREIGN KEY (BookID) REFERENCES books(BookID),
    FOREIGN KEY (AuthorID) REFERENCES authors(AuthorID)
);
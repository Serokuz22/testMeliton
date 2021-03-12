
SELECT users.id, CONCAT(users.first_name, ' ', users.last_name) as name,
(
  SELECT GROUP_CONCAT(books.name)
  FROM user_books, books
  WHERE user_books.book_id = books.id AND user_books.user_id=users.id
)
as books
FROM users
WHERE 2 IN
(
  SELECT COUNT(books.author)
  FROM user_books, books
  WHERE user_books.book_id = books.id AND user_books.user_id=users.id
  GROUP BY books.author
)
AND users.age >= 7 AND users.age <= 17

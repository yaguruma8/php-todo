DROP TABLE IF EXISTS todos;

CREATE TABLE todos (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  title TEXT,
  is_done BOOL DEFAULT false
);

INSERT INTO todos (title) VALUES ('aaa');
INSERT INTO todos (title, is_done) VALUES ('bbb', true);
INSERT INTO todos (title) VALUES ('ccc');
 
SELECT * FROM todos;
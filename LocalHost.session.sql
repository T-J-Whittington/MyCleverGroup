CREATE TABLE content (
    id SERIAL NOT NULL PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    tags JSON NULL
);


CREATE TABLE images (
    id SERIAL NOT NULL PRIMARY KEY,
    content_id INT NOT NULL REFERENCES content(id),
    path VARCHAR(30) NOT NULL
);

CREATE TABLE videos (
    id SERIAL NOT NULL PRIMARY KEY,
    content_id INT NOT NULL REFERENCES content(id),
    link VARCHAR(100) NOT NULL
);

CREATE TABLE text (
    id SERIAL NOT NULL PRIMARY KEY,
    content_id INT NOT NULL REFERENCES content(id),
    content TEXT NOT NULL
);

INSERT INTO content (name, tags)
VALUES ('Hello world!', '["first", "news", "sausage"]'),
        ('First picture', '["first", "images", "help"]'),
        ('Example Video', '["video", "youtube", "ididathing"]');

INSERT INTO text (content_id, content)
VALUES (1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.');

INSERT INTO images (content_id, path)
VALUES (2, '/content/hotdog.png');

INSERT INTO videos (content_id, link)
VALUES (3, 'https://www.youtube.com/watch?v=j-pKKM6CXr0');

SELECT * FROM JSON_TABLE(SELECT tags FROM content);

SELECT *
FROM content c



                    LEFT JOIN images i ON i.content_id = c.id
                    LEFT JOIN videos v ON v.content_id = c.id
                    LEFT JOIN text t ON t.content_id = c.id
WHERE c.name LIKE "%Hello%";

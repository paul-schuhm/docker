use example_prod

CREATE TABLE Student (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(33) NOT NULL,
    CONSTRAINT pk_student PRIMARY KEY (id)
);

INSERT INTO Student(name) VALUES('John (prod)'), ('Jane (prod)');
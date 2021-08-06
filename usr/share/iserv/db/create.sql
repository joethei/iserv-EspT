CREATE TYPE eventType as ENUM ('invite', 'break', 'book', 'block');

CREATE TABLE event(
  id INT PRIMARY KEY SERIAL,
  start_date TIMESTAMP NOT NULL,
  end_date TIMESTAMP NOT NULL,
  type eventType NOT NULL,
  teacher_group INT NOT NULL
);

CREATE TABLE teacher_group(
  id INT PRIMARY KEY SERIAL,
  room TEXT
);

CREATE TABLE teachers_group(
    id INT PRIMARY KEY SERIAL,
    "group" INT REFERENCES teacher_group(id),
    user INT REFERENCES users(id)
);

CREATE TABLE espt_settings(
    id INT PRIMARY KEY SERIAL,
    date TIMESTAMP NOT NULL,
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL,
    reg_start_date TIMESTAMP NOT NULL,
    reg_end_date TIMESTAMP NOT NULL,

)
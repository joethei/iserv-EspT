CREATE TABLE espt_settings
(
    id                 BIGSERIAL NOT NULL,
    start_date         TIMESTAMP NOT NULL,
    end_date           TIMESTAMP NOT NULL,
    registration_start TIMESTAMP NOT NULL,
    registration_end   TIMESTAMP NOT NULL
);

GRANT USAGE, SELECT ON "espt_settings_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_settings" TO "symfony";

CREATE TYPE espt_eventType AS ENUM (
    'invite',
    'break',
    'book',
    'block'
    );

CREATE TABLE espt_timeslot_template_collection
(
    id   BIGSERIAL NOT NULL,
    name text      NOT NULL
);

GRANT USAGE, SELECT ON "espt_timeslot_template_collection_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_timeslot_template_collection" TO "symfony";

CREATE TABLE espt_timeslot_template
(
    id         BIGSERIAL      NOT NULL,
    start_time TIMESTAMP      NOT NULL,
    end_time   TIMESTAMP      NOT NULL,
    type       espt_eventType NOT NULL,
    collection BIGINT REFERENCES espt_timeslot_template_collection (id)
);

GRANT USAGE, SELECT ON "espt_timeslot_template_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_timeslot_template" TO "symfony";

CREATE TABLE espt_teacher_group(
  id BIGSERIAL NOT NULL,
  room TEXT
);

GRANT USAGE, SELECT ON "espt_teacher_group_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_teacher_group" TO "symfony";

CREATE TABLE espt_teacher_groups(
    user_id BIGINT REFERENCES users(id),
    group_id BIGINT REFERENCES espt_teacher_group(id),
    PRIMARY KEY(user_id, group_id)
);

GRANT USAGE, SELECT ON "espt_teacher_groups_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_teacher_groups" TO "symfony";

CREATE TABLE espt_timeslot(
  id BIGSERIAL NOT NULL,
  start_time TIMESTAMP NOT NULL,
  end_time TIMESTAMP NOT NULL,
  type espt_eventType NOT NULL,
  group_id BIGINT REFERENCES espt_teacher_group(id),
  user_id BIGINT REFERENCES users(id)
);

GRANT USAGE, SELECT ON "espt_timeslot_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_timeslot" TO "symfony";
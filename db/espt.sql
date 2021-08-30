CREATE TABLE espt_settings
(
    id                 BIGSERIAL PRIMARY KEY NOT NULL,
    start_date         TIMESTAMPTZ NOT NULL,
    end_date           TIMESTAMPTZ NOT NULL,
    registration_start TIMESTAMPTZ NOT NULL,
    registration_end   TIMESTAMPTZ NOT NULL
);

GRANT USAGE, SELECT ON "espt_settings_id_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE ON "espt_settings" TO "symfony";

CREATE TYPE espt_eventType AS ENUM (
    'invite',
    'break',
    'book',
    'block'
    );

CREATE TABLE espt_timeslot_template_collection
(
    id   BIGSERIAL PRIMARY KEY NOT NULL,
    name text                  NOT NULL
);

GRANT USAGE, SELECT ON "espt_timeslot_template_collection_id_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_timeslot_template_collection" TO "symfony";

CREATE TABLE espt_timeslot_template
(
    id         BIGSERIAL PRIMARY KEY NOT NULL,
    start_time TIMESTAMPTZ             NOT NULL,
    end_time   TIMESTAMPTZ             NOT NULL,
    type       espt_eventType        NOT NULL,
    collection BIGINT REFERENCES espt_timeslot_template_collection (id)
);

GRANT USAGE, SELECT ON "espt_timeslot_template_id_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_timeslot_template" TO "symfony";

CREATE TABLE espt_teacher_group
(
    id   BIGSERIAL PRIMARY KEY NOT NULL,
    room TEXT
);

GRANT USAGE, SELECT ON "espt_teacher_group_id_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_teacher_group" TO "symfony";

CREATE TABLE espt_teacher_groups
(
    user_id  UUID REFERENCES users (uuid),
    group_id BIGINT REFERENCES espt_teacher_group (id),
    PRIMARY KEY (user_id, group_id)
);

GRANT USAGE, SELECT ON "espt_teacher_groups_id_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_teacher_groups" TO "symfony";

CREATE TABLE espt_timeslot
(
    id         BIGSERIAL PRIMARY KEY NOT NULL,
    start_time TIMESTAMPTZ             NOT NULL,
    end_time   TIMESTAMPTZ             NOT NULL,
    type       espt_eventType        NOT NULL,
    group_id   BIGINT REFERENCES espt_teacher_group (id),
    user_id    UUID REFERENCES users (uuid)
);

GRANT USAGE, SELECT ON "espt_timeslot_id_seq" to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON "espt_timeslot" TO "symfony";
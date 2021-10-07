CREATE TABLE espt_settings
(
    id                 BIGSERIAL PRIMARY KEY NOT NULL,
    start_date         TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    end_date           TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    registration_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    registration_end   TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
);
GRANT USAGE, SELECT ON espt_settings_id_seq to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE ON espt_settings TO "symfony";

CREATE TABLE espt_eventType
(
    id   BIGSERIAL PRIMARY KEY NOT NULL,
    name TEXT                  NOT NULL UNIQUE
);

GRANT USAGE, SELECT ON espt_eventType_id_seq to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON espt_eventType TO "symfony";

---INSERT INTO espt_eventType (name) VALUES ('invite') ON CONFLICT (name) DO NOTHING;
---INSERT INTO espt_eventType (name) VALUES ('book') ON CONFLICT (name) DO NOTHING;
---INSERT INTO espt_eventType (name) VALUES ('blocked') ON CONFLICT (name) DO NOTHING;
---INSERT INTO espt_eventType (name) VALUES ('break') ON CONFLICT (name) DO NOTHING;

CREATE TABLE espt_timeslot_template_collection
(
    id   BIGSERIAL PRIMARY KEY NOT NULL,
    day  INTEGER NOT NULL,
    name TEXT                  NOT NULL
);

GRANT USAGE, SELECT ON espt_timeslot_template_collection_id_seq to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON espt_timeslot_template_collection TO "symfony";

CREATE TABLE espt_timeslot_template
(
    id            BIGSERIAL PRIMARY KEY NOT NULL,
    start_time    TIME(0) WITHOUT TIME ZONE NOT NULL,
    end_time      TIME(0) WITHOUT TIME ZONE NOT NULL,
    type_id       BIGINT REFERENCES espt_eventType (id),
    collection_id BIGINT REFERENCES espt_timeslot_template_collection (id)
);

GRANT USAGE, SELECT ON espt_timeslot_template_id_seq to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON espt_timeslot_template TO "symfony";

CREATE TABLE espt_teacher_group
(
    id                BIGSERIAL PRIMARY KEY NOT NULL,
    room              TEXT
);

GRANT USAGE, SELECT ON espt_teacher_group_id_seq to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE ON espt_teacher_group TO "symfony";

CREATE TABLE espt_timeslot_templates(
    template_id BIGINT REFERENCES espt_timeslot_template_collection(id),
    group_id BIGINT REFERENCES espt_teacher_group(id),
    PRIMARY KEY (template_id, group_id)
);

GRANT SELECT, INSERT, UPDATE, DELETE ON espt_timeslot_templates TO "symfony";

CREATE TABLE espt_teacher_groups
(
    user_id  TEXT REFERENCES users (act),
    group_id BIGINT REFERENCES espt_teacher_group (id),
    PRIMARY KEY (user_id, group_id)
);

GRANT SELECT, INSERT, UPDATE, DELETE ON espt_teacher_groups TO "symfony";

CREATE TABLE espt_timeslot
(
    id         BIGSERIAL PRIMARY KEY NOT NULL,
    start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    end_time   TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    type_id    BIGINT REFERENCES espt_eventType (id),
    group_id   BIGINT REFERENCES espt_teacher_group (id),
    user_id    TEXT REFERENCES users (act)
);

GRANT USAGE, SELECT ON espt_timeslot_id_seq to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE ON espt_timeslot TO "symfony";

CREATE TABLE espt_teacher_group_selection(
    id BIGSERIAL PRIMARY KEY NOT NULL,
    user_id TEXT REFERENCES users(act)
);

GRANT USAGE, SELECT ON espt_teacher_group_selection_id_seq to "symfony";
GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE ON espt_teacher_group_selection TO "symfony";

CREATE TABLE espt_teacher_group_selections(
    selection_id BIGINT REFERENCES espt_teacher_group_selection(id),
    group_id BIGINT REFERENCES espt_teacher_group(id),
    PRIMARY KEY (selection_id, group_id)
);

GRANT SELECT, INSERT, UPDATE, DELETE, TRUNCATE ON espt_teacher_group_selections TO "symfony";
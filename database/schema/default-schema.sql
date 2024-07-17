CREATE TABLE IF NOT EXISTS "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);
CREATE TABLE IF NOT EXISTS "ip_lock" ("id" integer primary key autoincrement not null, "ip" varchar not null default '', "end_at" datetime, "created_at" datetime not null default CURRENT_TIMESTAMP, "updated_at" datetime not null default CURRENT_TIMESTAMP);
CREATE INDEX "ip_lock_ip_index" on "ip_lock" ("ip");
CREATE TABLE IF NOT EXISTS "language" ("id" integer primary key autoincrement not null, "name" varchar not null, "code" varchar not null, "iso" varchar not null, "rtl" tinyint(1) not null default '0', "default" tinyint(1) not null default '0', "enabled" tinyint(1) not null default '0', "created_at" datetime not null default CURRENT_TIMESTAMP, "updated_at" datetime not null default CURRENT_TIMESTAMP);
CREATE UNIQUE INDEX "language_code_unique" on "language" ("code");
CREATE UNIQUE INDEX "language_iso_unique" on "language" ("iso");
CREATE TABLE IF NOT EXISTS "queue_fail" ("id" integer primary key autoincrement not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP, "created_at" datetime not null default CURRENT_TIMESTAMP, "updated_at" datetime not null default CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS "log" ("id" integer primary key autoincrement not null, "action" varchar not null, "ip" varchar, "related_table" varchar not null, "related_id" integer, "payload" text, "created_at" datetime not null default CURRENT_TIMESTAMP, "log_id" integer, "user_id" integer, foreign key("log_id") references "log"("id") on delete CASCADE, foreign key("user_id") references "user"("id") on delete SET NULL);
CREATE INDEX "log_action_index" on "log" ("action");
CREATE INDEX "log_ip_index" on "log" ("ip");
CREATE INDEX "log_log_id_index" on "log" ("log_id");
CREATE INDEX "log_related_id_index" on "log" ("related_id");
CREATE INDEX "log_related_table_index" on "log" ("related_table");
CREATE INDEX "log_user_id_index" on "log" ("user_id");
CREATE INDEX "log_related_table_related_id_index" on "log" ("related_table", "related_id");
CREATE INDEX "log_related_table_related_id_action_index" on "log" ("related_table", "related_id", "action");
CREATE TABLE IF NOT EXISTS "measure" ("id" integer primary key autoincrement not null, "ip" varchar not null, "uptime" integer not null, "cores" integer not null, "tasks_total" integer not null, "tasks_running" integer not null, "tasks_sleeping" integer not null, "tasks_stopped" integer not null, "tasks_zombie" integer not null, "memory_total" integer not null, "memory_used" integer not null, "memory_free" integer not null, "memory_buffer" integer not null, "memory_available" integer not null, "memory_percent" integer not null, "swap_total" integer not null, "swap_used" integer not null, "swap_free" integer not null, "swap_percent" integer not null, "cpu_load_1" float not null, "cpu_load_5" float not null, "cpu_load_15" float not null, "cpu_percent" float not null, "created_at" datetime not null default CURRENT_TIMESTAMP, "measure_app_cpu_id" integer, "measure_app_memory_id" integer, "measure_disk_id" integer, "server_id" integer not null, foreign key("measure_app_cpu_id") references "measure_app"("id") on delete SET NULL, foreign key("measure_app_memory_id") references "measure_app"("id") on delete SET NULL, foreign key("measure_disk_id") references "measure_disk"("id") on delete SET NULL, foreign key("server_id") references "server"("id") on delete CASCADE);
CREATE INDEX "measure_cpu_percent_index" on "measure" ("cpu_percent");
CREATE INDEX "measure_measure_app_cpu_id_index" on "measure" ("measure_app_cpu_id");
CREATE INDEX "measure_measure_app_memory_id_index" on "measure" ("measure_app_memory_id");
CREATE INDEX "measure_measure_disk_id_index" on "measure" ("measure_disk_id");
CREATE INDEX "measure_memory_percent_index" on "measure" ("memory_percent");
CREATE INDEX "measure_memory_used_index" on "measure" ("memory_used");
CREATE INDEX "measure_server_id_index" on "measure" ("server_id");
CREATE TABLE IF NOT EXISTS "measure_app" ("id" integer primary key autoincrement not null, "pid" integer not null, "user" varchar not null, "memory_virtual" integer not null, "memory_resident" integer not null, "memory_percent" float not null, "cpu_load" float not null, "cpu_percent" float not null, "time" integer not null, "command" varchar not null, "created_at" datetime not null default CURRENT_TIMESTAMP, "measure_id" integer not null, foreign key("measure_id") references "measure"("id") on delete CASCADE);
CREATE INDEX "measure_app_cpu_percent_index" on "measure_app" ("cpu_percent");
CREATE INDEX "measure_app_measure_id_index" on "measure_app" ("measure_id");
CREATE INDEX "measure_app_memory_resident_index" on "measure_app" ("memory_resident");
CREATE TABLE IF NOT EXISTS "measure_disk" ("id" integer primary key autoincrement not null, "filesystem" varchar not null, "size" integer not null, "used" integer not null, "available" integer not null, "percent" integer not null, "mount" varchar not null, "created_at" datetime not null default CURRENT_TIMESTAMP, "measure_id" integer not null, foreign key("measure_id") references "measure"("id") on delete CASCADE);
CREATE INDEX "measure_disk_measure_id_index" on "measure_disk" ("measure_id");
CREATE INDEX "measure_disk_percent_index" on "measure_disk" ("percent");
CREATE TABLE IF NOT EXISTS "server" ("id" integer primary key autoincrement not null, "name" varchar not null, "ip" varchar not null default '', "auth" varchar not null, "order" integer not null default '0', "measure_retention" integer not null default '0', "enabled" tinyint(1) not null default '0', "dashboard" tinyint(1) not null default '0', "created_at" datetime not null default CURRENT_TIMESTAMP, "updated_at" datetime not null default CURRENT_TIMESTAMP, "measure_id" integer, "user_id" integer, foreign key("measure_id") references "measure"("id") on delete SET NULL, foreign key("user_id") references "user"("id") on delete SET NULL);
CREATE UNIQUE INDEX "server_auth_unique" on "server" ("auth");
CREATE INDEX "server_measure_id_index" on "server" ("measure_id");
CREATE UNIQUE INDEX "server_name_unique" on "server" ("name");
CREATE INDEX "server_user_id_index" on "server" ("user_id");
CREATE TABLE IF NOT EXISTS "user" ("id" integer primary key autoincrement not null, "name" varchar not null default '', "email" varchar not null, "password" varchar not null, "remember_token" varchar, "enabled" tinyint(1) not null default '0', "created_at" datetime not null default CURRENT_TIMESTAMP, "updated_at" datetime not null default CURRENT_TIMESTAMP, "language_id" integer not null, foreign key("language_id") references "language"("id") on delete CASCADE);
CREATE UNIQUE INDEX "user_email_unique" on "user" ("email");
CREATE INDEX "user_language_id_index" on "user" ("language_id");
CREATE TABLE IF NOT EXISTS "user_code" ("id" integer primary key autoincrement not null, "type" varchar not null, "code" varchar not null, "ip" varchar not null, "expired_at" datetime, "finished_at" datetime, "canceled_at" datetime, "created_at" datetime not null default CURRENT_TIMESTAMP, "updated_at" datetime not null default CURRENT_TIMESTAMP, "user_id" integer not null, foreign key("user_id") references "user"("id") on delete CASCADE);
CREATE INDEX "user_code_code_index" on "user_code" ("code");
CREATE INDEX "user_code_type_index" on "user_code" ("type");
CREATE INDEX "user_code_user_id_index" on "user_code" ("user_id");
CREATE TABLE IF NOT EXISTS "user_fail" ("id" integer primary key autoincrement not null, "type" varchar not null, "text" varchar, "ip" varchar not null, "created_at" datetime not null default CURRENT_TIMESTAMP, "user_id" integer, foreign key("user_id") references "user"("id") on delete SET NULL);
CREATE INDEX "user_fail_ip_index" on "user_fail" ("ip");
CREATE INDEX "user_fail_type_index" on "user_fail" ("type");
CREATE INDEX "user_fail_user_id_index" on "user_fail" ("user_id");
CREATE TABLE IF NOT EXISTS "user_session" ("id" integer primary key autoincrement not null, "auth" varchar not null, "ip" varchar not null, "created_at" datetime not null default CURRENT_TIMESTAMP, "user_id" integer, foreign key("user_id") references "user"("id") on delete SET NULL);
CREATE INDEX "user_session_auth_index" on "user_session" ("auth");
CREATE INDEX "user_session_ip_index" on "user_session" ("ip");
CREATE INDEX "user_session_user_id_index" on "user_session" ("user_id");

INSERT INTO migrations VALUES(1,'2024_04_08_000000_base',1);
INSERT INTO migrations VALUES(2,'2024_07_17_180000_index',1);
INSERT INTO migrations VALUES(3,'2024_07_17_180000_server_measure_retention',1);
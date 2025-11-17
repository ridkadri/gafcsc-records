CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "inventory_categories"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "code" varchar not null,
  "description" text,
  "color" varchar not null default '#6B7280',
  "requires_approval" tinyint(1) not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "inventory_categories_code_unique" on "inventory_categories"(
  "code"
);
CREATE TABLE IF NOT EXISTS "inventory_items"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "item_code" varchar not null,
  "barcode" varchar,
  "category_id" integer not null,
  "description" text,
  "manufacturer" varchar,
  "model" varchar,
  "serial_number" varchar,
  "unit_cost" numeric,
  "total_quantity" integer not null,
  "available_quantity" integer not null,
  "assigned_quantity" integer not null default '0',
  "maintenance_quantity" integer not null default '0',
  "minimum_stock_level" integer not null default '0',
  "condition" varchar not null default 'good',
  "location" varchar,
  "purchase_date" date,
  "warranty_expiry" date,
  "specifications" text,
  "notes" text,
  "status" varchar not null default 'active',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("category_id") references "inventory_categories"("id")
);
CREATE INDEX "inventory_items_category_id_status_index" on "inventory_items"(
  "category_id",
  "status"
);
CREATE INDEX "inventory_items_condition_status_index" on "inventory_items"(
  "condition",
  "status"
);
CREATE INDEX "inventory_items_location_index" on "inventory_items"("location");
CREATE UNIQUE INDEX "inventory_items_item_code_unique" on "inventory_items"(
  "item_code"
);
CREATE UNIQUE INDEX "inventory_items_barcode_unique" on "inventory_items"(
  "barcode"
);
CREATE TABLE IF NOT EXISTS "inventory_assignments"(
  "id" integer primary key autoincrement not null,
  "item_id" integer not null,
  "staff_id" integer not null,
  "quantity" integer not null,
  "assigned_date" date not null,
  "expected_return_date" date,
  "actual_return_date" date,
  "status" varchar not null default 'active',
  "assignment_notes" text,
  "return_notes" text,
  "condition_on_return" varchar,
  "assigned_by" integer not null,
  "returned_to" integer,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("item_id") references "inventory_items"("id"),
  foreign key("staff_id") references "staff"("id"),
  foreign key("assigned_by") references "users"("id"),
  foreign key("returned_to") references "users"("id")
);
CREATE INDEX "inventory_assignments_staff_id_status_index" on "inventory_assignments"(
  "staff_id",
  "status"
);
CREATE INDEX "inventory_assignments_item_id_status_index" on "inventory_assignments"(
  "item_id",
  "status"
);
CREATE INDEX "inventory_assignments_expected_return_date_index" on "inventory_assignments"(
  "expected_return_date"
);
CREATE INDEX "inventory_assignments_status_index" on "inventory_assignments"(
  "status"
);
CREATE TABLE IF NOT EXISTS "inventory_transactions"(
  "id" integer primary key autoincrement not null,
  "item_id" integer not null,
  "staff_id" integer,
  "transaction_type" varchar not null,
  "quantity" integer not null,
  "quantity_before" integer not null,
  "quantity_after" integer not null,
  "notes" text,
  "processed_by" integer not null,
  "metadata" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("item_id") references "inventory_items"("id"),
  foreign key("staff_id") references "staff"("id"),
  foreign key("processed_by") references "users"("id")
);
CREATE INDEX "inventory_transactions_item_id_created_at_index" on "inventory_transactions"(
  "item_id",
  "created_at"
);
CREATE INDEX "inventory_transactions_staff_id_created_at_index" on "inventory_transactions"(
  "staff_id",
  "created_at"
);
CREATE INDEX "inventory_transactions_transaction_type_created_at_index" on "inventory_transactions"(
  "transaction_type",
  "created_at"
);
CREATE INDEX "inventory_transactions_processed_by_index" on "inventory_transactions"(
  "processed_by"
);
CREATE TABLE IF NOT EXISTS "inventory_maintenance"(
  "id" integer primary key autoincrement not null,
  "item_id" integer not null,
  "maintenance_type" varchar not null,
  "scheduled_date" date not null,
  "completed_date" date,
  "status" varchar not null default 'scheduled',
  "description" text not null,
  "notes" text,
  "cost" numeric,
  "performed_by" varchar,
  "created_by" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("item_id") references "inventory_items"("id"),
  foreign key("created_by") references "users"("id")
);
CREATE INDEX "inventory_maintenance_item_id_status_index" on "inventory_maintenance"(
  "item_id",
  "status"
);
CREATE INDEX "inventory_maintenance_scheduled_date_index" on "inventory_maintenance"(
  "scheduled_date"
);
CREATE INDEX "inventory_maintenance_status_index" on "inventory_maintenance"(
  "status"
);
CREATE TABLE IF NOT EXISTS "users_new"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "role" varchar not null default 'viewer',
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "staff"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "service_number" varchar not null,
  "department" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "rank" varchar,
  "type" varchar not null default('civilian'),
  "location" varchar,
  "sex" varchar,
  "trade" varchar,
  "arm_of_service" varchar,
  "deployment" varchar,
  "date_of_enrollment" date,
  "date_of_birth" date,
  "last_promotion_date" date,
  "present_grade" varchar,
  "date_of_employment" date,
  "date_of_posting" date,
  "job_description" text,
  "profile_picture" varchar,
  "appointment" varchar
);
CREATE UNIQUE INDEX "staff_service_number_unique" on "staff"("service_number");
CREATE UNIQUE INDEX "staff_staff_id_unique" on "staff"("service_number");
CREATE TABLE IF NOT EXISTS "staff_documents"(
  "id" integer primary key autoincrement not null,
  "staff_id" integer not null,
  "uploaded_by" integer not null,
  "document_type" varchar not null,
  "document_name" varchar not null,
  "file_path" varchar not null,
  "file_name" varchar not null,
  "file_type" varchar not null,
  "file_size" integer not null,
  "description" text,
  "issue_date" date,
  "expiry_date" date,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("staff_id") references "staff"("id") on delete cascade,
  foreign key("uploaded_by") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar,
  "email_verified_at" datetime,
  "password" varchar not null,
  "role" varchar not null default('viewer'),
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "date_of_birth" date,
  "staff_id" integer,
  "profile_picture" varchar,
  "service_number" varchar,
  "username" varchar not null,
  "password_changed_at" datetime,
  foreign key("staff_id") references staff("id") on delete cascade on update no action
);
CREATE UNIQUE INDEX "users_new_email_unique" on "users"("email");
CREATE INDEX "users_service_number_index" on "users"("service_number");
CREATE UNIQUE INDEX "users_username_unique" on "users"("username");

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_06_24_111818_create_staff_table',2);
INSERT INTO migrations VALUES(5,'2025_06_25_152743_add_role_to_users_table',3);
INSERT INTO migrations VALUES(6,'2025_06_26_184654_add_role_column_to_users_table',4);
INSERT INTO migrations VALUES(7,'2025_06_26_190626_add_rank_to_staff_table',5);
INSERT INTO migrations VALUES(9,'2025_06_30_195210_update_staff_for_military_university',6);
INSERT INTO migrations VALUES(10,'2025_06_30_202311_safely_update_staff_table_columns',6);
INSERT INTO migrations VALUES(11,'2025_06_30_210538_add_staff_type_to_staff_table',7);
INSERT INTO migrations VALUES(12,'2025_06_30_231614_add_location_to_staff_table',8);
INSERT INTO migrations VALUES(14,'2025_08_28_202906_create_inventory_tables',9);
INSERT INTO migrations VALUES(15,'2025_09_16_211651_modify_user_role_enum_add_super_admin',10);
INSERT INTO migrations VALUES(16,'2025_09_16_215328_create_inventory_tables',9);
INSERT INTO migrations VALUES(17,'2025_09_22_165356_remove_inspection_columns_from_inventory_items',11);
INSERT INTO migrations VALUES(18,'2025_10_20_175749_rename_staff_type_to_type_in_staff_table',12);
INSERT INTO migrations VALUES(19,'2025_10_27_105438_make_department_nullable_in_staff_table',13);
INSERT INTO migrations VALUES(20,'2025_10_28_110009_update_users_table_for_name_dob_auth',14);
INSERT INTO migrations VALUES(21,'2025_10_29_093217_create_staff_documents_migration',15);
INSERT INTO migrations VALUES(22,'2025_10_29_213824_add_appointment_to_staff',16);
INSERT INTO migrations VALUES(23,'2025_10_30_110633_add_service_number_to_users',17);
INSERT INTO migrations VALUES(24,'2025_11_09_204300_add_username_to_users_table',18);

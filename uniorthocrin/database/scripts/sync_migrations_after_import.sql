-- Rode após importar dump de produção: alinha nomes antigos às migrations atuais do repositório.
-- Remove duplicados que o phpMyAdmin / histórico deixou.

DELETE FROM migrations WHERE migration = '2025_01_27_000006_add_file_id_to_products';
DELETE FROM migrations WHERE migration = '2025_08_29_155853_remove_file_id_columns_from_content_tables';

UPDATE migrations SET migration = '2025_08_27_193034_fix_products_table_structure'
  WHERE migration = '2025_01_27_000000_fix_products_table_structure';

UPDATE migrations SET migration = '2025_06_13_142602_fix_trainings_table_structure'
  WHERE migration = '2025_01_27_000001_fix_trainings_table_structure';

UPDATE migrations SET migration = '2025_06_13_142603_fix_libraries_table_structure'
  WHERE migration = '2025_01_27_000002_fix_libraries_table_structure';

UPDATE migrations SET migration = '2025_06_13_142604_add_file_id_to_trainings'
  WHERE migration = '2025_01_27_000007_add_file_id_to_trainings';

UPDATE migrations SET migration = '2025_06_13_142605_add_file_id_to_news'
  WHERE migration = '2025_01_27_000008_add_file_id_to_news';

UPDATE migrations SET migration = '2025_06_13_142606_add_file_id_to_library'
  WHERE migration = '2025_01_27_000009_add_file_id_to_library';

UPDATE migrations SET migration = '2025_06_13_142607_remove_fileable_columns_from_files'
  WHERE migration = '2025_01_27_000010_remove_fileable_columns_from_files';

UPDATE migrations SET migration = '2025_06_13_142608_create_news_categories_table'
  WHERE migration = '2025_01_27_000011_create_news_categories_table';

UPDATE migrations SET migration = '2025_06_13_142609_create_campaign_folders_table'
  WHERE migration = '2025_01_27_000013_create_campaign_folders_table';

UPDATE migrations SET migration = '2025_06_13_142610_create_campaign_posts_table'
  WHERE migration = '2025_01_27_000014_create_campaign_posts_table';

UPDATE migrations SET migration = '2025_06_13_142611_create_campaign_videos_table'
  WHERE migration = '2025_01_27_000015_create_campaign_videos_table';

UPDATE migrations SET migration = '2025_06_13_142612_create_campaign_miscellaneous_table'
  WHERE migration = '2025_01_27_000016_create_campaign_miscellaneous_table';

UPDATE migrations SET migration = '2025_06_13_142613_drop_access_history_table'
  WHERE migration = '2025_01_27_000021_drop_access_history_table';

UPDATE migrations SET migration = '2025_06_13_161934_fix_sessions_last_activity_columns'
  WHERE migration = '2025_01_27_000020_fix_sessions_table_structure';

UPDATE migrations SET migration = '2025_06_13_161935_fix_sessions_id_column_length'
  WHERE migration = '2025_01_27_000022_fix_sessions_table_structure';

UPDATE migrations SET migration = '2025_06_13_161936_recreate_sessions_table'
  WHERE migration = '2025_01_27_000023_recreate_sessions_table';

UPDATE migrations SET migration = '2025_06_13_145001_add_business_fields_to_users_table'
  WHERE migration = '2025_01_27_000024_add_business_fields_to_users_table';

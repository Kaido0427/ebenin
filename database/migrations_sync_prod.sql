-- =============================================================
-- SYNC MIGRATIONS TABLE - À exécuter UNE SEULE FOIS en production
-- Marque toutes les migrations comme déjà appliquées
-- IMPORTANT : ne touche pas aux données existantes
-- =============================================================

TRUNCATE TABLE `migrations`;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
-- Tables de base (ordre de dépendances)
('2014_10_11_000000_create_organizations_table', 1),
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2014_10_12_200000_create_password_reset_tokens_table', 1),
('2019_12_14_000001_create_personal_access_tokens_table', 1),
('2021_01_01_000001_create_rubriques_table', 1),
('2021_01_01_000002_create_posts_table', 1),
('2021_01_01_000003_create_post_rubrique_table', 1),
('2021_01_01_000004_create_biographies_table', 1),
('2021_01_01_000005_create_comments_table', 1),
('2021_01_01_000006_create_contacts_table', 1),
('2021_01_01_000007_create_news_letters_table', 1),
('2021_01_01_000008_create_readings_table', 1),
-- Migrations 2024 existantes
('2024_08_13_121132_add_subdomain_to_organizations_table', 1),
('2024_08_14_183648_create_user_organizations_table', 1),
('2024_08_16_114642_create_transactions_table', 1),
('2024_09_04_160723_create_socials_table', 1),
('2024_09_04_165934_create_organization_socials_table', 1),
('2024_09_20_094503_create_publicites_table', 1),
('2024_10_25_171827_create_article_views_table', 1),
('2024_10_25_173638_add_views_to_posts_table', 1),
('2026_01_26_014557_fix_viewed_at_column_in_article_views_table', 1);

-- NOTE : '2024_10_25_175710_add_user_identifier_to_article_views_table'
-- est volontairement EXCLU → elle ajoutera la colonne user_identifier
-- à article_views lors du prochain php artisan migrate (colonne nullable, sans risque)

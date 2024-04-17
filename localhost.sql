-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 17 avr. 2024 à 15:20
-- Version du serveur : 8.0.30
-- Version de PHP : 8.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `boulangerie`
--
CREATE DATABASE IF NOT EXISTS `boulangerie` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `boulangerie`;

-- --------------------------------------------------------

--
-- Structure de la table `achat_stock_maisons`
--

CREATE TABLE `achat_stock_maisons` (
  `id` bigint UNSIGNED NOT NULL,
  `prix_achat` decimal(30,2) NOT NULL,
  `quantite` decimal(30,2) NOT NULL,
  `id_fournisseur` bigint UNSIGNED NOT NULL,
  `id_stock_maisons` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `achat_stock_maisons`
--

INSERT INTO `achat_stock_maisons` (`id`, `prix_achat`, `quantite`, `id_fournisseur`, `id_stock_maisons`, `created_at`, `updated_at`) VALUES
(1, 30000.00, 100.00, 3, 1, '2024-04-17 10:18:47', '2024-04-17 10:18:47'),
(2, 25000.00, 50.00, 1, 2, '2024-04-17 10:19:10', '2024-04-17 10:19:10'),
(3, 8000.00, 100.00, 2, 4, '2024-04-17 10:20:52', '2024-04-17 10:20:52'),
(4, 700.00, 45.00, 2, 3, '2024-04-17 10:38:50', '2024-04-17 10:38:50');

-- --------------------------------------------------------

--
-- Structure de la table `compositions`
--

CREATE TABLE `compositions` (
  `id` bigint UNSIGNED NOT NULL,
  `designation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` decimal(30,2) NOT NULL,
  `prix` decimal(30,2) NOT NULL,
  `stock_usine_id` bigint UNSIGNED NOT NULL,
  `production_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `depenses`
--

CREATE TABLE `depenses` (
  `id` bigint UNSIGNED NOT NULL,
  `motif` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` decimal(30,2) NOT NULL,
  `personne` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `nom`, `telephone`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Kotecha', '0995755838', 'kotecha@gmail.com', '2024-04-17 06:48:17', '2024-04-17 06:48:17'),
(2, 'Maki', '0995755838', 'maki@gmail.com', '2024-04-17 06:48:39', '2024-04-17 06:48:39'),
(3, 'Kishibisha', '0987876543', NULL, '2024-04-17 06:49:05', '2024-04-17 06:49:41');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_04_02_212809_create_fournisseurs_table', 1),
(6, '2024_04_05_110052_create_stock_maisons_table', 1),
(7, '2024_04_05_113137_create_stock_usines_table', 1),
(8, '2024_04_06_204714_create_achat_stock_maisons_table', 1),
(9, '2024_04_07_090306_create_mouvement_stock_mps_table', 1),
(10, '2024_04_09_144403_create_stock_pfs_table', 1),
(11, '2024_04_10_132422_create_productions_table', 1),
(12, '2024_04_10_133439_create_compositions_table', 1),
(13, '2024_04_12_123652_create_stock_boulangeries_table', 1),
(14, '2024_04_12_173533_create_mouvement_stock_pfs_table', 1),
(15, '2024_04_14_075618_create_ventes_table', 1),
(16, '2024_04_15_170753_create_depenses_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `mouvement_stock_mps`
--

CREATE TABLE `mouvement_stock_mps` (
  `id` bigint UNSIGNED NOT NULL,
  `id_stock_mp` int NOT NULL,
  `quantite` decimal(30,2) NOT NULL,
  `reste_maison` decimal(30,2) NOT NULL,
  `reste_usine` decimal(30,2) NOT NULL,
  `statut` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mouvement_stock_pfs`
--

CREATE TABLE `mouvement_stock_pfs` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_pf_id` int NOT NULL,
  `quantite` decimal(30,2) NOT NULL,
  `reste_stock_pf` decimal(30,2) NOT NULL,
  `reste_boulangerie` decimal(30,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `productions`
--

CREATE TABLE `productions` (
  `id` bigint UNSIGNED NOT NULL,
  `designation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` decimal(30,2) NOT NULL,
  `stock_pf_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stock_boulangeries`
--

CREATE TABLE `stock_boulangeries` (
  `id` bigint UNSIGNED NOT NULL,
  `solde` decimal(30,2) NOT NULL DEFAULT '0.00',
  `stock_pf_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stock_maisons`
--

CREATE TABLE `stock_maisons` (
  `id` bigint UNSIGNED NOT NULL,
  `designation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(30,2) NOT NULL,
  `solde` decimal(30,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stock_maisons`
--

INSERT INTO `stock_maisons` (`id`, `designation`, `prix`, `solde`, `created_at`, `updated_at`) VALUES
(1, 'Sac Farine 25kg', 30000.00, 100.00, '2024-04-17 09:57:16', '2024-04-17 10:18:47'),
(2, 'Sac sucre blanc', 25000.00, 50.00, '2024-04-17 09:58:47', '2024-04-17 10:19:10'),
(3, 'Sachet Levure', 700.00, 45.00, '2024-04-17 10:01:02', '2024-04-17 10:38:50'),
(4, 'Sachet Sel', 8000.00, 100.00, '2024-04-17 10:01:41', '2024-04-17 10:20:52');

-- --------------------------------------------------------

--
-- Structure de la table `stock_pfs`
--

CREATE TABLE `stock_pfs` (
  `id` bigint UNSIGNED NOT NULL,
  `designation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(30,2) NOT NULL,
  `solde` decimal(30,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stock_usines`
--

CREATE TABLE `stock_usines` (
  `id` bigint UNSIGNED NOT NULL,
  `solde` decimal(30,2) NOT NULL DEFAULT '0.00',
  `id_stock_maisons` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stock_usines`
--

INSERT INTO `stock_usines` (`id`, `solde`, `id_stock_maisons`, `created_at`, `updated_at`) VALUES
(1, 0.00, 1, '2024-04-17 09:57:16', '2024-04-17 09:57:16'),
(2, 0.00, 2, '2024-04-17 09:58:47', '2024-04-17 09:58:47'),
(3, 0.00, 3, '2024-04-17 10:01:02', '2024-04-17 10:01:02'),
(4, 0.00, 4, '2024-04-17 10:01:41', '2024-04-17 10:01:41');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', 'admin', NULL, '$2y$10$9qul6yBsNQzAlvOuI38hhunYLF.oqLIowo6Pm2Geabs0Zf43EDOG.', NULL, '2024-04-16 06:36:14', '2024-04-16 06:36:14'),
(2, 'Gérant Dépôt Maison', 'gerant1@gmail.com', 'geran_depot_maison', NULL, '$2y$10$uENx3kbUr4mGByIaujuIseMspnPD6X8WlBFqI6cq9rfWVjFMpkod2', NULL, '2024-04-16 06:37:23', '2024-04-16 06:37:23'),
(3, 'Gérant Dépôt Usine', 'gerant2@gmail.com', 'geran_depot_usine', NULL, '$2y$10$qRPRRfXylvzvbGPfcmEo6.zX3rY4dBy1Casc.pEA1LWdFChU9C50C', NULL, '2024-04-16 06:38:00', '2024-04-16 06:38:00'),
(4, 'Gérant Dépôt Boulangerie', 'gerant3@gmail.com', 'geran_depot_boulangerie', NULL, '$2y$10$fSlXFLVH7cjSUgu8.ZyvmOBSpywHD08pb9ZC5UVIfb5myyFSSeOzi', NULL, '2024-04-16 06:38:46', '2024-04-16 06:38:46'),
(5, 'Gérant Bulangerie', 'gerant4@gmail.com', 'geran_depot_magasin', NULL, '$2y$10$k7qug06tPJT7EcRbiTTxFOVlXdB4sT2zt49DDqP3c3RGsajBVBeBO', NULL, '2024-04-16 06:39:29', '2024-04-16 06:39:29');

-- --------------------------------------------------------

--
-- Structure de la table `ventes`
--

CREATE TABLE `ventes` (
  `id` bigint UNSIGNED NOT NULL,
  `designation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` decimal(30,2) NOT NULL,
  `prix` decimal(30,2) NOT NULL,
  `reste` decimal(30,2) NOT NULL,
  `stock_pf_id` bigint UNSIGNED NOT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achat_stock_maisons`
--
ALTER TABLE `achat_stock_maisons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `achat_stock_maisons_id_fournisseur_foreign` (`id_fournisseur`),
  ADD KEY `achat_stock_maisons_id_stock_maisons_foreign` (`id_stock_maisons`);

--
-- Index pour la table `compositions`
--
ALTER TABLE `compositions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compositions_stock_usine_id_foreign` (`stock_usine_id`),
  ADD KEY `compositions_production_id_foreign` (`production_id`);

--
-- Index pour la table `depenses`
--
ALTER TABLE `depenses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mouvement_stock_mps`
--
ALTER TABLE `mouvement_stock_mps`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mouvement_stock_pfs`
--
ALTER TABLE `mouvement_stock_pfs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productions_stock_pf_id_foreign` (`stock_pf_id`);

--
-- Index pour la table `stock_boulangeries`
--
ALTER TABLE `stock_boulangeries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_boulangeries_stock_pf_id_foreign` (`stock_pf_id`);

--
-- Index pour la table `stock_maisons`
--
ALTER TABLE `stock_maisons`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stock_pfs`
--
ALTER TABLE `stock_pfs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stock_usines`
--
ALTER TABLE `stock_usines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_usines_id_stock_maisons_foreign` (`id_stock_maisons`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `achat_stock_maisons`
--
ALTER TABLE `achat_stock_maisons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `compositions`
--
ALTER TABLE `compositions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `depenses`
--
ALTER TABLE `depenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `mouvement_stock_mps`
--
ALTER TABLE `mouvement_stock_mps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mouvement_stock_pfs`
--
ALTER TABLE `mouvement_stock_pfs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `productions`
--
ALTER TABLE `productions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `stock_boulangeries`
--
ALTER TABLE `stock_boulangeries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `stock_maisons`
--
ALTER TABLE `stock_maisons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `stock_pfs`
--
ALTER TABLE `stock_pfs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `stock_usines`
--
ALTER TABLE `stock_usines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `ventes`
--
ALTER TABLE `ventes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achat_stock_maisons`
--
ALTER TABLE `achat_stock_maisons`
  ADD CONSTRAINT `achat_stock_maisons_id_fournisseur_foreign` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `achat_stock_maisons_id_stock_maisons_foreign` FOREIGN KEY (`id_stock_maisons`) REFERENCES `stock_maisons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `compositions`
--
ALTER TABLE `compositions`
  ADD CONSTRAINT `compositions_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compositions_stock_usine_id_foreign` FOREIGN KEY (`stock_usine_id`) REFERENCES `stock_usines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `productions`
--
ALTER TABLE `productions`
  ADD CONSTRAINT `productions_stock_pf_id_foreign` FOREIGN KEY (`stock_pf_id`) REFERENCES `stock_pfs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `stock_boulangeries`
--
ALTER TABLE `stock_boulangeries`
  ADD CONSTRAINT `stock_boulangeries_stock_pf_id_foreign` FOREIGN KEY (`stock_pf_id`) REFERENCES `stock_pfs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `stock_usines`
--
ALTER TABLE `stock_usines`
  ADD CONSTRAINT `stock_usines_id_stock_maisons_foreign` FOREIGN KEY (`id_stock_maisons`) REFERENCES `stock_maisons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

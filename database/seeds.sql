-- =============================================
-- KREDIX - Données de test
-- =============================================

USE `kredix`;

-- Utilisateur de test (mot de passe: password123)
INSERT INTO `users` (`full_name`, `email`, `phone`, `password_hash`) VALUES
('Mama Rose', 'mama.rose@email.com', '699000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Boutique de test
INSERT INTO `shops` (`user_id`, `name`, `type`, `phone`, `address`, `city`) VALUES
(1, 'Boutique Mama Rose', 'boutique', '699000001', 'Marché Central, Douala', 'Douala');

-- Clients de test
INSERT INTO `customers` (`shop_id`, `full_name`, `phone`, `address`, `trust_score`) VALUES
(1, 'Jean Kamga', '699100001', 'Akwa, Douala', 7),
(1, 'Marie Fotso', '699100002', 'Bonabéri, Douala', 9),
(1, 'Paul Njoya', '699100003', 'Deïdo, Douala', 5),
(1, 'Fatima Moussa', '699100004', 'Bonapriso, Douala', 8),
(1, 'Pierre Tchamba', '699100005', 'Bépanda, Douala', 6),
(1, 'Aïcha Adamou', '699100006', 'Makepe, Douala', 7),
(1, 'Thomas Ngando', '699100007', 'Ndokoti, Douala', 4),
(1, 'Cécile Mbarga', '699100008', 'Logbessou, Douala', 9);

-- Crédits de test
INSERT INTO `credits` (`shop_id`, `customer_id`, `amount`, `amount_paid`, `description`, `status`, `due_date`) VALUES
(1, 1, 15000.00, 5000.00, 'Riz 25kg + Huile', 'partial', DATE_ADD(NOW(), INTERVAL 15 DAY)),
(1, 2, 8500.00, 8500.00, 'Savon + Sucre + Lait', 'paid', NULL),
(1, 3, 22000.00, 0.00, 'Sac de ciment + Fer', 'pending', DATE_ADD(NOW(), INTERVAL 30 DAY)),
(1, 4, 12000.00, 7000.00, 'Provisions du mois', 'partial', DATE_ADD(NOW(), INTERVAL 7 DAY)),
(1, 5, 5000.00, 0.00, 'Boisson + Biscuits', 'overdue', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 6, 18000.00, 18000.00, 'Fournitures scolaires', 'paid', NULL),
(1, 7, 35000.00, 10000.00, 'Matériaux construction', 'partial', DATE_ADD(NOW(), INTERVAL 45 DAY)),
(1, 8, 6500.00, 0.00, 'Produits ménagers', 'pending', DATE_ADD(NOW(), INTERVAL 14 DAY)),
(1, 1, 9000.00, 9000.00, 'Poisson fumé', 'paid', NULL),
(1, 3, 7500.00, 2000.00, 'Peinture maison', 'partial', DATE_ADD(NOW(), INTERVAL 21 DAY));

-- Paiements de test
INSERT INTO `payments` (`shop_id`, `credit_id`, `customer_id`, `amount`, `payment_method`, `notes`) VALUES
(1, 1, 1, 5000.00, 'cash', 'Premier versement'),
(1, 2, 2, 8500.00, 'mobile_money', 'Paiement complet via MTN MoMo'),
(1, 4, 4, 3000.00, 'cash', 'Versement 1'),
(1, 4, 4, 4000.00, 'cash', 'Versement 2'),
(1, 6, 6, 18000.00, 'mobile_money', 'Paiement complet Orange Money'),
(1, 7, 7, 10000.00, 'cash', 'Acompte'),
(1, 9, 1, 9000.00, 'cash', 'Paiement direct'),
(1, 10, 3, 2000.00, 'cash', 'Petit versement');

-- Abonnement gratuit
INSERT INTO `subscriptions` (`user_id`, `plan`, `price`) VALUES
(1, 'free', 0.00);

-- Notifications de test
INSERT INTO `notifications` (`shop_id`, `customer_id`, `credit_id`, `type`, `channel`, `title`, `message`) VALUES
(1, 5, 5, 'overdue', 'in_app', '⚠️ Crédit en retard', 'Pierre Tchamba a un crédit de 5 000 FCFA en retard de 5 jours'),
(1, 1, 1, 'payment', 'in_app', '💰 Paiement reçu', '5 000 FCFA reçu de Jean Kamga'),
(1, NULL, NULL, 'system', 'in_app', '🎉 Bienvenue sur Kredix !', 'Votre boutique est prête. Commencez à ajouter vos clients.');

-- Mettre à jour les totaux des clients
UPDATE customers c SET 
    total_credits = (SELECT COALESCE(SUM(amount), 0) FROM credits WHERE customer_id = c.id AND status != 'cancelled'),
    total_paid = (SELECT COALESCE(SUM(amount_paid), 0) FROM credits WHERE customer_id = c.id AND status != 'cancelled');

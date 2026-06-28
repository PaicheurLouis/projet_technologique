-- bdd.sql final - Projet QCM
-- Base de données : qcm_app
-- Compte admin de test : admin@qcm.fr / admin123

CREATE DATABASE IF NOT EXISTS qcm_app
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE qcm_app;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS reponses;
DROP TABLE IF EXISTS tentatives;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS utilisateurs;
DROP TABLE IF EXISTS parametres;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE parametres (
    nom VARCHAR(100) NOT NULL,
    valeur VARCHAR(255) NOT NULL,
    PRIMARY KEY (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO parametres (nom, valeur) VALUES
('duree_qcm', '10');

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    bloque TINYINT(1) DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    reponse1 VARCHAR(255) NOT NULL,
    reponse2 VARCHAR(255) NOT NULL,
    reponse3 VARCHAR(255) NOT NULL,
    reponse4 VARCHAR(255) NOT NULL,
    bonne_reponse INT NOT NULL,
    categorie VARCHAR(100) DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE tentatives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    score FLOAT NOT NULL,
    date_tentative DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tentatives_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE reponses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tentative_id INT NOT NULL,
    question_id INT NOT NULL,
    reponse_utilisateur INT NOT NULL,
    correcte TINYINT(1) NOT NULL,
    CONSTRAINT fk_reponses_tentative
        FOREIGN KEY (tentative_id) REFERENCES tentatives(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_reponses_question
        FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Compte administrateur de test
-- Email : admin@qcm.fr
-- Mot de passe : admin123
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, bloque) VALUES
('Admin', 'Test', 'admin@qcm.fr', '$2y$12$YI2.bB5ryhODeAbnkapRAuGss2k7eJE7T4lhy9dQJuRkJlVDCkz8u', 'admin', 0);

-- Questions du QCM
INSERT INTO `questions` (`id`, `question`, `reponse1`, `reponse2`, `reponse3`, `reponse4`, `bonne_reponse`, `categorie`) VALUES
(1, 'Que signifie PHP ?', 'Personal Home Page', 'PHP Hypertext Preprocessor', 'Private Home Program', 'Page HTML Programmée', 2, 'general'),
(2, 'Que signifie HTML ?', 'HyperText Markup Language', 'HighText Machine Language', 'Hyper Tool Multi Language', 'Home Text Markup Language', 1, 'general'),
(3, 'Quel langage permet de styliser une page web ?', 'PHP', 'SQL', 'CSS', 'HTML', 3, 'general'),
(4, 'Quel système gère les bases de données ?', 'MySQL', 'CSS', 'HTML', 'JavaScript', 1, 'general'),
(5, 'Quelle balise HTML sert à créer un lien ?', '<p>', '<a>', '<div>', '<img>', 2, 'general'),
(6, 'Quelle méthode PHP permet de sécuriser un mot de passe ?', 'password_hash', 'md5 seulement', 'echo', 'include', 1, 'general'),
(7, 'Quelle variable PHP contient les données envoyées en POST ?', '$_POST', '$_GET', '$_SESSION', '$_SERVER', 1, 'general'),
(8, 'Quel mot-clé SQL permet de récupérer des données ?', 'SELECT', 'DELETE', 'UPDATE', 'INSERT', 1, 'general'),
(9, 'Quel mot-clé SQL permet d’ajouter une ligne ?', 'ADD', 'INSERT INTO', 'CREATE', 'JOIN', 2, 'general'),
(10, 'Quel fichier est souvent la page d’accueil du site?', 'style.css', 'index.php', 'config.php', 'header.php', 2, 'general'),
(11, 'Quelle fonction démarre une session PHP ?', 'start_session()', 'session_start()', 'open_session()', 'begin_session()', 2, 'general'),
(12, 'Quel langage est exécuté côté serveur ?', 'HTML', 'CSS', 'PHP', 'Markdown', 3, 'general'),
(13, 'Quelle est la capitale de la France ?', 'Paris', 'Lyon', 'Marseille', 'Toulouse', 1, 'general'),
(14, 'Combien font 2 + 2 ?', '3', '4', '5', '6', 2, 'general'),
(15, 'Quelle planète est surnommée la planète rouge ?', 'Vénus', 'Mars', 'Jupiter', 'Saturne', 2, 'general'),
(16, 'Quel animal miaule ?', 'Le chien', 'Le chat', 'Le cheval', 'La vache', 2, 'general'),
(17, 'Quelle est la couleur du ciel par temps clair ?', 'Rouge', 'Vert', 'Bleu', 'Noir', 3, 'general'),
(18, 'Combien y a-t-il de jours dans une semaine ?', '5', '6', '7', '8', 3, 'general'),
(19, 'Quel est l’océan le plus grand du monde ?', 'Atlantique', 'Indien', 'Pacifique', 'Arctique', 3, 'general'),
(20, 'Quel pays a pour capitale Rome ?', 'Espagne', 'Italie', 'Grèce', 'Portugal', 2, 'general'),
(21, 'Combien y a-t-il de continents généralement reconnus ?', '5', '6', '7', '8', 3, 'general'),
(22, 'Quel est le plus grand mammifère du monde ?', 'L’éléphant', 'La baleine bleue', 'Le rhinocéros', 'La girafe', 2, 'general'),
(23, 'Qui a peint La Joconde ?', 'Pablo Picasso', 'Léonard de Vinci', 'Claude Monet', 'Vincent van Gogh', 2, 'general'),
(24, 'Dans quel pays se trouve la Tour Eiffel ?', 'Italie', 'France', 'Allemagne', 'Belgique', 2, 'general'),
(25, 'Quelle langue parle-t-on principalement au Brésil ?', 'Espagnol', 'Portugais', 'Français', 'Italien', 2, 'general'),
(26, 'Combien de côtés possède un triangle ?', '2', '3', '4', '5', 2, 'general'),
(27, 'Quel gaz les êtres humains respirent-ils principalement pour vivre ?', 'Dioxyde de carbone', 'Oxygène', 'Azote', 'Hélium', 2, 'general'),
(28, 'Quel est le contraire de chaud ?', 'Froid', 'Grand', 'Rapide', 'Sec', 1, 'general'),
(29, 'Quel sport utilise un ballon rond et deux buts ?', 'Tennis', 'Football', 'Natation', 'Cyclisme', 2, 'general'),
(30, 'Combien y a-t-il de mois dans une année ?', '10', '11', '12', '13', 3, 'general'),
(31, 'Quel est le premier mois de l’année ?', 'Mars', 'Janvier', 'Juin', 'Décembre', 2, 'general'),
(32, 'Quel fruit est jaune et souvent associé aux singes ?', 'Pomme', 'Banane', 'Orange', 'Raisin', 2, 'general'),
(33, 'Quel est le symbole chimique de l’eau ?', 'CO2', 'O2', 'H2O', 'NaCl', 3, 'general'),
(34, 'Qui était le premier président de la Ve République française ?', 'François Mitterrand', 'Charles de Gaulle', 'Jacques Chirac', 'Georges Pompidou', 2, 'general'),
(35, 'Quel est le plus long fleuve du monde selon les classements les plus courants ?', 'Le Nil', 'La Seine', 'Le Rhin', 'La Loire', 1, 'general'),
(36, 'Dans quel pays se trouvent les pyramides de Gizeh ?', 'Maroc', 'Égypte', 'Tunisie', 'Jordanie', 2, 'general'),
(37, 'Quel écrivain a écrit Les Misérables ?', 'Victor Hugo', 'Émile Zola', 'Molière', 'Albert Camus', 1, 'general'),
(38, 'Combien de joueurs compte une équipe de football sur le terrain ?', '9', '10', '11', '12', 3, 'general'),
(39, 'Quelle est la capitale de l’Espagne ?', 'Barcelone', 'Madrid', 'Séville', 'Valence', 2, 'general'),
(40, 'Quel est le plus grand pays du monde par sa superficie ?', 'Canada', 'Chine', 'Russie', 'États-Unis', 3, 'general'),
(41, 'Quel instrument possède des touches blanches et noires ?', 'Guitare', 'Piano', 'Violoncelle', 'Flûte', 2, 'general'),
(42, 'Quel est le nom de notre galaxie ?', 'Andromède', 'La Voie lactée', 'Orion', 'Alpha Centauri', 2, 'general'),
(43, 'Quelle est la capitale de l’Allemagne ?', 'Munich', 'Berlin', 'Hambourg', 'Francfort', 2, 'general'),
(44, 'Quel métal est principalement utilisé dans les câbles électriques ?', 'Cuivre', 'Or', 'Argent', 'Fer', 1, 'general'),
(45, 'Qui a découvert l’Amérique en 1492 selon la tradition européenne ?', 'Vasco de Gama', 'Christophe Colomb', 'Magellan', 'Marco Polo', 2, 'general'),
(46, 'Quel est le plus petit nombre premier ?', '0', '1', '2', '3', 3, 'general'),
(47, 'Quel est l’auteur de Roméo et Juliette ?', 'Molière', 'William Shakespeare', 'Victor Hugo', 'Dante', 2, 'general'),
(48, 'Quelle est la capitale du Japon ?', 'Pékin', 'Séoul', 'Tokyo', 'Kyoto', 3, 'general'),
(49, 'Quel organe permet de pomper le sang dans le corps ?', 'Le foie', 'Le cœur', 'Le poumon', 'L’estomac', 2, 'general'),
(50, 'Quel est le sport de Rafael Nadal ?', 'Football', 'Tennis', 'Basket-ball', 'Golf', 2, 'general'),
(51, 'Quel pays est connu pour les tulipes et les moulins ?', 'Pays-Bas', 'Suisse', 'Autriche', 'Suède', 1, 'general'),
(52, 'Quelle est la monnaie officielle utilisée en France ?', 'Dollar', 'Livre sterling', 'Euro', 'Franc suisse', 3, 'general'),
(53, 'Combien de pattes a une araignée ?', '6', '8', '10', '12', 2, 'general'),
(54, 'Quel est le plus haut sommet du monde ?', 'Mont Blanc', 'Kilimandjaro', 'Everest', 'Mont Fuji', 3, 'general'),
(55, 'Quelle est la capitale du Royaume-Uni ?', 'Dublin', 'Londres', 'Édimbourg', 'Manchester', 2, 'general'),
(56, 'Quel peintre est célèbre pour Les Tournesols ?', 'Van Gogh', 'Picasso', 'Monet', 'Delacroix', 1, 'general'),
(57, 'Quel est le résultat de 9 x 9 ?', '72', '81', '90', '99', 2, 'general'),
(58, 'Quel pays a gagné la Coupe du monde de football 2018 ?', 'Brésil', 'France', 'Allemagne', 'Argentine', 2, 'general'),
(59, 'Dans quel continent se trouve le Kenya ?', 'Asie', 'Europe', 'Afrique', 'Amérique', 3, 'general'),
(60, 'Quelle planète est la plus proche du Soleil ?', 'Mercure', 'Vénus', 'Terre', 'Mars', 1, 'general'),
(61, 'Quel est le féminin de roi ?', 'Duchesse', 'Reine', 'Princesse', 'Comtesse', 2, 'general'),
(62, 'Combien de lettres compte l’alphabet français ?', '24', '25', '26', '27', 3, 'general'),
(63, 'Quelle ville française est surnommée la cité phocéenne ?', 'Lyon', 'Marseille', 'Nice', 'Bordeaux', 2, 'general'),
(64, 'Quel est le principal ingrédient du pain ?', 'Farine', 'Riz', 'Pomme de terre', 'Maïs', 1, 'general'),
(65, 'Qui a écrit Le Petit Prince ?', 'Jules Verne', 'Antoine de Saint-Exupéry', 'Victor Hugo', 'Marcel Proust', 2, 'general'),
(66, 'Quel est le nom du satellite naturel de la Terre ?', 'Mars', 'La Lune', 'Vénus', 'Soleil', 2, 'general'),
(67, 'Quelle est la capitale de la Chine ?', 'Shanghai', 'Pékin', 'Hong Kong', 'Canton', 2, 'general'),
(68, 'Quel pays a pour drapeau une feuille d’érable ?', 'Australie', 'Canada', 'Nouvelle-Zélande', 'Irlande', 2, 'general'),
(69, 'Quel est le contraire de jour ?', 'Matin', 'Nuit', 'Soleil', 'Midi', 2, 'general'),
(70, 'Combien de minutes y a-t-il dans une heure ?', '30', '45', '60', '90', 3, 'general'),
(71, 'Quel animal est connu comme le roi de la jungle ?', 'Tigre', 'Lion', 'Éléphant', 'Loup', 2, 'general'),
(72, 'Quelle matière étudie les nombres ?', 'Histoire', 'Mathématiques', 'Géographie', 'Musique', 2, 'general'),
(73, 'Quel est le nom de l’actuel système politique français ?', 'Monarchie absolue', 'République', 'Empire', 'Dictature', 2, 'general'),
(74, 'Quel est le symbole chimique de l’or ?', 'Ag', 'Au', 'Fe', 'Cu', 2, 'general'),
(75, 'Quelle est la capitale de la Grèce ?', 'Athènes', 'Sparte', 'Thessalonique', 'Patras', 1, 'general'),
(76, 'Quel scientifique est associé à la théorie de la relativité ?', 'Isaac Newton', 'Albert Einstein', 'Galilée', 'Marie Curie', 2, 'general'),
(77, 'Quel est le plus grand désert chaud du monde ?', 'Gobi', 'Sahara', 'Kalahari', 'Atacama', 2, 'general'),
(78, 'Dans quel pays se trouve la ville de New York ?', 'Canada', 'États-Unis', 'Mexique', 'Royaume-Uni', 2, 'general'),
(79, 'Quelle est la capitale de la Russie ?', 'Moscou', 'Saint-Pétersbourg', 'Kiev', 'Minsk', 1, 'general'),
(80, 'Quel est le nom de l’étoile au centre du système solaire ?', 'La Lune', 'Le Soleil', 'Vénus', 'Sirius', 2, 'general'),
(81, 'Quel est le résultat de 15 + 25 ?', '30', '35', '40', '45', 3, 'general'),
(82, 'Quel animal pond des œufs ?', 'Le chien', 'La poule', 'Le chat', 'La vache', 2, 'general'),
(83, 'Quel pays est associé au flamenco ?', 'Italie', 'Espagne', 'Portugal', 'France', 2, 'general'),
(84, 'Qui a écrit L’Étranger ?', 'Albert Camus', 'Victor Hugo', 'Molière', 'Émile Zola', 1, 'general'),
(85, 'Quelle est la capitale du Portugal ?', 'Porto', 'Lisbonne', 'Madrid', 'Coimbra', 2, 'general'),
(86, 'Quel est l’élément chimique dont le symbole est Fe ?', 'Fluor', 'Fer', 'Francium', 'Fermium', 2, 'general'),
(87, 'Combien de zéros y a-t-il dans mille ?', '2', '3', '4', '5', 2, 'general'),
(88, 'Quel pays est célèbre pour le Colisée ?', 'Grèce', 'Italie', 'Espagne', 'Turquie', 2, 'general'),
(89, 'Quel est le sport pratiqué au Tour de France ?', 'Course à pied', 'Cyclisme', 'Natation', 'Ski', 2, 'general'),
(90, 'Quel continent est le plus peuplé ?', 'Afrique', 'Europe', 'Asie', 'Océanie', 3, 'general'),
(91, 'Quel est le nom du dieu principal de la mythologie grecque ?', 'Zeus', 'Mars', 'Thor', 'Osiris', 1, 'general'),
(92, 'Quelle est la capitale de la Belgique ?', 'Bruxelles', 'Anvers', 'Liège', 'Gand', 1, 'general'),
(93, 'Quel est le plus grand océan après le Pacifique ?', 'Atlantique', 'Indien', 'Arctique', 'Austral', 1, 'general'),
(94, 'Quel pays a pour capitale Ottawa ?', 'États-Unis', 'Canada', 'Australie', 'Irlande', 2, 'general'),
(95, 'Qui a composé la Neuvième Symphonie ?', 'Mozart', 'Beethoven', 'Bach', 'Chopin', 2, 'general'),
(96, 'Quel est le nom du processus par lequel les plantes produisent leur énergie grâce à la lumière ?', 'Respiration', 'Photosynthèse', 'Digestion', 'Fermentation', 2, 'general'),
(97, 'Quelle est la capitale de l’Australie ?', 'Sydney', 'Melbourne', 'Canberra', 'Perth', 3, 'general'),
(98, 'Quel est le principal gaz présent dans l’atmosphère terrestre ?', 'Oxygène', 'Azote', 'Dioxyde de carbone', 'Hydrogène', 2, 'general'),
(99, 'Qui a écrit Germinal ?', 'Victor Hugo', 'Émile Zola', 'Molière', 'Balzac', 2, 'general'),
(100, 'Quelle est la monnaie officielle du Japon ?', 'Yuan', 'Yen', 'Won', 'Dollar', 2, 'general'),
(101, 'Dans quel pays se trouve le Machu Picchu ?', 'Mexique', 'Pérou', 'Chili', 'Argentine', 2, 'general'),
(102, 'Quel est le nom du célèbre détective créé par Arthur Conan Doyle ?', 'Hercule Poirot', 'Sherlock Holmes', 'Arsène Lupin', 'Maigret', 2, 'general'),
(103, 'Quel philosophe grec fut le maître d’Alexandre le Grand ?', 'Socrate', 'Platon', 'Aristote', 'Épicure', 3, 'general'),
(104, 'Quelle guerre s’est terminée en 1945 ?', 'Première Guerre mondiale', 'Seconde Guerre mondiale', 'Guerre froide', 'Guerre de Cent Ans', 2, 'general'),
(105, 'Quel est le nom de la première femme à avoir reçu un prix Nobel ?', 'Simone Veil', 'Marie Curie', 'Rosa Parks', 'Ada Lovelace', 2, 'general'),
(106, 'Quel pays a lancé le premier satellite artificiel, Spoutnik 1 ?', 'États-Unis', 'URSS', 'France', 'Chine', 2, 'general'),
(107, 'Quelle est la capitale de l’Inde ?', 'Mumbai', 'New Delhi', 'Calcutta', 'Bangalore', 2, 'general'),
(108, 'Quel est l’auteur de Don Quichotte ?', 'Miguel de Cervantes', 'Dante Alighieri', 'William Shakespeare', 'Goethe', 1, 'general'),
(109, 'Quelle mer sépare l’Europe de l’Afrique du Nord ?', 'Mer Noire', 'Mer Méditerranée', 'Mer Baltique', 'Mer Rouge', 2, 'general'),
(110, 'Quel est le nom du traité qui a créé l’Union européenne en 1992 ?', 'Traité de Versailles', 'Traité de Maastricht', 'Traité de Rome', 'Traité de Lisbonne', 2, 'general'),
(111, 'Quel est le nom de l’unité de mesure de la résistance électrique ?', 'Volt', 'Ampère', 'Ohm', 'Watt', 3, 'general'),
(112, 'Quel empire avait pour capitale Constantinople ?', 'Empire romain d’Occident', 'Empire byzantin', 'Empire ottoman uniquement', 'Empire carolingien', 2, 'general');

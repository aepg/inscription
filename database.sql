CREATE TABLE horaire (
  id INT NOT NULL AUTO_INCREMENT,
  libelle VARCHAR(100),
  tri INT NOT NULL,
  libelle_intervalle VARCHAR(50) DEFAULT NULL,
  libelle_jour VARCHAR(50) DEFAULT NULL,
  numero_jour VARCHAR(50) DEFAULT NULL,

  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE activite (
  id INT NOT NULL AUTO_INCREMENT,
  libelle VARCHAR(100) NOT NULL,
  tri INT NOT NULL,
  
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8


CREATE TABLE creneau (
  id_horaire INT NOT NULL,
  id_activite INT NOT NULL,
  nombre_participants INT DEFAULT 0,

  FOREIGN KEY (id_horaire) REFERENCES horaire(id),
  FOREIGN KEY (id_activite) REFERENCES activite(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE participant (
  id INT NOT NULL AUTO_INCREMENT,
  id_horaire INT NOT NULL,
  id_activite INT NOT NULL,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  email VARCHAR(100) DEFAULT NULL,
  telephone VARCHAR(100) DEFAULT NULL,
  
  PRIMARY KEY (id),
  FOREIGN KEY (id_horaire) REFERENCES horaire(id),
  FOREIGN KEY (id_activite) REFERENCES activite(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8


CREATE TABLE `parametre` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL DEFAULT 'N/A',
  `sous_titre` varchar(255) NOT NULL DEFAULT 'N/A',
  `email_obligatoire` tinyint NOT NULL DEFAULT '0',
  `tel_obligatoire` tinyint NOT NULL DEFAULT '0',
  `mode_anonyme` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO parametre (titre,sous_titre,email_obligatoire,tel_obligatoire,mode_anonyme) VALUES ('Inscription aux activités de l''AEPG','Activité Photos Vide Ta Chambre',1,1,1);
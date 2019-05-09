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

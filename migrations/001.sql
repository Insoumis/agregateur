CREATE TABLE IF NOT EXISTS `sources` (
  `id`                INT(11)      NOT NULL AUTO_INCREMENT,
  `title`             VARCHAR(255) NOT NULL,
  `type`              TINYINT      NOT NULL,
  `url`               TEXT         NOT NULL,
  `img_url`           TEXT         NOT NULL,
  `created_at`        DATETIME     NOT NULL,
  `updated_at`        DATETIME     NOT NULL,
  `online`            INT(11)               DEFAULT NULL,
  `source_reserved_1` INT(11)               DEFAULT NULL,
  `source_reserved_2` INT(11)               DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `contents` (
  `id`                 INT(11)  NOT NULL AUTO_INCREMENT,
  `title`             VARCHAR(255) NOT NULL,
  `url`                TEXT     NOT NULL,
  `source_id`          INT(11)  NOT NULL,
  `created_at`         DATETIME NOT NULL,
  `updated_at`         DATETIME NOT NULL,
  `description`        TEXT              DEFAULT NULL,
  `content_reserved_1` INT(11)           DEFAULT NULL,
  `content_reserved_2` INT(11)           DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


ALTER TABLE contents
  MODIFY COLUMN source_id INT NOT NULL,
  ADD CONSTRAINT FK_contents_sources_id
FOREIGN KEY (source_id)
REFERENCES sources (id);
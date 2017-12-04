CREATE TABLE casa (id VARCHAR(255) NOT NULL, id_usuario_cadastro INT DEFAULT NULL, id_usuario_ultima_alteracao INT DEFAULT NULL, nome VARCHAR(100) NOT NULL, slug VARCHAR(255) NOT NULL, status INT NOT NULL, data_cadastro DATETIME NOT NULL, ultima_alteracao DATETIME NOT NULL, UNIQUE INDEX UNIQ_7F655D1D54BD530C (nome), INDEX IDX_7F655D1DC08D23DA (id_usuario_cadastro), INDEX IDX_7F655D1D56D91935 (id_usuario_ultima_alteracao), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE casa ADD CONSTRAINT FK_7F655D1DC08D23DA FOREIGN KEY (id_usuario_cadastro) REFERENCES usuario (id);
ALTER TABLE casa ADD CONSTRAINT FK_7F655D1D56D91935 FOREIGN KEY (id_usuario_ultima_alteracao) REFERENCES usuario (id);
ALTER TABLE evento ADD id_casa VARCHAR(255) DEFAULT NULL;
ALTER TABLE evento ADD CONSTRAINT FK_47860B05F445411F FOREIGN KEY (id_casa) REFERENCES casa (id);
CREATE INDEX IDX_47860B05F445411F ON evento (id_casa);
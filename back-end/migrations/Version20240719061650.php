<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719061650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE profile (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_ROLE_PROFILE ON profile (name)');
        $this->addSql('CREATE TABLE profile_role (profile_id INT NOT NULL, role_id INT NOT NULL, PRIMARY KEY(profile_id, role_id))');
        $this->addSql('CREATE INDEX IDX_E1A105FECCFA12B8 ON profile_role (profile_id)');
        $this->addSql('CREATE INDEX IDX_E1A105FED60322AC ON profile_role (role_id)');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, project_code VARCHAR(255) NOT NULL, project_name VARCHAR(255) NOT NULL, project_date_debut VARCHAR(255) NOT NULL, project_date_end VARCHAR(255) NOT NULL, project_description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_PROJECT_CODE ON project (project_code)');
        $this->addSql('CREATE TABLE project_manager_relation (project_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(project_id, user_id))');
        $this->addSql('CREATE INDEX IDX_2B66FC44166D1F9C ON project_manager_relation (project_id)');
        $this->addSql('CREATE INDEX IDX_2B66FC44A76ED395 ON project_manager_relation (user_id)');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_ROLE_NAME ON role (name)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE user_profile_relation (user_id INT NOT NULL, profile_id INT NOT NULL, PRIMARY KEY(user_id, profile_id))');
        $this->addSql('CREATE INDEX IDX_E6F66425A76ED395 ON user_profile_relation (user_id)');
        $this->addSql('CREATE INDEX IDX_E6F66425CCFA12B8 ON user_profile_relation (profile_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE profile_role ADD CONSTRAINT FK_E1A105FECCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile_role ADD CONSTRAINT FK_E1A105FED60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_manager_relation ADD CONSTRAINT FK_2B66FC44166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_manager_relation ADD CONSTRAINT FK_2B66FC44A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_profile_relation ADD CONSTRAINT FK_E6F66425A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_profile_relation ADD CONSTRAINT FK_E6F66425CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE profile_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('ALTER TABLE profile_role DROP CONSTRAINT FK_E1A105FECCFA12B8');
        $this->addSql('ALTER TABLE profile_role DROP CONSTRAINT FK_E1A105FED60322AC');
        $this->addSql('ALTER TABLE project_manager_relation DROP CONSTRAINT FK_2B66FC44166D1F9C');
        $this->addSql('ALTER TABLE project_manager_relation DROP CONSTRAINT FK_2B66FC44A76ED395');
        $this->addSql('ALTER TABLE user_profile_relation DROP CONSTRAINT FK_E6F66425A76ED395');
        $this->addSql('ALTER TABLE user_profile_relation DROP CONSTRAINT FK_E6F66425CCFA12B8');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE profile_role');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_manager_relation');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_profile_relation');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

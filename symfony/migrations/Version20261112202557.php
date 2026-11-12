<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261112202557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE beds (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(10) NOT NULL, bed_type VARCHAR(50) NOT NULL, is_active TINYINT NOT NULL, room_id INT NOT NULL, INDEX IDX_36EA677A54177093 (room_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE bookings (id INT AUTO_INCREMENT NOT NULL, check_in DATE NOT NULL, check_out DATE NOT NULL, status VARCHAR(20) NOT NULL, total_price NUMERIC(10, 2) NOT NULL, guest_id INT NOT NULL, bed_id INT NOT NULL, INDEX IDX_7A853C359A4AA658 (guest_id), INDEX IDX_7A853C3588688BB9 (bed_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE guests (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(30) NOT NULL, passport_number VARCHAR(30) NOT NULL, country VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_4D11BCB2E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, method VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, paid_at DATETIME DEFAULT NULL, booking_id INT NOT NULL, INDEX IDX_65D29B323301C60 (booking_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rooms (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(10) NOT NULL, type VARCHAR(50) NOT NULL, floor SMALLINT NOT NULL, price_per_night NUMERIC(8, 2) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_7CA11A9696901F54 (number), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE beds ADD CONSTRAINT FK_36EA677A54177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C359A4AA658 FOREIGN KEY (guest_id) REFERENCES guests (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C3588688BB9 FOREIGN KEY (bed_id) REFERENCES beds (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B323301C60 FOREIGN KEY (booking_id) REFERENCES bookings (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beds DROP FOREIGN KEY FK_36EA677A54177093');
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C359A4AA658');
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C3588688BB9');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B323301C60');
        $this->addSql('DROP TABLE beds');
        $this->addSql('DROP TABLE bookings');
        $this->addSql('DROP TABLE guests');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE rooms');
    }
}

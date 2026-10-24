<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261024133855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Hostel schema: rooms, amenities, beds, guests, bookings, payments';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE amenities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_EB7054775E237E06 (name), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE beds (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(10) NOT NULL, bed_type VARCHAR(20) NOT NULL, is_active TINYINT DEFAULT 1 NOT NULL, room_id INT NOT NULL, INDEX IDX_36EA677A54177093 (room_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE bookings (id INT AUTO_INCREMENT NOT NULL, check_in DATE NOT NULL, check_out DATE NOT NULL, status VARCHAR(20) NOT NULL, total_price NUMERIC(10, 2) NOT NULL, notes LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, guest_id INT NOT NULL, bed_id INT NOT NULL, INDEX IDX_7A853C359A4AA658 (guest_id), INDEX IDX_7A853C3588688BB9 (bed_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE guests (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(30) NOT NULL, passport_number VARCHAR(30) NOT NULL, country VARCHAR(100) NOT NULL, birth_date DATE DEFAULT NULL, UNIQUE INDEX UNIQ_4D11BCB2E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, method VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, paid_at DATETIME DEFAULT NULL, booking_id INT NOT NULL, INDEX IDX_65D29B323301C60 (booking_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rooms (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(10) NOT NULL, type VARCHAR(20) NOT NULL, floor SMALLINT NOT NULL, price_per_night NUMERIC(8, 2) NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_7CA11A9696901F54 (number), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE room_amenity (room_id INT NOT NULL, amenity_id INT NOT NULL, INDEX IDX_4742C58354177093 (room_id), INDEX IDX_4742C5839F9F1305 (amenity_id), PRIMARY KEY (room_id, amenity_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE beds ADD CONSTRAINT FK_36EA677A54177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C359A4AA658 FOREIGN KEY (guest_id) REFERENCES guests (id)');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C3588688BB9 FOREIGN KEY (bed_id) REFERENCES beds (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B323301C60 FOREIGN KEY (booking_id) REFERENCES bookings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_amenity ADD CONSTRAINT FK_4742C58354177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_amenity ADD CONSTRAINT FK_4742C5839F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenities (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE beds DROP FOREIGN KEY FK_36EA677A54177093');
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C359A4AA658');
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C3588688BB9');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B323301C60');
        $this->addSql('ALTER TABLE room_amenity DROP FOREIGN KEY FK_4742C58354177093');
        $this->addSql('ALTER TABLE room_amenity DROP FOREIGN KEY FK_4742C5839F9F1305');
        $this->addSql('DROP TABLE amenities');
        $this->addSql('DROP TABLE beds');
        $this->addSql('DROP TABLE bookings');
        $this->addSql('DROP TABLE guests');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE rooms');
        $this->addSql('DROP TABLE room_amenity');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

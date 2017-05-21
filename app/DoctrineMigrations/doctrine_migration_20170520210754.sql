-- Doctrine Migration File Generated on 2017-05-20 21:07:54

-- Version 20170520210659
ALTER TABLE configuration ADD small_screen TINYINT(1) NOT NULL;
ALTER TABLE configuration ADD background_color VARCHAR(255) NOT NULL;
-- Migration: add_adesivo_banner_faixa_to_campaign_miscellaneous_type (2026-02-16)
-- Adiciona os tipos 'adesivo', 'banner' e 'faixa' ao enum type da tabela campaign_miscellaneous.
-- Execute no MySQL/MariaDB após selecionar o banco (USE nome_do_banco; ou via phpMyAdmin).

ALTER TABLE campaign_miscellaneous
  MODIFY COLUMN type ENUM('spot', 'tag', 'sticker', 'script', 'adesivo', 'banner', 'faixa') NOT NULL DEFAULT 'spot';

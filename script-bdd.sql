-- Adminer 4.8.1 PostgreSQL 17.2 (Debian 17.2-1.pgdg120+1) dump

DROP TABLE IF EXISTS "_affiliation";
DROP SEQUENCE IF EXISTS _affiliation_id_seq;
CREATE SEQUENCE _affiliation_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."_affiliation" (
    "nom" character varying(255) NOT NULL,
    "lat" real,
    "lon" real,
    "id" integer DEFAULT nextval('_affiliation_id_seq') NOT NULL,
    CONSTRAINT "_affiliation_nom" UNIQUE ("nom"),
    CONSTRAINT "_affiliation_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "_auteur";
CREATE TABLE "public"."_auteur" (
    "id" integer NOT NULL,
    "nom" character varying(255) NOT NULL,
    "prénom" character varying(255) NOT NULL,
    CONSTRAINT "_auteur_id" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "_publication";
CREATE TABLE "public"."_publication" (
    "id" integer NOT NULL,
    "titre" character varying(255) NOT NULL,
    "url" character varying(255) NOT NULL,
    "type" character varying(255),
    "annee" integer,
    CONSTRAINT "_publication_id" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "_publication_affiliation";
CREATE TABLE "public"."_publication_affiliation" (
    "pub_id" integer NOT NULL,
    "aff_id" integer NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "_publication_auteur";
CREATE TABLE "public"."_publication_auteur" (
    "aut_id" integer NOT NULL,
    "pub_id" integer NOT NULL,
    CONSTRAINT "_auteur_publication_aut_id_pub_id" PRIMARY KEY ("aut_id", "pub_id")
) WITH (oids = false);


-- 2025-03-25 12:43:19.841639+00


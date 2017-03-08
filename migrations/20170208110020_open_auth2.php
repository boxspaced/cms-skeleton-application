<?php

use Phinx\Migration\AbstractMigration;

class OpenAuth2 extends AbstractMigration
{

    public function up()
    {
        $this->execute(
            "
                CREATE TABLE oauth_clients (
                    client_id VARCHAR(80) NOT NULL,
                    client_secret VARCHAR(80),
                    redirect_uri VARCHAR(2000) NOT NULL,
                    grant_types VARCHAR(80),
                    scope VARCHAR(100),
                    user_id VARCHAR(80),
                    CONSTRAINT clients_client_id_pk PRIMARY KEY (client_id)
                )
            "
        );

        $this->execute(
            "
                CREATE TABLE oauth_access_tokens (
                    access_token VARCHAR(40) NOT NULL,
                    client_id VARCHAR(80) NOT NULL,
                    user_id VARCHAR(255),
                    expires TIMESTAMP NOT NULL,
                    scope VARCHAR(2000),
                    CONSTRAINT access_token_pk PRIMARY KEY (access_token)
                )
            "
        );

        $this->execute(
            "
                CREATE TABLE oauth_authorization_codes (
                    authorization_code VARCHAR(40) NOT NULL,
                    client_id VARCHAR(80) NOT NULL,
                    user_id VARCHAR(255),
                    redirect_uri VARCHAR(2000),
                    expires TIMESTAMP NOT NULL,
                    scope VARCHAR(2000),
                    CONSTRAINT auth_code_pk PRIMARY KEY (authorization_code)
                )
            "
        );

        $this->execute(
            "
                CREATE TABLE oauth_refresh_tokens (
                    refresh_token VARCHAR(40) NOT NULL,
                    client_id VARCHAR(80) NOT NULL,
                    user_id VARCHAR(255),
                    expires TIMESTAMP NOT NULL,
                    scope VARCHAR(2000),
                    CONSTRAINT refresh_token_pk PRIMARY KEY (refresh_token)
                )
            "
        );

        $this->execute(
            "
                CREATE TABLE oauth_scopes (
                    scope TEXT,
                    is_default BOOLEAN
                )
            "
        );

        $this->execute(
            "
                CREATE TABLE oauth_jwt (
                    client_id VARCHAR(80) NOT NULL,
                    subject VARCHAR(80),
                    public_key VARCHAR(2000),
                    CONSTRAINT jwt_client_id_pk PRIMARY KEY (client_id)
                )
            "
        );

        $this->execute(
            "
                INSERT INTO oauth_clients (client_id) VALUES ('public')
            "
        );

        $this->execute(
            "
                ALTER TABLE user
                ADD UNIQUE INDEX username_unique (username)
            "
        );
    }

    public function down()
    {
        $this->execute(
            "
                DROP TABLE oauth_clients
            "
        );

        $this->execute(
            "
                DROP TABLE oauth_access_tokens
            "
        );

        $this->execute(
            "
                DROP TABLE oauth_authorization_codes
            "
        );

        $this->execute(
            "
                DROP TABLE oauth_refresh_tokens
            "
        );

        $this->execute(
            "
                DROP TABLE oauth_scopes
            "
        );

        $this->execute(
            "
                DROP TABLE oauth_jwt
            "
        );

        $this->execute(
            "
                ALTER TABLE user
                DROP INDEX username_unique
            "
        );
    }

}

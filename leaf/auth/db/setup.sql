-- CREATE AN EMPTY DATABASE AND RUN THE QUERY BELOW TO ADD YOUR TABLES

CREATE TABLE users (
    id serial PRIMARY KEY,
    username varchar(255) NOT NULL UNIQUE,
    password varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    verified_at timestamp,
    created_at timestamp NOT NULL DEFAULT now(),
    updated_at timestamp NOT NULL DEFAULT now()
);

CREATE TABLE otps (
    id serial PRIMARY KEY,
    user_id integer NOT NULL,
    code varchar(255) NOT NULL,
    created_at timestamp NOT NULL DEFAULT now(),
    updated_at timestamp NOT NULL DEFAULT now(),
    FOREIGN KEY (user_id)
      REFERENCES users (id)
);

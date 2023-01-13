-- CREATE AN EMPTY DATABASE AND RUN THE QUERY BELOW TO ADD YOUR TABLES

CREATE TABLE users (
    id serial PRIMARY KEY,
    username varchar(255) NOT NULL UNIQUE,
    password varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    created_at timestamp NOT NULL DEFAULT now(),
    updated_at timestamp NOT NULL DEFAULT now()
);

ARG POSTGRES_VERSION=alpine
FROM postgres:${POSTGRES_VERSION}

COPY ./docker-entrypoint-initdb.d /docker-entrypoint-initdb.d

CMD ["postgres"]

EXPOSE 5432

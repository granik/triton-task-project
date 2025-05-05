FROM mysql:5.7

RUN yum install -y tzdata && \
    yum clean all
ENV TZ='Europe/Moscow'
ENV MYSQL_ALLOW_EMPTY_PASSWORD=true

COPY ./sql-scripts/ /docker-entrypoint-initdb.d/

COPY ./sql-scripts/build_schema.sql .
COPY ./sql-scripts/db1107101_tasks.sql .

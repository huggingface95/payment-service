FROM golang:1.18-buster
ENV GO111MODULE=on

WORKDIR /auth

ADD . .

RUN go mod download

EXPOSE 2491

RUN set -x && apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    ca-certificates && \
    rm -rf /var/lib/apt/lists/*


# Run the web service on container startup.
CMD ["/app/main"]
FROM alpine:3.20

RUN apk add --no-cache logrotate bash

COPY logrotate.conf /etc/logrotate.conf
COPY nginx /etc/logrotate.d/nginx

CMD ["sh", "-c", "while true; do logrotate /etc/logrotate.conf; sleep 86400; done"]
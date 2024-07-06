FROM alpine:latest

RUN apk --no-cache add apache2 php-apache2 php php-curl

# Configure Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN mkdir -p /run/apache2

# Respect .htaccess files
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/httpd.conf

EXPOSE 80

CMD ["httpd", "-D", "FOREGROUND"]

FROM php:7.4-alpine

RUN apt-get update && \
    apt-get -y install cron

# Copy the script to the container
COPY script.sh /usr/local/bin/script.sh

# Add crontab file to the cron directory
COPY crontab /etc/cron.d/crontab

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/crontab

# Apply cron job
RUN crontab /etc/cron.d/crontab

# Run the command on container startup
CMD cron && tail -f /var/log/cron.log
# pas de montion de version == choisit automatiquement la version "stable"
FROM nginx      

# synchronisation vhost.conf avec default.conf
ADD docker/conf/vhost.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html

CMD ["nginx", "-g", "daemon off;"]
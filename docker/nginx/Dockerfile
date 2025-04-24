FROM nginx:stable-alpine

ADD conf/nginx.conf /etc/nginx/nginx.conf
ADD conf/conf.d/default.conf /etc/nginx/conf.d/default.conf

CMD ["nginx", "-g", "daemon off;"]

FROM node:18-alpine
WORKDIR /app
COPY index.html .
RUN npm install -g serve
EXPOSE 80
CMD ["serve", "-s", ".", "-l", "80"]

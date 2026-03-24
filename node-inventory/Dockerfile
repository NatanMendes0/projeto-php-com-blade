FROM node:20-alpine

WORKDIR /usr/src/app

COPY package*.json ./
RUN npm install

COPY src ./src
COPY .env.example ./.env.example
COPY sql ./sql

EXPOSE 3000

CMD ["npm", "run", "dev"]

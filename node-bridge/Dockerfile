# builder
FROM node:25-alpine AS builder
WORKDIR /usr/src/app
COPY package.json package-lock.json* tsconfig.json ./
RUN npm install
COPY src ./src
RUN npm run build

# runtime
FROM node:25-alpine
WORKDIR /usr/src/app
COPY --from=builder /usr/src/app/dist ./dist
COPY package.json package-lock.json* ./
RUN npm install --production
EXPOSE 9000
CMD ["node","dist/index.js"]

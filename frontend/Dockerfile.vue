# # Use a Node.js image to build the Vue.js application
# FROM node:18-alpine AS builder

# # Set the working directory inside the container
# WORKDIR /app

# # Copy the Vue.js project files into the container
# COPY ./frontend /app 

# # Install dependencies
# RUN npm install

# # Build the Vue.js application for production
# RUN npm run build

# # Use a lightweight Nginx image to serve the static assets
# FROM nginx:alpine

# # Copy the built Vue.js files from the builder stage to Nginx's web root
# COPY --from=builder /app/dist /usr/share/nginx/html

# # Expose port 80 for the Nginx server
# EXPOSE 80

# # Optional: Custom Nginx configuration
# # COPY ./nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Dockerfile.vue
FROM node:18

WORKDIR /app

COPY package*.json ./

RUN npm install

# ✅ Force esbuild to match binary to container architecture
RUN npm rebuild esbuild

COPY . .

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0", "--port", "5174"]

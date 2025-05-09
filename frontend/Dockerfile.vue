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

# Set working directory inside container
WORKDIR /app

# Copy package files first (for better layer caching)
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the project
COPY . .

# Expose Vite/Vue CLI port
EXPOSE 5173

# Start the dev server with hot reload
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0", "--port", "5174"]

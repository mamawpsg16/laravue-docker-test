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

# Use a Node.js image for development
FROM node:18-alpine AS builder

# Set the working directory inside the container
WORKDIR /app

# Copy the Vue.js project files into the container
COPY ./frontend /app

# Install dependencies
RUN npm install

# Start the development server
CMD ["npm", "run", "dev"] #  Use npm run dev


# Use a lightweight Nginx image to act as a reverse proxy
FROM nginx:alpine

# Copy a custom Nginx configuration
# COPY ./nginx/development.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
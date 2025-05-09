import axios from 'axios';

// Create axios instance with default config
const instance = axios.create({
  baseURL: 'http://localhost:8002',
  withCredentials: true, // Important for sending cookies
  withXSRFToken: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest', // Required for Laravel to identify AJAX requests
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

export default instance;
import axios from 'axios';

// For CSRF and auth endpoints
const authApi = axios.create({
  baseURL: 'http://localhost:8002',
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json'
  }
});

// For API v1 endpoints
const apiV1 = axios.create({
  baseURL: 'http://localhost:8002/api/v1',
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json'
  }
});

export { authApi, apiV1 };
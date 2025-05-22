<template>
    <div class="loading-spinner">
      <!-- Simple spinner or text -->
      <svg
        class="spinner"
        viewBox="0 0 50 50"
        xmlns="http://www.w3.org/2000/svg"
      >
        <circle
          class="path"
          cx="25"
          cy="25"
          r="20"
          fill="none"
          stroke-width="5"
        />
      </svg>
      <p>Loading...</p>
    </div>
  </template>
  
  <style scoped>
  .loading-spinner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-weight: bold;
    color: #555;
  }
  
  .spinner {
    animation: spin 1s linear infinite;
    width: 50px;
    height: 50px;
    stroke: #42b983; /* Vue green */
  }
  
  @keyframes spin {
    100% {
      transform: rotate(360deg);
    }
  }
  </style>
  
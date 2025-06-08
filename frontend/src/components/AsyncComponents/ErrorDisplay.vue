<template>
    <div class="error-display">
      <p>Sorry, something went wrong while loading the component.</p>
      <button @click="$emit('retry')">Try Again</button>
    </div>
  </template>
  
  <style scoped>
  .error-display {
    padding: 20px;
    background-color: #ffe5e5;
    border: 1px solid #ff4d4d;
    color: #a10000;
    font-weight: bold;
    text-align: center;
  }
  button {
    margin-top: 10px;
    padding: 5px 15px;
    background-color: #ff4d4d;
    color: white;
    border: none;
    cursor: pointer;
  }
  button:hover {
    background-color: #d93636;
  }
  </style>
  
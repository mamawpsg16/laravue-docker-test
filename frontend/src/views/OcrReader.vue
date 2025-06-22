<template>
    <div>
      <input type="file" @change="onFileChange" accept="image/*" />
      <div style="position: relative; display: inline-block;">
        <img :src="imageSrc" ref="image" alt="Uploaded Image" />
        <div
          v-for="(word, index) in words"
          :key="index"
          :style="{
            position: 'absolute',
            border: '1px solid red',
            left: word.bbox.x0 + 'px',
            top: word.bbox.y0 + 'px',
            width: (word.bbox.x1 - word.bbox.x0) + 'px',
            height: (word.bbox.y1 - word.bbox.y0) + 'px',
            color: 'red',
            fontSize: '12px',
            pointerEvents: 'none',
            backgroundColor: 'rgba(255,255,255,0.3)'
          }"
        >
          {{ word.text }}
        </div>
      </div>
      <button class="p-4 bg-blue-500 border rounded-lg" @click="recognizeText" :disabled="!imageSrc || processing">
        {{ processing ? 'Processing...' : 'Recognize Text' }}
      </button>
      <pre>{{ fullText }}</pre>
    </div>
  </template>
  
  <script>
  import { ref, onMounted, onUnmounted } from 'vue'
  import { createWorker, PSM } from 'tesseract.js'
  
  export default {
    setup() {
      const imageSrc = ref(null)
      const words = ref([])
      const fullText = ref('')
      const processing = ref(false)
      let worker = null
  
      onMounted(async () => {
        worker = createWorker({
          logger: (m) => console.log(m),
        })
        await worker.load()
        await worker.loadLanguage('eng')
        await worker.initialize('eng')
        await worker.setParameters({
          tessedit_pageseg_mode: PSM.AUTO,
        })
      })
  
      onUnmounted(async () => {
        if (worker) {
          await worker.terminate()
        }
      })
  
      const onFileChange = (event) => {
        const file = event.target.files[0]
        if (file) {
          imageSrc.value = URL.createObjectURL(file)
          words.value = []
          fullText.value = ''
        }
      }
  
      const recognizeText = async () => {
        if (!imageSrc.value || !worker) return
        processing.value = true
        words.value = []
        fullText.value = ''
  
        try {
          const { data } = await worker.recognize(imageSrc.value)
          console.log('OCR data:', data)
  
          fullText.value = data.text || ''
  
          if (data.words && Array.isArray(data.words)) {
            words.value = data.words.map((w) => ({
              text: w.text,
              bbox: w.bbox,
            }))
          } else {
            words.value = []
          }
        } catch (error) {
          console.error('OCR error:', error)
          words.value = []
          fullText.value = 'Error recognizing text'
        } finally {
          processing.value = false
        }
      }
  
      return {
        imageSrc,
        onFileChange,
        recognizeText,
        words,
        fullText,
        processing,
      }
    },
  }
  </script>
  
  <style>
  img {
    max-width: 100%;
    height: auto;
    display: block;
  }
  </style>
  
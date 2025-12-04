<template>
  <div class="hello-world">
    <div class="card shadow">
      <div class="card-body">
        <h2 class="card-title text-center mb-4">{{ titulo }}</h2>
        <p class="card-text text-center mb-4">{{ descripcion }}</p>
        
        <div class="text-center mb-4">
          <button @click="incrementar" class="btn btn-success btn-lg">
            <i class="bi bi-hand-thumbs-up"></i> Clic aquí
          </button>
          <p class="mt-3 fs-4">
            <span class="badge bg-primary">Contador: {{ contador }}</span>
          </p>
        </div>

        <div class="mt-4">
          <label class="form-label fw-bold">Escribe tu nombre:</label>
          <input 
            v-model="nombre" 
            type="text" 
            class="form-control form-control-lg" 
            placeholder="Tu nombre aquí..."
          >
          <div v-if="nombre" class="alert alert-success mt-3" role="alert">
            <h4 class="alert-heading">¡Hola, {{ nombre }}! 👋</h4>
            <p class="mb-0">Bienvenido a Vue.js con Laravel</p>
          </div>
        </div>

        <div class="mt-4">
          <h5>Lista de tareas:</h5>
          <ul class="list-group">
            <li v-for="(tarea, index) in tareas" :key="index" class="list-group-item">
              {{ tarea }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HelloWorld',
  data() {
    return {
      titulo: '¡Hola desde Vue.js!',
      descripcion: 'Este es un componente de Vue 3 funcionando perfectamente en Laravel',
      contador: 0,
      nombre: '',
      tareas: [
        'Aprender Vue.js',
        'Integrar con Laravel',
        'Crear componentes reutilizables',
        'Construir aplicaciones increíbles'
      ]
    }
  },
  methods: {
    incrementar() {
      this.contador++
    }
  },
  mounted() {
    console.log('Componente HelloWorld montado correctamente!');
  }
}
</script>

<style scoped>
.hello-world {
  padding: 20px;
}

.card {
  border: none;
  border-radius: 15px;
}

.card-title {
  color: #42b983;
  font-weight: bold;
}

.btn-success {
  background: linear-gradient(135deg, #42b983 0%, #35a372 100%);
  border: none;
  padding: 12px 30px;
  transition: transform 0.2s;
}

.btn-success:hover {
  transform: scale(1.05);
}
</style>
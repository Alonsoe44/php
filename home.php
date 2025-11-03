<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Fotos</title>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>
    body { font-family: sans-serif; display: flex; flex-direction: column; align-items: center; background: #f7f7f7; min-height: 100vh; padding: 2rem; }
    form { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; text-align: center; margin-bottom: 2rem; }
    input[type="text"], input[type="file"] { display: block; margin: 1rem auto; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 5px; }
    button { width: 100%; padding: 0.6rem; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #0056b3; }
    .message { margin-top: 1rem; color: green; font-weight: bold; }
    .gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; width: 90%; max-width: 800px; }
    .photo-card { background: white; padding: 0.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); text-align: center; }
    .photo-card img { width: 100%; height: 120px; object-fit: cover; border-radius: 5px; }
    .photo-card h4 { font-size: 0.9rem; margin-top: 0.5rem; }
  </style>
</head>
<body>

<div x-data="photoForm()" x-init="fetchPhotos()">

  <!-- 📤 Formulario -->
  <form @submit.prevent="submitForm" enctype="multipart/form-data">
    <h2>Subir Imagen 📷</h2>

    <input type="text" placeholder="Nombre de la foto" x-model="photoName" required>
    <input type="file" @change="handleFile" accept="image/*" required>

    <button type="submit" x-text="loading ? 'Subiendo...' : 'Subir'"></button>

    <p class="message" x-text="message"></p>
  </form>

  <!-- 🖼️ Galería -->
  <div class="gallery">
    <template x-for="photo in photos" :key="photo.id">
      <div class="photo-card">
        <img :src="photo.location" :alt="photo.name">
        <h4 x-text="photo.name"></h4>
      </div>
    </template>
  </div>

</div>

<script>
function photoForm() {
  return {
    photoName: '',
    file: null,
    message: '',
    loading: false,
    photos: [],

    handleFile(event) {
      this.file = event.target.files[0];
    },

    async submitForm() {
      if (!this.file) {
        this.message = 'Selecciona una imagen';
        return;
      }

      this.loading = true;
      this.message = '';

      const formData = new FormData();
      formData.append('name', this.photoName);
      formData.append('photo', this.file);

      const res = await fetch('upload.php', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();
      this.loading = false;

      if (data.success) {
        this.message = 'Imagen subida correctamente';
        this.photoName = '';
        this.file = null;
        this.fetchPhotos(); // actualiza la galería
      } else {
        this.message = data.message || 'Error al subir la imagen';
      }
    },

    async fetchPhotos() {
      try {
        const res = await fetch('fetch_photos.php');
        const data = await res.json();
        this.photos = data;
      } catch (e) {
        console.error(e);
      }
    }
  }
}
</script>

</body>
</html>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>
    body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f4f4; }
    form { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; }
    input { display: block; width: 100%; margin-bottom: 1rem; padding: 0.5rem; border: 1px solid #ccc; border-radius: 5px; }
    button { width: 100%; padding: 0.6rem; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #0056b3; }
    .error { color: red; font-size: 0.9rem; text-align: center; }
  </style>
</head>
<body>

<div x-data="loginForm()">
  <form @submit.prevent="login">
    <h2>Iniciar sesión</h2>

    <input type="email" placeholder="Email" x-model="email" required>
    <input type="password" placeholder="Contraseña" x-model="password" required>

    <button type="submit" x-text="loading ? 'Verificando...' : 'Entrar'"></button>

    <p class="error" x-text="error"></p>
  </form>
</div>

<script>
function loginForm() {
  return {
    email: '',
    password: '',
    error: '',
    loading: false,

    async login() {
      this.loading = true;
      this.error = '';

      try {
        const res = await fetch('auth.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ email: this.email, password: this.password })
        });

        const data = await res.json();

        if (data.success) {
          window.location.href = 'home.php'; // página privada
        } else {
          this.error = data.message;
        }
      } catch (e) {
        this.error = 'Error de conexión con el servidor.';
      }

      this.loading = false;
    }
  }
}
</script>

</body>
</html>
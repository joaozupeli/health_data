const API = 'http://localhost:8080';
const form = document.getElementById('login-form');
const message = document.getElementById('message');

if (localStorage.getItem('healthDataUser')) location.href = 'dashboard.php';

form.addEventListener('submit', async (event) => {
  event.preventDefault();
  message.className = 'message';
  const button = form.querySelector('button');
  button.disabled = true; button.textContent = 'Entrando...';
  try {
    const response = await fetch(`${API}/modules/auth/AuthController.php`, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ username:form.username.value.trim(), password:form.password.value }) });
    const result = await response.json();
    if (!response.ok || !result.success) throw new Error(result.message || 'Não foi possível entrar');
    localStorage.setItem('healthDataUser', JSON.stringify(result.user));
    location.href = 'dashboard.php';
  } catch (error) { message.textContent = error.message; message.className = 'message error show'; }
  finally { button.disabled = false; button.textContent = 'Entrar'; }
});

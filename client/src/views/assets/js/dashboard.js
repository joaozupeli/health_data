const API = 'http://localhost:8080';
const user = JSON.parse(localStorage.getItem('healthDataUser') || 'null');
if (!user) location.href = 'login.php';
else document.getElementById('welcome').textContent = `Bem-vindo, ${user.name}.`;
document.getElementById('logout-btn').addEventListener('click', () => { localStorage.removeItem('healthDataUser'); location.href='login.php'; });
Promise.all(['medicos','pacientes'].map(type => fetch(`${API}/modules/records.php?type=${type}`).then(r => r.json())))
  .then(([medicos,pacientes]) => { document.getElementById('medicos-count').textContent=medicos.data.length; document.getElementById('pacientes-count').textContent=pacientes.data.length; })
  .catch(() => { document.getElementById('medicos-count').textContent='!'; document.getElementById('pacientes-count').textContent='!'; });

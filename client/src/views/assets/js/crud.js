const API_URL = 'http://localhost:8080/modules/records.php';
const entity = document.body.dataset.entity;
const action = document.body.dataset.action;
const plural = entity === 'medico' ? 'medicos' : 'pacientes';
const user = JSON.parse(localStorage.getItem('healthDataUser') || 'null');
if (!user) location.href = '../login.php';

document.getElementById('logout-btn')?.addEventListener('click', () => {
  localStorage.removeItem('healthDataUser'); location.href = '../login.php';
});

function escapeHtml(value) {
  return String(value ?? '').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char]));
}

function showMessage(text, type = 'success') {
  const box = document.getElementById('message');
  if (!box) return;
  box.textContent = text; box.className = `message ${type} show`;
}

async function request(url = `${API_URL}?type=${plural}`, options = {}) {
  const response = await fetch(url, { headers:{'Content-Type':'application/json'}, ...options });
  const result = await response.json();
  if (!response.ok || !result.success) throw new Error(result.message || 'Erro na operação');
  return result;
}

async function loadList() {
  const tbody = document.getElementById('records-body');
  try {
    const { data } = await request();
    if (!data.length) { tbody.innerHTML = '<tr><td colspan="6" class="empty">Nenhum cadastro encontrado.</td></tr>'; return; }
    tbody.innerHTML = data.map(item => entity === 'medico' ? `
      <tr><td>${escapeHtml(item.nome)}</td><td>${escapeHtml(item.crm)}</td><td>${escapeHtml(item.especialidade)}</td><td>${escapeHtml(item.telefone)}</td><td>${escapeHtml(item.email)}</td><td class="actions"><a class="button secondary small" href="edit.php?id=${item.id}">Editar</a><button class="button danger small" data-delete="${item.id}">Excluir</button></td></tr>` : `
      <tr><td>${escapeHtml(item.nome)}</td><td>${escapeHtml(item.cpf)}</td><td>${formatDate(item.nascimento)}</td><td>${escapeHtml(item.telefone)}</td><td>${escapeHtml(item.email)}</td><td class="actions"><a class="button secondary small" href="edit.php?id=${item.id}">Editar</a><button class="button danger small" data-delete="${item.id}">Excluir</button></td></tr>`).join('');
  } catch (error) { tbody.innerHTML = `<tr><td colspan="6" class="empty">${escapeHtml(error.message)}</td></tr>`; }
}

function formatDate(value) {
  if (!value) return '—';
  return new Date(`${value}T12:00:00`).toLocaleDateString('pt-BR');
}

document.getElementById('records-body')?.addEventListener('click', async event => {
  const button = event.target.closest('[data-delete]');
  if (!button || !confirm('Deseja realmente excluir este cadastro?')) return;
  try { await request(`${API_URL}?type=${plural}&id=${button.dataset.delete}`, {method:'DELETE'}); await loadList(); }
  catch (error) { alert(error.message); }
});

const form = document.getElementById('record-form');
if (form) {
  const id = new URLSearchParams(location.search).get('id');
  if (action === 'edit' && id) {
    request(`${API_URL}?type=${plural}&id=${id}`).then(({data}) => Object.entries(data).forEach(([key,value]) => { if (form.elements[key]) form.elements[key].value=value; })).catch(error => showMessage(error.message,'error'));
  }
  form.addEventListener('submit', async event => {
    event.preventDefault();
    const payload = Object.fromEntries(new FormData(form).entries());
    const button = form.querySelector('[type=submit]'); button.disabled=true; button.textContent='Salvando...';
    try {
      const url = action === 'edit' ? `${API_URL}?type=${plural}&id=${id}` : `${API_URL}?type=${plural}`;
      const result = await request(url, {method:action === 'edit' ? 'PUT' : 'POST', body:JSON.stringify(payload)});
      showMessage(result.message); setTimeout(() => location.href='list.php', 650);
    } catch (error) { showMessage(error.message,'error'); button.disabled=false; button.textContent='Salvar'; }
  });
}

if (action === 'list') loadList();

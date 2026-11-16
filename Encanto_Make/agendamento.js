(() => {
 const dialog = document.getElementById('novo-agendamento');
 if (!dialog) return;
 const open = () => { if (!dialog.open) { dialog.showModal(); document.body.classList.add('appointment-open'); } };
 document.querySelectorAll('[data-open-appointment]').forEach(button => button.addEventListener('click', open));
 document.querySelectorAll('[data-close-appointment]').forEach(button => button.addEventListener('click', () => dialog.close()));
 dialog.addEventListener('close', () => {
   document.body.classList.remove('appointment-open');
   const url = new URL(location.href);
   if (url.searchParams.has('novo')) { url.searchParams.delete('novo'); history.replaceState(null, '', url); }
 });
 dialog.addEventListener('click', event => {
   const rect = dialog.getBoundingClientRect();
   if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom)) dialog.close();
 });
 if (new URLSearchParams(location.search).get('novo') === '1') open();
 const form = dialog.querySelector('form');
 form.addEventListener('submit', () => {
   const submit = form.querySelector('button[type="submit"]');
   if (submit) { submit.disabled = true; submit.textContent = 'SALVANDO…'; }
 });
})();

import { apiLogin, apiLogout } from './credentials.js';
const viewLogin = document.getElementById('view-login');
const viewLoggedIn = document.getElementById('view-loggedin');
const statusEl = document.getElementById('status');
const userEmailEl = document.getElementById('user-email');
async function checkAuth() {
    const { token, userEmail } = await chrome.storage.local.get(['token', 'userEmail']);
    if (token) {
        viewLogin.style.display = 'none';
        viewLoggedIn.style.display = 'block';
        userEmailEl.textContent = userEmail ?? '';
    }
    else {
        viewLogin.style.display = 'block';
        viewLoggedIn.style.display = 'none';
    }
}
document.getElementById('btn-login').addEventListener('click', async () => {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    if (!email || !password) {
        statusEl.textContent = 'Remplis tous les champs.';
        statusEl.className = 'status error';
        return;
    }
    statusEl.textContent = 'Connexion…';
    statusEl.className = 'status';
    const success = await apiLogin(email, password);
    if (success) {
        await chrome.storage.local.set({ userEmail: email });
        statusEl.textContent = '';
        await checkAuth();
    }
    else {
        statusEl.textContent = 'Courriel ou mot de passe invalide.';
        statusEl.className = 'status error';
    }
});
document.getElementById('btn-logout').addEventListener('click', async () => {
    await apiLogout();
    await checkAuth();
});
checkAuth();

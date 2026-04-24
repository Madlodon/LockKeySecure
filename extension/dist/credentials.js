const API_BASE = 'https://laniproject.dev/api';
export async function apiLogin(email, password) {
    const response = await fetch(`${API_BASE}/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
    });
    if (!response.ok)
        return false;
    const data = await response.json();
    await chrome.storage.local.set({ token: data.token });
    return true;
}
export async function apiLogout() {
    const { token } = await chrome.storage.local.get('token');
    if (!token)
        return;
    await fetch(`${API_BASE}/logout`, {
        method: 'POST',
        headers: { 'Authorization': `Bearer ${token}` },
    });
    await chrome.storage.local.remove('token');
}
export async function getServicesByDomain(domain) {
    const { token } = await chrome.storage.local.get('token');
    if (!token)
        return [];
    const response = await fetch(`${API_BASE}/services?domain=${domain}`, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        },
    });
    if (response.status === 401) {
        await chrome.storage.local.remove('token');
        return [];
    }
    if (!response.ok)
        return [];
    return response.json();
}
export async function fillCredentials(service) {
    const passwordField = document.querySelector('input[type="password"]');
    if (!passwordField)
        return;
    const form = passwordField.closest('form');
    const usernameField = form?.querySelector('input[type="email"]') ||
        form?.querySelector('input[type="text"]') ||
        form?.querySelector('input[name*="user"]') ||
        form?.querySelector('input[name*="email"]');
    if (usernameField && service.account_id) {
        usernameField.value = service.account_id;
        usernameField.dispatchEvent(new Event('input', { bubbles: true }));
    }
    passwordField.value = service.password;
    passwordField.dispatchEvent(new Event('input', { bubbles: true }));
}
// ── Auto-fill au chargement de la page ──────────────────────────────────────
(async () => {
    const passwordField = document.querySelector('input[type="password"]');
    if (!passwordField)
        return;
    const domain = window.location.hostname;
    const services = await getServicesByDomain(domain);
    if (services.length === 0)
        return;
    // Utilise le premier service correspondant au domaine
    await fillCredentials(services[0]);
})();

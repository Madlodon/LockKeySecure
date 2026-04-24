var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
const API_BASE = 'https://laniproject.dev/api';
export function apiLogin(email, password) {
    return __awaiter(this, void 0, void 0, function* () {
        const response = yield fetch(`${API_BASE}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password }),
        });
        if (!response.ok)
            return false;
        const data = yield response.json();
        yield chrome.storage.local.set({ token: data.token });
        return true;
    });
}
export function apiLogout() {
    return __awaiter(this, void 0, void 0, function* () {
        const { token } = yield chrome.storage.local.get('token');
        if (!token)
            return;
        yield fetch(`${API_BASE}/logout`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}` },
        });
        yield chrome.storage.local.remove('token');
    });
}
export function getServicesByDomain(domain) {
    return __awaiter(this, void 0, void 0, function* () {
        const { token } = yield chrome.storage.local.get('token');
        if (!token)
            return [];
        const response = yield fetch(`${API_BASE}/services?domain=${domain}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            },
        });
        if (response.status === 401) {
            yield chrome.storage.local.remove('token');
            return [];
        }
        if (!response.ok)
            return [];
        return response.json();
    });
}
export function fillCredentials(service) {
    return __awaiter(this, void 0, void 0, function* () {
        const passwordField = document.querySelector('input[type="password"]');
        if (!passwordField)
            return;
        const form = passwordField.closest('form');
        const usernameField = (form === null || form === void 0 ? void 0 : form.querySelector('input[type="email"]')) ||
            (form === null || form === void 0 ? void 0 : form.querySelector('input[type="text"]')) ||
            (form === null || form === void 0 ? void 0 : form.querySelector('input[name*="user"]')) ||
            (form === null || form === void 0 ? void 0 : form.querySelector('input[name*="email"]'));
        if (usernameField && service.account_id) {
            usernameField.value = service.account_id;
            usernameField.dispatchEvent(new Event('input', { bubbles: true }));
        }
        passwordField.value = service.password;
        passwordField.dispatchEvent(new Event('input', { bubbles: true }));
    });
}

/* ============================================================
   HazardLIDM — API Client (js/api.js)
   Axios-like wrapper menggunakan native Fetch API
   ============================================================ */

const API_BASE_URL = window.location.origin + '/api';

const API = {
  /**
   * Ambil token autentikasi dari localStorage
   */
  _getToken() {
    return localStorage.getItem('hazard_token');
  },

  /**
   * Bangun headers default dengan Authorization jika token tersedia
   */
  _buildHeaders(extra = {}) {
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...extra,
    };
    const token = this._getToken();
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }
    return headers;
  },

  /**
   * Proses response — throw error jika bukan 2xx
   */
  async _handleResponse(res) {
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      const message = data.message || data.error || `HTTP Error ${res.status}`;
      throw new Error(message);
    }
    return data;
  },

  /**
   * GET request
   * @param {string} endpoint - contoh: '/stats', '/simulations'
   */
  async get(endpoint) {
    const res = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'GET',
      headers: this._buildHeaders(),
    });
    return this._handleResponse(res);
  },

  /**
   * POST request
   * @param {string} endpoint
   * @param {object} body
   */
  async post(endpoint, body = {}) {
    const res = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'POST',
      headers: this._buildHeaders(),
      body: JSON.stringify(body),
    });
    return this._handleResponse(res);
  },

  /**
   * DELETE request
   * @param {string} endpoint
   */
  async delete(endpoint) {
    const res = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: 'DELETE',
      headers: this._buildHeaders(),
    });
    return this._handleResponse(res);
  },

  // ── Auth Helpers ─────────────────────────────────────────────
  isAuthenticated() {
    return !!this._getToken();
  },

  getCurrentUser() {
    const raw = localStorage.getItem('hazard_user');
    return raw ? JSON.parse(raw) : null;
  },

  logout() {
    this.post('/logout').catch(() => {});
    localStorage.removeItem('hazard_token');
    localStorage.removeItem('hazard_user');
    localStorage.removeItem('hazard_pretest_score');
    window.location.href = '/';
  },

  // ── Endpoint Wrappers ─────────────────────────────────────────
  auth: {
    login: (email, password) => API.post('/login', { email, password }),
    register: (name, email, password) => API.post('/register', { name, email, password }),
    logout: () => API.post('/logout'),
    me: () => API.get('/user'),
  },

  pretest: {
    submit: (score, answers) => API.post('/pre-tests', { score, answers }),
    getHistory: () => API.get('/pre-tests'),
  },

  simulation: {
    /**
     * Kirim hasil simulasi ke backend Laravel
     * @param {object} payload
     * @param {'amonia'|'klorin'} payload.gas_type
     * @param {number} payload.duration - detik
     * @param {number} payload.max_ppm
     * @param {number} payload.final_ppm
     * @param {'survived'|'failed'} payload.status
     * @param {string} payload.ppe_selected
     * @param {'water_spray'|'capping_kit'} payload.mitigation_action
     */
    submit: (payload) => API.post('/simulations', payload),
    getHistory: () => API.get('/simulations'),
  },

  stats: {
    summary: () => API.get('/stats'),
  },
};

// Expose globally
window.API = API;

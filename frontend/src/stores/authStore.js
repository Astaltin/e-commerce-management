import { AxiosError } from 'axios';
import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

import { api } from 'src/boot/axios';

const isDevelopment = import.meta.env.DEV;

export const useAuthStore = defineStore(
  'auth',
  () => {
    const token = ref(null);
    const user = ref(null);

    const isAuthenticated = computed(() => !!token.value);

    const load = async () => {
      if (token.value) {
        api.defaults.headers.common.Authorization = `${token.value.type} ${token.value.value}`;
        await getUser();
      }
    };

    const getUser = async () => {
      try {
        const res = await api.get('/api/user');
        user.value = res.data;
      } catch (error) {
        clearToken();
        handleAxiosError(error, 'User request failed');
      }
    };

    const updatePassword = async (data) => {
      try {
        const serializedData = JSON.stringify(data);
        const res = await api.put('/password', serializedData);
        return res.status === 204;
      } catch (error) {
        handleAxiosError(error, 'Change password request failed');
        return false;
      }
    };

    const login = async (data) => {
      try {
        const serializedData = JSON.stringify(data);
        const res = await api.post('/login', serializedData);
        if (res.status === 200) {
          handleToken(res.data.token);
          return true;
        } else {
          return false;
        }
      } catch (error) {
        handleAxiosError(error, 'Login request failed');
        return false;
      }
    };

    const logout = async () => {
      try {
        const res = await api.post('/logout');
        if (res.status === 204) {
          clearToken();
          return true;
        } else {
          return false;
        }
      } catch (error) {
        handleAxiosError(error, 'Logout request failed');
        return false;
      }
    };

    const clearToken = () => {
      token.value = null;
      user.value = null;
      api.defaults.headers.common.Authorization = undefined;
    };

    const handleToken = (tokenData) => {
      if (tokenData) {
        token.value = tokenData;
        api.defaults.headers.common.Authorization = `${tokenData.type} ${tokenData.value}`;
      } else {
        clearToken();
      }
    };

    const handleAxiosError = (error, message) => {
      if (error instanceof AxiosError && isDevelopment) {
        console.error(`${message}:`, error);
      } else {
        throw error;
      }
    };

    return {
      token,
      user,
      isAuthenticated,
      updatePassword,
      load,
      login,
      logout,
    };
  },
  {
    persist: {
      key: 'tradeon_auth',
      pick: ['token', 'user'],
    },
  }
);

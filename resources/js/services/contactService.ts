import api from './api';
import type { Message, ApiResponse } from '@/types';

export interface ContactPayload {
  name: string;
  email: string;
  phone?: string;
  subject?: string;
  message: string;
  honeypot?: string;
}

export const contactService = {
  async submit(payload: ContactPayload) {
    const { data } = await api.post<ApiResponse<Message>>('/contact', payload);
    return data;
  },
};

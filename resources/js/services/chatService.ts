import api from './api';
import type { ChatRequest, ChatResponse, ApiResponse } from '@/types';

export const chatService = {
  async send(payload: ChatRequest) {
    const { data } = await api.post<ApiResponse<ChatResponse>>('/chat', payload);
    return data;
  },
};

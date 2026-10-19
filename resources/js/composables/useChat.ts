import { ref } from 'vue';
import { chatService } from '@/services/chatService';
import type { ChatMessage } from '@/types';

export function useChat() {
  const messages = ref<ChatMessage[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);

  async function send(text: string) {
    const trimmed = text.trim();
    if (!trimmed) return;

    const userMsg: ChatMessage = {
      id: crypto.randomUUID(),
      role: 'user',
      content: trimmed,
      createdAt: new Date().toISOString(),
    };
    messages.value.push(userMsg);
    loading.value = true;
    error.value = null;

    try {
      const res = await chatService.send({ message: trimmed, history: messages.value });
      const reply = res?.data?.reply ?? '';
      messages.value.push({
        id: crypto.randomUUID(),
        role: 'assistant',
        content: reply,
        createdAt: new Date().toISOString(),
      });
    } catch (e: unknown) {
      error.value = (e as { response?: { data?: { message?: string } } })?.response?.data?.message ?? 'Chat failed';
    } finally {
      loading.value = false;
    }
  }

  function clear() {
    messages.value = [];
    error.value = null;
  }

  return { messages, loading, error, send, clear };
}

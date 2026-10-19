import { ref } from 'vue';
import { z } from 'zod';

export const contactSchema = z.object({
  name: z.string().min(1, 'Name is required').max(255),
  email: z.string().email('Invalid email address').max(255),
  phone: z.string().max(50).optional().or(z.literal('')),
  subject: z.string().max(255).optional().or(z.literal('')),
  message: z.string().min(1, 'Message is required').max(5000),
  honeypot: z.string().optional(),
});

export type ContactFormValues = z.infer<typeof contactSchema>;

export function useFormValidation<T extends z.ZodTypeAny>(schema: T) {
  const errors = ref<Record<string, string>>({});

  function validate(values: unknown): values is z.infer<T> {
    const result = schema.safeParse(values);
    if (result.success) {
      errors.value = {};
      return true;
    }
    const next: Record<string, string> = {};
    for (const issue of result.error.issues) {
      const key = issue.path.join('.') || '_';
      if (!next[key]) next[key] = issue.message;
    }
    errors.value = next;
    return false;
  }

  function clearError(field: string) {
    delete errors.value[field];
  }

  return { errors, validate, clearError };
}

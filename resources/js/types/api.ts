export interface ApiResponse<T = unknown> {
  success: boolean;
  message: string;
  data: T;
  meta?: PaginationMeta;
}

export interface PaginationMeta {
  current_page: number;
  from: number;
  to: number;
  per_page: number;
  total: number;
  last_page: number;
}

export interface Blog {
  id: number;
  title: string;
  slug: string;
  excerpt?: string;
  content: string;
  featured_image?: string;
  reading_time: number;
  status: 'draft' | 'published';
  is_featured: boolean;
  views_count: number;
  published_at?: string;
  user?: User;
  categories?: Category[];
  tags?: Tag[];
  created_at: string;
  updated_at: string;
}

export interface Category {
  name: string;
  slug: string;
  description?: string;
  icon?: string;
  color?: string;
}

export interface Tag {
  name: string;
  slug: string;
}

export interface Message {
  id: number;
  name: string;
  email: string;
  phone?: string;
  subject?: string;
  message: string;
  status: 'unread' | 'read' | 'replied';
  replied_at?: string;
  reply_content?: string;
  created_at: string;
}

import type { User } from './profile';
export type { User, LoginCredentials } from './profile';

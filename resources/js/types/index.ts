export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  role?: string;
  permissions?: string[];
}

export interface LoginCredentials {
  email: string;
  password: string;
  remember?: boolean;
}

export interface Project {
  id: number;
  title: string;
  slug: string;
  description: string;
  content?: string;
  thumbnail?: string;
  demo_url?: string;
  github_url?: string;
  video_url?: string;
  is_featured: boolean;
  is_published: boolean;
  views_count: number;
  technologies?: Technology[];
  images?: ProjectImage[];
  created_at: string;
  updated_at: string;
}

export interface Technology {
  id: number;
  name: string;
  slug: string;
  icon?: string;
  category?: string;
}

export interface ProjectImage {
  id: number;
  image_url: string;
  caption?: string;
  order_position: number;
}

export type BlogPost = Blog;

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
  id: number;
  name: string;
  slug: string;
  description?: string;
  icon?: string;
  color?: string;
}

export interface Tag {
  id: number;
  name: string;
  slug: string;
}

export interface Skill {
  id: number;
  name: string;
  slug: string;
  category?: string;
  proficiency_level: number;
  years_experience?: number;
  icon?: string;
  description?: string;
  is_featured: boolean;
  order_position: number;
}

export interface Service {
  id: number;
  title: string;
  slug: string;
  description: string;
  icon?: string;
  price_starting?: number;
  currency: string;
  delivery_time?: string;
  is_active: boolean;
  is_featured: boolean;
}

export interface Experience {
  id: number;
  company: string;
  position: string;
  location?: string;
  employment_type?: string;
  description?: string;
  start_date: string;
  end_date?: string;
  is_current: boolean;
}

export interface Education {
  id: number;
  institution: string;
  degree: string;
  field_of_study?: string;
  location?: string;
  description?: string;
  start_date: string;
  end_date?: string;
  is_current: boolean;
  grade?: string;
}

export interface Certificate {
  id: number;
  title: string;
  issuer: string;
  issue_date: string;
  expiry_date?: string;
  credential_id?: string;
  credential_url?: string;
  certificate_file?: string;
  description?: string;
  is_verified: boolean;
}

export interface Testimonial {
  id: number;
  client_name: string;
  client_position?: string;
  client_company?: string;
  client_avatar?: string;
  content: string;
  rating: number;
  project_id?: number;
  is_featured: boolean;
  is_published: boolean;
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

export interface ApiResponse<T = any> {
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

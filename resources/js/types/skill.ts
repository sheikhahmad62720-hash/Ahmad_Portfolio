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

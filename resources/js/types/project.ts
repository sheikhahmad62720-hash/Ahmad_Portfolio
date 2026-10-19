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

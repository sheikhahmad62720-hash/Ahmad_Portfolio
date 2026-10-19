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

export interface Profile {
  name: string;
  tagline?: string;
  description?: string;
  email?: string;
  phone?: string;
  location?: string;
  avatar?: string;
  resumeUrl?: string;
  social?: {
    github?: string;
    linkedin?: string;
    facebook?: string;
    instagram?: string;
    twitter?: string;
  };
}

export interface ProfileStat {
  value: string;
  label: string;
}

export interface ProfileData {
  profile: Profile;
  roles: string[];
  stats: ProfileStat[];
  bio: string[];
  info: { label: string; value: string; icon?: string }[];
}
